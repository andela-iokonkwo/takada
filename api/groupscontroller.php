<?php
include ('dbaccess.php');
Class GroupsController
{  

	public function GetAction($request) {
        $db = $db = DbAccess::getInstance();
        try
        {   
           
      if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
         {
    	  if(isset($request->url_elements[3]) && $request->url_elements[3] == 'discussions') {
 
	             if(isset($request->url_elements[4]) && is_numeric($request->url_elements[4])) {
	$query = $db->query('select g.Name, g.Description, g.CreatedDate, d.Title, d.Description, d.Likes, d.CreatedDate FROM groups g join discussion d on (g.Id = d.GroupId) WHERE d.Id = Id ', array('Id' => $request->url_elements[4]));
			         }  /// /groups/:id/discussion/:id
			else {
		     $query = $db->query('select g.Name, g.Description, g.CreatedDate, d.Title, d.Description, d.Likes, d.CreatedDate FROM groups g join discussion d on (g.Id = d.GroupId) WHERE g.Id = Id ', array('Id' => $request->url_elements[2])); 
		            }  /// /groups/:id/discussion 
            		
			   }
	     elseif(isset($request->url_elements[3]) && $request->url_elements[3] == 'users') {
		      $query = $db->query('select g.Name, g.Description, g.CreatedDate, u.Name, u.Email FROM groups g join usersingroup p on (g.Id = p.GroupId) join users u on (p.UserId = u.Id) WHERE g.Id = Id', array('Id' => $request->url_elements[2]));
		     }///  /groups/:id/users
	     else {
		      $query = $db->query('select * FROM groups WHERE Id = Id', array('Id' => $request->url_elements[2]));
		    }  ///  /groups/:id
		}
     else if(isset($request->url_elements[2]) && $request->url_elements[2] == "search")
    	{
    		if(isset($request->url_elements[3]) && $request->url_elements[3] == ":name")
    	    {           
           $query = $db->query('select g.Name, g.Description, g.CreatedDate, c.Name, from groups g join groupcategory on c (g.CategoryId = c.Id) where g.Name = :name', array(':name' => $request->url_elements[3])); /// /groups/search/:name
    	    }
		  elseif(isset($request->url_elements[3]) && $request->url_elements[3] == ":categories") {
		    $query = $db->query('select g.Name, g.Description, g.CreatedDate, c.Name, from groups g join groupcategory c on (g.CategoryId = c.Id) where c.Name = :categories', array(':categories' => $request->url_elements[3]));
		  }/// /groups/search/:categories
    	}
     	      
    else if(!isset($request->url_elements[2]))
    {
        $query = $db->query('select * from groups');    	
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
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2])) {
	    if (isset($request->url_elements[3]) && $request->url_elements[3] == 'discussion') {
        return $db->insertupdate('discussion','Id', array('Title', 'CreatorId', 'GroupId', 'Description', 'Likes', 'CreatedDate'), $request->parameters);
		  }  ///  /gropus/:id/dicussion
		}
    else {
  return $db->insertupdate('groups','Id', array('CreatorId', 'CategoryId','Name', 'Description', 'CreatedDate'), $request->parameters);
  }  ////   /groups
}

public function DeleteAction($request)
{
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
    {
        $db = $db = DbAccess::getInstance();
        $query = $db->query('delete from groups where Id = :Id', array('Id'=> $request->url_elements[2]));
        $query = $db->query('delete from discussion where GroupId = :Id', array('Id'=> $request->url_elements[2]));
        return $query->rowCount() > 0 ; 
    }
}

public function PutAction($request)
   {
    $db = $db = DbAccess::getInstance();
    return $db->insertupdate('groups','Id', array('CreatorId', 'CategoryId','Name', 'Description', 'CreatedDate'), $request->parameters);
   }
}


?>