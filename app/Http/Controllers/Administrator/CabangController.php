<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Support\Facades\Validator;


class CabangController extends Controller
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['data'] = Cabang::where('cabang.project_id', $user->project_id)->select('cabang.*')->get();
        }else{
            $params['data'] = Cabang::all();
        }

        return view('administrator.cabang.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('administrator.cabang.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = Cabang::where('id', $id)->first();

        return view('administrator.cabang.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'name'  => 'required|max:30'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        $data       = Cabang::where('id', $id)->first();
        $data->name             = $request->name;
        $data->alamat           = $request->alamat;
        $data->telepon          = $request->telepon;
        $data->fax              = $request->fax;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->radius           = $request->radius;
        $data->timezone         = $request->timezone;
        $data->save();

        return redirect()->route('administrator.cabang.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = Cabang::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.cabang.index')->with('message-success', 'Data berhasi di hapus');
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import(Request $request)
    {
        if($request->hasFile('file'))
        {
            //$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
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
                $rows[] = $cells;
            }

            foreach($rows as $key => $item)
            {
                if($key >= 1)
                {
                    if($item[1] == "") continue;
                    $data  = Cabang::where('name',strtoupper($item[1]))->first();
                    if(!$data) {
                        $data = new Cabang();
                        $data->name = strtoupper($item[1]);
                    }
                    $data->alamat       = $item[2];
                    $data->telepon      = $item[3];
                    $data->fax          = $item[4];
                    $data->latitude     = $item[5];
                    $data->longitude    = $item[6];
                    $data->radius       = $item[7];
                    $data->timezone     = $item[8];

                    $user = \Auth::user();
                    if($user->project_id != NULL)
                    {
                        $data->user_created = $user->id;
                        $data->project_id   = $user->project_id;
                    }
                    $data->save();
                }
            }

            return redirect()->route('administrator.cabang.index')->with('message-success', 'Data berhasil diimports !');
        }
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name'  => 'required|max:30'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }

        $data           = new Cabang();
        $data->name             = $request->name;
        $data->alamat           = $request->alamat;
        $data->telepon          = $request->telepon;
        $data->fax              = $request->fax;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->radius           = $request->radius;
        $data->timezone         = $request->timezone;
        $data->project_id       = $request->timezone;
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
            $data->project_id   = $user->project_id;
        }
        $data->save();

        return redirect()->route('administrator.cabang.index')->with('message-success', 'Data saved successfully !');
    }
}
