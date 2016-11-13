<?php

class ViewLoaderTest extends PHPUnit_Framework_TestCase
{
    public function test_view_loaded()
    {
        $configLoader = new \Asvae\ApiTester\Services\ViewLoader();

        $view = $configLoader->getView();

        $this->assertNotEmpty($view);
    }
}