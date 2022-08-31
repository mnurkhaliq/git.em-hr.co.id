<?php

namespace App\Http\Controllers\Administrator;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequestType;
use App\Models\PaymentRequestForm;
use Illuminate\Support\Str;
use App\Models\Setting;

class PaymentRequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PaymentRequestType::get();
        $period_ca_pr = Setting::where('key', 'period_ca_pr')->first();
        return view('administrator.cash-advance-setting.type', compact('data', 'period_ca_pr'));
    }
    
    public function store(Request $request)
    {
        $word = PaymentRequestType::updateOrCreate(
            [
                'type' => $request->type
            ],[
                'type' => $request->type,
                'plafond' => $request->plafond ? preg_replace('/[^0-9]/', '', $request->plafond ) : NULL,
                'period' => $request->period,
                'description' => $request->description,
            ]
        );

        return redirect()->route('administrator.payment-request-type.index')->with('message-success', 'Data saved successfully !');
    }

    public function update(Request $request, $id)
    {
        $data = PaymentRequestType::find($id);
        $data->type = $request->type;
        $data->plafond = $request->plafond ? preg_replace('/[^0-9]/', '', $request->plafond ) : NULL;
        $data->period = $request->period;
        $data->description = $request->description;
        $data->save();

        return redirect()->route('administrator.payment-request-type.index')->with('message-success', 'Data saved successfully !');
    }

    public function destroy($id)
    {
        PaymentRequestType::destroy($id);
        return redirect()->route('administrator.payment-request-type.index')->with('message-success', 'Data deleted successfully !');
    }

    public function period(Request $request){
        // dd($request);
        $user = \Auth::user();

        if ($request->setting) {
            foreach ($request->setting as $key => $value) {
                $setting = Setting::where('key', $key)->first();
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

        return \Redirect::route('administrator.payment-request-type.index')->with('message-success', 'Data saved successfully');
    }
}
