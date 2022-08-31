<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BirthdayCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        \Carbon\Carbon::setLocale('en');
        return [
            'id' => $this->id,
            'comment_by' => $this->comment_by,
            'user_comment' => new UserMinResource($this->commentBy),
            'date'=> $this->date,
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'comment_like' => BirthdayCommentLikeResource::collection($this->birthdayCommentLike),
            'count_comment_like' => count($this->birthdayCommentLike),
            'comment_reply' => BirthdayCommentChildrenResource::collection($this->children),
            'count_comment_reply' => count($this->children),
            'status_like' => cek_user_comment_like($this->id) ? true: false,
            'can_reply' => cek_user_comment_reply($this->user_id, $this->id) ? true : false,
            'comment_time' => $this->created_at->diffForHumans(),
            'created_at' => $this->created_at,
        ];
    }
}
