<?php

namespace App\Services;

use App\Models\UserToken;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DeviceService
{
    public function registerDevice($user, $request)
    {
        $fingerprint = hash(
            'sha256',
            $request->ip() .
                $request->userAgent() .
                $request->header('accept-language')
        );

        // Hapus token lama untuk user ini
        UserToken::where('user_id', $user->id)->delete();

        // Generate token unik
        $token = bin2hex(random_bytes(32)); // token 64 karakter

        UserToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'refresh_token' => Str::random(64), // wajib diisi
            'device_fingerprint' => $fingerprint,
            'ip_address' => $request->ip(),
            'device' => $request->userAgent(),
            'expired_at' => Carbon::now()->addHours(12) // masa berlaku 12 jam
        ]);
        return $token;
    }

    public function logoutDevice($userId)
    {
        UserToken::where('user_id', $userId)->delete();
    }
}
