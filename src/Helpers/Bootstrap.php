<?php


namespace BagOfIdeas\Helpers;


use Propel\Runtime\Propel;
use Propel\Runtime\ServiceContainer\StandardServiceContainer;
use RuntimeException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Bootstrap
{
    public function init()
    {
        $this->registerErrorHandlers();
        $this->initDatabase();
    }


    private function initDatabase()
    {
        /** @var StandardServiceContainer $serviceContainer */
        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', 'mysql');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration(array(
            'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
            'dsn' => 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE . ';charset=utf8',
            'user' => MYSQL_USERNAME,
            'password' => MYSQL_PASSWORD,
            'attributes' =>
                array(
                    'ATTR_EMULATE_PREPARES' => false,
                    'ATTR_TIMEOUT' => 30,
                ),
            'settings' =>
                array(
                    'charset' => 'utf8mb4',
                    'queries' =>
                        array(),
                ),
            'model_paths' =>
                array(
                    0 => 'src',
                    1 => 'vendor',
                ),
        ));
        $manager->setName('default');
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');


    }

    private function registerErrorHandlers(): void
    {
        $whoops = new Run;
        $whoops->prependHandler(new PrettyPageHandler);
        $whoops->register();
    }


    public function handleRouting(): void {


        $route = $_SERVER['REQUEST_URI'];
        $route = preg_replace('%/+%', '/', $route);

        if(preg_match('%^/(.*?)/(.*?)(?:/(.*))?$%', $route, $result)){
            $route1 = isset($result[3]) ? $result[3] : '';
            $request = new Request($result[2] ?: 'wiki', $route1);

            $class = $request->getModuleClass();
            if(class_exists($class)) {
                $instance = new $class();
                if($instance instanceof AbstractController){
                    $result = $instance->handleRequest($request);
                    $result->export();
                } else {
                    throw new RuntimeException("The loaded module $class is not a instance of AbsctractController");
                }
            } else {
                throw new RuntimeException("Could not find module {$class}");
            }
        } else {
            throw new RuntimeException("Unknown route found {$route}");
        }

    }
}