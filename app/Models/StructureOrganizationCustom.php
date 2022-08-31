<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureOrganizationCustom extends Model
{
    protected $table = 'structure_organization_custom';

    public function title()
    {
    	return $this->hasOne('\App\Models\OrganisasiTitle', 'id', 'organisasi_title_id');
    }
    public function division()
    {
    	return $this->hasOne('\App\Models\OrganisasiDivision', 'id', 'organisasi_division_id');
    }
    public function position()
    {
    	return $this->hasOne('\App\Models\OrganisasiPosition', 'id', 'organisasi_position_id');
    }
    public function grade()
    {
        return $this->hasOne('\App\Models\Grade', 'id', 'grade_id');
    }
    public function grades()
    {
        return $this->hasMany('\App\Models\Grade', 'id', 'grade_id');
    }
    
}
