<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domain\Diving\Models\DivingTechnicalDirectorInvitation;
use Domain\Diving\States\AcceptedDivingTechnicalDirectorInvitationState;
use Domain\Diving\States\CanceledDivingTechnicalDirectorInvitationState;
use Domain\Diving\States\PendingDivingTechnicalDirectorInvitationState;
use Domain\Diving\States\RejectedDivingTechnicalDirectorInvitationState;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DivingTechnicalDirectorInvitationController extends Controller
{
    public function index(Request $request): View
    {
        $query = DivingTechnicalDirectorInvitation::query()
            ->with(['entity', 'individual', 'licenseAttributed.license'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->whereState('status_class', $request->status);
        }

        // Filter by entity
        if ($request->filled('entity_id')) {
            $query->where('entity_id', $request->entity_id);
        }

        // Search by entity name or individual name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('entity', function ($eq) use ($search) {
                    $eq->where('name', 'like', "%{$search}%");
                })->orWhereHas('individual', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('code_cmas', 'like', "%{$search}%");
                });
            });
        }

        $invitations = $query->paginate(20);

        $statusOptions = [
            PendingDivingTechnicalDirectorInvitationState::class => __('diving.pending'),
            AcceptedDivingTechnicalDirectorInvitationState::class => __('diving.accepted'),
            RejectedDivingTechnicalDirectorInvitationState::class => __('diving.rejected'),
            CanceledDivingTechnicalDirectorInvitationState::class => __('diving.canceled'),
        ];

        return view('web.admin.diving_technical_director_invitations.index', compact(
            'invitations',
            'statusOptions'
        ));
    }

    public function show(DivingTechnicalDirectorInvitation $invitation): View
    {
        $invitation->load([
            'entity',
            'individual',
            'licenseAttributed.license',
            'individual.divingProfessionalCertifications' => function ($query) {
                $query->whereState('status_class', \Domain\Diving\States\ActiveDivingCertificationState::class);
            },
        ]);

        return view('web.admin.diving_technical_director_invitations.show', compact('invitation'));
    }

    public function cancel(DivingTechnicalDirectorInvitation $invitation): RedirectResponse
    {
        if (! $invitation->status()->canTransitionTo(CanceledDivingTechnicalDirectorInvitationState::class)) {
            return back()->with('error', __('diving.cannot_cancel_invitation'));
        }

        $invitation->status_class = CanceledDivingTechnicalDirectorInvitationState::class;
        $invitation->save();

        return redirect()->route('admin.diving_technical_director_invitations.show', $invitation)
            ->with('success', __('diving.invitation_canceled_successfully'));
    }
}
