<?php
namespace App;

use Sylius\Bundle\ThemeBundle\Context\SettableThemeContext;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ThemeRequestListener
{
    /** @var ThemeRepositoryInterface */
    private $themeRepository;

    /** @var SettableThemeContext */
    private $themeContext;

    public function __construct(ThemeRepositoryInterface $themeRepository, SettableThemeContext $themeContext)
    {
        $this->themeRepository = $themeRepository;
        $this->themeContext = $themeContext;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }


        // resolve theme by anything
        // $theme = $this->themeRepository->findOneByName('marsch/vanandern-theme');
        $theme = $this->themeRepository->findOneByName('marsch/oxelmoxel-theme');

        // dd($theme);
        $this->themeContext->setTheme($theme );
    }
}