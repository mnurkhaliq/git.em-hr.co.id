<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetHistoryApprovalNoteResource extends JsonResource
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
            'history_approval_timesheet_id' => $this->history_approval_timesheet_id,
            'timesheet_transaction_id' => $this->timesheet_transaction_id,
            'activity_name' => ($this->timesheetTransaction->timesheetActivity ? $this->timesheetTransaction->timesheetActivity->name : $this->timesheetTransaction->timesheet_activity_name).' - '.($this->timesheetTransaction->timesheetCategory ? $this->timesheetTransaction->timesheetCategory->name : 'Other'),
            'status' => $this->timesheetTransaction->status,
            'is_approved' => $this->is_approved,
            'note'=> $this->note,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
