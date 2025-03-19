<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\ImportedVoterCard;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/verify';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $regions = Region::orderBy('name')->get();
        return view('auth.register', compact('regions'));
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:voter,candidate'],
            'region_id' => ['required', 'exists:regions,id'],
            'nin' => ['required', 'string', 'size:13', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
            'voter_card_number' => ['required', 'string', 'regex:/^[A-Z0-9]{10}$/'],
        ];

        if (isset($data['role']) && $data['role'] === 'candidate') {
            $rules['birth_date'] = ['required', 'date', 'before:'.Carbon::now()->subYears(35)->format('Y-m-d')];
            $rules['party_name'] = ['required', 'string', 'max:255'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        // Vérifier si le numéro de carte d'électeur est valide
        if (!ImportedVoterCard::isCardNumberValid($data['voter_card_number'])) {
            throw ValidationException::withMessages([
                'voter_card_number' => ['Ce numéro de carte d\'électeur n\'est pas inscrit sur les listes électorales.']
            ]);
        }

        DB::beginTransaction();
        try {
            // Marquer le numéro de carte comme utilisé
            ImportedVoterCard::markAsUsed($data['voter_card_number']);

            // Créer l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'nin' => $data['nin'],
                'voter_card_number' => $data['voter_card_number'],
                'phone' => $data['phone'],
                'region_id' => $data['region_id'],
                'status' => 'pending',
                'verification_code' => sprintf('%06d', random_int(0, 999999))
            ]);

            if ($data['role'] === 'candidate') {
                $user->birth_date = $data['birth_date'];
                $user->party_name = $data['party_name'];
                $user->save();
            }

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function registered(Request $request, $user)
    {
        return redirect()->route('verification.notice')
            ->with('success', 'Votre compte a été créé. Veuillez vérifier votre email pour l\'activer.');
    }
}
