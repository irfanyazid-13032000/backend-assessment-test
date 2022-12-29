<?php

namespace App\Models;

use App\Models\ScheduledRepayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduledRepayment extends Model
{
    use HasFactory;

    public const STATUS_DUE = 'due';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_REPAID = 'repaid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scheduled_repayments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //
    ];

    /**
     * A Scheduled Repayment belongs to a Loan
     *
     * @return BelongsTo
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function createScheduledRepayment(Request $request)
    {
        DB::table('scheduled_repayments')->insert([
            'loan_id' => $request->id,
            'amount' => 1666,
            'outstanding_amount' => 1666,
            'currency_code' => $currencyCode,
            'due_date' => '2020-02-20',
            'status' => ScheduledRepayment::STATUS_DUE,
        ]);
    }


}
