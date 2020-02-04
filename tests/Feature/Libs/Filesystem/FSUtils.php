<?php

namespace Tests\Feature\Libs\Filesystem;

use App\Libs\System\OperatingSystem;
use Tests\TestCase;

class FSUtils extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCheckRedundantDirectorySeparators()
    {
        $os = OperatingSystem::getOS();

        switch ($os) {
            case \App\Libs\Constants\OperatingSystem::WINDOWS:
                $path = "C:\\\\Test\\\Folder\\\\Archive.dat";
                $realPath = "C:\\Test\\Folder\\Archive.dat";
                break;
            default:
                $path = "/root///test///home//file.tar.gz";
                $realPath = "/root/test/home/file.tar.gz";
                break;
        }

        $this->assertEquals($realPath, \App\Libs\Filesystem\FSUtils::checkRedundantDirSeparators($path), 'Redundant directory separator formatter not working correctly');
    }
}
