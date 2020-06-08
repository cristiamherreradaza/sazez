<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function inicio()
    {
        return view('auth.login');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        // Almacena en la variable los datos del usuario actual de Github
        $githubUser = Socialite::driver('github')->user();
        
        // Buscamos en la base de datos un registro que sea igual al del usuario de Github
        $user = User::where('provider_id', $githubUser->getId())->first();

        // En el caso de que la variable este vacia
        if(!$user){
            // Buscamos en la base de datos un registro que sea tenga el mismo email al del usuario de Github
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        // En el caso de que no lo encuentre lo creara
        if(!$user){
            // Agregaremos usuario a la base de datos
            $user = User::create([
                'email' => $githubUser->getEmail(),
                'name' => $githubUser->getName(),
                'rol' => 'Cliente',
                'provider' => 'Github',
                'provider_id' => $githubUser->getId(),
                'image' => $githubUser->getAvatar(),
            ]);
        }

        // Loguear al usuario
        Auth::login($user, true);
        
        // Redirecciona al home
        return redirect($this->redirectTo);
    }

    public function redirectToProviderFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallbackFacebook()
    {
        // Almacena en la variable los datos del usuario actual de Github
        $facebookUser = Socialite::driver('facebook')->user();
        
        //dd($facebookUser);
        // Buscamos en la base de datos un registro que sea igual al del usuario de Github
        $user = User::where('provider_id', $facebookUser->getId())->first();

        // En el caso de que la variable este vacia
        if(!$user){
            // Buscamos en la base de datos un registro que sea tenga el mismo email al del usuario de Facebook
            $user = User::where('email', $facebookUser->getEmail())->first();
        }

        // En el caso de que la variable este vacia
        if(!$user){
            // Agregaremos usuario a la base de datos
            $user = User::create([
                'email' => $facebookUser->getEmail(),
                'name' => $facebookUser->getName(),
                'rol' => 'Cliente',
                'provider' => 'Facebook',
                'provider_id' => $facebookUser->getId(),
                'image' => $facebookUser->getAvatar(),
            ]);
        }

        // Loguear al usuario
        Auth::login($user, true);
        
        // Redirecciona al home
        return redirect($this->redirectTo);
    }

    public function redirectToProviderGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallbackGoogle()
    {
        // Almacena en la variable los datos del usuario actual de Github
        $googleUser = Socialite::driver('google')->user();
        
        // Buscamos en la base de datos un registro que sea igual al del usuario de Github
        $user = User::where('provider_id', $googleUser->getId())->first();

        // En el caso de que la variable este vacia
        if(!$user){
            // Buscamos en la base de datos un registro que sea tenga el mismo email al del usuario de Google
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        // En el caso de que no lo encuentre lo creara
        if(!$user){
            // Agregaremos usuario a la base de datos
            $user = User::create([
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'rol' => 'Cliente',
                'provider' => 'Google',
                'provider_id' => $googleUser->getId(),
                'image' => $googleUser->getAvatar(),
            ]);
        }

        // Loguear al usuario
        Auth::login($user, true);
        
        // Redirecciona al home
        //return redirect($this->redirectTo);
        return redirect('Cliente/inicio');
    }

}
