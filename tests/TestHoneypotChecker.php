<?php
require_once __DIR__ . '/../src/HoneypotChecker.php';

use PHPUnit\Framework\TestCase;

class TestHoneypotChecker extends TestCase
{
    public function testCleanPost()
    {
        $postData = [];
        $this->assertFalse(HoneypotChecker::hasTriggered($postData));
    }

    public function testBotDetected()
    {
        $postData = ['aiwaf_honeytrap' => 'gotcha'];
        $this->assertTrue(HoneypotChecker::hasTriggered($postData));
    }
}
