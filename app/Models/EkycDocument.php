<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EkycDocument extends Model
{
    protected $fillable = [
        'loan_application_id',
        'front_path',
        'back_path',
        'holding_front_path',
        'holding_back_path',
        'selfie_path',
        'ocr_json',
    ];

    protected function casts(): array
    {
        return [
            'ocr_json' => 'array',
        ];
    }

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
