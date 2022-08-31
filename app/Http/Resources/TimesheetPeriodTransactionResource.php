<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SettingApprovalTimesheetTransactionItem;

class TimesheetPeriodTransactionResource extends JsonResource
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
            'timesheet_category_id' => $this->timesheet_category_id,
            'timesheet_category_name' => $this->timesheet_category_id ? $this->timesheetCategory->name : null,
            'timesheet_activity_id' => $this->timesheet_activity_id,
            'timesheet_activity_name' => $this->timesheet_activity_id ? $this->timesheetActivity->name : $this->timesheet_activity_name,
            'status'=> $this->status,
            'is_editable'=> $this->status == 4 || $this->status == 3,
            'is_approvable'=> $this->status == 1 && SettingApprovalTimesheetTransactionItem::where('timesheet_category_id', $this->timesheet_category_id)->where('user_id', \Auth::user()->id)->first(),
            'date' => (string) $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_time' => $this->total_time,
            'duration' => $this->duration,
            'description' => $this->description,
            'approval_id'=> $this->approval_id,
            'approval_name' => $this->userApproved ? $this->userApproved->name : null,
            'approval_note' => $this->approval_note,
            'date_approved' => (string)$this->date_approved,
            'admin_note' => $this->admin_note,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
