<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    use HasFactory; 

    protected $table = 'cash_advance';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * [payment_request_form description]
     * @return [type] [description]
     */
    public function cash_advance_form()
    {
    	return $this->hasMany('App\Models\CashAdvanceForm', 'cash_advance_id', 'id');
    }

    /**
     * @return [type]
     */
    
    public function historyApproval()
    {
        return $this->hasMany('\App\Models\HistoryApprovalCashAdvance', 'cash_advance_id', 'id')->orderBy('setting_approval_level_id', 'ASC');
    }
}
