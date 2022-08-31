<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use File;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Response\Exceptions\InvalidRequestException;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void*/

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
        //$params['data'] = \App\News::where('status', 1)->orderBy('id', 'DESC')->get();
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['data'] = News::orderBy('id', 'DESC')->join('users','users.id','=','news.user_created')->where('users.project_id', $user->project_id)->select('news.*')->paginate(50);
        } else
        {
            $params['data'] = News::orderBy('id', 'DESC')->paginate(50);
        }
        return view('administrator.news.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        return view('administrator.news.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = News::where('id', $id)->first();

        return view('administrator.news.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'             => 'required|unique:news,title,'.$id,
            'content'          => 'required',
        ],[
            'title.required' => 'Title must be completed!',
            'title.unique' => 'title is already used, Please use another title!',
            'content.required' => 'Content must be completed!',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        $data                   = News::where('id', $id)->first();
        $data->title            = $request->title;
        $data->content          = $request->content;
        $data->status           = $request->status;
        $notification           = $data->notification;
        $data->notification     = $request->status == 1 && $notification == 0 ? 1 : $notification;

        if (request()->hasFile('thumbnail'))
        {

            $file = $request->file('thumbnail');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/news/');
            $file->move($destinationPath, $fileName);

            \Image::make(public_path('storage/news/'. $fileName))->save(public_path('storage/news/'. $fileName));

            $data->thumbnail = $fileName;
        }

        $data->save();

        if($data->status == '1' && $notification == 0){
            foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'news');
            }

            $config = [
                'title' => $data->title,
                'body' => strip_tags($data->content),
                'type' => 'news',
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

        return response()->json(['status'=>"success", 'news_id'=>$data->id]);
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = News::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.news.index')->with('message-success', 'Data successfully deleted');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'title'             => 'required|unique:news',
            'content'          => 'required',
        ],[
            'title.required' => 'Title must be completed!',
            'title.unique' => 'title is already used, Please use another title!',
            'content.required' => 'Content must be completed!',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        if($request->imageReject == 0){
            $data                   = new News();
            $data->title            = $request->title;
            $data->content          = $request->content;
            $data->status           = $request->status;
            $data->notification     = $request->status == 1 ? 1 : 0;
            
            if (request()->hasFile('thumbnail'))
            {
                $file = $request->file('thumbnail');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

                $destinationPath = public_path('/storage/news/');
                $file->move($destinationPath, $fileName);

                \Image::make(public_path('storage/news/'. $fileName))->save(public_path('storage/news/'. $fileName));

                $data->thumbnail = $fileName;
            }
        
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data->user_created = $user->id;
            }else{
                $data->user_created = "";
            }

            $data->save();

            if($data->status == '1'){

                foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'news');
                }
    
                $config = [
                    'title' => $data->title,
                    'body' => strip_tags($data->content),
                    'type' => 'news',
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

            // return redirect()->route('administrator.news.index')->with('message-success', 'Data successfully saved !');
            return response()->json(['status'=>"success", 'news_id'=>$data->id]);
        }
        else{
            return response()->json(['status'=>"error", 'message'=>'Please remove image reject']);
        }
    }

    public function storeFile(Request $request){
        if($request->file('file')){
            if (request()->hasFile('file'))
            {
                $files = $request->file('file');
                foreach ($files as $key => $file) {
                    $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();        
                    $destinationPath = public_path('/storage/news/');
                    $file->move($destinationPath, $fileName);
    
                    $dataImages = new NewsImage();
                    $dataImages->news_id = $request->news_id;
                    $dataImages->image = $fileName;
                    $dataImages->save();
                }
            }
            return response()->json(['status'=>"success"]);
        }
    }

    public function deleteFile($id){
        $data = NewsImage::find($id);
        if($data->image != null){
            $destinationPath = public_path('/storage/news/');
            File::delete($destinationPath.$data->image);
        }

        $id = $data->news_id;
        $data->delete();

        return redirect()->route('administrator.news.edit', $id)->with('message-success', 'Image detail successfully deleted');
    }
}
