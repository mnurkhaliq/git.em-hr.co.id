<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\BankCvOption;
use App\Models\BankCvOptionValue;
use App\Models\JobseekerTag;
use Illuminate\Http\Request;

class SettingBankCVController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:31');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = [
            'bankCvOptions' => BankCvOption::all(),
        ];

        return view('administrator.setting-bank-cv.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.setting-bank-cv.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        BankCvOption::create([
            'name' => $request->name,
            'is_dropdown' => $request->is_dropdown,
            'is_date' => $request->is_date,
            'date_name' => $request->date_name,
            'is_list' => $request->is_list,
            'is_filter' => $request->is_filter,
        ]);

        return redirect()->route('administrator.setting-bank-cv.index')->with('message-success', 'Data saved successfully');
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
        $params = [
            'bankCvOption' => BankCvOption::find($id),
        ];

        return view('administrator.setting-bank-cv.edit')->with($params);
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
        BankCvOption::where('id', $id)->update([
            'name' => $request->name,
            'is_dropdown' => $request->is_dropdown,
            'is_date' => $request->is_date,
            'date_name' => $request->date_name,
            'is_list' => $request->is_list,
            'is_filter' => $request->is_filter,
        ]);

        return redirect()->route('administrator.setting-bank-cv.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BankCvOption::destroy($id);

        return redirect()->route('administrator.setting-bank-cv.index')->with('message-success', 'Data deleted successfully');
    }

    public function indexOption($id)
    {
        return \Response::json([
            'data' => BankCvOptionValue::where('bank_cv_option_id', $id)->get(),
        ]);
    }

    public function storeOption(Request $request)
    {
        BankCvOptionValue::create($request->all());

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Add data success',
        ));
    }

    public function updateOption(Request $request, $id)
    {
        BankCvOptionValue::where('id', $id)->update($request->all());

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Edit data success',
        ));
    }

    public function destroyOption($id)
    {
        BankCvOptionValue::destroy($id);

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Delete data success',
        ));
    }

    public function indexSkill() {
        return \Response::json([
            'data' => JobseekerTag::groupBy('tag')->get(),
        ]);
    }

    public function storeSkill(Request $request)
    {
        if (JobseekerTag::where('tag', $request->tag)->first()) {
            return \Response::json(array(
                'type' => 'error',
                'title' => 'Data already exist',
            ));
        }

        JobseekerTag::create($request->all());

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Add data success',
        ));
    }

    public function destroySkill(Request $request)
    {
        JobseekerTag::where('tag', $request->tag)->delete();

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Delete data success',
        ));
    }
}
