<?php

use App\Http\Controllers\AdminPrivateFileController;
use App\Http\Controllers\ApplicationLookupController;
use App\Http\Controllers\ApplicationPublicSummaryController;
use App\Http\Controllers\Apply\AiReviewController;
use App\Http\Controllers\Apply\ApplicationPortalController;
use App\Http\Controllers\Apply\LoanOutcomeController;
use App\Http\Controllers\Apply\WizardController;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::get('/gioi-thieu', [SitePageController::class, 'about'])->name('pages.about');
Route::get('/dich-vu', [SitePageController::class, 'services'])->name('pages.services');

Route::get('/tin-tuc', [BlogPostController::class, 'index'])->name('blog.index');
Route::get('/tin-tuc/{post}', [BlogPostController::class, 'show'])->name('blog.show');

Route::get('/lien-he', [ContactController::class, 'create'])->name('contact.create');
Route::post('/lien-he', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::get('/ho-so/tra-cuu', [ApplicationLookupController::class, 'create'])->name('application.lookup.create');
Route::post('/ho-so/tra-cuu', [ApplicationLookupController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('application.lookup.store');
Route::get('/ho-so/tra-cuu/chi-tiet', [ApplicationLookupController::class, 'dossier'])->name('application.lookup.dossier');
Route::get('/ho-so/tra-cuu/anh/{loanApplication}/{field}', [ApplicationLookupController::class, 'lookupImage'])
    ->where('field', 'front|back|holding_front|holding_back|selfie')
    ->name('application.lookup.image');

Route::get('/ho-so/{application}/tom-tat', [ApplicationPublicSummaryController::class, 'show'])
    ->middleware('signed')
    ->name('application.public.summary');

Route::get('/ho-so/{application}/anh/{field}', [ApplicationPublicSummaryController::class, 'image'])
    ->middleware('signed')
    ->where('field', 'front|back|holding_front|holding_back|selfie')
    ->name('application.public.image');

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::get('/staff/ekyc/{loanApplication}/{field}', [AdminPrivateFileController::class, 'ekyc'])
        ->where('field', 'front|back|holding_front|holding_back|selfie')
        ->name('staff.files.ekyc');
    Route::get('/staff/contract-pdf/{contract}', [AdminPrivateFileController::class, 'contractPdf'])
        ->name('staff.files.contract');
});

Route::prefix('apply')->group(function (): void {
    Route::get('/start', [OtpAuthController::class, 'create'])->name('apply.start');
    Route::post('/otp', [OtpAuthController::class, 'store'])
        ->middleware('throttle:otp-send')
        ->name('apply.otp.store');
    Route::get('/verify', [OtpAuthController::class, 'verifyForm'])->name('apply.verify.show');
    Route::post('/verify', [OtpAuthController::class, 'verify'])
        ->middleware('throttle:otp-verify')
        ->name('apply.verify.submit');

    Route::middleware(['loan.app.access'])->prefix('{reference}')->group(function (): void {
        Route::get('/', [ApplicationPortalController::class, 'entry'])->name('apply.ref.entry');

        Route::get('/personal', [WizardController::class, 'personal'])->name('apply.ref.personal');
        Route::post('/personal', [WizardController::class, 'savePersonal'])->name('apply.ref.personal.save');

        Route::get('/employment', [WizardController::class, 'employment'])->name('apply.ref.employment');
        Route::post('/employment', [WizardController::class, 'saveEmployment'])->name('apply.ref.employment.save');

        Route::get('/documents', [WizardController::class, 'documents'])->name('apply.ref.documents');
        Route::post('/documents', [WizardController::class, 'saveDocuments'])->name('apply.ref.documents.save');

        Route::get('/ai', [AiReviewController::class, 'show'])->name('apply.ref.ai');
        Route::get('/ai/status', [AiReviewController::class, 'status'])->name('apply.ref.ai.status');

        Route::get('/cho-duyet', [LoanOutcomeController::class, 'pendingAdmin'])->name('apply.ref.pending');
        Route::get('/tu-choi', [LoanOutcomeController::class, 'rejected'])->name('apply.ref.rejected');

        Route::get('/result', [LoanOutcomeController::class, 'result'])->name('apply.ref.result');
        Route::post('/result/continue', [LoanOutcomeController::class, 'continueToContract'])->name('apply.ref.result.continue');

        Route::get('/contract', [LoanOutcomeController::class, 'contract'])->name('apply.ref.contract');
        Route::get('/contract/pdf-status', [LoanOutcomeController::class, 'contractPdfStatus'])->name('apply.ref.contract.pdf-status');
        Route::get('/contract/download', [LoanOutcomeController::class, 'downloadContract'])->name('apply.ref.contract.download');
        Route::post('/contract/otp', [LoanOutcomeController::class, 'sendContractOtp'])->name('apply.ref.contract.otp');
        Route::post('/contract/confirm', [LoanOutcomeController::class, 'confirmContract'])->name('apply.ref.contract.confirm');

        Route::get('/success', [LoanOutcomeController::class, 'success'])->name('apply.ref.success');
    });
});
