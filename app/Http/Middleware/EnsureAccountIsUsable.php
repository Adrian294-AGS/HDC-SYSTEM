<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Runs on every web request:
 * - signs out accounts that were deactivated while logged in;
 * - keeps users with a temporary password on the "change password" page until they change it.
 */
class EnsureAccountIsUsable
{
    /**
     * Routes a user with a temporary password may still use.
     *
     * @var list<string>
     */
    private const PASSWORD_CHANGE_ROUTES = ['password.change', 'password.change.update', 'logout'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => __('This account has been deactivated. Please contact the HDC Director.')]);
        }

        if ($user->must_change_password && ! $request->routeIs(...self::PASSWORD_CHANGE_ROUTES)) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
