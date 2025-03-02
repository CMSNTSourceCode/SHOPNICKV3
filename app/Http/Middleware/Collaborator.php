<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Collaborator
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

    if (!in_array(auth()->user()->colla_type, ['items', 'boosting'])) {
      return abort(403);
    }

    if ($request->user()->status !== 'active') {
      return abort(401);
    }

    return $next($request);
  }
}
