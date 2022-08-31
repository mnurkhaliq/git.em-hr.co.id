<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectSettingController extends Controller
{

    public function __construct(\Maatwebsite\Excel\Excel $excel)
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $params['projects'] = Project::all();

        return view('administrator.project-setting.index')->with($params);
    }

    public function create()
    {
        return view('administrator.project-setting.create');
    }

    public function store(Request $request)
    {
        $data = new Project();
        $data->name = $request->name;
        $data->save();

        return \Redirect::route('administrator.project-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function edit($id)
    {
        $params['data'] = Project::find($id);

        return view('administrator.project-setting.edit')->with($params);
    }

    public function update(Request $request, $id)
    {
        $data = Project::where('id', $id)->first();
        $data->name = $request->name;
        $data->save();

        return \Redirect::route('administrator.project-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function destroy($id)
    {
        Project::destroy($id);

        return \Redirect::route('administrator.project-setting.index')->with('message-success', 'Data deleted successfully');
    }
}
