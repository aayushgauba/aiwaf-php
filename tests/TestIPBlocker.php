<?php
require_once __DIR__ . '/../src/Utils.php';
require_once __DIR__ . '/../src/IPBlocker.php';
require_once __DIR__ . '/../src/Config.php';

use PHPUnit\Framework\TestCase;

class TestIPBlocker extends TestCase
{
    public function testBlockUnblockIp()
    {
        $ip = '192.0.2.1';
        IPBlocker::blockIp($ip);

        $blockedIps = IPBlocker::getBlockedIps();
        $this->assertContains($ip, $blockedIps);
    }
}
