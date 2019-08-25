<?php


namespace BagOfIdeas\Helpers;


class Request
{
    /** @var string */
    private $route;
    /** @var string */
    private $module;

    /**
     * Request constructor.
     * @param string $module
     * @param string $route
     */
    public function __construct(string $module, string $route)
    {
        $this->module = $module;
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return explode('/', $this->route);
    }

    public function getRoutePart(int $index): string {
        return $this->getRoute()[$index] ?? '';
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }



    /**
     * @return string
     */
    public function getModulePath(): string
    {
        return realpath(ROOT . 'src/Modules/' . $this->module);
    }

    /**
     * @return string
     */
    public function getModuleClass(): string
    {
        $moduleCase = basename($this->getModulePath());
        return '\BagOfIdeas\Modules\\'. $moduleCase . '\Route';
    }


    function getParams(): array{
        return $_GET;
    }

    function postParams(): array{
        return $_POST;
    }
}