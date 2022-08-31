<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;
use App\Models\BirthdayLike;
use App\Models\BirthdayComment;
use App\Models\BirthdayCommentLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BirthdayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function like($id, Request $request){
        $item = new BirthdayLike;
        $item->user_id = $id;
        $item->like_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->save();
        if($id==auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to like birthday');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to like birthday');
        }
        
    }

    public function unlike($id, Request $request){
        BirthdayLike::where('user_id', $id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->delete();
        if($id==auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to cancel birthday');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to cancel like birthday');
        }
    }

    public function comment($id, Request $request){
        $out = '';
        for ($i = 0; $i < mb_strlen($request->comment); $i++) {
            $out .= $this->emoji_to_unicode(mb_substr($request->comment, $i, 1));
        }
        $out = str_replace('<p>','',$out);
        $out = str_replace('</p>','',$out);

        $item = new BirthdayComment;
        $item->user_id = $id;
        $item->comment_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->comment = $out;
        $item->save();
        if($id==auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to comment');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to comment');
        }
    }

    public function commentLike($id, Request $request){
        $item = new BirthdayCommentLike;
        $item->comment_id = $id;
        $item->like_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->save();

        $cek = BirthdayComment::find($id);
        if($cek->user_id ==auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to comment like birthday');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to comment like birthday');
        }
    }

    public function commentUnlike($id, Request $request){
        BirthdayCommentLike::where('comment_id', $id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->delete();
        $cek = BirthdayComment::find($id);
        if($cek->user_id == auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to cancel comment birthday');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to cancel like comment birthday');
        }
    }

    function emoji_to_unicode($emoji) {
        if (mb_ord($emoji) < 256) return $emoji;
        $json = json_encode($emoji);
        // $emoji = mb_convert_encoding($emoji, 'UTF-32', 'UTF-8');
        // $unicode = strtoupper(preg_replace("/^[0]{3}/","U+",bin2hex($emoji)));
        return str_replace('"','',$json);
     }

    public function commentReply($id, Request $request){
        $out = '';
        for ($i = 0; $i < mb_strlen($request->comment); $i++) {
            $out .= $this->emoji_to_unicode(mb_substr($request->comment, $i, 1));
        }
        $out = str_replace('<p>','',$out);
        $out = str_replace('</p>','',$out);

        $item = new BirthdayComment;
        $item->user_id = $request->user_id;
        $item->comment_by = auth()->user()->id;
        $item->date = date('Y-m-d');
        $item->comment = $out;
        $item->parent_id = $id;
        $item->save();
        if($request->user_id==auth()->user()->id){
            return redirect()->route('karyawan.notification.more', ['tab' => 'myBirthday'])->with('message-success', 'Success to like birthday');
        }
        else{
            return redirect()->route('karyawan.notification.more', ['tab' => 'otherBirthday'])->with('message-success', 'Success to comment');
        }
    }
    
}
