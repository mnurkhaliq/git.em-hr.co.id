<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;
use App\Models\BirthdayLike;
use App\Models\BirthdayComment;
use App\Models\BirthdayCommentLike;
use App\Models\BirthdayWording;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BirthdayUserResource;

class BirthdayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }

    public function wording(){
        $data = BirthdayWording::get();
        return response()->json(
            [
                'status'    => 'success',
                'message'   => 'Successfully get data birthday wording!',
                'data'     => $data,
            ],
            200
        );
    }

    public function birthday(){
        $birthday = User::whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->with(['birthdayComment' => function($qry){
            $qry->with(['commentBy','birthdayCommentLike', 'children'])
                ->where('parent_id', NULL)->where('date', date('Y-m-d'))->orderBy('id', 'DESC');
        }])->with(['birthdayLike' => function($qry){
            $qry->with('likeBy')->where('date', date('Y-m-d'))->orderBy('id', 'DESC');
        }])->orderByRaw('IF(id = '.auth()->user()->id.', 0,1)')->get();

        return response()->json(
            [
                'status'    => 'success',
                'message'   => 'Successfully get data birthday!',
                'datas'     => BirthdayUserResource::collection($birthday),
            ],
            200
        );
    }

    public function birthdayDetail($id){
        $birthday = User::whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->with(['birthdayComment' => function($qry){
            $qry->with(['commentBy','birthdayCommentLike', 'children'])
                ->where('parent_id', NULL)->where('date', date('Y-m-d'))->orderBy('id', 'DESC');
        }])->with(['birthdayLike' => function($qry){
            $qry->with('likeBy')->where('date', date('Y-m-d'))->orderBy('id', 'DESC');
        }])->where('id', $id)->first();

        return response()->json(
            [
                'status'    => 'success',
                'message'   => 'Successfully get detail data birthday!',
                'data'     => new BirthdayUserResource($birthday),
            ],
            200
        );
    }

    public function like(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'status'  => 'required|in:1,0'
        ],
        [
            'user_id.required' => 'user birthday id does not exist!',
            'status.required' => 'status can not be empty!',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        if($request->status==1){
            $item = new BirthdayLike;
            $item->user_id = $request->user_id;
            $item->like_by = auth()->user()->id;
            $item->date = date('Y-m-d');
            $item->save();

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Success to like birthday'
                ], 201);
        }
        else if($request->status==0){
            BirthdayLike::where('user_id', $request->user_id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->delete();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Success to cancel like birthday'
                ], 201);
        }

    }

    public function comment(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'comment' => 'required',
        ],
        [
            'user_id.required' => 'user birthday id does not exist!',
            'comment.required' => 'comment can not be empty!',
        ]); 

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $item = new BirthdayComment;
        $item->user_id = $request->user_id;
        $item->comment_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->comment = $request->comment;
        $item->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Success to comment birthday'
            ], 201);
    }

    public function commentLike(Request $request){
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
            'status'  => 'required|in:1,0'
        ],
        [
            'comment_id.required' => 'comment id does not exist!',
            'status.required' => 'status can not be empty!',
        ]); 

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        if($request->status==1){
            $item = new BirthdayCommentLike;
            $item->comment_id = $request->comment_id;
            $item->like_by = auth()->user()->id;
            $item->date = date('Y-m-d');
            $item->save();

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Success to like comment birthday'
                ], 201);
        }
        else if($request->status==0){
            BirthdayCommentLike::where('comment_id', $request->comment_id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->delete();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Success to cancel like comment birthday'
                ], 201);
        }

        $item = new BirthdayCommentLike;
        $item->comment_id = $request->comment_id;
        $item->like_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Success to like comment birthday'
            ], 201);;
    }

    public function commentReply(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'comment' => 'required',
            'parent_id' => 'required',
        ],
        [
            'user_id.required' => 'user birthday id does not exist!',
            'parent_id.required' => 'parent comment id does not exist!',
            'comment.required' => 'comment can not be empty!',
        ]); 

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $item = new BirthdayComment;
        $item->user_id = $request->user_id;
        $item->comment_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->comment = $request->comment;
        $item->parent_id = $request->parent_id;
        $item->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Success to reply a comment in birthday'
            ], 201);
    }
    
}
