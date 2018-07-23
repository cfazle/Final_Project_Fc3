<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Http\Request;
use Socialite;
use App;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }


    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
  /*  public function handleProviderCallback()

    {


        $user = Socialite::driver('facebook')->stateless()->user();

    }*/

   public function handleProviderCallback()
    {

        $socialUser = Socialite::driver('facebook')->stateless()->user();

       $user = User::where('email', $socialUser->user['email'])->first();

             if($user)
               if (Auth::loginUsingId($user->id)) {
                   return redirect()->route('home');
               }

               $userSignup = User::create([
                   'name' => $socialUser->user['name'],
                   'email' => $socialUser->user['email'],
                   'password' => bcrypt('1234'),
                   'avatar' => $socialUser->avatar,
                   //    'facebook_profile'=>$socialUser->user['link'],
                   //    'gender' =>$socialUser->user['gender'],
               ]);

               // finally log the user in
               if ($userSignup) {
                   if (Auth::loginUsingId($userSignup->id)) {
                       return redirect()->route('home');
                   }
               }


       // return redirect ($socialUser); */
    }


}
