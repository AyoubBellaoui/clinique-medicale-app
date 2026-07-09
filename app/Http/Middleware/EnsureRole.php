<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Restrict a route to one or more permission tiers: "admin", "medical", "support".
     * Admins are always allowed through regardless of which tiers are listed.
     */
    public function handle(Request $request, Closure $next, string ...$tiers): Response
    {
        $user = $request->user();

        $allowed = $user->isAdmin() || collect($tiers)->contains(fn($tier) => match ($tier) {
            'medical' => $user->isMedical(),
            'support' => $user->isSupport(),
            default    => false,
        });

        if (!$allowed) {
            flash()->error("Vous n'avez pas la permission d'accéder à cette page.");

            return redirect()->route('dashboard.show');
        }

        return $next($request);
    }
}
