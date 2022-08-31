<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Jobseeker;
use App\Models\JobseekerOption;
use App\Models\BankCvOption;
use App\Models\JobseekerTag;
use App\User;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BankCvController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:31');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['layout_bank_cv'])) {
            $auth = Auth::user();

            if ($auth && $auth->project_id != null) {
                $setting = \App\Models\Setting::where('key', 'layout_bank_cv')->where('project_id', $auth->project_id)->first();
            } else {
                $setting = \App\Models\Setting::where('key', 'layout_bank_cv')->first();
            }

            if (!$setting) {
                $setting = new \App\Models\Setting();
                $setting->key = 'layout_bank_cv';
                if ($auth && $auth->project_id != null) {
                    $setting->project_id = $auth->project_id;
                }
            }
            $setting->value = $_GET['layout_bank_cv'];
            $setting->save();
        }

        $data = Jobseeker::select("*");

        if(count(request()->all())) {
            \Session::put('bc-max_salary', request()->max_salary);
            \Session::put('bc-min_salary', request()->min_salary);
            \Session::put('bc-skill', request()->skill);
            \Session::put('bc-name', request()->name);
            \Session::put('bc-option', request()->option);
        }

        $max_salary   = \Session::get('bc-max_salary');
        $min_salary   = \Session::get('bc-min_salary');
        $skill        = \Session::get('bc-skill');
        $name         = \Session::get('bc-name');
        $option       = \Session::get('bc-option');

        if (request()) {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('name', 'LIKE', '%' . $name . '%')->orWhere('nik', 'LIKE', '%' . $name . '%');
                });
            }

            if (!empty($skill)) {
                $skill = $skill;
                $data = $data->whereHas('tags', function($query) use ($skill) {
                    $query->where('tag', 'like', '%'.$skill.'%');
                });
            }

            if (!empty($min_salary)) {
                $data = $data->where('salary', '>=', $min_salary);
            }
            if (!empty($max_salary)) {
                $data = $data->where('salary', '<=', $max_salary);
            }

            if (!empty($option)) {
                foreach ($option as $key => $value) {
                    \Session::put('bc-value',  $value);
                    if (!empty($value)) {
                        $data = $data->whereHas('options', function($query) use ($value) {
                            $query->where(function($query) use ($value) {
                                $query->where('bank_cv_option_value_id', $value)->whereHas('option', function($query) use ($value) {
                                    $query->where('is_dropdown', 1);
                                });
                            })->orWhere(function($query) use ($value) {
                                $query->where('bank_cv_option_value', $value)->whereHas('option', function($query) use ($value) {
                                    $query->where('is_dropdown', 0);
                                });
                            }); 
                        });
                    }
                }
            }

            if (request()->action == 'download') {
                return $this->downloadExcel($data->orderBy('id', 'DESC')->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('bc-max_salary');
            \Session::forget('bc-min_salary');
            \Session::forget('bc-skill');
            \Session::forget('bc-name');
            \Session::forget('bc-option');
            \Session::forget('bc-value');

            return redirect()->route('administrator.bank-cv.index');
        }

        $params['data'] = $data->orderBy('id', 'DESC')->get();
        $params['column'] = BankCvOption::all();

        return view('administrator.bank-cv.index')->with($params);
    }

    public function downloadExcel($data, $template = false)
    {
        $params = [];
        foreach ($data as $k => $item) {
            if (!$template) {
                $params[$k]['No']               = $k + 1;
                $params[$k]['NIK']              = $item->nik;
                $params[$k]['Name']             = $item->name;
                $params[$k]['Gender']           = $item->gender;
                $params[$k]['Email']            = $item->email;
            } else {
                $params[$k]['NIK (Dont edit)']  = $item->nik;
                $params[$k]['Name *']           = $item->name;
                $params[$k]['Gender *']         = $item->gender;
                $params[$k]['Email *']          = $item->email;
            }
            $params[$k]['Phone Number']         = $item->phone_number;
            $params[$k]['Born Year']            = $item->born_year;
            $params[$k]['Skill']                = implode(',', $item->tags->pluck('tag')->toArray());
            $params[$k]['Address']              = $item->address;
            $params[$k]['Expected Salary']      = $item->salary;
            $params[$k]['Notes']                = $item->notes;
            $params[$k]['Input Date']           = $item->input_date;
            if (!$template) {
                $params[$k]['Created By']       = $item->createdBy ? $item->createdBy->nik.' - '.$item->createdBy->name : 'Applicant';
                $params[$k]['Updated By']       = $item->updatedBy ? $item->updatedBy->nik.' - '.$item->updatedBy->name : 'Applicant';
            }
            foreach (BankCvOption::all() as $no => $val) {
                if ($val->is_dropdown) {
                    $params[$k][ucwords($val->name)] = isset($item->options->where('bank_cv_option_id', $val->id)->first()->value->value) ? $item->options->where('bank_cv_option_id', $val->id)->first()->value->value : '';
                } else {
                    $params[$k][ucwords($val->name)] = $item->options->where('bank_cv_option_id', $val->id)->first() ? $item->options->where('bank_cv_option_id', $val->id)->first()->bank_cv_option_value : '';
                }
            }
        }

        if ($template) {
            return (new \App\Models\KaryawanExport($params, 'Template Bank CV'))->download('EM-HR.Template-Bank-CV.xlsx');
        }

        return (new \App\Models\KaryawanExport($params, 'Report Bank CV ' . date('d M Y')))->download('EM-HR.Report-Bank-CV-' . date('d-m-Y') . '.xlsx');
    }

    public function download()
    {
        return $this->downloadExcel(Jobseeker::orderBy('id', 'DESC')->get(), true);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message-error', 'Import file required');
        }

        if($request->hasFile('file'))
        {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                info($cells);
                $rows[] = $cells;
            }

            $countColumn = 11;
            $cvArray = [];
            $error = [];
            $existing_email = Jobseeker::pluck('email')->toArray();
            $bankCvOption = BankCvOption::all();

            foreach ($rows as $key => $item) {
                if ($key >= 2) {
                    $cv = Jobseeker::where('nik', $item[0])->first();
                    if (!$cv) {
                        $cv               = new Jobseeker();
                        $cv->input_date   = isset($item[10]) ? $item[10] : Carbon::now()->format('Y-m-d');
                        $cv->created_by   = Auth::user()->id;
                    }
                    $cv->updated_by = Auth::user()->id;

                    $cvTemp = array_search($item[0], array_map(function($value) {
                        return $value->nik;
                    }, $cvArray));
                    if ($cvTemp && isset($item[0])) {
                        $error[$key + 1][] = 'Duplicate NIK';
                    }

                    $cvTemp = array_search($item[3], array_map(function($value) {
                        return $value->email;
                    }, $cvArray));
                    if ($cvTemp) {
                        $error[$key + 1][] = 'Duplicate Email';
                    }

                    if (!empty($item[3])) {
                        if (array_search($item[3], $existing_email) && ((isset($cv->email) && $cv->email != $item[3]) || !isset($cv->email))) {
                            $error[$key + 1][] = 'Email already taken';
                        } else {
                            $cv->email = $item[3];
                        }
                    } else {
                        $error[$key + 1][] = 'Email can not be blank';
                    }

                    if (!empty($item[1])) {
                        $cv->name = strtoupper($item[1]);
                    } else {
                        $error[$key + 1][] = 'Name can not be blank';
                    }

                    if (!empty($item[2])) {
                        if ($item[2] == 'Male' || $item[2] == 'male' || $item[2] == 'Laki-laki' || $item[2] == 'laki-laki' || strtoupper($item[2]) == 'PRIA') {
                            $cv->gender = 'Male';
                        } else if ($item[2] == 'Female' || $item[2] == 'female' || $item[2] == 'Perempuan' || $item[2] == 'perempuan' || strtoupper($item[2]) == 'WANITA') {
                            $cv->gender = 'Female';
                        } else {
                            $error[$key + 1][] = 'Gender is not valid';
                        }
                    } else {
                        $error[$key + 1][] = 'Gender can not be blank';
                    }
                    
                    $cv->phone_number = $item[4];

                    if (empty($item[5]) || is_numeric($item[5])) {
                        $cv->born_year = $item[5];
                    } else {
                        $error[$key + 1][] = 'Born Year is not valid';
                    }

                    $cv->address = $item[7];
                    
                    if (empty($item[8]) || is_numeric($item[8])) {
                        $cv->salary = $item[8];
                    } else {
                        $error[$key + 1][] = 'Expected Salary is not valid';
                    }

                    $cv->notes = $item[9];

                    foreach ($bankCvOption as $no => $val) {
                        if ($val->is_dropdown && isset($item[$countColumn + $no])) {
                            if (!$val->values->where('value', $item[$countColumn + $no])->first()) {
                                $error[$key + 1][] = 'Option '.$item[$countColumn + $no].' for column '.ucwords($val->name).' did not exist';
                            }
                        }
                    }

                    $cvArray[$key] = $cv;
                }
            }

            if (count($error)) {
                $errorText = '';
                foreach ($error as $key => $values) {
                    foreach ($values as $value) {
                        $errorText .= '<br>– row '.$key.' '.$value;
                    }
                }

                return redirect()->back()->with('message-error-format', $errorText);
            } else {
                foreach($cvArray as $key => $value) {
                    if (!isset($value->id)) {
                        $value->save();
                        $value->nik = $cvArray[$key]->nik = Carbon::parse($value->input_date)->format('Ymd-').$value->id;
                    }
                    $value->save();
                }
            }

            foreach ($rows as $key => $item) {
                if ($key >= 2) {
                    $cv = $cvArray[$key];

                    JobseekerTag::where('jobseekers_id', $cv->id)->delete();
                    foreach (explode(',', $item[6]) as $no => $val) {
                        if ($val != "") {
                            $tag = new JobseekerTag();
                            $tag->jobseekers_id = $cv->id;
                            $tag->tag = $val;
                            $tag->save();
                        }
                    }
                    
                    foreach ($bankCvOption as $no => $val) {
                        $option = JobseekerOption::where('jobseekers_id', $cv->id)->where('bank_cv_option_id', $val->id)->first();
                        if (!$option) {
                            $option = new JobseekerOption();
                            $option->jobseekers_id = $cv->id;
                            $option->bank_cv_option_id = $val->id;
                        }
                        
                        if ($val->is_dropdown) {
                            $option->bank_cv_option_value_id = isset($item[$countColumn + $no]) ? $val->values->where('value', $item[$countColumn + $no])->first()->id : NULL;
                        } else {
                            $option->bank_cv_option_value = $item[$countColumn + $no];
                        }
                        
                        $option->save();
                    }
                }
            }

            return redirect()->route('administrator.bank-cv.index')->with('message-success', 'Data Bank CV successfully imported!');
        }
    }

    public function table()
    {
        $data = Jobseeker::select("*");

        if (request()) {
            if (!empty(request()->name)) {
                $data = $data->where(function ($table) {
                    $table->where('name', 'LIKE', '%' . request()->name . '%')->orWhere('nik', 'LIKE', '%' . request()->name . '%');
                });
            }

            if (!empty(request()->min_salary)) {
                $data = $data->where('salary', '>=', request()->min_salary);
            }
            if (!empty(request()->max_salary)) {
                $data = $data->where('salary', '<=', request()->max_salary);
            }
        }

        return DataTables::of($data)
            ->addColumn('column_nik', function ($item) {
                return '<a href="' . route('administrator.bank-cv.edit', $item->id) . '"><b>' . strtoupper($item->nik) . '</b></a>';
            })
            ->addColumn('column_name', function ($item) {
                return '<a href="' . route('administrator.bank-cv.edit', $item->id) . '"><b>' . strtoupper($item->name) . '</b></a>';
            })
            ->addColumn('column_action', function ($item) {
                $html = '<div class="btn-group m-r-10">
                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle waves-effect waves-light" type="button">Action
                            <span class="caret"></span>
                        </button>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="' . route('administrator.bank-cv.edit', $item->id) . '"><i class="fa fa-search-plus"></i> Detail</a>
                            </li>
                            <li>
                                <form action="' . route('administrator.bank-cv.destroy', $item->id) . '" method="post">
                                    <a href="javascript:void(0)" onclick="confirm_delete("Delete this data ?", this)"><i class="ti-trash"></i> Delete</a>
                                </form>
                            </li>';
                // $html .= '<li>
                //     <a href="'.route('administrator.bank-cv.print-profile', $item->id).'" target="_blank"><i class="fa fa-print"></i> Print</a>
                // </li>';
                $html .= '</ul>
                    </div>';
                return $html;
            })
            ->rawColumns(['column_nik', 'column_name', 'column_action'])
            ->make(true);
    }

    public function tag() {
        return JobseekerTag::select('tag')->groupBy('tag')->pluck('tag')->toArray();
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = Jobseeker::find($id);
        if ($params['data']) {
            return view('administrator.bank-cv.edit')->with($params);
        }
        return redirect()->route('administrator.bank-cv.index');
    }

    /**
     * [` description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('administrator.bank-cv.create');
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:jobseekers,email,'.$id,
        ], [
            'gender.required' => 'The gender field is required.',
        ]);

        $data = Jobseeker::find($id);
        $data->name = strtoupper($request->name);
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone_number = $request->phone_number;
        $data->born_year = $request->born_year;
        $data->address = $request->address;
        $data->salary = $request->salary;
        $data->notes = $request->notes;

        if ($request->hasFile('photos')) {
            $file = $request->file('photos');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-cv-photos/') . $company_url;
            $file->move($destinationPath, $fileName);

            $data->photos = $company_url . $fileName;
        }

        if ($request->hasFile('cv')) {
            $fileCv = $request->file('cv');
            $fileNameCv = md5(rand() . $fileCv->getClientOriginalName() . time()) . "." . $fileCv->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-cv/') . $company_url;
            $fileCv->move($destinationPath, $fileNameCv);

            $data->cv = $company_url . $fileNameCv;
        }

        $data->updated_by = Auth::user()->id;
        $data->save();

        JobseekerTag::where('jobseekers_id', $data->id)->delete();
        foreach (json_decode($request->skill) as $key => $value) {
            $tag = new JobseekerTag();
            $tag->jobseekers_id = $data->id;
            $tag->tag = $value->value;
            $tag->save();
        }

        foreach ($request->option as $key => $value) {
            $option = JobseekerOption::where('jobseekers_id', $data->id)->where('bank_cv_option_id', $key)->first();
            if (!$option) {
                $option = new JobseekerOption();
                $option->jobseekers_id = $data->id;
                $option->bank_cv_option_id = $key;
            }
            if ($option->option->is_dropdown)
                $option->bank_cv_option_value_id = $value;
            else
                $option->bank_cv_option_value = $value;
            $option->save();
        }

        return redirect()->route('administrator.bank-cv.edit', $data->id)->with('message-success', 'Data saved successfully');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:jobseekers',
        ], [
            'gender.required' => 'The gender field is required.',
        ]);

        $data = new Jobseeker();
        $data->name = strtoupper($request->name);
        $data->input_date = $request->input_date ?: Carbon::now()->format('Y-m-d');
        $data->gender = $request->gender;
        $data->email = $request->email;
        $data->phone_number = $request->phone_number;
        $data->born_year = $request->born_year;
        $data->address = $request->address;
        $data->salary = $request->salary;
        $data->notes = $request->notes;

        if ($request->hasFile('photos')) {
            $file = $request->file('photos');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-cv-photos/') . $company_url;
            $file->move($destinationPath, $fileName);

            $data->photos = $company_url . $fileName;
        }

        if ($request->hasFile('cv')) {
            $fileCv = $request->file('cv');
            $fileNameCv = md5(rand() . $fileCv->getClientOriginalName() . time()) . "." . $fileCv->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-cv/') . $company_url;
            $fileCv->move($destinationPath, $fileNameCv);

            $data->cv = $company_url . $fileNameCv;
        }

        $data->created_by = Auth::user()->id;
        $data->updated_by = Auth::user()->id;
        $data->save();

        $data->nik = Carbon::parse($data->input_date)->format('Ymd-').$data->id;
        $data->save();

        foreach (json_decode($request->skill) as $key => $value) {
            $tag = new JobseekerTag();
            $tag->jobseekers_id = $data->id;
            $tag->tag = $value->value;
            $tag->save();
        }

        foreach ($request->option as $key => $value) {
            $option = new JobseekerOption();
            $option->jobseekers_id = $data->id;
            $option->bank_cv_option_id = $key;
            if ($option->option->is_dropdown)
                $option->bank_cv_option_value_id = $value;
            else
                $option->bank_cv_option_value = $value;
            $option->save();
        }

        return redirect()->route('administrator.bank-cv.edit', $data->id)->with('message-success', 'Data saved successfully');
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        Jobseeker::destroy($id);

        return redirect()->route('administrator.bank-cv.index')->with('message-success', 'Data successfully deleted');
    }
}
