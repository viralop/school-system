<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate(Model $authenticatable, string $purpose, string $email, ?string $ipAddress = null): Otp
    {
        $this->invalidatePrevious($authenticatable, $purpose);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = Otp::create([
            'authenticatable_type' => get_class($authenticatable),
            'authenticatable_id' => $authenticatable->getKey(),
            'code_hash' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $ipAddress,
        ]);

        Mail::to($email)->send(new OtpMail($code, $purpose));

        return $otp;
    }

    public function verify(Model $authenticatable, string $purpose, string $code): array
    {
        $otp = Otp::where('authenticatable_type', get_class($authenticatable))
            ->where('authenticatable_id', $authenticatable->getKey())
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otp) {
            return ['success' => false, 'reason' => 'No OTP found. Please request a new one.'];
        }

        if ($otp->isExpired()) {
            return ['success' => false, 'reason' => 'OTP has expired. Please request a new one.'];
        }

        if ($otp->isMaxAttempts()) {
            return ['success' => false, 'reason' => 'Too many attempts. Please request a new OTP.'];
        }

        $otp->increment('attempts');

        if (! Hash::check($code, $otp->code_hash)) {
            return ['success' => false, 'reason' => 'Invalid OTP code.'];
        }

        $otp->update(['verified_at' => now()]);

        return ['success' => true, 'otp' => $otp];
    }

    public function canResend(Model $authenticatable, string $purpose): array
    {
        $cacheKey = "otp_resend:{$purpose}:" . get_class($authenticatable) . ':' . $authenticatable->getKey();

        if (Cache::has($cacheKey)) {
            $remaining = Cache::get($cacheKey) - now()->timestamp;
            return [
                'can_resend' => false,
                'remaining_seconds' => max(0, $remaining),
            ];
        }

        return ['can_resend' => true, 'remaining_seconds' => 0];
    }

    public function setResendCooldown(Model $authenticatable, string $purpose): void
    {
        $cacheKey = "otp_resend:{$purpose}:" . get_class($authenticatable) . ':' . $authenticatable->getKey();
        Cache::put($cacheKey, now()->addSeconds(60)->timestamp, 60);
    }

    private function invalidatePrevious(Model $authenticatable, string $purpose): void
    {
        Otp::where('authenticatable_type', get_class($authenticatable))
            ->where('authenticatable_id', $authenticatable->getKey())
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->update(['verified_at' => now()]);
    }
}
