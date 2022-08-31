<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayLikeResource extends JsonResource
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
            'like_by' => $this->like_by,
            'user_like' => new UserMinResource($this->likeBy),
            'date'=> $this->date,
            'user_id' => $this->user_id,
        ];
    }
}
