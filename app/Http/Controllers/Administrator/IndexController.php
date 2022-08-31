<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\Cabang;
use DB;
use App\Models\Directorate;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->project_id != null) {
            $division = OrganisasiDivision::where('project_id', \Auth::user()->project_id)->orderBy('name', 'asc')->get();
            $position = OrganisasiPosition::where('project_id', \Auth::user()->project_id)->orderBy('name', 'asc')->get();
            $cabang = Cabang::where('project_id', \Auth::user()->project_id)->orderBy('name', 'asc')->get();
        } else {
            $division = OrganisasiDivision::orderBy('name', 'asc')->get();
            $position = OrganisasiPosition::orderBy('name', 'asc')->get();
            $cabang = Cabang::orderBy('name', 'asc')->get();
        }

        return view('administrator.dashboard', compact('division', 'position', 'cabang'));
    }
    /**
     * [updateProfile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateProfile(Request $request)
    {
        $user = User::where('id', \Auth::user()->id)->first();
        
        if($user)
        {   
            // cek nik 
            if($user->nik != $request->nik)
            {
                $getOtherUser = User::where('id', \Auth::user()->id)->where('nik', $request->nik)->first();
                if($getOtherUser)
                {
                    return redirect()->route('administrator.profile')->with('message-error', 'NIK sudah dipakai oleh Karyawan lain !');
                }
                else
                {
                    $user->nik = $request->nik;
                }
            }

            if($user->email != $request->email) $user->email = $request->email;

            $user->save();

            return redirect()->route('administrator.profile')->with('message-success', 'Data Profil berhasil di simpan !');
        } 
    }

    /**
     * [setting description]
     * @return [type] [description]
     */
    public function setting()
    {
        return view('administrator.setting');
    }

    /**
     * [structure description]
     * @return [type] [description]
     */
    public function structure()
    {
        $params['directorate'] = Directorate::all();

        return view('administrator.structure')->with($params);
    }

    /**
     * [profile description]
     * @return [type] [description]
     */
    public function profile()
    {
        $params['data'] = \Auth::user();
        
        return view('administrator.profile')->with($params);
    }

    public function switchToEmployee()
    {
//            session(['access'=>'admin']);
        return redirect()->route('karyawan.dashboard')->with('message-success', 'Welcome as Employee');
    }
}