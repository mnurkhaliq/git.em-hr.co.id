<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestPaySlipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent=>=>toArray($request);
        return [
            'id'=> $this->id,
            'user_id'=> $this->user_id,
            'status'=> $this->status,
            'created_at'=> (string) $this->created_at,
            'updated_at'=> (string) $this->updated_at,
            'note'=> $this->note,
            'items' => $this->items
        ];
    }
}
