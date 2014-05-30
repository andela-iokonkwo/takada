<?php
session_start();
Class MembersController
{
    public function GetAction($request) {
        $db = DbAccess::getInstance();
               
    }
	

    public function PostAction($request)
    {
       
        if(isset($request->url_elements[2]) && $request->url_elements[2] == 'login')
        {
            return $this->GetToken($request->parameters);
        }

        elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'register')
        {
            return $this->AddAccount($request->parameters);
        }
        elseif(isset($request->url_elements[2]) && $request->url_elements[3] == 'logout')
        {
            session_destroy();
           // $db = DbAccess::getInstance();
            //return $db->insertupdate('user','Id', array('Token','Expires') , array('Token' => '', 'Expires' => 0, 'Id' => $request->url_elements[2] )); 
        }
        elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'changepassword')
        {
            return $this->ChangePassword($request->parameters);
        }
        return null;
    }

    public function DeleteAction($request) 
    {
    if($this->Authenticate()){
       
      }
    }

    public function PutAction($request)
    {
      if($this->Authenticate()){
        $db = $db = DbAccess::getInstance();
        $Id = $db->insertupdate('Users','Id', array('Name','Location', 'Email', 'State', 'About', 'Loves', 'Hates', 'F-Music','F-Authors', 'F-Movies', 'F-Books', 'F-Quotes'), $request->parameters);
        return $Id;
       }        
    }

    function GetToken($parameters)
    {
        if(!$parameters['Email'])
            return;
        
        $db = DbAccess::getInstance();
        $query = $db->query('select Id, Password, Salt, Name from Users where Email= :email', array('email'=> $parameters['Email']));
        if(!$query->rowCount() > 0 )
            return;
        $result = $query->fetchAll();
        //$password = hash('SHA256', $parameters['Password']);
        $hashedPassword = hash_hmac('SHA256', $parameters['Password'], $result[0]['Salt']);
        if($hashedPassword !== $result[0]['Password'])
            return;
        $tokenSalt = bin2hex(rand(5555, 12457897654));
        $token = hash_hmac('SHA256', $hashedPassword, $tokenSalt);
       
        $mem = array('token' => $token, 'Name' => $result[0]['Name'], 'Id' => $result[0]['Id']);
        $_SESSION['token'] = $token;
        return $mem;
    }

    function AddAccount($parameters)
    {
     // return $parameters;
        $salt = rand();
        $parameters['Salt'] = $salt;
        //$password = hash('SHA256', $parameters['Password']);
        $dbPassword = hash_hmac('SHA256', $parameters['Password'] , $salt);
        $parameters['Password'] = $dbPassword;
        $db = $db = DbAccess::getInstance();
        return $db->insertupdate('Users','Id', array('Name', 'Email', 'Password', 'Salt'), $parameters);
    }
    function Authenticate()
    {
        $request_headers = apache_request_headers();

        if ( ! isset($request_headers['Auth-Token'])) {
            return false;
        }
     
     if(!isset($_SESSION['token'])){
        header('HTTP/1.0 401 Login Required');
        return false;
        }
        return $_SESSION['token'] === $request_headers['Auth-Token'];
    }

    function ChangePassword($parameters)
    {
        $db = DbAccess::getInstance();
        $Id = Authenticate();
        $query = $db->query('select Id, Salt from Users where Email= :email', array('email'=> $parameters['Email']));
        if(!$query->rowCount() > 0 )
            return;
        $result = $query->fetchAll();
        if($Id)
        {
            $dbPassword = hash_hmac('SHA256', $parameters['NewPassword'] , $result[0]['Salt']);
            $parameters = array();
            $parameters['Password'] = $dbPassword;
            $parameters['Id'] = $result[0]['Id'];
            $db = $db = DbAccess::getInstance();
            return $db->insertupdate('Users','Id', array('Password'), $parameters);
        }


    }
}
?>