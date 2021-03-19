<?php
namespace App\Controller;

use App\TemplateRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoController extends AbstractController
{
    /**
     * @Route("/demo")
     */
    public function __invoke(TranslatorInterface $translator)
    {
        $view = 'helloworld.html.twig';
        $parameters = [];

        $content = $this->render(
            $view,
            $parameters
        );

        $response = new Response();
        $response->setContent($content);

        return $response;
    }


}