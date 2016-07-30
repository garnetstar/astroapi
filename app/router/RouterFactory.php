<?php

namespace App;

use Drahak\Restful\Application\Routes\CrudRoute;
use Drahak\Restful\Application\Routes\ResourceRoute;
use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $router[] = new ResourceRoute("diary[/<id>]", [
            'presenter' => 'Diary',
            'action' => array(
                ResourceRoute::GET => 'list',
                ResourceRoute::POST => 'update'
            )
        ], ResourceRoute::POST | ResourceRoute::GET);

        $router[] = new ResourceRoute("messierData", [
            'presenter' => 'Data',
            'action' => [
                ResourceRoute::GET => 'messierData'
            ]
        ]);

//        $router[] = new CrudRoute('api/v1/<presenter>[/<id>[/<relation>[/<relationId>]]]', 'Sample');

//        $router[] = new ResourceRoute('test', array(
//            'presenter' => 'Sample',
//            'action' => array(
//                ResourceRoute::GET => 'content',
//                ResourceRoute::DELETE => 'delete'
//            )
//        ), ResourceRoute::GET | ResourceRoute::DELETE);

        $router[] = new ResourceRoute("token", [
                'presenter' => 'OAuth',
                'action' => [ResourceRoute::POST => 'token']
            ], ResourceRoute::POST

        );


        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');


        return $router;
    }

}
