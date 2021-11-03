<?php

namespace MakelarisJR\Laravel2FA\Tests\Actions;

use MakelarisJR\Laravel2FA\Actions\GenerateBackupCodes;

class GenerateBackupCodesTest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    public function receives_array_of_string_of_specific_size()
    {
        $count = 10;
        $class = new GenerateBackupCodes();
        $output = $class->generate($count);

        $this->assertIsArray($output);
        $this->assertEquals(count($output), $count);
    }
}
