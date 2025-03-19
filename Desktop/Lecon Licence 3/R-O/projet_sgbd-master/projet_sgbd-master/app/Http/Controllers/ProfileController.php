<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('voter.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'region_id' => ['required', 'exists:regions,id']
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'region_id' => $request->region_id
        ]);

        return redirect()->route('voter.profile')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
