<?php

namespace App\Models\CourseInstance;

use Illuminate\Database\Eloquent\Model;

class EventUserPayment extends Model
{
    protected $table = 'event_user_payments';

    protected $fillable = ['id', 'event_user_id', 'fee_type_descrp', 'amount_before_coupon', 'coupon_code', 'amount_after_coupon',
                            'transaction_successful', 'created_by', 'last_edited_by', 'created_at', 'updated_at'];

    public function eventUser()
    {
        return $this->belongsTo(EventUser::class,'event_user_id');
    }
}
