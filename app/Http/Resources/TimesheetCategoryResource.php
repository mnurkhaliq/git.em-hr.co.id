<?php

namespace App\Http\Resources;

use App\Models\SettingApprovalTimesheetTransactionItem;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $id = $this->id;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'deleted_at' => (string)$this->deleted_at,
            'delete_status' => $this->delete_status,
            'timesheet_activity' => $this->timesheetActivity,
            'pics' => User::whereHas('settingApprovalTimesheet', function ($q) use ($id){
                $q->where('timesheet_category_id', $id);
            })->pluck('name')
        ];
    }
}
