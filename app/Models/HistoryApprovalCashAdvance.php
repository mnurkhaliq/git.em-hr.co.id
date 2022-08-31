<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryApprovalCashAdvance extends Model
{
    use HasFactory;
    protected $table = 'history_approval_cash_advance';

    public function cashAdvance()
    {
    	return $this->hasOne('App\Models\CashAdvance', 'id', 'cash_advance_id');
    }

    public function level()
    {
    	return $this->hasOne('App\Models\SettingApprovalLevel', 'id', 'setting_approval_level_id');
    }
    
    public function structure()
    {
    	return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }

    public function userApprovedClaim()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id_claim');
    }
}
