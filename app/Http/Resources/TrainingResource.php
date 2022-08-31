<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id'=> $this->id,
            'number' => $this->number != null ? $this->number : '',
            'user_id'=> $this->user_id,
            'user' => new UserMinResource($this->user),
            'training_type_id'=> $this->training_type?$this->training_type->name:"",
            'lokasi_kegiatan'=> $this->lokasi_kegiatan,
            'tempat_tujuan'=> $this->tempat_tujuan,
            'topik_kegiatan'=> $this->topik_kegiatan,
            'tanggal_kegiatan_start'=> $this->tanggal_kegiatan_start,
            'tanggal_kegiatan_end'=> $this->tanggal_kegiatan_end,
            'pengambilan_uang_muka'=> $this->pengambilan_uang_muka,
            'tanggal_pengajuan'=> $this->tanggal_pengajuan,
            'tanggal_penyelesaian'=> date('Y-m-d', strtotime($this->tanggal_pengajuan.' +'.(get_setting('settlement_duration') ?: 10).' day')),
            'tipe_perjalanan'=> $this->tipe_perjalanan,
            'transportasi_berangkat'=> $this->transportasi_berangkat,
            'rute_dari_berangkat'=> $this->rute_dari_berangkat,
            'rute_tujuan_berangkat'=> $this->rute_tujuan_berangkat,
            'tipe_kelas_berangkat'=> $this->tipe_kelas_berangkat,
            'nama_transportasi_berangkat'=> $this->nama_transportasi_berangkat,
            'tanggal_berangkat'=> $this->tanggal_berangkat,
            'waktu_berangkat'=> $this->waktu_berangkat,
            'transportasi_pulang'=> $this->transportasi_pulang,
            'rute_dari_pulang'=> $this->rute_dari_pulang,
            'rute_tujuan_pulang'=> $this->rute_tujuan_pulang,
            'tipe_kelas_pulang'=> $this->tipe_kelas_pulang,
            'nama_transportasi_pulang'=> $this->nama_transportasi_pulang,
            'tanggal_pulang'=> $this->tanggal_pulang,
            'waktu_pulang'=> $this->waktu_pulang,
            'status'=> $this->status,
            'status_actual_bill'=> $this->status_actual_bill,
            'note_pembatalan'=> $this->note_pembatalan,
            'sub_total_1'=> $this->sub_total_1,
            'sub_total_2'=> $this->sub_total_2,
            'sub_total_3'=> $this->sub_total_3,
            'sub_total_4'=> $this->sub_total_4,
            'total_claimed'=> isset($this->sub_total_1) ? (int) $this->sub_total_1 + (int) $this->sub_total_2 + (int) $this->sub_total_3 + (int) $this->sub_total_4 : null,
            'sub_total_1_disetujui'=> $this->sub_total_1_disetujui,
            'sub_total_2_disetujui'=> $this->sub_total_2_disetujui,
            'sub_total_3_disetujui'=> $this->sub_total_3_disetujui,
            'sub_total_4_disetujui'=> $this->sub_total_4_disetujui,
            'total_approved'=> isset($this->sub_total_1_disetujui) ? (int) $this->sub_total_1_disetujui + (int) $this->sub_total_2_disetujui + (int) $this->sub_total_3_disetujui + (int) $this->sub_total_4_disetujui : null,
            'total_reimbursement_disetujui'=> isset($this->sub_total_1_disetujui) ? (int) $this->sub_total_1_disetujui + (int) $this->sub_total_2_disetujui + (int) $this->sub_total_3_disetujui + (int) $this->sub_total_4_disetujui - (int) $this->pengambilan_uang_muka : null,
            'pergi_bersama'=> $this->pergi_bersama,
            'note'=> $this->note,
            'is_transfer' => $this->is_transfer,
            'transfer_proof' => $this->transfer_proof != null ? "/storage/training-custom/transfer-proof/".$this->transfer_proof : NULL,
            'is_transfer_by' => $this->is_transfer_by,
            'disbursement' => $this->disbursement,
            'is_transfer_claim' => $this->is_transfer_claim,
            'transfer_proof_claim' => $this->transfer_proof_claim != null ? "/storage/training-custom/transfer-proof/".$this->transfer_proof_claim : NULL,
            'is_transfer_claim_by' => $this->is_transfer_claim_by,
            'disbursement_claim' => $this->disbursement_claim,
            'can_approve' => !cek_training_id_approval_or_no($this->id) ? 'no' : 'yes',
            'can_transfer' => cek_transfer_setting_user() != null ? 'yes' : 'no',
            'history_approval' => TrainingHistoryApprovalResource::collection($this->historyApproval),
            'acomodations' => TrainingAcomodationResource::collection($this->training_acomodation),
            'allowances' => TrainingAllowanceResource::collection($this->training_allowance),
            'dailies' => TrainingDailyResource::collection($this->training_daily),
            'others' => TrainingOtherResource::collection($this->training_other),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
