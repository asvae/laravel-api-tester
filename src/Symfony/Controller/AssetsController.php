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

class AssetsController extends Controller
{

    /**
     * @Route("/api-tester/assets/{file}")
     */
    public function mainAssets($file)
    {
        return $this->generateResponse($file);
    }

    /**
     * @Route("/api-tester/assets/img/{file}")
     */
    public function img($file)
    {
        return $this->generateResponse('img/'.$file);
    }

    /**
     * @Route("/api-tester/assets/fonts/{file}")
     */
    public function fonts($file)
    {
        return $this->generateResponse('../fonts/'.$file);
    }

    protected function generateResponse($path){
        $assetsLoader = new AssetsLoader($path);
        $response = new Response($assetsLoader->getContents());
        $response->headers->add($assetsLoader->getHeaders());

        return $response;
    }
}