<?php

namespace Tests\Feature\Libs\System;

use Tests\TestCase;

class OperatingSystem extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetOs()
    {
        $os = \App\Libs\System\OperatingSystem::getOS();

        $this->assertEquals('string', gettype($os), 'OperatingSystem::getOS method should be returns string type. But it returned `' . gettype($os) . '`');
    }
}
