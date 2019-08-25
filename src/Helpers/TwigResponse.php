<?php


namespace BagOfIdeas\Helpers;


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigResponse extends AbstractResponse
{
    /** @var Request */
    private $request;

    /** @var string */
    private $template;

    /** @var array */
    private $temlateParams;

    /** @var string[] */
    private $paths = [];

    /**
     * TwigResponse constructor.
     * @param Request $request
     * @param string $template
     * @param array $temlateParams
     */
    public function __construct(Request $request, string $template, array $temlateParams)
    {
        $this->request = $request;
        $this->template = $template;
        $this->temlateParams = $temlateParams;

        $templateDirectory = $request->getModulePath() . "/Templates";
        if (is_dir($templateDirectory)) {
            $this->paths[] = $templateDirectory;
        }

        $this->paths[] = ROOT . 'Templates';
    }


    /**
     *
     */
    function export(): void
    {
        if (!headers_sent()) {
            header('Content-type: text/html');
        }
        echo $this->renderTemplate();
    }

    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * @return string
     */
    public function renderTemplate(): string
    {
        $loader = new FilesystemLoader($this->paths);
        $twig = new Environment($loader, [
            'strict_variables' => true
        ]);

        $this->temlateParams['EXT_ROOT'] = EXT_ROOT;
        $this->temlateParams['TPL_ROOT'] = EXT_ROOT . 'Templates/';
        $this->temlateParams['SB_ADMIN_ROOT'] = EXT_ROOT . 'Templates/sb-admin/';


        /** @noinspection PhpUnhandledExceptionInspection */
        $results = $twig->render($this->template, $this->temlateParams);
        return $results;
    }
}