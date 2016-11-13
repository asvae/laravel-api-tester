<?php

namespace Asvae\ApiTester\Symfony\Controller;

use Asvae\ApiTester\Services\AssetsLoader;
use Asvae\ApiTester\Services\ViewLoader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/api-tester")
     */
    public function apiTester()
    {
        $view = (new ViewLoader())->getView();

        return new Response($view);
    }
}