<?php

namespace App\Http\Controllers\Administrator;

use App\Models\StructureOrganizationCustom;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Response\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
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
            $params['data'] = Product::orderBy('id', 'DESC')->join('users','users.id','=','product.user_created')->where('users.project_id', $user->project_id)->select('product.*')->get();
        } else
        {
            $params['data'] = Product::orderBy('id', 'DESC')->get();
        }
        return view('administrator.product.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        return view('administrator.product.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = Product::where('id', $id)->first();

        return view('administrator.product.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data                   = Product::where('id', $id)->first();
        $data->title            = $request->title;
        $data->content            = $request->content;
        $data->status            = $request->status;
        
        if (request()->hasFile('file'))
        {
            $file = $request->file('file');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            $data->file = $fileName;
        }

        if (request()->hasFile('thumbnail'))
        {

            $file = $request->file('thumbnail');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            \Image::make(public_path('storage/product/'. $fileName))->fit(300, 210)->save(public_path('storage/product/'. $fileName));

            $data->thumbnail = $fileName;
        }
        
        if (request()->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            $data->image = $fileName;
        }


        $data->save();

        if($data->status == '1'){

            foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'product');
            }

            $config = [
                'title' => $data->title,
                'body' => strip_tags($data->content),
                'type' => 'product',
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

        return redirect()->route('administrator.product.index')->with('message-success', 'Data successfully saved !');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = Product::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.product.index')->with('message-success', 'Data successfully deleted !');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                   = new Product();
        $data->title            = $request->title;
        $data->content          = $request->content;
        $data->status            = $request->status;

        if (request()->hasFile('file'))
        {
            $file = $request->file('file');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            $data->file = $fileName;
        }

        if (request()->hasFile('thumbnail'))
        {
            $file = $request->file('thumbnail');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            \Image::make(public_path('storage/product/'. $fileName))->fit(300, 210)->save(public_path('storage/product/'. $fileName));

            $data->thumbnail = $fileName;
        }
        
        if (request()->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/product/');
            $file->move($destinationPath, $fileName);

            $data->image = $fileName;
        }



        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
        }else{
            $data->user_created           = "";
        }

        $data->save();

        if($data->status == '1'){

            foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'product');
            }

            $config = [
                'title' => $data->title,
                'body' => strip_tags($data->content),
                'type' => 'product',
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

        return redirect()->route('administrator.product.index')->with('message-success', 'Data successfully saved !');
    }

    public function testSendDevice(){
        $user = User::where('nik','6517')->first();
        $config = [
            'title' => "Ngetes Aja",
            'content' => "Ngetes ngirim notifikasi ke HP Pengguna",
            'type' => 'test',
            'app_type' => config('constants.apps.emhr_mobile_attendance'),
            'firebase_token' => $user->firebase_token
        ];
        $db = Config::get('database.default', 'mysql');
        Config::set('database.default', 'mysql');
        dispatch((new \App\Jobs\SendPushTokens(null,$config))->onQueue('push'));
        Config::set('database.default', $db);
        dd($response->tokensToDelete());
    }

//    public function testSendGroup(){
//        $structure = StructureOrganizationCustom::find(13);
//        $config = [
//            'title' => "Ngetes Kirim Group Aja",
//            'content' => "Ngetes ngirim notifikasi ke Group",
//            'type' => 'test',
//            'group_firebase_token' => $structure->group_firebase_token
//        ];
//        $response = \FCMHelper::sendToGroup(null,$config);
//        dd($response);
//    }
}
