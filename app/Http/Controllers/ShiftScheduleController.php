<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftScheduleChange;
use App\Models\ShiftScheduleChangeEmployee;
use App\Models\ShiftScheduleChangeTemp;
use App\User;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \Response::json([
            'data' => ShiftScheduleChange::with(['shift', 'shiftScheduleChangeEmployees'])->orderBy('change_date', 'DESC')->orderBy('shift_id', 'ASC')->get(),
        ]);
    }

    public function getShifts($id)
    {
        return \Response::json([
            'data' => Shift::where('branch_id', $id)->get(),
        ]);
    }

    public function getDisplay()
    {
        return \Response::json([
            'data' => ShiftScheduleChangeEmployee::with(['shiftScheduleChange.shift', 'user'])->get(),
        ]);
    }

    public function getUsers()
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.nik',
                'users.name',
                'users.cabang_id',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )
            ->orderBy('users.id', 'DESC')
            ->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function getAssign($id)
    {
        return \Response::json([
            'message' => 'success',
            'data' => ShiftScheduleChangeEmployee::select('user_id')->where('shift_schedule_change_id', $id)->get(),
        ]);
    }

    public function postAssign(Request $request, $id)
    {
        if ($request->user_id) {
            ShiftScheduleChangeEmployee::where(function ($query) use ($id, $request) {
                $query->whereIn('user_id', $request->user_id)->whereHas('shiftScheduleChange', function ($query) use ($id) {
                    $query->where('change_date', '=', ShiftScheduleChange::find($id)->change_date);
                });
            })->orWhere(function ($query) use ($id, $request) {
                $query->whereNotIn('user_id', $request->user_id)->where('shift_schedule_change_id', $id);
            })->delete();

            $temp = [];
            foreach ($request->user_id as $value) {
                array_push($temp, [
                    'user_id' => $value,
                    'shift_schedule_change_id' => $id,
                ]);
            }

            ShiftScheduleChangeEmployee::insert($temp);
        } else {
            ShiftScheduleChangeEmployee::where('shift_schedule_change_id', $id)->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Assign setting success']);
    }

    public function importTemp(Request $request)
    {
        if ($request->hasFile('file')) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            $allShift = Shift::select('id', 'name', 'branch_id')->get();

            ShiftScheduleChangeTemp::truncate(); // delete all table temp
            
            $check = [];
            foreach ($rows as $key => $item) {
                if ((empty($item[0]) && empty($item[1]) && empty($item[2])) || $key == 0) {
                    continue;
                }

                foreach (explode(',', str_replace(".", ",", $item[2])) as $userNik) {
                    array_push($check, [$item[0], $userNik]);

                    $data = new ShiftScheduleChangeTemp();

                    // check date
                    if (!empty($item[0])) {
                        try {
                            $data->change_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[0])->format('Y-m-d');
                            if (date('Y-m-d') >= $data->change_date) {
                                $data->status = 2;
                                $data->description .= $data->description ? ', Minimum date is tomorrow' : 'Error: Minimum date is tomorrow';
                            }
                        } catch (\Exception $e) {
                            $data->status = 2;
                            $data->description .= $data->description ? ', Date is not valid' : 'Error: Date is not valid';
                        }
                    } else {
                        $data->status = 2;
                        $data->description .= $data->description ? ', Date cannot be blank' : 'Error: Date cannot be blank';
                    }

                    // check shift
                    if (!empty($item[1])) {
                        $data->shift_name = $item[1];
                        $shift = $allShift->filter(function ($value) use ($item) {
                            return $value->name == $item[1];
                        })->first();
                        if (!$shift) {
                            $data->status = 2;
                            $data->description .= $data->description ? ', Shift not found' : 'Error: Shift not found';
                        }
                    } else {
                        $shift = false;
                        $data->status = 2;
                        $data->description .= $data->description ? ', Shift cannot be blank' : 'Error: Shift cannot be blank';
                    }

                    // check nik
                    if ($userNik) {
                        $data->user_nik = $userNik;
                        $user = User::where('nik', $userNik)->first();
                        if ($user) {
                            $data->user_name = $user->name;
                            if (count(array_filter($check, function ($value) use ($item, $userNik) {
                                return $value[1] == $userNik && $value[0] == $item[0];
                            })) < 2) {
                                // need to be fix
                                if ($shift && $shift->branch_id == $user->cabang_id) {
                                    $data->shift_id = $shift->id;
                                    $data->user_id = $user->id;
                                } else if ($shift) {
                                    $data->status = 2;
                                    $data->description .= $data->description ? ', User branch is different with shift' : 'Error: User branch is different with shift';
                                }
                                // need to be fix
                            } else {
                                $data->status = 2;
                                $data->description .= $data->description ? ', User already assigned on same date in this import file' : 'Error: User already assigned on same date in this import file';
                            }
                        } else {
                            $data->status = 2;
                            $data->description .= $data->description ? ', User not found' : 'Error: User not found';
                        }
                    } else {
                        $user = false;
                        $data->status = 2;
                        $data->description .= $data->description ? ', NIK cannot be blank' : 'Error: NIK cannot be blank';
                    }

                    $data->save();
                }
            }
        }

        return redirect()->route('shift-schedule.preview')->with('message-success', 'Import success');
    }

    public function importPreview()
    {
        $params['data'] = ShiftScheduleChangeTemp::all();

        return view('shift.preview')->with($params);
    }

    public function importAll()
    {
        $data = ShiftScheduleChangeTemp::orderBy('user_id', 'DESC')->get();

        if ($data->where('status', 2)->count()) {
            return \Redirect::route('shift-setting.index', ['tab' => 'schedule'])->with('message-error', 'No data to be imported.');
        }

        $temp = [];
        foreach ($data as $value) {
            $item = array_values(array_filter($temp, function ($val) use ($value) {
                return $val->change_date == $value->change_date && $val->shift_id == $value->shift_id;
            }));
            if (!$item) {
                $item = ShiftScheduleChange::where('change_date', $value->change_date)->where('shift_id', $value->shift_id)->first();
                if (!$item) {
                    $item = new ShiftScheduleChange();
                    $item->change_date = $value->change_date;
                    $item->shift_id = $value->shift_id;
                    $item->save();
                }

                $temp[$item->id] = new \stdClass();
                $temp[$item->id]->id = $item->id;
                $temp[$item->id]->change_date = $item->change_date;
                $temp[$item->id]->shift_id = $item->shift_id;
                $temp[$item->id]->users = [];
            } else {
                $item = $item[0];
            }

            array_push($temp[$item->id]->users, $value->user_id);
        }

        foreach ($temp as $value) {
            ShiftScheduleChangeEmployee::whereIn('user_id', $value->users)->whereHas('shiftScheduleChange', function ($query) use ($value) {
                $query->where('change_date', '=', $value->change_date);
            })->delete();

            $temp2 = [];
            foreach ($value->users as $user) {
                array_push($temp2, [
                    'user_id' => $user,
                    'shift_schedule_change_id' => $value->id,
                ]);
            }

            ShiftScheduleChangeEmployee::insert($temp2);
        }

        ShiftScheduleChangeTemp::truncate();

        return \Redirect::route('shift-setting.index', ['tab' => 'schedule'])->with('message-success', 'Import success');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (ShiftScheduleChange::where('change_date', $request->change_date)->where('shift_id', $request->shift_id)->first()) {
            return \Response::json(array(
                'type' => 'error',
                'title' => 'This schedule already exist',
            ));
        }

        ShiftScheduleChange::create($request->all());

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Add schedule success',
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (ShiftScheduleChange::where('id', '!=', $id)->where('change_date', $request->change_date)->where('shift_id', $request->shift_id)->first()) {
            return \Response::json(array(
                'type' => 'error',
                'title' => 'This schedule already exist',
            ));
        }

        ShiftScheduleChange::where('id', $id)->update($request->all());

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Edit schedule success',
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShiftScheduleChange::destroy($id);

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Delete schedule success',
        ));
    }
}
