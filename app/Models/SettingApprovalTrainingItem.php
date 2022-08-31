<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingApprovalTrainingItem extends Model
{
    //
    protected $table = 'setting_approval_training_item';

    public function structureApproval()
    {
    	return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function Approval()
    {
    	return $this->hasOne('\App\Models\SettingApproval', 'id', 'setting_approval_id');
    }

    public function ApprovalLevel()
    {
    	return $this->hasOne('\App\Models\SettingApprovalLevel', 'id', 'setting_approval_level_id');
    }
}
