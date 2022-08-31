<?php
/**
 * Created by PhpStorm.
 * User: baso
 * Date: 2020-08-04
 * Time: 10:05
 */
namespace App\Http\Resources;


use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiItemResources extends JsonResource
{
    public function toArray($request)
    {
        if($this->user!=null)
            $user = $this->user;
        else
            $user = User::find($this->id_user);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'date_out' => $this->date_out,
            'date_shift' => $this->date_shift,
            'timetable' => $this->timetable,
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'late' => $this->late,
            'early' => $this->early,
            'work_time' => $this->work_time,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'long' => $this->long,
            'lat' => $this->lat,
            'pic' => str_replace("upload/attendance", "", $this->pic),
            'pic_out' => str_replace("upload/attendance", "", $this->pic_out),
            'long_out' => $this->long_out,
            'lat_out' => $this->lat_out,
            'lat_office_in' => $this->lat_office_in,
            'long_office_in' => $this->long_office_in,
            'radius_office_in' => $this->radius_office_in,
            'lat_office_out' => $this->lat_office_out,
            'long_office_out' => $this->long_office_out,
            'radius_office_out' => $this->radius_office_out,
            'timezone' => $this->timezone,
            'attendance_type_in' => $this->attendance_type_in,
            'attendance_type_out' => $this->attendance_type_out,
            'justification_in' => $this->justification_in,
            'justification_out' => $this->justification_out,
            'cabang_in' => $this->cabangIn?$this->cabangIn->name:null,
            'cabang_out' => $this->cabangOut?$this->cabangOut->name:null,
            'location_name_in' => $this->location_name_in,
            'location_name_out' => $this->location_name_out,
            'shift_type' => $this->shift_type,
            'shift_id' => $this->shift_id,
            'shift_justification' => $this->shift_justification,
            'shift' => $this->shift,
            'user' => new UserResources($user),
        ];
    }
}