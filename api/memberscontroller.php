<?php
session_start();
Class MembersController
{
    public function GetAction($request) {
        if($this->Authenticate()){
            $db = DbAccess::getInstance();
            try{
                if(isset($request->url_elements[2]) && $request->url_elements[2]=='search' && isset($request->url_elements[2])){
                    //members/search/:name?skip?take?
                    $query = $db->query("select Id, Name from Users u where u.Name Like ':name' limit :skip :take",
                        array('name'=> $request->url_elements[3], 'skip'=> $request->url_parameters['skip'], 'take'=> $request->url_parameters['take']));
                    return $query->fetchAll();
                }
                else if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2])){
                    //members/:id
                    $result = array();
                    $query = $db->query('select * from Users where Id= :Id',array('Id'=> $request->url_elements[2]));
                    $r = $query->fetchAll()[0];
                    unset($r[0]['Salt']);
                    $result['profile'] = $r;

                    $query = $db->query('select (count(*) * 2) as Count from CommentsInPost cp Join Posts p on (p.Id = cp.PostId) where p.UserId = :Id',array('Id'=> $request->url_elements[2]));
                    $result['posts_comments'] = $query->fetchAll()[0];

                    $query = $db->query('select (count(*) * 3) as Count, sum(c.Likes) as Likes from Comments c  where c.UserId = :Id',array('Id'=> $request->url_elements[2]));
                    $result['users_comments'] = $query->fetchAll()[0];

                    $query = $db->query('select (count(*) * 5) as Count, sum(Likes) as Likes from Posts p  where UserId = :Id',array('Id'=> $request->url_elements[2]));
                    $result['users_posts'] = $query->fetchAll()[0];

                    $query = $db->query('select (count(*) * 2) as book_c, sum(Endorse) as book_e from UsersBook ub where ub.UserId = :Id',array('Id'=> $request->url_elements[2]));
                    $result['users_books'] = $query->fetchAll()[0];

                    return $result;
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'all-activity'){
                    $query = $db->query('select * from Activity where UserId IN (select UserId from Follow where FollowerId = :id)',array('id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'my-activity'){
                    $query = $db->query('select * from Activity where UserId= :Id',array('Id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'group-activity'){
                    $query = $db->query('select * from Activity where UserId= :Id',array('Id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'groups'){
                    $query = $db->query('select g.Id, g.Name, g.Description (select group_concat(t.Name) From Tags t where t.Id IN
                    (select TagId From TagsInGroup tg where tg.GroupId = g.Id ) as Tags from Groups g join UsersInGroup ug  on (g.id = ug.GroupId) where ug.UserId = :id)',array('id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'books'){
                    $query = $db->query('select b.Title, b.CoverArt from Books b  where b.Id IN (select ub.BookId from UsersBook ub where ub.UserId = :id)',array('id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'writing'){
                    $query = $db->query('select * from Posts where UserId= :Id',array('Id'=> $_SESSION['User_Id']));
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2] == 'me' && $request->url_elements[3] == 'profile'){
                    $query = $db->query('select * from Users where Id= :Id',array('Id'=> $_SESSION['User_Id']));
                    $result = $query->fetchAll();
                    unset($result[0]['Salt']);
                    return $result[0];
                }

                elseif(isset($request->url_elements[2]) && $request->url_elements[2]=='newest'){
                    //members/mostactive
                   // $query = $db->query('select u.Id,  ((select count(*) from Posts where UserId = u.Id ) * 5,  ) from Users where Id= :Id',array('Id'=> $_SESSION['User_Id']));
                    $query = $db->query('select Id,Name from Users order by AddedDate desc limit 10 ');
                }
                elseif(isset($request->url_elements[2]) && $request->url_elements[2]== 'featured'){
                    //members/featured
                    $query = $db->query('select Id,Name from Users where Featured = true ');
                }
                elseif(!isset($request->url_elements[2])){
                    $query = $db->query('select Id,Name from Users');
                }
                if(!isset($query))
                {
                    header('HTTP/1.0 404 Url Not Found');
                    return;
                }
                if($query->rowCount() > 0 )
                    return $query->fetchAll();
                else
                {
                    header('HTTP/1.0 204 No Result Found');
                    return;
                }

            }
            catch(PDOException $e){
                header('HTTP/1.0 500 Url Server Error');
                return;
            }
        }
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
            return false;
        $result = $query->fetchAll();
        //$password = hash('SHA256', $parameters['Password']);
        $hashedPassword = hash_hmac('SHA256', $parameters['Password'], $result[0]['Salt']);
        if($hashedPassword !== $result[0]['Password'])
            return;
        $_SESSION['loggedIn'] = true;
        $_SESSION['User_Id'] = $result[0]['Id'];
        return true;
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
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true;
    }

    function ChangePassword($parameters)
    {
        if($this->Authenticate())
        {
        $db = DbAccess::getInstance();
        //$Id = Authenticate();
        $query = $db->query('select Id, Salt from Users where Email= :email', array('email'=> $parameters['Email']));
        if(!$query->rowCount() > 0 )
            return;
        $result = $query->fetchAll();
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