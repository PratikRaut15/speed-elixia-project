<?php
include '../../lib/system/utilities.php';
include '../../lib/bo/ArticleManager.php';
include '../../lib/bo/VehicleManager.php';
include '../../lib/bo/UnitManager.php';
if(!isset($_SESSION))
{
    session_start();
}
function addart($form)
{
    $artname = GetSafeValueString($form['artname'], 'string');
    $maxtemp = GetSafeValueString($form['maxtemp'], 'float');
    $mintemp = GetSafeValueString($form['mintemp'], 'float');
    $artman = new ArticleManager($_SESSION['customerno']);
    $artman->addarticle($artname,$maxtemp,$mintemp, $_SESSION['userid']);
}
function editart($form)
{
    $artid = GetSafeValueString($form['artid'], 'long');
    $artname = GetSafeValueString($form['artname'], 'string');
    $maxtemp = GetSafeValueString($form['maxtemp'], 'float');
    $mintemp = GetSafeValueString($form['mintemp'], 'float');
    $artman = new ArticleManager($_SESSION['customerno']);
    $artman->modifyarticle($artid,$artname,$maxtemp,$mintemp, $_SESSION['userid']);
}
function checkartname($artname,$artid=NULL)
{
    $artname = GetSafeValueString($artname, 'string');
    $articles = getallart();
    $status = NULL;
    if(isset($articles))
    {
        foreach($articles as $thisarticle)
        {
            if($thisarticle->artname == $artname && $thisarticle->artid=$artid)
            {
                $status = 'ok';
                break;
            }
            else if($thisarticle->artname == $artname)
            {
                $status = "notok";
                break;
            }
        }
        if(!isset($status))
        {
            $status = "ok";
        }
    }   
    else
    {
        $status = "ok";
    }
    echo $status;
}
function getallart()
{
    $artman = new ArticleManager($_SESSION['customerno']);
    $articles = $artman->get_all_articles();
    return $articles;
}
function delart($id)
{
    $artid = GetSafeValueString($id, 'int');
    $artman = new ArticleManager($_SESSION['customerno']);
    $artman->delarticle($artid, $_SESSION['userid']);
}
function getarticle($id)
{
    $artman = new ArticleManager($_SESSION['customerno']);
    $article = $artman->get_article($id);
    return $article;
}
function getvehicles()
{
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $vehiclemanager->get_all_vehicles_with_tempsensor();
    return $vehicles;
}
function mapart($form)
{
    $artman = new ArticleManager($_SESSION['customerno']);
    foreach($form as $vehicleid=>$artid)
    {
        $artman->maparttype($vehicleid,$artid, $_SESSION['userid']);
    }
}
function getallartwithvehicles()
{
    $artman = new ArticleManager($_SESSION['customerno']);
    $articles = $artman->get_all_articles_with_vehicles();
    return $articles;
}
?>
