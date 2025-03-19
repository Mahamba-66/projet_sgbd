<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour vérifier votre compte.');
        }

        if ($user->verification_code === $request->verification_code) {
            $user->email_verified_at = now();
            $user->status = 'active';
            $user->save();

            return redirect()->route($user->role === 'candidate' ? 'candidate.dashboard' : 'voter.dashboard')
                ->with('success', 'Votre compte a été vérifié avec succès.');
        }

        return back()->with('error', 'Le code de vérification est incorrect.');
    }

    public function resend(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour recevoir un nouveau code.');
        }

        // Générer un nouveau code
        $user->verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->save();

        // Envoyer le nouveau code par email
        try {
            Mail::to($user->email)->send(new VerificationEmail($user));
            return back()->with('success', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible d\'envoyer le code de vérification. Veuillez réessayer plus tard.');
        }
    }
}
