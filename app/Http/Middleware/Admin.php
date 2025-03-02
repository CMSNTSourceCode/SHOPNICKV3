<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    // check status
    if ($request->user()->status !== 'active') {
      return abort(401);
    }

    $canAccess = false;

    if (in_array($request->user()->role, ['admin', 'accounting', 'partner'])) {
      $canAccess = true;
    }

    if (!$canAccess) {
      return abort(401);
    }

    // accounting
    if ($request->user()->role === 'accounting' || $request->user()->role === 'partner') {
      //  only access to admin.dashboard
      if ($request->routeIs('admin.dashboard')) {
        $canAccess = true;
      } else {
        $canAccess = false;
      }
    } elseif ($request->user()->role === 'admin') {
      $canAccess = true;
    } else {
      $canAccess = false;
    }

    if (!$canAccess) {
      return abort(403);
    }

    // disable method POST on Demo
    if (env('APP_DEMO', false) && $request->method() === 'POST') {
      return redirect()->back()->with('error', 'Thao tác này bị vô hiệu hoá trên trang DEMO!');
    }

    // // check license
    // if ($request->method() === 'POST' && !$request->routeIs('admin.tools.upload')) {


    //   // only groups settings
    //   if ($request->routeIs('admin.settings.*')) {


    //     $check = checkLicenseKey(env('CLIENT_SECRET_KEY'));

    //     if ($check['status'] !== true) {
    //       if ($request->wantsJson()) {
    //         return response()->json([
    //           'status'  => 400,
    //           'error'   => [
    //             'message' => '[POST][License Error]: ' . $check['msg'],
    //           ],
    //           'message' => '[POST][License Error]: ' . $check['msg'],
    //         ], 400);
    //       } else {
    //         return redirect()->back()->with('error', '[POST][License Error]: ' . $check['msg']);
    //       }
    //     }

    //   }

    // }

    return $next($request);
  }
}
