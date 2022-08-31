<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\LoanAssetType;
use App\Models\LoanPaymentRate;
use App\Models\LoanPlafond;
use App\Models\LoanPurpose;
use App\Models\OrganisasiPosition;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanSettingController extends Controller
{

    public function __construct(\Maatwebsite\Excel\Excel $excel)
    {
        parent::__construct();
        $this->middleware('module:33');
        $this->excel = $excel;
    }

    public function index(Request $request)
    {
        $user = \Auth::user();
        $params['purpose'] = LoanPurpose::all();
        $params['plafond'] = LoanPlafond::all();
        $params['rate'] = LoanPaymentRate::all();
        $params['asset'] = LoanAssetType::all();

        if ($request->tab) {
            $params['tab'] = $request->tab;
        }

        return view('administrator.loan-setting.index')->with($params);
    }

    public function store(Request $request)
    {
        $user = \Auth::user();

        if ($request->setting) {
            foreach ($request->setting as $key => $value) {
                if ($user->project_id != null) {
                    $setting = Setting::where('key', $key)->where('project_id', $user->project_id)->first();
                } else {
                    $setting = Setting::where('key', $key)->first();
                }
                if (!$setting) {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->user_created = $user->id;
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        return \Redirect::route('administrator.loan-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function addPurpose()
    {
        return view('administrator.loan-setting.add-purpose');
    }

    public function storePurpose(Request $request)
    {
        $data = new LoanPurpose();
        $data->purpose = $request->purpose;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'purpose'])->with('message-success', 'Data saved successfully');
    }

    public function editPurpose($id)
    {
        $params['data'] = LoanPurpose::find($id);

        return view('administrator.loan-setting.edit-purpose')->with($params);
    }

    public function updatePurpose(Request $request, $id)
    {
        $data = LoanPurpose::where('id', $id)->first();
        $data->purpose = $request->purpose;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'purpose'])->with('message-success', 'Data saved successfully');
    }

    public function deletePurpose($id)
    {
        LoanPurpose::destroy($id);

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'purpose'])->with('message-success', 'Data deleted successfully');
    }

    public function addPlafond()
    {
        $params['position'] = OrganisasiPosition::doesntHave('loanPlafond')->get();

        return view('administrator.loan-setting.add-plafond')->with($params);
    }

    public function storePlafond(Request $request)
    {
        $data = new LoanPlafond();
        $data->organisasi_position_id = $request->organisasi_position_id;
        $data->type = $request->type;
        $data->plafond = preg_replace('/[^0-9]/', '',$request->plafond);
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'plafond'])->with('message-success', 'Data saved successfully');
    }

    public function editPlafond($id)
    {
        $params['data'] = LoanPlafond::find($id);
        $params['position'] = OrganisasiPosition::doesntHave('loanPlafond')->orWhere('id', $params['data']->organisasi_position_id)->get();

        return view('administrator.loan-setting.edit-plafond')->with($params);
    }

    public function updatePlafond(Request $request, $id)
    {
        $data = LoanPlafond::where('id', $id)->first();
        $data->organisasi_position_id = $request->organisasi_position_id;
        $data->type = $request->type;
        $data->plafond = preg_replace('/[^0-9]/', '',$request->plafond);
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'plafond'])->with('message-success', 'Data saved successfully');
    }

    public function deletePlafond($id)
    {
        LoanPlafond::destroy($id);

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'plafond'])->with('message-success', 'Data deleted successfully');
    }

    public function addRate()
    {
        return view('administrator.loan-setting.add-rate');
    }

    public function storeRate(Request $request)
    {
        $data = new LoanPaymentRate();
        $data->rate = $request->rate;
        $data->interest = $request->interest;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'rate'])->with('message-success', 'Data saved successfully');
    }

    public function editRate($id)
    {
        $params['data'] = LoanPaymentRate::find($id);

        return view('administrator.loan-setting.edit-rate')->with($params);
    }

    public function updateRate(Request $request, $id)
    {
        $data = LoanPaymentRate::where('id', $id)->first();
        $data->rate = $request->rate;
        $data->interest = $request->interest;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'rate'])->with('message-success', 'Data saved successfully');
    }

    public function deleteRate($id)
    {
        LoanPaymentRate::destroy($id);

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'rate'])->with('message-success', 'Data deleted successfully');
    }

    public function addAsset()
    {
        return view('administrator.loan-setting.add-asset');
    }

    public function storeAsset(Request $request)
    {
        $data = new LoanAssetType();
        $data->name = $request->name;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'asset'])->with('message-success', 'Data saved successfully');
    }

    public function editAsset($id)
    {
        $params['data'] = LoanAssetType::find($id);

        return view('administrator.loan-setting.edit-asset')->with($params);
    }

    public function updateAsset(Request $request, $id)
    {
        $data = LoanAssetType::where('id', $id)->first();
        $data->name = $request->name;
        $data->save();

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'asset'])->with('message-success', 'Data saved successfully');
    }

    public function deleteAsset($id)
    {
        LoanAssetType::destroy($id);

        return \Redirect::route('administrator.loan-setting.index', ['tab' => 'asset'])->with('message-success', 'Data deleted successfully');
    }
}
