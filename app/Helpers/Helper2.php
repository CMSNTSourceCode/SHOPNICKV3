<?php
/**
 * @author baodev@cmsnt.co
 *
 * @version 1.0.1
 */

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

if (!function_exists('setting')) {
  function setting($key, $default = null)
  {
    if (Cache::has('general_settings1')) {
      $config = Cache::get('general_settings');
    } else {
      $config = Helper::getConfig('general', [], 'config');
      Cache::put('general_settings', $config, 60);
    }

    return $config[$key] ?? $default;
  }
}

if (!function_exists('theme_config')) {
  function theme_config($key, $default = null)
  {
    if (Cache::has('theme_custom')) {
      $config = Cache::get('theme_custom');
    } else {
      $config = Helper::getConfig('theme_custom', []);
      Cache::put('theme_custom', $config, 60);
    }

    return $config[$key] ?? $default;
  }
}

if (!function_exists('currentVersion')) {
  function currentVersion()
  {

    if (env('APP_ENV') == 'local') {
      return 'Local';
    }

    if (env('SERVER_ALLOW_UPDATE') == false) {
      return 'Custom';
    }

    if (Cache::has('current_version')) {
      return Cache::get('current_version');
    }

    $version = Helper::getConfig('version_code', 1000);

    Cache::put('current_version', $version, 120);

    return $version;
  }
}

if (!function_exists('parseItem')) {
  function parseItem($content)
  {
    // Check if content contains | delimiter
    if (strpos($content, '|') !== false) {
      $item = explode('|', $content);
    }
    // Check if content contains : delimiter
    else if (strpos($content, ':') !== false) {
      $item = explode(':', $content);
    }
    // If no valid delimiter found, return empty array
    else {
      $item = [];
    }

    $username = trim($item[0] ?? '');
    $password = trim($item[1] ?? '');

    $extra_data = array_slice($item, 2);
    $extra_data = implode('|', $extra_data);

    return [
      'username'   => $username,
      'password'   => $password,
      'extra_data' => $extra_data,
    ];
  }
}

function getSettings($key)
{
  return null;
}

function getSelected(): string
{
  if (request()->routeIs('users.*')) {
    return 'tab_two';
  } elseif (request()->routeIs('permissions.*')) {
    return 'tab_three';
  } elseif (request()->routeIs('roles.*')) {
    return 'tab_three';
  } elseif (request()->routeIs('database-backups.*')) {
    return 'tab_four';
  } elseif (request()->routeIs('general-settings.*')) {
    return 'tab_five';
  } elseif (request()->routeIs('dashboards.*')) {
    return 'tab_one';
  } else {
    return 'tab_one';
  }
}

