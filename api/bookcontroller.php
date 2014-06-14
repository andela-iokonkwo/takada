<?php
 Class BooksssController
{
	public function GetAction($request) {
		$db = $db = DbAccess::getInstance();
    
    try
 {
     if(isset($request->url_elements[2]))
       { 
	      if($request->url_elements[2] == "search" && isset($request->url_elements[3]))
    	{   
		    if($request->url_elements[3] == "genres" && isset($request->url_elements[4])) {        
           $query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice from books b join booksingenre g on(b.Id = g.BookId) join genre t on (g.GenreId = t.Id) WHERE t.Name = :genres LIMIT 0 30', array(':genres' => $request->url_elements[4]));///books/search/genres/:genres
		        }
		   elseif($request->url_elements[3] == "tags" && isset($request->url_elements[4])) {        
           $query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice from books b join tagsinbook g on(b.Id = g.BookId) join tags t on (g.TagId = t.Id) WHERE t.Name = :tag LIMIT 0 30', array(':tag' => $request->url_elements[4])); ///books/search/tags/:tag
		        }
		   elseif(!isset($request->url_elements[4])) {        
           $query = $db->query('select Title, AuthorName, ISBN, PublishedDate, CoverArt, TotalPrice from books WHERE Title = :name LIMIT 0 30', array(':name' => $request->url_elements[3])); ///books/search/:name
		       }
    	   }
		   else if($request->url_elements[2] == "user" && isset($request->url_elements[3]) && is_numeric($request->url_elements[3]))
    	{
		    	$query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice, k.Review, k.Endorse,  from books b join usersbook k on (b.Id = k.BookId) join users u on (k.UserId = u.Id) WHERE k.UsedId = :Id LIMIT 0 30', array('Id' => $request->url_elements[3]));  ///books/user/id
			  
    	}
     	else if($request->url_elements[2] == "genre")
    	      {
    		$query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice, t.Name, t.Description from books b join booksingenre g on(b.Id = g.BookId) join genre t on (g.GenreId = t.Id) WHERE t.Name = genre LIMIT 0 30', array('genre' => $request->url_elements[2]));           ///books/genres
    	      }
       else if($request->url_elements[2] == "tags")
    	      {
    		$query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice, t.Name, t.Description from books b join tagsinbook g on(b.Id = g.BookId) join tags t on (g.TagId = t.Id) WHERE t.Name = tags LIMIT 0 30', array('tags' => $request->url_elements[2]));  ///books/tags
    	      }
		   else if(is_numeric($request->url_elements[2]))
    	{
		    if(isset($request->url_elements[3])) {
			    if($request->url_elements[3] == "users") {
    		$query = $db->query('select b.Title, b.AuthorName, b.ISBN, b.PublishedDate, b.CoverArt, b.TotalPrice, k.Review, k.Endorse, u.Name, u.Email,   from books b join usersbook k on (b.Id = k.BookId) join users u on (k.UserId = u.Id) WHERE k.BookId = :Id LIMIT 0 30', array('Id' => $request->url_elements[2]));  ///books/id/users
			    }
		       elseif($request->url_elements[3] == "groups"){
    		$query = $db->query('select * from products where StateId = :Id', array('Id' => $request->url_elements[2]));  ///books/id/groups
			   }
			   else {
			  $query = $db->query('Select * FROM books WHERE Id = :Id LIMIT 0 30', array('Id' => $request->url_elements[2])); ///books/id  
			        }
    	      }
           }
	    }
		else if(!isset($request->url_elements[2]))
    {
        $query = $db->query('select * from books'); //books   	
    }
        if(!isset($query))
    {
        $status_report['code'] = 1;
        $status_report['message'] = 'Wrong api url';
        return $status_report;
    }
   if($query->rowCount() > 0 )
        return $query->fetchAll();
    else
    {
        $status_report['code'] = 0;
        $status_report['message'] = 'No result found';
        return $status_report;
    }
    
} //end of try
    catch (PDOException $e)
   {
         $status_report['code'] = 3;
        $status_report['message'] = 'There was some problems with this request';
        return $status_report;
   }

} //end of get function 
	

public function PostAction($request)
{
  $db = $db = DbAccess::getInstance();
    return $db->insertupdate('books','Id', array('Title', 'Description', 'Summary', 'AuthorName', 'ISBN', 'IsFeature', 'AddedDate', 'PublishedDate', 'CoverArt', 'TotalPrice'), $request->parameters);
  }

public function DeleteAction($request)
{
   $db = $db = DbAccess::getInstance();    
        $query = $db->query('delete from books where Id = :Id', array('Id'=> $request->url_elements[2]));
        return $query->rowCount() > 0 ;   
}

public function PutAction($request)
   {
   $db = $db = DbAccess::getInstance();
     return $db->insertupdate('books','Id', array('Title', 'Description', 'Summary', 'AuthorName', 'ISBN', 'IsFeature', 'AddedDate', 'PublishedDate', 'CoverArt', 'TotalPrice'), $request->parameters);
   }
?>