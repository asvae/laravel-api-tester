<?php

namespace Asvae\ApiTester\Services;

/**
 * Retrieves asset files without relying on framework specific libraries.
 */
class AssetsLoader
{
    protected $path;

    /**
     * route like this:
     * - 'api-tester.css'
     * - 'img/jsoneditor-icons.svg'
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Get asset contents.
     *
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->getPath().$this->path);
    }

    /**
     * Get asset headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return [
            // Cache for year
            'Cache-Control' => 'max-age=31536000, public, s-maxage=31536000',
            'Expires'       => new \DateTime('+1 year'),
            'Content-Type'  => $this->getContentType(),
        ];
    }

    private function getPath()
    {
        return __DIR__.'/../../resources/assets/build/';
    }

    private function getContentType()
    {
        $array = explode('.', $this->path);
        $ext = end($array);

        $contentTypes = [
            'css'   => 'text/css',
            'js'    => 'text/javascript',
            'svg'   => 'image/svg+xml',
            'map'   => 'text/css',
            'woff'  => 'application/x-font-woff',
            'woff2' => 'application/x-font-woff2',
        ];

        return $contentTypes[$ext];
    }
}