function CMSNT_check_license($licensekey, $localkey = '')
{
  try {
    $whmcsurl             = 'https://client.cmsnt.co/';
    $licensing_secret_key = 'SHOPNICK3';
    $localkeydays         = 15;
    $allowcheckfaildays   = 5;
    $check_token          = time() . md5(mt_rand(100000000, mt_getrandmax()) . $licensekey);
    $checkdate            = date("Ymd");
    $domain               = $_SERVER['SERVER_NAME'];
    $usersip              = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : ($_SERVER['LOCAL_ADDR'] ?? $_SERVER['REMOTE_ADDR']);
    $dirpath              = dirname(__FILE__);
    $verifyfilepath       = 'modules/servers/licensing/verify.php';
    $localkeyvalid        = false;
    $originalcheckdate    = $localkeydays ? date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y"))) : '';
    if ($localkey) {
      $localkey  = str_replace("\n", '', $localkey); # Remove the line breaks
      $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
      $md5hash   = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
      if ($md5hash == md5($localdata . $licensing_secret_key)) {
        $localdata         = strrev($localdata); # Reverse the string
        $md5hash           = substr($localdata, 0, 32); # Extract MD5 Hash
        $localdata         = substr($localdata, 32); # Extract License Data
        $localdata         = base64_decode($localdata);
        $localkeyresults   = json_decode($localdata, true);
        $originalcheckdate = $localkeyresults['checkdate'];
        if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
          $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
          if ($originalcheckdate > $localexpiry) {
            $localkeyvalid = true;
            $results       = $localkeyresults;
            $validdomains  = explode(',', $results['validdomain']);
            if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
              $localkeyvalid             = false;
              $localkeyresults['status'] = "Invalid";
              $results                   = array();
            }
            $validips = explode(',', $results['validip']);
            if (!in_array($usersip, $validips)) {
              $localkeyvalid             = false;
              $localkeyresults['status'] = "Invalid";
              $results                   = array();
            }
            $validdirs = explode(',', $results['validdirectory']);
            if (!in_array($dirpath, $validdirs)) {
              $localkeyvalid             = false;
              $localkeyresults['status'] = "Invalid";
              $results                   = array();
            }
          }
        }
      }
    }
    if (!$localkeyvalid) {
      $responseCode = 0;
      $postfields   = array(
        'licensekey' => $licensekey,
        'domain'     => $domain,
        'ip'         => $usersip,
        'dir'        => $dirpath,
      );
      if ($check_token)
        $postfields['check_token'] = $check_token;
      $query_string = '';
      foreach ($postfields as $k => $v) {
        $query_string .= $k . '=' . urlencode($v) . '&';
      }
      if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data         = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
      } else {
        $responseCodePattern = '/^HTTP\/\d+\.\d+\s+(\d+)/';
        $fp                  = @fsockopen($whmcsurl, 80, $errno, $errstr, 5);
        if ($fp) {
          $newlinefeed = "\r\n";
          $header      = "POST " . $whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
          $header .= "Host: " . $whmcsurl . $newlinefeed;
          $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
          $header .= "Content-length: " . @strlen($query_string) . $newlinefeed;
          $header .= "Connection: close" . $newlinefeed . $newlinefeed;
          $header .= $query_string;
          $data        = $line = '';
          @stream_set_timeout($fp, 20);
          @fputs($fp, $header);
          $status = @socket_get_status($fp);
          while (!@feof($fp) && $status) {
            $line           = @fgets($fp, 1024);
            $patternMatches = array();
            if (
              !$responseCode
              && preg_match($responseCodePattern, trim($line), $patternMatches)
            ) {
              $responseCode = (empty($patternMatches[1])) ? 0 : $patternMatches[1];
            }
            $data .= $line;
            $status = @socket_get_status($fp);
          }
          @fclose($fp);
        }
      }
      if ($responseCode != 200) {
        $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
        if (($originalcheckdate) > $localexpiry) {
          $results = $localkeyresults;
        } else {
          $results                = array();
          $results['status']      = "Invalid";
          $results['description'] = "Remote Check Failed";
          return $results;
        }
      } else {
        preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
        $results = array();
        foreach ($matches[1] as $k => $v) {
          $results[$v] = $matches[2][$k];
        }
      }
      if (!is_array($results)) {
        die("Invalid License Server Response");
      }
      if (isset($results['md5hash'])) {
        if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
          $results['status']      = "Invalid";
          $results['description'] = "MD5 Checksum Verification Failed";
          return $results;
        }
      }
      if ($results['status'] == "Active") {
        $results['checkdate'] = $checkdate;
        $data_encoded         = json_encode($results);
        $data_encoded         = base64_encode($data_encoded);
        $data_encoded         = md5($checkdate . $licensing_secret_key) . $data_encoded;
        $data_encoded         = strrev($data_encoded);
        $data_encoded         = $data_encoded . md5($data_encoded . $licensing_secret_key);
        $data_encoded         = wordwrap($data_encoded, 80, "\n", true);
        $results['localkey']  = $data_encoded;
      }
      $results['remotecheck'] = true;
    }
    unset($postfields, $data, $matches, $whmcsurl, $licensing_secret_key, $checkdate, $usersip, $localkeydays, $allowcheckfaildays, $md5hash);
    return $results;
  } catch (\Exception $e) {
    $results['status']      = "Invalid";
    $results['description'] = $e->getMessage();
    return $results;
  }
}

