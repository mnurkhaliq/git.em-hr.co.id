<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Models\InternalMemo;
use App\Models\News;
use App\Models\Product;
use App\Models\RecruitmentRequest;
use App\Http\Resources\RecruitmentResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\InternalMemoResource;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }

    public function getNews(Request $request){
        $currentUser = Auth::user();
        $result['news_list'] = NewsResource::collection(News::with(['images', 'author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'news.user_created','=','u.id')
            ->where(['news.status'=>1,'u.project_id'=>$currentUser->project_id])->orderBy('news.updated_at','desc')->select('news.*')->get());
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ],
            200
        );
    }

    public function getNewsDetail(Request $request, $id){
        $currentUser = Auth::user();
        $result['news'] = News::with(['images', 'author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'news.user_created','=','u.id')
            ->where(['news.status'=>1,'u.project_id'=>$currentUser->project_id,'news.id'=>$id])->select('news.*')->first();
        if($result['news']) {
            $result['news'] = new NewsResource($result['news']);

            return response()->json([
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ], 200);
        }
        else
            return response()->json([
                'status' => 'error',
                'message'=>'News is not found'
            ], 404);
    }

    public function getMemo(Request $request){
        $currentUser = Auth::user();
        $result['memos'] = InternalMemoResource::collection(InternalMemo::with(['files', 'author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'internal_memo.user_created','=','u.id')
            ->where(['internal_memo.status'=>1,'u.project_id'=>$currentUser->project_id])->orderBy('internal_memo.updated_at','desc')->select('internal_memo.*')->get());
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ],
            200
        );
    }

    public function getMemoDetail(Request $request, $id){
        $currentUser = Auth::user();
        $result['memo'] = InternalMemo::with(['files', 'author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'internal_memo.user_created','=','u.id')
            ->where(['internal_memo.status'=>1,'u.project_id'=>$currentUser->project_id,'internal_memo.id'=>$id])->select('internal_memo.*')->first();
        if($result['memo']) {
            $result['memo'] = new InternalMemoResource($result['memo']);

            return response()->json([
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ], 200);
        }
        else
            return response()->json([
                'status' => 'error',
                'message'=>'Memo is not found'
            ], 404);
    }

    public function getProduct(Request $request){
        $currentUser = Auth::user();
        $result['products'] = ProductResource::collection(Product::with(['author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'product.user_created','=','u.id')
            ->where(['product.status'=>1,'u.project_id'=>$currentUser->project_id])->orderBy('product.updated_at','desc')->select('product.*')->get());
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ],
            200
        );
    }

    public function getProductDetail(Request $request, $id){
        $currentUser = Auth::user();
        $result['product'] = Product::with(['author' => function($query){
            $query->select('id','nik','name');
        }])->leftJoin('users as u', 'product.user_created','=','u.id')
            ->where(['product.status'=>1,'u.project_id'=>$currentUser->project_id,'product.id'=>$id])->select('product.*')->first();
        if($result['product']) {
            $result['product'] = new ProductResource($result['product']);

            return response()->json([
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ], 200);
        }
        else
            return response()->json([
                'status' => 'error',
                'message'=>'Product is not found'
            ], 404);
    }

    public function getRecruitment(Request $request){
        $currentUser = Auth::user();
        $result['recruitments'] = RecruitmentResource::collection(RecruitmentRequest::with(['branch', 'internals' => function($query) use ($currentUser){
            $query->where('user_id', $currentUser->id);
        }])->join('recruitment_request_detail as rrd', 'recruitment_request.id','=','rrd.recruitment_request_id')
            ->where(['approval_hr'=>1,'approval_user'=>1,'rrd.status_post'=>1,'recruitment_type_id'=>1])->orderBy('posting_date', 'desc')->select('rrd.*', 'recruitment_request.*')->get());
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ],
            200
        );
    }

    public function getRecruitmentDetail(Request $request, $id){
        $currentUser = Auth::user();
        $result['recruitment'] = RecruitmentRequest::with(['branch', 'internals' => function($query) use ($currentUser){
            $query->where('user_id', $currentUser->id);
        }])->join('recruitment_request_detail as rrd', 'recruitment_request.id','=','rrd.recruitment_request_id')
            ->where(['approval_hr'=>1,'approval_user'=>1,'rrd.status_post'=>1,'recruitment_type_id'=>1,'recruitment_request.id'=>$id])->select('rrd.*', 'recruitment_request.*')->first();
        if($result['recruitment']) {
            $result['recruitment'] = new RecruitmentResource($result['recruitment']);

            return response()->json([
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $result
            ], 200);
        }
        else
            return response()->json([
                'status' => 'error',
                'message'=>'Product is not found'
            ], 404);
    }
}
