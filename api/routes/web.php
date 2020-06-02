<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*$router->get('/', function () use ($router) {
    return $router->app->version();
});*/

$router = app('Dingo\Api\Routing\Router');
$router->version('v1', function ($api) {
    $namespace = 'App\Http\Controllers';
    $api->group(['namespace' => $namespace], function ($api) use ($namespace) {
        $api->post('login','LoginController@login');
        $api->get('outlogin','LoginController@outLogin');
        $api->get('refresh','LoginController@refresh');
        $api->get('getcaptcha','LoginController@getCaptcha');

        $api->post('system/count','SystemController@getCount');


        $api->post('log/action','LogController@getUserAction');
        $api->post('system/config','SystemController@getSystemConfig');
        $api->post('project/get','ProjectController@getProjectList');
        $api->post('user/getlist','UserController@getUserList');
        $api->post('user/getone','UserController@getOneUserInfo');
        $api->post('user/edit','UserController@editUser');
        $api->post('user/del','UserController@delUser');


        $api->post('project/getlist','ProjectController@getProjectList');
        $api->post('project/getone','ProjectController@getOneProject');
        $api->post('project/edit','ProjectController@editProject');
        $api->post('project/module/edit','ProjectController@editProjectModule');
        $api->post('project/module/getone','ProjectController@getOneProjectModule');
        $api->post('project/del','ProjectController@delProject');
        $api->post('project/module/del','ProjectController@delProjectModule');
        $api->post('project/module/list','ProjectController@getOneProjectModuleList');
        $api->post('project/testuser','ProjectController@getProjectTestuserList');
        $api->post('project/testuser/getone','ProjectController@getOneProjectTestuser');
        $api->post('project/testuser/edit','ProjectController@editProjectTestuser');
        $api->post('project/testuser/del','ProjectController@delProjectTestuser');



        $api->post('api/lists','ApiController@getApiLists');
        $api->post('api/info','ApiController@getApiInfo');
        $api->post('api/edit','ApiController@editApi');
        $api->post('api/del','ApiController@delApi');
        $api->post('api/param/get','ApiController@getParam');
        $api->post('api/goback/get','ApiController@getGoback');
        $api->post('api/param/edit','ApiController@editParam');
        $api->post('api/goback/edit','ApiController@editGoback');
        $api->post('api/param/del','ApiController@delParam');
        $api->post('api/goback/del','ApiController@delGoback');
        $api->post('api/runapi','ApiController@runApi');
        $api->post('api/info/test','ApiController@getApiInfoTest');


    });


});
