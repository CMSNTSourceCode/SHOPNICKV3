<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Notification;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralController extends Controller
{
  public function index(Request $request)
  {
    return view('admin.settings.general');
  }

  public function update(Request $request)
  {
    $type = $request->input('type', null);

    if ($type === 'general') {
      $payload = $request->validate([
        'title'              => 'nullable|string|max:255',
        'keywords'           => 'nullable|string|max:255',
        'description'        => 'nullable|string|max:255',
        'primary_color'      => 'nullable|string|max:255',
        'logo'               => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'favicon'            => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'logo_share'         => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'logo_dark'          => 'nullable|file|mimes:png,jpg,jpeg,svg,gif|max:20000',
        'email'              => 'nullable|email|max:255',
        'captcha'            => 'nullable|boolean',
        'upload_provider'    => 'required|string|in:public,s3,do_spaces,imgbb',
        'captcha_site_key'   => 'nullable|string',
        'captcha_secret_key' => 'nullable|string',
        'time_wait_free'     => 'nullable|integer',
        'max_ip_reg'         => 'nullable|integer',
        'rate_robux'         => 'nullable|integer',
        'comm_percent'       => 'nullable|integer',
        'default_theme'      => 'nullable|string|in:dark,light',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      if ($request->hasFile('logo_dark')) {
        $payload['logo_dark'] = Helper::uploadFile($request->file('logo_dark'), 'public');
        if (isset($config->value['logo_dark'])) {
          Helper::deleteFile($config->value['logo_dark']);
        }
      } else {
        $payload['logo_dark'] = $config->value['logo_dark'] ?? null;
      }

      if ($request->hasFile('logo_share')) {
        $payload['logo_share'] = Helper::uploadFile($request->file('logo_share'), 'public');
        if (isset($config->value['logo_share'])) {
          Helper::deleteFile($config->value['logo_share']);
        }
      } else {
        $payload['logo_share'] = $config->value['logo_share'] ?? null;
      }

      if ($request->hasFile('logo_light')) {
        $payload['logo_light'] = Helper::uploadFile($request->file('logo_light'), 'public');
        if (isset($config->value['logo_light'])) {
          Helper::deleteFile($config->value['logo_light']);
        }
      } else {
        $payload['logo_light'] = $config->value['logo_light'] ?? null;
      }

      if ($request->hasFile('favicon')) {
        $payload['favicon'] = Helper::uploadFile($request->file('favicon'), 'public');
        if (isset($config->value['favicon'])) {
          Helper::deleteFile($config->value['favicon']);
        }
      } else {
        $payload['favicon'] = $config->value['favicon'] ?? null;
      }

      $config->update([
        'value' => $payload,
      ]);

      Cache::forget('general_settings');

      return redirect()->back()->with('success', 'Cập nhật cài đặt chung thành công.');
    } elseif ($type === 'theme_custom') {
      // $payload = $request->validate([
      //   'banner'              => 'nullable|file|mimes:png,jpg,jpeg,gif,svg|max:20000',
      //   'youtube'             => 'nullable|string',
      //   'card_stats'          => 'nullable|boolean',
      //   'buy_button_img'      => 'required|string',
      //   'product_info_type'   => 'nullable|boolean',
      //   'enable_custom_theme' => 'nullable|boolean',
      // ]);
      $payload = $request->all();

      unset($payload['_token']);

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      if ($request->hasFile('banner')) {
        $payload['banner'] = Helper::uploadFile($request->file('banner'), 'public');
        if (isset($config->value['banner'])) {
          Helper::deleteFile($config->value['banner']);
        }
      } else {
        $payload['banner'] = $config->value['banner'] ?? null;
      }

      $config->update([
        'value' => $payload,
      ]);

      Cache::forget('theme_custom');

      return redirect()->back()->with('success', 'Cập nhật banner thành công.');
    } elseif ($type === 'contact_info') {
      $payload = $request->validate([
        'email'     => 'nullable|string',
        'twitter'   => 'nullable|string',
        'discord'   => 'nullable|string',
        'facebook'  => 'nullable|string',
        'telegram'  => 'nullable|string',
        'phone_no'  => 'nullable|string',
        'instagram' => 'nullable|string',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông tin liên hệ thành công.');
    } elseif ($type === 'deposit_info') {
      $payload = [
        'prefix'   => $_POST['prefix'] ?? 'hello ',
        'discount' => (int) $request->input('discount', 0),
      ];

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông tin nạp tiền thành công.');
    } elseif ($type === 'mng_withdraw') {
      $payload = [
        'unit'         => $request->input('unit', 'Coin'),
        'youtube_id'   => $request->input('youtube_id', null),
        'min_withdraw' => (int) ($request->input('min_withdraw', 0)),
        'max_withdraw' => (int) ($request->input('max_withdraw', 0)),
      ];

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông tin liên hệ thành công.');
    } elseif ($type === 'shop_info') {
      $payload = $request->validate([
        'footer_text_1'    => 'nullable|string',
        'footer_text_2'    => 'nullable|string',
        'dashboard_text_1' => 'nullable|string',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông tin liên hệ thành công.');
    } elseif ($type === 'affiliate_config') {

      $config = Config::firstOrCreate(['name' => $type], ['value' => $request->all()]);

      $config->update([
        'value' => $request->all(),
      ]);

      return redirect()->back()->with('success', 'Cập nhật cấu hình cộng tác viên thành công.');
    } else if ($type === 'telegram_config') {
      $payload = $request->validate([
        'bot_token' => 'nullable|string',
        'chat_id'   => 'nullable|string',
      ]);

      $config = Config::firstOrCreate(['name' => $type], ['value' => $payload]);

      $config->update([
        'value' => $payload,
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông tin Telegram thành công.');
    } else if ($type === 'deposit_port') {
      $config = Config::firstOrCreate(['name' => $type], ['value' => $request->input('value', [])]);

      $config->update([
        'value' => $request->input('value', []),
      ]);

      return redirect()->back()->with('success', 'Cập nhật thông nạp tiền thành công.');
    } elseif ($type === 'header_script') {
      $payload = $request->validate([
        'code' => 'nullable|string',
      ]);

      $config = Notification::firstOrCreate(['name' => $type], ['value' => $payload['code']]);

      $config->update([
        'value' => $payload['code'],
      ]);

      return redirect()->back()->with('success', 'Cập nhật mã script thành công.');
    } elseif ($type === 'footer_script') {
      $payload = $request->validate([
        'code' => 'nullable|string',
      ]);

      $config = Notification::firstOrCreate(['name' => $type], ['value' => $payload['code']]);

      $config->update([
        'value' => $payload['code'],
      ]);

      return redirect()->back()->with('success', 'Cập nhật mã script thành công.');
    } else if ($type === 'get_gift') {
      $payload = $request->validate([
        'min'      => 'nullable|integer',
        'max'      => 'nullable|integer',
        'width'    => 'nullable|integer',
        'hight'    => 'nullable|integer',
        'image'    => 'nullable|url',
        'status'   => 'nullable|boolean',
        'balance'  => 'nullable|integer',
        'up_image' => 'nullable|file|mimes:png,jpg,jpeg,gif,svg|max:20000',
      ]);

      if ($request->hasFile('up_image') && !$request->input('image')) {
        $payload['image'] = Helper::uploadFile($request->file('up_image'), 'public');
      } else {
        $payload['image'] = $request->input('image');
      }

      unset($payload['up_image']);

      $config = Config::firstOrCreate(['name' => $type], ['value' => []]);

      $config->update([
        'value' => $payload,
      ]);

      Helper::addHistory('Cập nhật hệ thống tặng quà miễn phí cho người mới');

      return redirect()->back()->with('success', 'Cập nhật thông tin tặng quà thành công.');
    }

  }
}
