<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InternalMemo;
use App\Models\InternalMemoFile;
use ZipArchive;
use Illuminate\Support\Facades\Config;

class InternalMemoController extends Controller
{

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
            $params['data'] = InternalMemo::orderBy('id', 'DESC')->join('users','users.id','=','internal_memo.user_created')->where('users.project_id', $user->project_id)->select('internal_memo.*')->get();
        } else
        {
            $params['data'] = InternalMemo::orderBy('id', 'DESC')->get();
        }
        return view('administrator.internal-memo.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        return view('administrator.internal-memo.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = InternalMemo::where('id', $id)->first();

        return view('administrator.internal-memo.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data                   = InternalMemo::where('id', $id)->first();
        $data->title            = $request->title;
        $data->content            = $request->content;
        $data->status            = $request->status;
        
        if (request()->hasFile('thumbnail'))
        {

            $file = $request->file('thumbnail');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            \Image::make(public_path('storage/internal-memo/'. $fileName))->fit(300, 210)->save(public_path('storage/internal-memo/'. $fileName));

            $data->thumbnail = $fileName;
        }

        if (request()->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            $data->image = $fileName;
        }

        if (request()->hasFile('file'))
        {
            $file = $request->file('file')[0];
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            $data->file = $fileName;
        }

        $data->save();

        if (request()->hasFile('file'))
        {
            InternalMemoFile::where('Internal_memo_id', $id)->delete();

            $files = $request->file('file');
            foreach ($files as $key => $file) {
                if ($key > 0) {
                    $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
    
                    $destinationPath = public_path('/storage/internal-memo/');
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = $data->file;
                }

                $datafiles = new InternalMemoFile();
                $datafiles->Internal_memo_id = $data->id;
                $datafiles->file = $fileName;
                $datafiles->save();
            }
        }

        if($data->status == '1'){

            foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'memo');
            }

            $config = [
                'title' => $data->title,
                'body' => strip_tags($data->content),
                'type' => 'memo',
                'app_type' => config('constants.apps.emhr_mobile_attendance'),
                'topic' => session('company_url','umum'),
                'data' => $data,
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
            $config['app_type'] = config('constants.apps.emhr_mobile');
            dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('administrator.internal-memo.index')->with('message-success', 'Data successfully saved !');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = InternalMemo::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.internal-memo.index')->with('message-success', 'Data successfully deleted');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                   = new InternalMemo();
        $data->title            = $request->title;
        $data->content          = $request->content;
        $data->status           = $request->status;

        
        
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
        }

        if (request()->hasFile('file'))
        {
            $file = $request->file('file')[0];
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            $data->file = $fileName;
        }


        if (request()->hasFile('thumbnail'))
        {
            $file = $request->file('thumbnail');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            \Image::make(public_path('storage/internal-memo/'. $fileName))->fit(300, 210)->save(public_path('storage/internal-memo/'. $fileName));

            $data->thumbnail = $fileName;
        }
        
        if (request()->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/internal-memo/');
            $file->move($destinationPath, $fileName);

            $data->image = $fileName;
        }
        
        if($user->project_id != Null){
            $data->user_created           = \Auth::user()->id;
        }else{
            $data->user_created           = "";
        }
        
        $data->save();

        if (request()->hasFile('file'))
        {
            $files = $request->file('file');
            foreach ($files as $key => $file) {
                if ($key > 0) {
                    $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
    
                    $destinationPath = public_path('/storage/internal-memo/');
                    $file->move($destinationPath, $fileName);
                } else {
                    $fileName = $data->file;
                }

                $dataFiles = new InternalMemoFile();
                $dataFiles->Internal_memo_id = $data->id;
                $dataFiles->file = $fileName;
                $dataFiles->save();
            }
        }

        if($data->status == '1'){

            foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'memo');
            }

            $config = [
                'title' => $data->title,
                'body' => strip_tags($data->content),
                'type' => 'memo',
                'app_type' => config('constants.apps.emhr_mobile_attendance'),
                'topic' => session('company_url','umum'),
                'data' => $data,
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
            $config['app_type'] = config('constants.apps.emhr_mobile');
            dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('administrator.internal-memo.index')->with('message-success', 'Data successfully saved !');
    }

    public function download($id)
    {
        $internalMemo = InternalMemo::where('id', $id)->first();
        if($internalMemo) {
            if($internalMemo->file == null || $internalMemo->file == '' || !file_exists(public_path('storage/internal-memo/') . $internalMemo->file)) {
                return redirect()->back()->with('message-error', 'File is not found!');
            }
            if (count($internalMemo->files) < 2) {
                return redirect('storage/internal-memo/'. $internalMemo->file);
            } else {
                $zip = new ZipArchive;
                if ($zip->open(public_path('storage/internal-memo/').$internalMemo->title.'.zip', ZipArchive::CREATE) === TRUE) {
                    for($i = 0; $i < $zip->numFiles; $i++) {
                        $zip->deleteIndex($i);
                    }
                    foreach ($internalMemo->files as $key => $value) {
                        $zip->addFile(public_path('storage/internal-memo/').$value->file, $value->file);  
                    }
                    $zip->close();
                }
                return \Response::download(public_path('storage/internal-memo/').$internalMemo->title.'.zip', $internalMemo->title.'.zip', ['Content-Type' => 'application/octet-stream']);
            }
        } else {
            return redirect()->back()->with('message-error', 'Invalid id!');
        }
    }
}
