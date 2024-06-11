<?php
declare(strict_types = 1);
namespace Project;

class ProjectRoutes implements \Ninja\Routes
{


    /**
     * 
     * That public construct function instantiate the class 
     * but without parameters
     * 
     * 
     **/

     private $usersTable;
     private $authentication;


     
    public function __construct()
    {
        include __DIR__. '/../../includes/DatabaseConnection.php';
        
      
    }



    /**
     * getRoutes( )
     * **************************************
     * Return the route which will intantiate
     *  the controller and the action required
     * ***************************************
     * @return routes array
     */
    public function getRoutes()
    {
     
        $exposesController = new \Project\Controller\ExposesController();
        $loginController = new \Project\Controller\LoginController($this->authentication);
        $registerController = new \Project\Controller\RegisterController($this->usersTable);

      $routes = [
                    'exposes'=>
                    [
                        'GET'=>
                        [
                            'controller'=>$exposesController,
                            'action'=>'exposes'
                        ]
                    ] ,

                   
                ]  ;
        return $routes;
        
    }

   
}
