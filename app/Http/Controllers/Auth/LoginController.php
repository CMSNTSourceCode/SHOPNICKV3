<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $this->validate($request, [
      'username'             => 'required|string',
      'password'             => 'required',
      'remember'             => 'nullable|string',
      'g-recaptcha-response' => 'nullable|string',
    ]);

    if (setting('captcha')) {
      $captcha = Helper::verifyCaptcha($request->input('g-recaptcha-response'), 'login_ssr');
      if (!$captcha) {
        return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors([
          'g-recaptcha-response' => 'Xác thực không hợp lệ, vui lòng tải lại trang',
        ]);
      }
    }

    $phase1 = Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember === 'on' ? true : false);
    $phase2 = null;

    if (!$phase1) {
      // trying with md5 encrypted password
      $phase2 = Auth::attempt(['username' => $request->username, 'password' => md5($request->password)], $request->remember === 'on' ? true : false);
    }

    if ($phase1 || $phase2) {

      $user = User::find(Auth::id());

      if ($user->status !== 'active') {
        Auth::logout();
        return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors([
          'username' => 'Tài khoản của bạn đã bị khóa',
        ]);
      }

      // if phase2 => update password
      if ($phase2) {
        $user->update([
          'password' => bcrypt($request->password),
        ]);
      }

      if (!$user->access_token) {
        $user->update([
          'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
        ]);
      }

      $user->update([
        'last_login_at' => now(),
        'last_login_ip' => $request->ip(),
      ]);

      session(['last_login_at' => $user->last_login_at]);
      session(['last_login_ip' => $user->last_login_ip]);


      $user->histories()->create([
        'role'       => 'user',
        'data'       => [],
        'content'    => 'Đăng nhập thành công qua WEB, số dư ' . Helper::formatCurrency($user->balance),
        'user_id'    => $user->id,
        'username'   => $user->username,
        'ip_address' => $request->ip(),
        'domain'     => Helper::getDomain(),
      ]);

      // if successful, then redirect to their intended location
      return redirect()->intended(route('home'));
    }

    // if unsuccessful, then redirect back to the login with the form data
    return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors([
      'username' => 'Thông tin đăng nhập không chính xác',
    ]);
  }

  public function logout()
  {
    Auth::logout();

    return redirect()->route('login');
  }
}
