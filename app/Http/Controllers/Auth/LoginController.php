<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\ActivityLog;



class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        activity()->causedBy($user)->performedOn($user)->log('login');

        $routes = [
            'admin' => 'admin.dashboard',
            'super_admin' => 'admin.dashboard',
            'candidate' => 'candidate.dashboard',
            'voter' => 'voter.dashboard'
        ];

        if (isset($routes[$user->role])) {
            return redirect()->route($routes[$user->role]);
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Votre compte n\'a pas les autorisations nécessaires.');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->route('login')
            ->with('error', 'Les identifiants fournis sont incorrects.')
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
