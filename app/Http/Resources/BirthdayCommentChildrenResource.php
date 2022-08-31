<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayCommentChildrenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment_by' => $this->comment_by,
            'user_comment' => new UserMinResource($this->commentBy),
            'date'=> $this->date,
            'comment' => $this->comment,
            'parent_id' => $this->parent_id,
            'reply_time' => $this->created_at->diffForHumans(),
            'created_at' => $this->created_at,
        ];
    }
}
