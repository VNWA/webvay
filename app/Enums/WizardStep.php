<?php

namespace App\Enums;

enum WizardStep: string
{
    case Personal = 'personal';
    case Employment = 'employment';
    case Documents = 'documents';
    case AiProcessing = 'ai_processing';

    /** Đã gửi, chờ admin duyệt. */
    case PendingAdminReview = 'pending_admin_review';

    case Result = 'result';
    case ContractOtp = 'contract_otp';
    case Completed = 'completed';

    /** Admin từ chối — chỉ xem thông báo. */
    case ApplicationRejected = 'application_rejected';

    public function label(): string
    {
        return match ($this) {
            self::Personal => __('Personal'),
            self::Employment => __('Employment'),
            self::Documents => __('Documents'),
            self::AiProcessing => __('apply_step_validity'),
            self::PendingAdminReview => __('wizard_pending_admin'),
            self::Result => __('Offer'),
            self::ContractOtp => __('Contract'),
            self::Completed => __('Done'),
            self::ApplicationRejected => __('wizard_application_rejected'),
        };
    }
}
