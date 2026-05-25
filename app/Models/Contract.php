<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'loan_application_id',
        'code',
        'pdf_path',
        'signed_at',
        'signing_ip',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
