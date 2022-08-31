<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\OrganisasiTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TitleController extends Controller
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
        $params['data'] = OrganisasiTitle::orderBy('id', 'DESC')->get();

        return view('administrator.title.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('administrator.title.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = OrganisasiTitle::where('id', $id)->first();

        if (!$params['data']) {
            return redirect()->route('administrator.title.index')->with('message-error', 'Data is not found!');
        }

        return view('administrator.title.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        }

        $data = OrganisasiTitle::where('id', $id)->first();

        $checkExistName = OrganisasiTitle::where('name', $request->name)->whereNotIn('id', [$data->id])->first();
        $checkExistCode = OrganisasiTitle::where('code', $request->code)->whereNotIn('id', [$data->id])->first();
        if (isset($checkExistName) || isset($checkExistCode)) {
            return redirect()->back()->withInput()->withErrors(['Data is not updated']);
        } else {
            $data->name = $request->name;
            $data->code = $request->code;
            $data->save();

            return redirect()->route('administrator.title.index')->with('message-success', 'Data updated successfully!');
        }
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = OrganisasiTitle::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.title.index')->with('message-success', 'Data successfully deleted');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        }

        $checkExistName = OrganisasiTitle::where('name', $request->name)->first();
        $checkExistCode = OrganisasiTitle::where('code', $request->code)->first();
        if (isset($checkExistName) || isset($checkExistCode)) {
            return redirect()->back()->withInput()->withErrors(['Data already existed']);
        } else {
            $data = new OrganisasiTitle();
            $data->name = $request->name;
            $data->code = $request->code;
            $data->save();

            return redirect()->route('administrator.title.index')->with('message-success', 'Data successfully saved!');
        }

    }

    public function checkCode(Request $r)
    {
        if ($r->type == 'store') {
            $checkExistCode = OrganisasiTitle::where('code', $r->code)->first();
            if (isset($checkExistCode)) {
                $status = 'false';
            } else {
                $status = 'true';
            }
        } else {
            $data = OrganisasiTitle::where('id', $r->id)->first();
            $checkExistCode = OrganisasiTitle::where('code', $r->code)->whereNotIn('id', [$data->id])->first();
            if (isset($checkExistCode)) {
                $status = 'false';
            } else {
                $status = 'true';
            }
        }

        return response($status);
    }

    public function checkName(Request $r)
    {
        if ($r->type == 'store') {
            $checkExistName = OrganisasiTitle::where('name', $r->name)->first();
            if (isset($checkExistName)) {
                $status = 'false';
            } else {
                $status = 'true';
            }
        } else {
            $data = OrganisasiTitle::where('id', $r->id)->first();
            $checkExistName = OrganisasiTitle::where('name', $r->name)->whereNotIn('id', [$data->id])->first();
            if (isset($checkExistName)) {
                $status = 'false';
            } else {
                $status = 'true';
            }
        }

        return response($status);
    }
}
