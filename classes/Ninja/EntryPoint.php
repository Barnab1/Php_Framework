<?php
declare(strict_types = 1);
namespace Ninja;


/**
 * CLASS EntryPoint
 * 
 */
class EntryPoint 
{
    private $route;

    /**
     * The method is either POST or GET
     */
    private $method;

    /**
     * 
     * @@@parama{*routes where there is all values}
     */
    private $routes;
   

    public function __construct(string $route , string $method, \Ninja\Routes $routes)
    {
        $this->route = $route;
        $this->method = $method;
        $this->routes = $routes;
       
        $this->checkUrl();
    }

    private function checkUrl()
    {
        if($this->route !== strtolower($this->route))
        {
            http_response_code(301);
            header('location: ' . strtolower($this->route));
        }
    }

    private function loadTemplate($templateFilename, $variables = [])
    {
        extract($variables);
        ob_start();
        include __DIR__ . '/../../templates/'. $templateFilename ;
        return ob_get_clean();
    }
    public function run()
    {
        $routes = $this->routes->getRoutes();

        $controller = $routes[$this->route][$this->method]['controller'];
        $action = $routes[$this->route][$this->method]['action'];

        $page = $controller->$action() ;

        $title = $page['title'];
        if(isset($page['variables']))
        {
            $output = $this->loadTemplate($page['template'],$page['variables']);
        }else{
            $output = $this->loadTemplate($page['template']);
        }
        include __DIR__. '/../../templates/index.html.php';
    }
}