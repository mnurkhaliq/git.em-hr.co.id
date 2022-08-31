<?php
/**
 * Created by PhpStorm.
 * User: baso
 * Date: 2020-08-04
 * Time: 10:06
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'nik' => $this->nik,
            'position' => get_position_name2($this->structure_organization_custom_id),
            'division' => get_division_name($this->structure_organization_custom_id),
        ];
    }
}