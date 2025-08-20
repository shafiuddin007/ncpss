<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\Auth;
use App\Enums\ApprovalStatus;

class ApprovalService
{
    /**
     * Create a new approval history record.
     * 
     * @param array $data
     * @param string|null $documentPath Path to the uploaded document, if any.
     * @return ApprovalHistory
     */
    public function createApprovalHistory(array $data, string $documentPath = null): ApprovalHistory
    {
        if ($documentPath !== null) {
            $data['document_path'] = $documentPath;
        }
        return ApprovalHistory::create($data);
    }

    /**
     * Update approval history for a given application.
     */
    public function updateApprovalHistory(ApprovalHistory $history, array $data): ApprovalHistory
    {
        $history->fill($data);
        $history->approval_date = now();
        $history->approved_by = optional(Auth::user())->id;
        $history->approved_by_name = Auth::user()->name ?? '';
        $history->save();
        return $history;
    }

    /**
     * Forward application to next step.
     */
    public function forwardApplication(Application $application, int $step, string $nextRole, string $applicationType = null)
    {
        $application->approval_step = $step;
        $application->role = $nextRole;
        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();

        ApprovalHistory::create([
            'application_id' => $application->id,
            'application_type' => $applicationType ?? $application->model_type,
            'approval_step' => $step,
            'approval_role' => $nextRole,
            'status' => ApprovalStatus::PENDING->value,
            'created_at' => now(),
        ]);
    }

    /**
     * Update application status (not final step).
     */
    public function updateApplicationStatus(Application $application, string $status, string $role)
    {
        $application->status = $status;
        $application->role = $role;
        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();
    }

    /**
     * Update application status for final step.
     */
    public function updateFinalApplicationStatus(Application $application, string $status, string $role)
    {
        $application->status = $status;
        $application->role = $role;

        if ($status === ApprovalStatus::APPROVED->value) {
            $application->is_approved = true;
            $application->notes = 'Application approved by the committee';
            $application->approval_date = now();
            $application->approved_by = optional(Auth::user())->id;
            $application->approved_by_name = Auth::user()->name ?? '';
        } elseif ($status === ApprovalStatus::REJECTED->value) {
            $application->is_rejected = true;
            $application->rejection_date = now();
            $application->rejected_by = optional(Auth::user())->id;
            $application->rejected_by_name = Auth::user()->name ?? '';
        }

        $application->updated_by = optional(Auth::user())->id;
        $application->updated_by_name = Auth::user()->name ?? '';
        $application->updated_at = now();
        $application->save();
    }
}
