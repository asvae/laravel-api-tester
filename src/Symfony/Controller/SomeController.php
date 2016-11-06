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
     * @Route("/api-tester/assets/api-tester.js")
     */
    public function jsAction()
    {
        $fileName = 'api-tester.js';
        $fullPath = __DIR__.'/../../../resources/assets/build/'.$fileName;

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($fullPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName, iconv('UTF-8', 'ASCII//TRANSLIT', $fileName));

        return $response;
    }

    /**
     * @Route("/api-tester/assets/api-tester.css")
     */
    public function cssAction()
    {
        $fileName = 'api-tester.css';
        $fullPath = __DIR__.'/../../../resources/assets/build/'.$fileName;

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($fullPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName, iconv('UTF-8', 'ASCII//TRANSLIT', $fileName));

        return $response;
    }

    /**
     * @Route("/api-tester/assets/api-tester.css")
     */
    public function someAction3()
    {
        $basePath = __DIR__.'/../../../resources/assets/build/';
        echo ($basePath.'api-tester.js');

        return new Response('some3');

    }
}