<?php

namespace MakelarisJR\Laravel2FA\Actions;

use Illuminate\Support\Str;

class GenerateBackupCodes
{
    public function generate(int $total = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $total; $i++)
        {
            $codes[] = implode('-', [Str::random(5), Str::random(5)]);
        }

        return $codes;
    }
}
