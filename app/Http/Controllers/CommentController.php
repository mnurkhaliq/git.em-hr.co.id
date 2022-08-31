<?php

namespace App\Http\Controllers;

use App;
use App\Models\CrmBlog;
use Illuminate\Http\Request;
use App\Models\CrmBlogComment;
use App\Models\CrmCommentUser;
use Log;
use Session;

class CommentController extends Controller
{
    public function __construct()
    {
        App::setLocale("id");
    }

    public function store(Request $request)
    {

        if($request->ajax())
        {
            $comment = new CrmBlogComment();
            $comment_user = new CrmCommentUser();

            $user_id = "";

            $data = CrmCommentUser::where('email', $request->user_email_comment)->first();
            if(!$data){
                $comment_user->username = $request->user_name_comment;
                $comment_user->email = $request->user_email_comment;
                $comment_user->phone = $request->user_phone_comment ?: '';
                $comment_user->position = $request->user_phone_comment ?: '';
                $comment_user->save();
                $user_id = $comment_user->id;
            }else{
                $user_id = $data->id;
            }
            
            $comment->name = $request->name;
            $comment->blog_id = $request->blog_id;
            $comment->user_id = $user_id;
            $comment->is_publish = true;

            $comment->save();

            return response()->json(['success'=>'Komentar berhasil ditambahkan...', 'data' => $comment]);
            
        }

    
    }

    public function loginUser(Request $request)
    {

        if($request->ajax())
        {

            // $params = CrmCommentUser::select()->where('email', $request->email)->get();
            $data = CrmCommentUser::where('email', $request->email)->first();

            Session::put('user', [
                'isSessionUser' => true,
                'name' => $data->username,
                'email' => $data->email,
                'phone' => $data->phone,
                'position' => $data->position,
                'id_user' => $data->id,
            ]);
    
            return response()->json(['message' => 'success', 'data' => $data]);

            
        }

    
    }

    public function saveUser(Request $request)
    {

        if($request->ajax())
        {

            $data = CrmCommentUser::where('email', $request->email)->first();
            $comment_user = new CrmCommentUser();
            if($data){
                return response()->json(['status'=>'failed', 'message'=>'Email telah terdaftar...']);   
            }else{
                $comment_user->username = $request->name;
                $comment_user->email = $request->email;
                $comment_user->phone = $request->phone ?: '';
                $comment_user->position = $request->position ?: '';
                $comment_user->save();

                Session::put('user', [
                    'isSessionUser' => true,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'position' => $request->position,
                    'id_user' => $comment_user->id,
                ]);
                return response()->json(['status'=>'success', 'message'=>'User berhasil ditambahkan...']);
            }
    
            
        }

    
    }

    public function reply(Request $request)
    {

        if($request->ajax())
        {
            $comment = new CrmBlogComment();
            $comment_user = new CrmCommentUser();

            $user_id = "";

            $data = CrmCommentUser::where('email', $request->user_email_comment)->first();
            if(!$data){
                $comment_user->username = $request->user_name_comment;
                $comment_user->email = $request->user_email_comment;
                $comment_user->phone = $request->user_phone_comment ?: '';
                $comment_user->position = $request->user_phone_comment ?: '';
                $comment_user->save();
                $user_id = $comment_user->id;
            }else{
                $user_id = $data->id;
            }

            $comment->name = $request->name;
            $comment->parent_id = $request->parent_id;
            $comment->user_id = $user_id;
            $comment->is_publish = true;
    
            $comment->save();
            return response()->json(['success'=>'Komentar berhasil ditambahkan...']);
        }
    
    }
}
