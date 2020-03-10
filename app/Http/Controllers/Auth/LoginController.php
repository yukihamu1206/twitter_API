<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Http\Request;


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
    protected $redirectTo = '/tweets';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToTwitterProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterProviderCallback(){

        try {
            $twitter_user = Socialite::with("twitter")->user();
        }
        catch (\Exception $e) {
            return redirect('/login')->with('oauth_error', 'ログインに失敗しました');
            // エラーならログイン画面へ転送
        }

        $user = User::where('token',$twitter_user->token)->first();
        if(!$user) {
            $user = User::create(
                [
                    'token' => $user->token,
                    'name' => $user->name,
                    'email' => $user->getEmail(),
                    'introduction' => $user->user['description'],
                    'screen_name' => $user->nickname,
                    'profile_image' => $user->avatar
                ]);
        }

        Auth::login($user);
        return redirect()->to('/tweets');
    }

    public function logout(Request $request)
    {
        // 各自ログアウト処理
        // 例
        Auth::logout();
        return redirect('/');
    }

}
