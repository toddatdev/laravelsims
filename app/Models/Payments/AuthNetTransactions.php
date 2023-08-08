<?php

namespace App\Models\Payments;

use App\Models\CourseInstance\EventUserPayment;
use Illuminate\Database\Eloquent\Model;

class AuthNetTransactions extends Model
{
    protected $table;

    protected $fillable = ['event_user_payment_id', 'transaction_id', 'type', 'payload', 'exception'];

    /**
     * Get the event_user_payment that owns the transaction.
     */
    public function eventUserPayment()
    {
        return $this->belongsTo(EventUserPayment::class);
    }

}
