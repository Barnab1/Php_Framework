
<?php
 include __DIR__ .'/../includes/autoload.php';
 
try
{
        
    $route = '';
    /* $_SERVER['REQUEST_METHOD'] = GET  */
    $entryPoint = new \Ninja\EntryPoint($route,$_SERVER['REQUEST_METHOD'],new \Project\ProjectRoutes());
    $entryPoint->run();
   
}catch(PDOException $e)
{
    $output = 'Database Error From index: '. $e->getMessage() . ' In '. $e->getFile() . 'At Line' . $e->getLine();  
    
    include __DIR__. '/../templates/index.html.php';
}
catch(Throwable $e)
{
    $output = ' Error :<b> '. $e->getMessage() . '</b> That Error will be found in '. $e->getFile() . 'At Line ' . $e->getLine();  
    
    include __DIR__. '/../templates/index.html.php';
}
