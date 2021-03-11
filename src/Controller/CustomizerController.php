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
     * @Route("/customize/{customPath}", methods={"GET"}, requirements={"customPath"=".+"})
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
     * @Route("/customize/{customPath}", methods={"PUT"}, requirements={"customPath"=".+"})
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

    /**
     * @Route("/customize-tree", methods={"POST"})
     */
    public function showTree(string $customPath = '', Request $request) {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $baseDir = $projectRoot . "/custom/";
        $customDir = $baseDir;
        $dir = $request->request->get('dir');


        if ($dir) {
            $customDir = $customDir . $dir;
        }

        if (file_exists($customDir)) {
            $files = scandir($customDir);
            $returnDir = substr($customDir, strlen($baseDir));
            natcasesort($files);

            $html = "";
            if (count($files) > 2) { // The 2 accounts for . and ..
                $html = "<ul class='jqueryFileTree'>";
                foreach ($files as $file)
                {
                    $htmlRel = htmlentities($returnDir . $file);
                    $htmlName = htmlentities($file);
                    $ext = preg_replace('/^.*\./', '', $file);
                    if (file_exists($customDir . $file) && $file != '.' && $file != '..') {
                        if (is_dir($customDir . $file)) {
                            $html .= "<li class='directory collapsed'><a rel='" . $htmlRel . "/'>" . $htmlName . "</a></li>";
                        }
                        else {
                            $html .= "<li class='file ext_{$ext}'><a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
                        }
                    }
                }
                $html .= "</ul>";
            }

            return new Response($html, 200);
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