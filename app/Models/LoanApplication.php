<?php

namespace App\Models;

use App\Enums\LoanApplicationStatus;
use App\Enums\WizardStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LoanApplication extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'access_token',
        'desired_amount',
        'status',
        'wizard_step',
        'score',
        'approved_amount',
        'risk_level',
        'tenure_months',
        'monthly_payment',
        'ai_progress',
        'admin_decision',
        'admin_notes',
        'revision_message',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => LoanApplicationStatus::class,
            'wizard_step' => WizardStep::class,
            'ai_progress' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function ekyc(): HasOne
    {
        return $this->hasOne(EkycDocument::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    /**
     * Đảm bảo có bản ghi profile/eKYC để admin xem/sửa trên Filament (infolist + form quan hệ).
     */
    public function ensureDossierChildren(): void
    {
        $this->profile()->firstOrCreate([]);
        $this->ekyc()->firstOrCreate([]);
        $this->unsetRelation('profile');
        $this->unsetRelation('ekyc');
        $this->load(['profile', 'ekyc']);
    }

    /**
     * Chỉ hiển thị link PDF hợp đồng cho admin khi đã ký hoặc hồ sơ ở trạng thái hoàn tất
     * (tránh gợi ý “có PDF” khi mới tạo file nháp trước khi ký).
     */
    public function adminCanViewFinalContractPdf(): bool
    {
        $contract = $this->contract;
        if (! $contract || ! filled($contract->pdf_path)) {
            return false;
        }

        return $contract->signed_at !== null
            || $this->status === LoanApplicationStatus::Completed;
    }
}
