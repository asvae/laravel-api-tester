<?php

namespace Asvae\ApiTester\Services;

class ConfigLoader
{
    public function loadConfig()
    {
        return include __DIR__.'/../../config/api-tester.php';
    }
}