<?php
include ('dbaccess.php');
public Class GenresController
{
	public function GETAction($request) {
            $db = $db = DbAccess::getInstance();
        try{
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
    {

          $query = $db->query('select * from Genre where Id = :Id',array('Id' => $request->url_elements[2]));
    } 
   else if(!isset($request->url_elements[2]))
    {
        $query = $db->query('select * from Genre');    	
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
         catch (PDOException $e)
   {
        header('HTTP/1.0 500 Url Server Error');
        return;
   }
}
	
public function DeleteAction($request)
{
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2]))
    {
        $db = $db = DbAccess::getInstance();
        $query = $db->query('delete from Genre where Id = :Id', array('Id'=> $request->url_elements[2]));
        return $query->rowCount() > 0 ; 
    }
}
public function PostAction($request)
{
     $db = $db = DbAccess::getInstance();
  return $db->insertupdate('Genre','Id', array('Name','Descripition'), $request->parameters);
}
    public function PutAction($request)
{
     $db = $db = DbAccess::getInstance();
    return $db->insertupdate('Genre','Id', array('Name','Description'), $request->parameters);
}

}


?>