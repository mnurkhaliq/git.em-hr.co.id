<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashAdvanceFormResource extends JsonResource
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
            'id'=> $this->id,
            'cash_advance_id'=> $this->cash_advance_id,
            'description'=> $this->description,
            'quantity'=> $this->quantity,
            'estimation_cost'=> $this->estimation_cost,
            'amount'=> $this->amount,
            'plafond'=> $this->plafond,
            'sisa_plafond'=> $this->sisa_plafond,
            'using_period' =>  $this->plafond != $this->sisa_plafond ? true : false,
            'note'=> $this->note,
            'nominal_approved'=> $this->nominal_approved,
            'actual_amount'=> $this->actual_amount,
            'nominal_claimed' => $this->nominal_claimed,
            'note_claimed' => $this->note_claimed,
            'file_struk'=>  $this->file_struk != null ? "/storage/cash-advance/file-struk/".$this->file_struk : NULL,
            'file_struk_raw' => $this->file_struk,
            'type_form'=> $this->type_form,
            'gasoline' => $this->bensin,
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at
        ];
    }
}
