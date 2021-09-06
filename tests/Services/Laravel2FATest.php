<?php

namespace MakelarisJR\Laravel2FA\Tests\Services;

use MakelarisJR\Laravel2FA\Services\Laravel2FA;
use Orchestra\Testbench\TestCase;

class Laravel2FATest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('laravel2fa.yubikey.client_id', 'THIS_IS_A_TEST');
        $app['config']->set('laravel2fa.yubikey.secret_key', 'THIS_IS_A_TEST');
        $app['config']->set('laravel2fa.yubikey.api_server', 'http://localhost');
    }

    /** @test */
    public function generates_secret_code()
    {
        $class = new Laravel2FA();

        $this->assertIsString($class->generateSecretKey());
    }

    /** @test */
    public function verifies_google_otp()
    {
        $this->markTestIncomplete();
    }
}
