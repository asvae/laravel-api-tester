<?php

class ConfigLoaderTest extends PHPUnit_Framework_TestCase
{
    public function test_config_loaded()
    {
        $configLoader = new \Asvae\ApiTester\Services\ConfigLoader();

        $config = $configLoader->loadConfig();

        $this->assertNotEmpty($config);
    }
}