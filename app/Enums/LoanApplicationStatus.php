<?php

namespace App\Enums;

enum LoanApplicationStatus: string
{
    /** Khách đang điền / chỉnh sửa hồ sơ (chưa gửi xong). */
    case InProgress = 'in_progress';

    case AiProcessing = 'ai_processing';

    /** Hồ sơ hợp lệ (AI), đang chờ quản trị phê duyệt. */
    case PendingAdmin = 'pending_admin';

    /** Admin yêu cầu khách chỉnh sửa bổ sung. */
    case NeedsRevision = 'needs_revision';

    /** Admin đã duyệt — tiếp tục đề xuất & ký hợp đồng. */
    case Approved = 'approved';

    /** Admin từ chối hồ sơ. */
    case Rejected = 'rejected';

    case ContractPending = 'contract_pending';

    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => __('loan_status_in_progress'),
            self::AiProcessing => __('loan_status_ai_processing'),
            self::PendingAdmin => __('loan_status_pending_admin'),
            self::NeedsRevision => __('loan_status_needs_revision'),
            self::Approved => __('loan_status_admin_approved'),
            self::Rejected => __('loan_status_admin_rejected'),
            self::ContractPending => __('loan_status_contract_pending'),
            self::Completed => __('loan_status_completed'),
        };
    }

    /**
     * Bước portal gợi ý khi admin chỉ chọn trạng thái (ẩn trường bước wizard trên panel).
     */
    public function suggestedWizardStep(): WizardStep
    {
        return match ($this) {
            self::InProgress => WizardStep::Personal,
            self::AiProcessing => WizardStep::AiProcessing,
            self::PendingAdmin => WizardStep::PendingAdminReview,
            self::NeedsRevision => WizardStep::Personal,
            self::Approved => WizardStep::Result,
            self::Rejected => WizardStep::ApplicationRejected,
            self::ContractPending => WizardStep::ContractOtp,
            self::Completed => WizardStep::Completed,
        };
    }
}
