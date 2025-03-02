<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Helper;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

  // use RegistersUsers;

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

  public function showRegistrationForm()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $attributes = [
      'email'    => 'Email',
      'username' => 'Tên người dùng',
      'password' => 'Mật khẩu',
    ];

    $messages = [
      'email.required'     => 'Trường email là bắt buộc.',
      'email.string'       => 'Trường email phải là một chuỗi.',
      'email.email'        => 'Trường email phải là một địa chỉ email hợp lệ.',
      'email.max'          => 'Trường email không được dài quá 255 ký tự.',
      'email.unique'       => 'Địa chỉ email đã được sử dụng.',
      'username.min'       => 'Tên người dùng phải dài ít nhất 6 ký tự.',
      'username.required'  => 'Trường tên người dùng là bắt buộc.',
      'username.string'    => 'Trường tên người dùng phải là một chuỗi.',
      'username.max'       => 'Trường tên người dùng không được dài quá 16 ký tự.',
      'username.unique'    => 'Tên người dùng đã được sử dụng.',
      'username.alpha_num' => 'Tên người dùng chỉ được chứa các chữ cái và số.',
      'password.required'  => 'Trường mật khẩu là bắt buộc.',
      'password.string'    => 'Trường mật khẩu phải là một chuỗi.',
      'password.min'       => 'Mật khẩu phải dài ít nhất 6 ký tự.',
      'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ];

    $validate = Validator::make($request->all(), [
      'email'    => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
      'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'min:6', 'max:16', 'unique:users'],
      'password' => ['required', 'string', 'min:6', 'max:64'],
    ], $messages, $attributes);

    if ($validate->fails()) {
      return redirect()->back()->withErrors($validate)->withInput();
    }

    $data = $request->only(['email', 'username', 'password']);

    $maxRegisterPerIP = setting('max_ip_reg', 1);

    if ($maxRegisterPerIP > 0) {
      $ip = request()->ip();

      $count = User::where('register_ip', $ip)->count();

      if ($count >= $maxRegisterPerIP) {
        return redirect()->back()->with('error', 'Bạn đã đăng ký quá số lần cho phép, vui lòng liên hệ admin.');
      }
    }

    if (Cookie::has('ref_id')) {
      $cref = Affiliate::where('code', Cookie::get('ref_id'))->first();
      if ($cref) {
        $refId = $cref->username;
      } else {
        $refId = null;
      }
    } else {
      $refId = null;
    }

    $user = User::create([
      'email'         => isset($data['email']) ? $data['email'] : time() . '@' . \Helper::getDomain(),
      'username'      => $data['username'],
      'password'      => Hash::make($data['password']),
      'fullname'      => $data['fullname'] ?? null,
      'register_by'   => 'WEB',
      'referral_by'   => $refId,
      'register_ip'   => request()->ip(),
      'referral_code' => str()->random(12),
    ]);

    $user->update([
      'access_token' => explode('|', $user->createToken('access_token')->plainTextToken)[1],
    ]);

    if ($user->id === 1) {
      $user->update([
        'role'   => 'admin',
        'status' => 'active',
      ]);
      Helper::addHistory("Lần đầu đăng nhập, IP " . request()->ip());
    }


    if ($refId) {
      $cref->users()->create([
        'username'       => $data['username'],
        'affiliate_user' => $user->username,
      ]);
      $cref->update([
        'signups' => $cref->signups + 1
      ]);
    }

    Auth::login($user);

    return redirect()->route('home');
  }
}
