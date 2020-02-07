<?php
include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOArticle.php';

class ArticleManager extends VersionedManager
{

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
    
    //Adding Article
    public function addarticle($name,$max,$min, $userid)
    {
        $Query = "INSERT into article (artname,maxtemp,mintemp,customerno,userid,isdeleted) VALUES ('%s','%s','%s',%d,%d,0)";
        $ArtQuery = sprintf($Query,Sanitise::String($name),Sanitise::Float($max),
                Sanitise::Float($min), $this->_Customerno, Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($ArtQuery);
    }
    
    //Update Article
    public function modifyarticle($id,$name,$max,$min, $userid)
    {
        $Query = "UPDATE article SET maxtemp=%f, mintemp=%f, artname='%s', userid=%d where customerno = %d and artid = %d ";
        $ArtQuery = sprintf($Query,Sanitise::Float($max),Sanitise::Float($min),Sanitise::String($name),
                Sanitise::Long($userid), $this->_Customerno, Sanitise::Long($id));
        $this->_databaseManager->executeQuery($ArtQuery);
    }
    
    //Getting All Articles
    public function get_all_articles()
    {
        $Query = 'SELECT * from article where customerno= %d AND isdeleted=0';
        $ArtQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($ArtQuery);
        $articles = array();
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $article = new VOArticle();
                $article->artid = $row['artid'];
                $article->artname = $row['artname'];
                $article->maxtemp = $row['maxtemp'];
                $article->mintemp = $row['mintemp'];
                $articles[] = $article;
            }
            return $articles;
        }
        return NULL;
    }
    
    //Getting All Articles With Vehicles Associated
    public function get_all_articles_with_vehicles()
    {
        $Query = 'SELECT article.artid,article.artname,article.maxtemp,
            article.mintemp, vehicle.vehicleid from article 
            LEFT OUTER JOIN articlemanage ON articlemanage.artid = article.artid
            LEFT OUTER JOIN vehicle ON vehicle.vehicleid = articlemanage.vehicleid
            where article.customerno= %d AND article.isdeleted=0 AND vehicle.isdeleted=0 AND articlemanage.isdeleted=0';
        $ArtQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($ArtQuery);
        $articles = array();
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $article = new VOArticle();
                $article->artid = $row['artid'];
                $article->artname = $row['artname'];
                $article->maxtemp = $row['maxtemp'];
                $article->mintemp = $row['mintemp'];
                if($row['vehicleid']!=NULL)
                {
                    $article->vehicleid = $row['vehicleid'];
                }
                else
                    $article->vehicleid = 0;
                $articles[] = $article;
            }
            return $articles;
        }
        return NULL;
    }
    
    //Delete Article
    public function delarticle($id, $userid)
    {
        $Query = 'UPDATE article SET isdeleted=1, userid=%d WHERE artid=%d and customerno=%d';
        $ArtQuery = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($id),  $this->_Customerno);
        $this->_databaseManager->executeQuery($ArtQuery);
        
        $Query = 'UPDATE articlemanage SET isdeleted=1, userid=%d WHERE artid=%d and customerno=%d';
        $ArtQuery = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($id),  $this->_Customerno);
        $this->_databaseManager->executeQuery($ArtQuery);        
    }
    
    public function get_article($id)
    {
        $Query = 'SELECT * from article where customerno=%d and artid=%d';
        $ArtQuery = sprintf($Query,  $this->_Customerno, Sanitise::Long($id));
        $this->_databaseManager->executeQuery($ArtQuery);
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            $row = $this->_databaseManager->get_nextRow();
            
            $Article = new VOArticle();
            $Article->artid = $row['artid'];
            $Article->artname = $row['artname'];
            $Article->maxtemp = $row['maxtemp'];
            $Article->mintemp = $row['mintemp'];
            return $Article;
        }
        return NULL;
    }
    
    //Map Vehicle
    public function maparttype($vehicleid,$artid, $userid)
    {
        $Query = 'UPDATE articlemanage SET isdeleted=1, userid=%d WHERE vehicleid=%d';
        $ArtQuery = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($ArtQuery);
        
        if($artid!=0)
        {
            $Query = 'INSERT into articlemanage (artid,vehicleid,customerno,userid) values (%d,%d,%d,%d)';
            $ArtQuery = sprintf($Query, Sanitise::Long($artid), Sanitise::Long($vehicleid),
                    $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($ArtQuery);
        }
    }
}
?>