<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminMiddleware
{
    /**
     * Hanya user dengan flag is_admin (Gate `admin`) yang boleh lewat.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && Gate::forUser($user)->allows('admin')) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat membuka halaman tersebut.');
    }
}
