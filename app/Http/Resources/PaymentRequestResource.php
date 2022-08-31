<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRequestResource extends JsonResource
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
            'number' => $this->number,
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'tujuan' => $this->tujuan,
            'transaction_type'=> $this->transaction_type,
            'payment_method'=> $this->payment_method,
            'nama_pemilik_rekening'=> $this->nama_pemilik_rekening,
            'no_rekening'=> $this->no_rekening,
            'nama_bank'=> $this->nama_bank,
            'nominal_pembayaran'=> $this->nominal_pembayaran,
            'status'=> $this->status,
            'note_pembatalan' => $this->note_pembatalan,
            'history_approval' => PaymentRequestHistoryApprovalResource::collection($this->historyApproval),
            'details' => PaymentRequestFormResource::collection($this->payment_request_form),
            'is_transfer' => $this->is_transfer,
            'transfer_proof' => $this->transfer_proof != null ? "/storage/payment-request/transfer-proof/".$this->transfer_proof : NULL,
            'is_transfer_by' => $this->is_transfer_by,
            'disbursement' => $this->disbursement,
            'can_approve' => !cek_payment_request_id_approval_or_no($this->id) ? 'no' : 'yes',
            'can_transfer' => cek_transfer_setting_user() != null ? 'yes' : 'no',
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }


}
