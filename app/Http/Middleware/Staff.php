<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Staff
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

    if (in_array($request->user()->colla_type, ['account', 'boosting', 'items'])) {
      $canAccess = true;
    }

    if (!$canAccess) {
      return abort(401);
    }

    // disable method POST on Demo
    if (env('APP_DEMO', false) && $request->method() === 'POST') {
      return redirect()->back()->with('error', 'Thao tác này bị vô hiệu hoá trên trang DEMO!');
    }

    return $next($request);
  }
}
