<?php

namespace MakelarisJR\Laravel2FA\Tests\Actions;

use MakelarisJR\Laravel2FA\Actions\GenerateGoogleQRCode;
use PragmaRX\Google2FA\Google2FA;

class GenerateGoogleQRCodeTest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    public function google_qr_returns_an_image_string()
    {
        $g2fa = new Google2FA();
        $class = new GenerateGoogleQRCode($g2fa->generateSecretKey());
        $out = $class->generate();

        $this->assertIsString($out);
        $this->assertStringStartsWith('data:image/svg+xml;base64', $out);
    }
}
