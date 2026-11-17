<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\GuruActivation;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class GuruActivationCodeService
{
    public const VALID_DAYS = 7;

    public const MAX_ATTEMPTS = 5;

    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function issue(Guru $guru, int $adminId): string
    {
        do {
            $rawCode = $this->randomCode();
            $code = $rawCode;
            $fingerprint = $this->fingerprint($rawCode);
        } while (GuruActivation::where('code_fingerprint', $fingerprint)->exists());

        GuruActivation::updateOrCreate(
            ['guru_id' => $guru->id],
            [
                'generated_by' => $adminId,
                'code_hash' => Hash::make($rawCode),
                'code_fingerprint' => $fingerprint,
                'attempts' => 0,
                'expires_at' => now()->addDays(self::VALID_DAYS),
            ]
        );

        return $code;
    }

    public function normalize(string $code): string
    {
        return strtoupper(trim($code));
    }

    public function fingerprint(string $code): string
    {
        $key = (string) config('app.key');

        if ($key === '') {
            throw new RuntimeException('APP_KEY must be configured to issue activation codes.');
        }

        return hash_hmac('sha256', $code, $key);
    }

    private function randomCode(): string
    {
        $code = '';
        $lastIndex = strlen(self::ALPHABET) - 1;

        for ($index = 0; $index < 5; $index++) {
            $code .= self::ALPHABET[random_int(0, $lastIndex)];
        }

        return $code;
    }
}