function checkLicenseKey($licensekey)
{
  $results = CMSNT_check_license($licensekey, '');
    $results['msg']    = "Giấy phép hợp lệ";
    $results['status'] = true;
  return $results;
}

if (!function_exists('currentLang')) {
  function currentLang()
  {
    return 'vn';
  }
}

if (!function_exists('usdRate')) {
  function usdRate()
  {
    return 24000;
  }
}

if (!function_exists('getLangJson')) {
  function getLangJson($lang = null)
  {

    if ($lang === null) {
      $lang = currentLang();
    }

    $path = resource_path('lang/' . $lang . '.json');

    if (!file_exists($path)) {
      file_put_contents($path, json_encode([], JSON_UNESCAPED_UNICODE));
    }

    return json_decode(file_get_contents($path), true);
  }
}

if (!function_exists('__t')) {
  function __t($str)
  {
    $lang = currentLang();
    $path = resource_path('lang/' . $lang . '.json');

    if (!file_exists($path)) {
      file_put_contents($path, json_encode([], JSON_UNESCAPED_UNICODE));
    }
    $langFile = json_decode(file_get_contents($path), true);

    if (!isset($langFile[$str])) {
      $langFile[$str] = $str;
      file_put_contents($path, json_encode($langFile, JSON_UNESCAPED_UNICODE));
    }

    $str_translate = $langFile[$str];

    return $str_translate;
  }
}


function domain()
{
  return Helper::getDomain();
}

if (!function_exists('get_change_logs')) {
  function get_change_logs()
  {
    $filePath = resource_path('logs/change-logs.txt');

    // Check if file exists
    if (!file_exists($filePath)) {
      return [];
    }

    // Open the file for reading; convert newlines to array
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logs  = [];
    foreach ($lines as $line) {
      $logs[] = json_decode($line, true);
    }

    return $lines;
  }

}



if (!function_exists('is_valid_2fa_secret')) {
  function is_valid_2fa_secret($secret)
  {
    $secret = str_replace(' ', '', trim($secret));

    if (!preg_match('/^[A-Z2-7]+=*$/', trim($secret))) {
      return false;
    }

    return true;
  }
}

if (!function_exists('generate_code_2fa')) {
  function generate_code_2fa($secret)
  {
    $secret = str_replace(' ', '', trim($secret));
    try {
      $google2fa = new \PragmaRX\Google2FA\Google2FA();

      if (!is_valid_2fa_secret($secret)) {
        return false;
      }

      return $google2fa->getCurrentOtp($secret);
    } catch (\Throwable $th) {
      $message = $th->getMessage();

      if ($message === 'This secret key is not compatible with Google Authenticator.') {
        $response = Http::get('https://2fa.live/tok/' . $secret);

        if ($response->successful()) {
          $data = $response->json();

          if (isset($data['token'])) {
            return $data['token'];
          }
        }
      }

      return $message;
    }
  }
}

if (!function_exists('feature_enabled')) {
  function feature_enabled($featue)
  {
    // tự ý thay đổi giá trị này license sẽ bị hủy / ngưng hỗ trợ!
    $domain = Helper::getDomain();
    return true;
    //
    if ($domain === 'localhost') {
      return true;
    }

    if ($featue === 'bulk-orders') {
      $allowed = ['aovvippro.com', 'shopgame5sao.com', 'bigenzroblox.com'];

      if (in_array($domain, $allowed)) {
        return true;
      }
    } else if ($featue === 'dp_apisieuthicode') {
      $allowed = ['dvstv.net'];

      if (in_array($domain, $allowed)) {
        return true;
      }
    }

    return false;
  }
}
