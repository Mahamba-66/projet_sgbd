<?php
// Ajouté le 03/19/2025 04:27:33
// feat: Service de gestion des parrainages

namespace App\Services;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\DB;
use App\Events\SponsorshipValidated;
use App\Exceptions\SponsorshipException;

class SponsorshipService
{
    public function createSponsorship(User $voter, User $candidate): Sponsorship
    {
        if (!$voter->isVoter()) {
            throw new SponsorshipException("Seuls les électeurs peuvent parrainer.");
        }

        if (!$voter->isActive()) {
            throw new SponsorshipException("Votre compte doit être actif pour parrainer.");
        }

        if (!$candidate->isCandidate()) {
            throw new SponsorshipException("Le destinataire doit être un candidat.");
        }

        if (!$candidate->isActive()) {
            throw new SponsorshipException("Ce candidat n'est pas éligible au parrainage.");
        }

        if (!$voter->canSponsor()) {
            throw new SponsorshipException("Vous avez atteint votre limite de parrainages.");
        }

        $existingSponsorship = Sponsorship::where('voter_id', $voter->id)
            ->where('candidate_id', $candidate->id)
            ->exists();

        if ($existingSponsorship) {
            throw new SponsorshipException("Vous avez déjà parrainé ce candidat.");
        }

        return DB::transaction(function () use ($voter, $candidate) {
            return Sponsorship::create([
                'voter_id' => $voter->id,
                'candidate_id' => $candidate->id,
                'region_id' => $voter->region_id,
                'status' => Sponsorship::STATUS_PENDING
            ]);
        });
    }

    public function validateSponsorship(Sponsorship $sponsorship): void
    {
        if (!$sponsorship->isPending()) {
            throw new SponsorshipException("Ce parrainage ne peut plus être validé.");
        }

        DB::transaction(function () use ($sponsorship) {
            $sponsorship->validate();
            event(new SponsorshipValidated($sponsorship));
        });
    }

    public function rejectSponsorship(Sponsorship $sponsorship, string $reason): void
    {
        if (!$sponsorship->isPending()) {
            throw new SponsorshipException("Ce parrainage ne peut plus être rejeté.");
        }

        $sponsorship->reject($reason);
    }

    public function getGlobalStats(): array
    {
        return [
            'total' => Sponsorship::count(),
            'validated' => Sponsorship::where('status', Sponsorship::STATUS_VALIDATED)->count(),
            'pending' => Sponsorship::where('status', Sponsorship::STATUS_PENDING)->count(),
            'rejected' => Sponsorship::where('status', Sponsorship::STATUS_REJECTED)->count(),
            'by_region' => Region::withCount(['sponsorships' => function ($query) {
                $query->where('status', Sponsorship::STATUS_VALIDATED);
            }])->get(),
            'top_candidates' => User::withCount(['receivedSponsorships' => function ($query) {
                $query->where('status', Sponsorship::STATUS_VALIDATED);
            }])
            ->where('role', User::ROLE_CANDIDATE)
            ->orderByDesc('received_sponsorships_count')
            ->limit(10)
            ->get()
        ];
    }

    public function getRegionalStats(Region $region): array
    {
        return [
            'total' => $region->sponsorships()->count(),
            'validated' => $region->sponsorships()->where('status', Sponsorship::STATUS_VALIDATED)->count(),
            'pending' => $region->sponsorships()->where('status', Sponsorship::STATUS_PENDING)->count(),
            'rejected' => $region->sponsorships()->where('status', Sponsorship::STATUS_REJECTED)->count(),
            'progress' => $region->getSponsorshipProgress()
        ];
    }

    public function getCandidateStats(User $candidate): array
    {
        if (!$candidate->isCandidate()) {
            throw new SponsorshipException("L'utilisateur n'est pas un candidat.");
        }

        $sponsorships = $candidate->receivedSponsorships();

        return [
            'total' => $sponsorships->count(),
            'validated' => $sponsorships->where('status', Sponsorship::STATUS_VALIDATED)->count(),
            'pending' => $sponsorships->where('status', Sponsorship::STATUS_PENDING)->count(),
            'rejected' => $sponsorships->where('status', Sponsorship::STATUS_REJECTED)->count(),
            'by_region' => Region::withCount(['sponsorships' => function ($query) use ($candidate) {
                $query->where('candidate_id', $candidate->id)
                    ->where('status', Sponsorship::STATUS_VALIDATED);
            }])->get()
        ];
    }
}
