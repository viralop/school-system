<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use App\Models\SecureLink;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecureLinkMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $throttleKey = 'secure-link:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            AccessLog::logAttempt(
                $request,
                'rate_limited',
                false,
                failureReason: 'Too many attempts',
            );

            return response()->view('errors.429', [], 429);
        }

        RateLimiter::hit($throttleKey, 60);

        $linkId = $request->route('link');
        $plainToken = $request->route('token');

        if (! $linkId || ! $plainToken) {
            AccessLog::logAttempt(
                $request,
                'access_missing_params',
                false,
                failureReason: 'Missing link ID or token',
            );

            abort(404);
        }

        $secureLink = SecureLink::validateToken($linkId, $plainToken);

        if (! $secureLink) {
            $existingLink = SecureLink::find($linkId);

            AccessLog::logAttempt(
                $request,
                'access_invalid_token',
                false,
                secureLinkId: $existingLink?->id,
                failureReason: $this->determineFailureReason($existingLink, $linkId),
            );

            abort(403, 'Invalid or expired link.');
        }

        AccessLog::logAttempt(
            $request,
            'access_validated',
            true,
            secureLinkId: $secureLink->id,
            userId: $secureLink->user_id,
        );

        $request->attributes->set('secureLink', $secureLink);

        return $next($request);
    }

    private function determineFailureReason(?SecureLink $link, int $linkId): string
    {
        if (! $link) {
            return 'Link not found';
        }

        if ($link->isExpired()) {
            return 'Link expired';
        }

        if ($link->isExhausted()) {
            return 'Link usage limit reached';
        }

        return 'Invalid token';
    }
}
