<?php

namespace Asvae\ApiTester\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;

class AssetsController extends Controller
{
    const SECONDS_IN_YEAR = 60*60*24*365;

    public function index($file)
    {
        $root = __DIR__.'/../../../resources/assets/build';

        return $this->file($file, $root);
    }

    public function image($file){
        $root = __DIR__.'/../../../resources/assets/build/img';
        return $this->file($file, $root);
    }

    protected function file($file, $root){
        $contents =  file_get_contents($root.DIRECTORY_SEPARATOR.$file);
        $response = response($contents, 200, [
            'Content-Type' => $this->getFileContentType($file)
        ]);

        // Browser will cache files for 1 year.
        $response->setSharedMaxAge(static::SECONDS_IN_YEAR);
        $response->setMaxAge(static::SECONDS_IN_YEAR);
        $response->setExpires(new DateTime('+1 year'));

        return $response;
    }

    /**
     * Figure out appropriate "Content-Type" header
     * by filename.
     *
     * @param $file
     * @return mixed
     */
    protected function getFileContentType($file)
    {
        $array = explode('.', $file);
        $ext = end($array);

        $contentTypes = [
            'css' => 'text/css',
            'js'  => 'text/javascript',
            'svg' => 'image/svg+xml',
        ];

        return $contentTypes[$ext];
    }
}
