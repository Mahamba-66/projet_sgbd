<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\ImportedVoter;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $regions = Region::orderBy('name')->get();
        return view('auth.register', compact('regions'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nin' => ['required', 'string', 'unique:users'],
            'role' => ['required', 'string', 'in:voter,candidate'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            // Pour les candidats, pas besoin de vérifier dans la liste des électeurs importés
            if ($data['role'] === 'voter') {
                // Vérifier si l'électeur existe dans la table des électeurs importés
                $existingVoter = ImportedVoter::where('nin', $data['nin'])
                    ->where('email', $data['email'])
                    ->first();

                if (!$existingVoter) {
                    throw new \Exception('Vous devez être dans la liste des électeurs importés pour vous inscrire.');
                }
            }

            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'nin' => $data['nin'],
                'role' => $data['role'],
                'status' => $data['role'] === 'candidate' ? 'pending' : 'active'
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Erreur lors de l\'inscription : ' . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription : ' . $e->getMessage()]);
        }
    }
}
