<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('OurRoute');

Router::extensions(['json']);

Router::scope('/', function ($routes) {
    $routes->connect('/', [ 'controller' => 'Dashboard', 'action' => 'index']);
    $routes->connect('/notifications/zencoder', [ 'controller' => 'Notifications', 'action' => 'zencoder']);
    $routes->connect('/go/*', ['controller' => 'Redirects', 'action' => 'go']);

    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes->connect('/register', ['controller' => 'Users', 'action' => 'register']);

    $routes->connect('/admin', ['prefix' => 'admin', 'controller' => 'Dashboard', 'action' => 'index']);
    $routes->connect('/marketing', ['prefix' => 'customer', 'controller' => 'Dashboard', 'action' => 'index']);
    $routes->connect('/development', ['prefix' => 'development', 'controller' => 'Dashboard', 'action' => 'index']);
    $routes->connect('/customer', ['prefix' => 'customer', 'controller' => 'Dashboard', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

Router::prefix('admin', function ($routes) {
    // Because you are in the admin scope,
    // you do not need to include the /admin prefix
    // or the admin route element.
    $routes->connect('/:controller', ['action' => 'index']);
    $routes->connect('/:controller/:action/*', []);
    $routes->connect('/apps/add', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'add']);
    $routes->connect('/apps/edit/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'edit']);
    $routes->connect('/apps/screens/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'screens']);
    #uirgh
    $routes->connect('/Apps/add', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'add']);
    $routes->connect('/Apps/edit/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'edit']);
    $routes->connect('/Apps/screens/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'screens']);

    $routes->fallbacks('DashedRoute');
});

Router::prefix('marketing', function ($routes) {
    $routes->connect('/:controller', ['action' => 'index']);
    $routes->connect('/:controller/:action/*', []);

    $routes->fallbacks('DashedRoute');
});

Router::prefix('development', function ($routes) {
    $routes->connect('/:controller', ['action' => 'index']);
    $routes->connect('/:controller/:action/*', []);

    $routes->fallbacks('DashedRoute');
});

Router::prefix('customer', function ($routes) {
    $routes->connect('/:controller', ['action' => 'index']);
    $routes->connect('/:controller/:action/*', []);
    $routes->connect('/apps/add', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'add']);
    $routes->connect('/apps/edit/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'edit']);
    $routes->connect('/apps/screens/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'screens']);
    #uirgh
    $routes->connect('/Apps/add', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'add']);
    $routes->connect('/Apps/edit/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'edit']);
    $routes->connect('/Apps/screens/*', ['prefix' => 'development', 'controller' => 'apps', 'action' => 'screens']);

    $routes->connect('/WddsDashboard', ['prefix' => 'WddsDashboard', 'controller' => 'WddsDashboard', 'action' => 'index']);

    $routes->fallbacks('DashedRoute');

});

Router::prefix('api', function ($routes) {
    $routes->connect('/:controller', ['action' => 'index']);
    $routes->connect('/:controller/:action/*', []);

    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
