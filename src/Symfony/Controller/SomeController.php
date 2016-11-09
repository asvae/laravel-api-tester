<?php

namespace Asvae\ApiTester\Symfony\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class SomeController extends Controller
{
    /**
     * @Route("/api-tester")
     */
    public function someAction()
    {
        return new Response(<<<EOT
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="{{ /api-tester/' }}">
    <link media="all" type="text/css" rel="stylesheet" href="/api-tester/assets/api-tester.css">
    <title>Laravel api tester</title>
</head>
<body>
<div id="api-tester">
    <vm-api-tester-main></vm-api-tester-main>
</div>
  <script src="/api-tester/assets/api-tester.js"></script>
</body>
</html>
EOT
        );
    }

    /**
     * @Route("/api-tester/assets/{file}")
     */
    public function loadAsset($file)
    {
        return $this->file($file, __DIR__.'/../../../resources/assets/build/');
    }

    /**
     * @Route("api-tester/assets/img/jsoneditor-icons.svg")
     */
    public function loadSvg()
    {

    }

    protected function file($file, $root)
    {
        $contents = file_get_contents($root.'/'.$file);
        $response = new Response($contents);
        $response->headers->set('Content-Type', $this->getFileContentType($file), $file);

        // Browser will cache files for 1 year.
        $secondsInYear = 60 * 60 * 24 * 365;
        $response->setSharedMaxAge($secondsInYear);
        $response->setMaxAge($secondsInYear);
        $response->setExpires(new \DateTime('+1 year'));

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