<?php
namespace App;

use Twig\Environment;
use Twig\Loader;

final class TemplateRenderer
{
    private $twig;
    private $loader;

    public function __construct(
        Environment $twig,
        Loader\LoaderInterface $loader
    ) {
        if (!$loader instanceof Loader\FilesystemLoader) {
            throw new \InvalidArgumentException(sprintf(
                'Loader needs to be an instance of "%s", got "%s" instead.',
                Loader\FilesystemLoader::class,
                get_class($loader)
            ));
        }

        $this->twig = $twig;
        $this->loader = $loader;
    }

    public function render(string $name, array $parameters = []): string
    {
        $this->loader->addPath(__DIR__ . '/../custom');
        $this->loader->addPath(__DIR__ . '/../templates');

        return $this->twig->render(
            $name,
            $parameters
        );
    }
}
