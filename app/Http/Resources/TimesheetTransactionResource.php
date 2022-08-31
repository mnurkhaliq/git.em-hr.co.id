<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetTransactionResource extends JsonResource
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
            'is_approvable'=> $this->status == 1,
            'date' => (string) $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_time' => $this->total_time,
            'duration' => $this->duration,
            'description' => $this->description,
            'admin_note' => $this->admin_note,
            'approval_note' => TimesheetHistoryApprovalNoteResource::collection($this->historyApprovalTimesheetNote),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
