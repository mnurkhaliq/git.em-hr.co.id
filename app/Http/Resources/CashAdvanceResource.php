<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashAdvanceResource extends JsonResource
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
            'number' => $this->number != null ? $this->number : '',
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
            'status_claim'=> $this->status_claim,
            'date_claim'=> $this->date_claim,
            'is_transfer' => $this->is_transfer,
            'transfer_proof' => $this->transfer_proof != null ? "/storage/cash-advance/transfer-proof/".$this->transfer_proof : NULL,
            'is_transfer_by' => $this->is_transfer_by,
            'disbursement' => $this->disbursement,
            'is_transfer_claim' => $this->is_transfer_claim,
            'transfer_proof_claim' => $this->transfer_proof_claim != null ? "/storage/cash-advance/transfer-proof/".$this->transfer_proof_claim : NULL,
            'is_transfer_claim_by' => $this->is_transfer_claim_by,
            'disbursement_claim' => $this->disbursement_claim,
            'total_amount_approved' => $this->cash_advance_form->sum('nominal_approved'),
            'total_amount_claimed' => $this->cash_advance_form->sum('nominal_claimed'),
            'can_approve' => !cek_cash_advance_id_approval_or_no($this->id) ? 'no' : 'yes',
            'can_transfer' => cek_transfer_setting_user() != null ? 'yes' : 'no',
            'history_approval' => CashAdvanceHistoryApprovalResource::collection($this->historyApproval),
            'details' => CashAdvanceFormResource::collection($this->cash_advance_form),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }


}
