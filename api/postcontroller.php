<?php
include ('dbaccess.php');
Class PostController
{  

	public function GetAction($request) {
        $db = $db = DbAccess::getInstance();
    try
        {   
           
      if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
          {   if(isset($request->url_elements[3]) && $request->url_elements[2] == 'comment') {
		        $query = $db->query('select p.Post, p.Likes, p.PostedDate, c.comments, c.AddedDate, c.Likes, u.Name FROM posts p join commentsinpost t on (p.Id = t.PostId) join comments c on (t.commentId = c.Id) join users u on (c.UserId = u.Id) WHERE p.Id = :Id', array('Id' => $request->url_elements[2])); /// /post/:id/comment
		         }
			  else {
    	    $query = $db->query('select Likes, Post, PostedDate from posts where UserId = :Id', array('Id' => $request->url_elements[2])); /// /post/:id
			    }
    	  }        
    else if(isset($request->url_elements[2]) && $request->url_elements[2] == "search" && $request->url_elements[3])
    	{
		   if($request->url_elements[3] == ':name') {
    		$query = $db->query('select  from products where StateId = :Id', array('Id' => $request->url_elements[2])); // /post/search/:name
			}
	elseif($request->url_elements[3] == 'genre') {
    		$query = $db->query('select * from products where StateId = :Id', array('Id' => $request->url_elements[2])); // /post/search/genre/:genre
			}
    	}
     	
    else if(!isset($request->url_elements[2]))
    {
        $query = $db->query('select * from posts');  // post  	
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
        return ;
    }
    
  } 
   catch (PDOException $e)
   {
        header('HTTP/1.0 500 Url Server Error');
        return;
   }  
}
	

public function PostAction($request)
  {
    $db = $db = DbAccess::getInstance();
    return $db->insertupdate('posts','Id', array('UserId', 'Post', 'PostedDate'), $request->parameters);
  }

public function DeleteAction($request)
{
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
    {
        $db = $db = DbAccess::getInstance();
        $query = $db->query('delete from posts where Id = :Id', array('Id'=> $request->url_elements[2]));
		$query = $db->query('delete from commentsinpost where PostId = :Id', array('Id'=> $request->url_elements[2]));
        return $query->rowCount() > 0 ; 
    }
}

public function PutAction($request)
      {
    $db = $db = DbAccess::getInstance();
    return $db->insertupdate('posts','Id', array('Likes'), $request->parameters);
     }
 }
  
  
?>