<?php

use Asvae\ApiTester\Services\AssetsLoader;

class AssetsLoaderTest extends PHPUnit_Framework_TestCase
{
    public function test_gets_content()
    {
        $assetsGetter = new AssetsLoader('api-tester.css');

        $assetBody = $assetsGetter->getContents();

        // This is a big file
        $this->assertTrue(strlen($assetBody) > 10000);
    }

    public function test_gets_headers()
    {
        $assetsGetter = new AssetsLoader('api-tester.js');
        $expectedKeys = [
            'Cache-Control',
            'Expires',
            'Content-Type',
        ];

        $headers = $assetsGetter->getHeaders();

        $this->assertEquals($expectedKeys, array_keys($headers));
    }

    public function test_works_with_nesting()
    {
        $assetsGetter = new AssetsLoader('img/jsoneditor-icons.svg');

        $headers = $assetsGetter->getHeaders();
        $this->assertCount(3, $headers);

        $assetBody = $assetsGetter->getContents();
        $this->assertNotEmpty($assetBody);
    }
}