<?php
/**
 * Dash
 *
 * @link      http://github.com/DASPRiD/Dash For the canonical source repository
 * @copyright 2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace DashTest\Router\Http;

use Dash\Router\Http\Parser\ParserManager;
use Dash\Router\Http\Route\RouteManager;
use Dash\Router\Http\RouterFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Dash\Router\Http\RouterFactory
 */
class RouterFactoryTest extends TestCase
{
    protected $config = [
        'dash_router' => [
            'base_path' => '/foo',
            'routes' => [
                'user' => ['/user', 'index', 'Application\Controller\UserController', 'children' => [
                    'create' => ['/create', 'create', 'Application\Controller\UserController', ['get', 'post']],
                    'edit' => ['/edit/:id', 'edit', 'Application\Controller\UserController', ['get', 'post'], 'constraints' => ['id' => '\d+']],
                    'delete' => ['/delete/:id', 'edit', 'Application\Controller\UserController', 'constraints' => ['id' => '\d+']],
                ]],
            ],
        ],
    ];

    public function testFactorySucceedsWithoutConfig()
    {
        $serviceLocator = $this->getServiceLocator();
        $serviceLocator->setService('config', []);

        $factory = new RouterFactory();
        $factory->createService($serviceLocator);
    }

    public function testFactoryIntegration()
    {
        $serviceLocator = $this->getServiceLocator();
        $serviceLocator->setService('config', $this->config);

        $factory = new RouterFactory();
        $router  = $factory->createService($serviceLocator);

        $this->assertEquals('/foo', $router->getBasePath());

        $request = new Request();
        $request->setUri('http://example.com/foo/user/edit/1');

        $match = $router->match($request);

        $this->assertInstanceOf('Dash\Router\Http\RouteMatch', $match);
        $this->assertEquals('user/edit', $match->getRouteName());
        $this->assertEquals(['controller' => 'Application\Controller\UserController', 'action' => 'edit', 'id' => '1'], $match->getParams());
    }

    /**
     * @return ServiceManager
     */
    protected function getServiceLocator()
    {
        $serviceLocator = new ServiceManager();

        $routeManager = new RouteManager();
        $routeManager->setServiceLocator($serviceLocator);
        $serviceLocator->setService('Dash\Router\Http\Route\RouteManager', $routeManager);

        $parserManager = new ParserManager();
        $parserManager->setServiceLocator($serviceLocator);
        $serviceLocator->setService('Dash\Router\Http\Parser\ParserManager', $parserManager);

        return $serviceLocator;
    }
}
