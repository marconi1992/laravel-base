<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\IActivationService;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'activateUser']);
    }


    public function activateUser(IActivationService $activationService, $token)
    {
        $activationService->activateUser($token);

        return redirect($this->redirectPath());
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @param string $provider
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $authUser = false;

        if ($provider == "facebook") {
            $authUser = Socialite::driver('facebook')->user();
        } else {
            abort(404);
        }

        if (!$authUser) {
            return redirect()->to("/");
        }

        $user = User::where($provider . '_id', $authUser->id)->first();

        if (!$user) {
            $userData = $this->getUserDataFromProvider($authUser, $provider);
            $user = $this->create($userData);
        }

        Auth::login($user, true);

        return redirect()->to($this->redirectTo);
    }


    protected function getUserDataFromProvider($authUser, $provider)
    {
        return [
            'name' => $authUser->name,
            'email' => $authUser->email,
            $provider . '_id' => $authUser->id
        ];
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if (array_has($data, "password")) {
            $data["password"] = bcrypt($data["password"]);
        }

        return User::create($data);
    }
}
