<?php

$params = [
    'driver'=>  '',
    'host'=>    '',
    'dbname'=>  '',
    'username'=> '',
    'pwd'     => ''
];

$dsn = sprintf('%s:host=%s;dbname=%s;charset=utf8',$params['driver'],$params['host'], $params['dbname']);
try
{
    $pdo = new PDO($dsn,$params['username'],$params['pwd']);
    $pdo->setAttribute(PDO::ERRMODE_EXCEPTION,PDO::ATTR_ERRMODE);

    //For testing purpose 
    //$output = 'all is fine';

}catch(PDOException $e)
{
    $output = 'Error : ' . $e->getMessage() . 'in '. $e->getFile() . 'At this line : ' . $e->getLine();

    include __DIR__. '/../templates/index.html.php';

}catch(Throwable $e)
{
    $output = 'Error : ' . $e->getMessage() . 'in '. $e->getFile() . 'At this line : ' . $e->getLine();
    include __DIR__. '/../templates/index.html.php';
};




