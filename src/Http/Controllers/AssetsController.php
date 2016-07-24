<?php

namespace Asvae\ApiTester\Http\Controllers;

use DateTime;
use Illuminate\Routing\Controller;

class AssetsController extends Controller
{
    public function index($file = '')
    {
        // Permit only safe characters in filename.
        if (! preg_match('%^([a-z_\-\.]+?)$%', $file)) {
            abort(404);
        }

        $contents = file_get_contents(__DIR__.'/../../../resources/assets/build/'.$file);
        $response = response($contents, 200, [
            'Content-Type' => $this->getFileContentType($file)
        ]);

        // Browser will cache files for 1 year.
        $secondsInYear = 60*60*24*365;
        $response->setSharedMaxAge($secondsInYear);
        $response->setMaxAge($secondsInYear);
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
