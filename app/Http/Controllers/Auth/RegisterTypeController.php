<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use App\Models\EligibleVoter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterTypeController extends Controller
{
    public function showTypeSelection()
    {
        return view('auth.register_type');
    }

    public function showVoterForm()
    {
        $regions = Region::all();
        return view('auth.register_voter', compact('regions'));
    }

    public function showCandidateForm()
    {
        return view('auth.register_candidate');
    }

    public function registerVoter(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'region_id' => 'required|exists:regions,id',
            'card_number' => 'required|string'
        ]);

        $eligibleVoter = EligibleVoter::where('card_number', $request->card_number)
            ->where('is_registered', false)
            ->first();

        if (!$eligibleVoter) {
            return back()->withInput()->withErrors(['card_number' => 'Carte invalide ou déjà utilisée.']);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'voter',
                'region_id' => $request->region_id
            ]);

            $eligibleVoter->update(['is_registered' => true]);

            DB::commit();
            Auth::login($user);

            return redirect()->route('voter.dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'inscription.']);
        }
    }

    public function registerCandidate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|' . Password::defaults(),
            'nin' => 'required|string|size:13|unique:users',
            'birth_date' => 'required|date|before:' . now()->subYears(35)->toDateString(),
            'party_name' => 'required|string|max:255',
            'party_position' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nin' => $request->nin,
                'role' => 'candidate',
                'birth_date' => $request->birth_date,
                'party_name' => $request->party_name,
                'party_position' => $request->party_position,
                'status' => 'pending'
            ]);

            DB::commit();
            Auth::login($user);

            return redirect()->route('candidate.dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'inscription du candidat: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'inscription.']);
        }
    }
}
