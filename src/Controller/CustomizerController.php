<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomizerController extends AbstractController
{
    /**
     * @Route("/customize")
     */
    public function __invoke()
    {
        return $this->render('customizer/customize.html.twig');
    }

    /**
     * @Route("/customize/{customPath}", methods={"GET"})
     */
    public function showAction(string $customPath)
    {
        $customAbsolutePath = $this->resolveCustomFile($customPath);

        if ($customAbsolutePath) {
            $mimeType = mime_content_type ($customAbsolutePath );
            $response = new BinaryFileResponse($customAbsolutePath);
            $response->headers->set('Content-Type', $mimeType);
            return $response;
        } else {
            throw $this->createNotFoundException('file not found');
        }
    }


    /**
     * @Route("/customize/{customPath}", methods={"PUT"})
     */
    public function updateAction(string $customPath, Request $request) {
        $customAbsolutePath = $this->resolveCustomFile($customPath);

        if ($customAbsolutePath) {
            $content = $request->getContent();
            file_put_contents($customAbsolutePath, $content, LOCK_EX);
            return new Response('', 202);
        } else {
            throw $this->createNotFoundException('file not found');
        }
    }

    protected function resolveCustomFile($filename) {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $baseDir = $projectRoot . "/custom/";
        $customAbsolutePath = $baseDir . $filename;

        if (!is_file($customAbsolutePath)) {
            return false;
        }
        return $customAbsolutePath;
    }
}