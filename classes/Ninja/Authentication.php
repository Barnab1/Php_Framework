<?php
declare(strict_types = 1);
namespace Ninja;


/**
 * 
 * That Classe will serve for user login. 
 * It will contain two method : the login() method
 * And the isLoggedIn() method for respectively
 * connecting and checking if the user is the  one he 
 * should be
 */

class Authentication

{
    private $usersTable;
    private $usernameColumn;
    private $passwordColumn;


    public function __construct(\Ninja\DatabaseTable $usersTable,string $usernameColumn, string $passwordColumn)
    {
        session_start();
       $this->usersTable = $usersTable;
       $this->usernameColumn = $usernameColumn;
       $this->passwordColumn = $passwordColumn;
    }

    /***
     * 
     * Responsible for login in a user according two credentials
     * And based the column provided by the User
     * 
     */
    public function login($username,$password)
    {
        $user = $this->usersTable->find($this->usernameColumn,strtolower($username));
        $passwordFromDatabase = $user[0]->{$this->passwordColumn};
        
       if(!empty($user) && password_verify($password,$passwordFromDatabase))
       {
          session_regenerate_id();
          $_SESSION['username'] = $username;
          $_SESSION['password'] = $passwordFromDatabase;
          return true;  
       }else
       {
        return false;
       }

    }

    public function isLoggedIn()
    {
        if(empty($_SESSION['username']))
        {
            return false;
        }
        $user = $this->usersTable->find($this->usernameColumn,strtolower($_SESSION['username']));

        if(!empty($user) && $user[0]->{$this->passwordColumn} === $_SESSION['password'])
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function getUser()
    {
        if($this->isLoggedIn())
        {
            $user = $this->usersTable->find($this->usernameColumn,strtolower($_SESSION['username']));
             return $user[0];
        }else
        {
            return false;
        }
    }
}