<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProfile extends Model
{
    protected $fillable = [
        'loan_application_id',
        'full_name',
        'birthday',
        'gender',
        'phone',
        'cccd_number',
        'address',
        'province',
        'district',
        'ward',
        'company',
        'job_title',
        'monthly_income',
        'bank_name',
        'bank_account',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
        ];
    }

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
