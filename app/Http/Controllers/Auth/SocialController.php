<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Helper;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
  public function redirectToProvider($provider)
  {
    $config = Helper::getApiConfig('auth_' . $provider);

    if (!$config) {
      return redirect('login')->withErrors([
        'username' => 'Đăng nhập bằng ' . ucfirst($provider) . ' chưa được cấu hình',
      ]);
    }

    if (isset($config['client_status']) && !$config['client_status']) {
      return redirect('login')->withErrors([
        'username' => 'Đăng nhập bằng ' . ucfirst($provider) . ' đã bị vô hiệu hóa',
      ]);
    }

    if (!isset($config['client_key']) || !isset($config['client_secret'])) {
      return redirect('login')->withErrors([
        'username' => 'Đăng nhập bằng ' . ucfirst($provider) . ' chưa được cấu hình',
      ]);
    }

    if ($config) {
      config([
        'services.' . $provider . '.active' => true,
        'services.' . $provider . '.client_id' => $config['client_key'],
        'services.' . $provider . '.client_secret' => $config['client_secret'],
        'services.' . $provider . '.redirect' => route('auth.social.callback', ['provider' => $provider]),
      ]);
    }
    return Socialite::driver($provider)->redirect();
  }

  public function handleProviderCallback($provider)
  {
    $user = Socialite::driver($provider);

    try {
      $user = $user->user();
    } catch (\Exception $e) {
      return redirect()->route('login')->withErrors([
        'username' => 'Đăng nhập bằng ' . $provider . ' thất bại',
      ]);//->withError('Lỗi: ' . $e->getMessage());
    }

    $username = $user->id;
    if ($user->email) {
      $username = explode('@', $user->email)[0];
    }

    $userExists = User::where('username', $username)->first();

    if ($userExists) {

      if (!$userExists->access_token) {
        $userExists->update([
          'access_token' => explode('|', $userExists->createToken('access_token')->plainTextToken)[1],
        ]);
      }

      $last_login_at = now();
      $last_login_ip = request()->ip();
      $userExists->update([
        'last_login_at' => $last_login_at,
        'last_login_ip' => $last_login_ip,
      ]);
      session(['last_login_at' => $last_login_at]);
      session(['last_login_ip' => $last_login_ip]);

      Auth::login($userExists);

      Helper::addHistory('Đăng nhập bằng ' . $provider . ' thành công');

      return redirect()->intended(route('home'));
    }

    $idExists = User::where('username', $user->id)->first();
    if ($idExists) {


      if (!$idExists->access_token) {
        $idExists->update([
          'access_token' => explode('|', $idExists->createToken('access_token')->plainTextToken)[1],
        ]);
      }

      $last_login_at = now();
      $last_login_ip = request()->ip();
      $idExists->update([
        'last_login_at' => $last_login_at,
        'last_login_ip' => $last_login_ip,
      ]);
      session(['last_login_at' => $last_login_at]);
      session(['last_login_ip' => $last_login_ip]);
      Auth::login($idExists);

      Helper::addHistory('Đăng nhập bằng ' . $provider . ' thành công');

      return redirect()->intended(route('home'));

    }

    $emailExists = User::where('email', $user->email)->first();

    if ($emailExists) {
      return redirect()->route('register')->withErrors([
        'email' => 'Email này đã được sử dụng bởi tài khoản khác',
      ]);
    }

    $newUser = User::create([
      'role'          => 'user',
      'email'         => $user->email ?? ($user->id . '@' . $provider . '.com'),
      'avatar'        => $user->avatar,
      'username'      => $username,
      'fullname'      => $user->name,
      'password'      => bcrypt($user->id) . '_baoinc' . mt_rand(400, 500),
      'ip_address'    => request()->ip(),
      'register_by'   => strtoupper($provider),
      'referral_code' => str()->random(12),

      'last_login_at' => now(),
      'last_login_ip' => request()->ip(),
    ]);

    if (!$newUser->access_token) {
      $newUser->update([
        'access_token' => explode('|', $newUser->createToken('access_token')->plainTextToken)[1],
      ]);
    }

    session(['last_login_at' => $newUser->last_login_at]);
    session(['last_login_ip' => $newUser->last_login_ip]);

    Auth::login($newUser);

    Helper::addHistory('Đăng ký tài khoản bằng ' . $provider . ' thành công');

    return redirect()->intended(route('home'));
  }
}
