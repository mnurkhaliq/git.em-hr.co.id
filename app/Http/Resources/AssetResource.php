<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'asset_number' => $this->asset_number,
            'asset_name' => $this->asset_name,
            'asset_type' => $this->asset_type?$this->asset_type->name:null,
            'asset_pic' => $this->asset_type?$this->asset_type->pic_department:null,
            'asset_sn' => $this->asset_sn,
            'spesification' => $this->spesifikasi,
            'asset_condition' => $this->asset_condition,
            'assign_to' => $this->assign_to,
            'pic_id' => $this->pic_id,
            'pic' => new UserMinResource($this->pic),
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'remark' => $this->remark,
            'status' => $this->status,
            'status_mobil' => $this->status_mobil,
            'purchase_date' => $this->purchase_date,
            'handover_date' => (string) $this->handover_date,
            'user_note' => $this->user_note_by == $this->user_id ? $this->user_note : null,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'term_agreement' => get_setting('term_and_agreement_asset') == '' ? NULL : get_setting('term_and_agreement_asset'),
        ];
    }
}
