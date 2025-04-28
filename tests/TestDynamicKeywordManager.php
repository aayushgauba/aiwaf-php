<?php
// tests/TestDynamicKeywordManager.php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/DynamicKeywordManager.php';

class TestDynamicKeywordManager extends TestCase
{
    private DynamicKeywordManager $manager;
    private string $path;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir() . '/test_keywords.json';
        file_put_contents($this->path, json_encode([]));
        // Override config
        copy(__DIR__ . '/../src/Config.php', __DIR__ . '/Config.stub.php');
        $config = file_get_contents(__DIR__ . '/Config.stub.php');
        $config = str_replace("'DYNAMIC_KEYWORDS_PATH'=> __DIR__ . '/../resources/dynamic_keywords.json'", "'DYNAMIC_KEYWORDS_PATH'=> '{$this->path}'", $config);
        file_put_contents(__DIR__ . '/Config.stub.php', $config);
        require_once __DIR__ . '/Config.stub.php';
        $this->manager = new DynamicKeywordManager();
    }

    public function testAddAndRemoveKeywords(): void
    {
        $added = $this->manager->addKeywords(['testkw']);
        $this->assertTrue($added);
        $this->assertContains('testkw', $this->manager->getKeywords());
        $removed = $this->manager->removeKeywords(['testkw']);
        $this->assertTrue($removed);
        $this->assertNotContains('testkw', $this->manager->getKeywords());
    }
}