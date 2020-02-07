<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/VersionedManager.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once 'lib/model/VOItem.php';

class ItemManager extends VersionedManager
{

    public function ItemManager($customerno)
    {
        // Constructor.
        parent::VersionedManager($customerno);
    }

    public function SaveItem($item)
    {
        if(!isset($item->itemno))
        {
            $this->Insert($item);
        }
        else
        {
            $this->Update($item);
        }
    }

    private function Insert($item)
    {
        $today = date("Y-m-d H:i:s");        
        $SQL = sprintf( "INSERT INTO items
                        (`customerno`,`itemname`,`itemdesc`,`trackingno`
                        ,`userid`,`trackeeid`,`createdon`,`recipientid`,`recipientname`,`sigreqd`,`devicekey`) VALUES
                        ( '%d','%s','%s','%s','%d','%d','%s','%d','%s','%s','%s')",
        $this->_Customerno,
        Sanitise::String($item->itemname),
        Sanitise::String($item->itemdesc),
        Sanitise::String($item->trackingno),
        Sanitise::Long($item->userid),
        Sanitise::Long($item->trackeeid),
        Sanitise::DateTime($today),                
        Sanitise::Long($item->recipientid),
        Sanitise::String($item->recipientname),                
        Sanitise::String($item->sigreqd),
        Sanitise::String($item->devicekey));
        $this->_databaseManager->executeQuery($SQL);
        $item->itemno = $this->_databaseManager->get_insertedId();
        
        $SQL = sprintf( "Update trackee
                        Set `pushitems`=1
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($item->trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                                
    }
    
    public function get_item($id) 
    {
        $item = null;
        $itemDetailsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND items.itemno='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($itemDetailsQuery);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $item = new VOItem();
            $item->itemno = $row['itemno'];
            $item->itemname = $row['itemname'];
            $item->itemdesc = $row['itemdesc'];   
            $item->isdelivered = $row["isdelivered"];
            $item->trackingno = $row['trackingno'];            
            $item->userid = $row['userid'];            
            $item->trackeeid = $row['trackeeid'];            
            $item->recipientid = $row['recipientid'];            
            $item->recipientname = $row['recipientname'];                        
            $item->sigreqd = $row['sigreqd'];            
            $item->signature = $row['signature'];            
            $item->signedby = $row['signedby'];     
            $item->tname = $row["tname"];
            $item->devicekey = $row["devicekey"];
            return $item;            
        }
        return null;
    }
    
    public function gettrackingnos()
    {
        $trackingnos = Array();
        $itemsQuery = sprintf("SELECT trackingno FROM `items` WHERE customerno=%d",
            $this->_Customerno);
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->trackingno = $row['trackingno'];            
                $trackingnos[] = $item;
            }
            return $trackingnos;            
        }
        return null;                        
    }

    public function getitems_trackingno_filtered($itemname)
    {
        $itemnamesearch = "";
        if(isset($itemname))
        {
            $itemnamesearch.= sprintf( " AND (items.itemname LIKE '%%%s%%')",$itemname);
        }
        
        $items = Array();
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d",
            $this->_Customerno, $this->_Customerno);
        $itemsQuery.=$itemnamesearch;
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $item->itemdesc = $row['itemdesc'];            
                $item->trackingno = $row['trackingno'];            
                $item->userid = $row['userid'];            
                $item->trackeeid = $row['trackeeid'];            
                $item->recipientid = $row['recipientid'];            
                $item->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $item->signvalue = "Yes";
                }
                else
                {
                    $item->signvalue = "No";                    
                }
                $item->signature = $row['signature'];            
                $item->signedby = $row['signedby'];                                        
                $item->tname = $row['tname'];          
                $item->recipientname = $row['recipientname'];     
                if($row["isdelivered"] == 1)
                {
                    $item->isdeliveredstring = "Yes";
                }
                else
                {
                    $item->isdeliveredstring = "No";                    
                }
                $items[] = $item;
            }
            return $items;            
        }
        return null;                
    }
    
    public function getfiltereditemsforcustomer($itemname = null, $trackeeid = null, $recipient = null, $sigonly = null, $isdelivered = null, $fdate = null, $tdate = null, $dfdate = null, $dtdate = null)
    {
        $today = date("Y-m-d H:i:s");        
        $itemnamesearch = "";
        if(isset($itemname))
        {
            $itemnamesearch .= sprintf( " AND (items.itemname LIKE '%%%s%%')",$itemname);
        }
        
        $recipientsearch = "";
        if(isset($recipient))
        {
            $recipientsearch .= sprintf( " AND (items.recipientname LIKE '%%%s%%')",$recipient);
        }                

        $sigsearch = "";
        if(isset($sigonly))
        {
            $sigsearch .= sprintf( " AND items.sigreqd=%d",$sigonly);
        }                                 
        
        $delsearch = "";
        if(isset($isdelivered))
        {
            $delsearch .= sprintf( " AND items.isdelivered=%d",$isdelivered);
        }                                         
        
        $trackeesearch = "";
        if(isset($trackeeid))
        {
            $trackeesearch .= sprintf( " AND items.trackeeid=%d",$trackeeid);
        }                                                 
        
        $fromdate = "";
        if(isset($fdate) && $fdate != "")
        {
        $fdate = $date->MakeMySQLDate($fdate);            
        $fdate= $fdate." 00:00:00";
        $fromdate .= sprintf( " AND items.createdon BETWEEN '%s'",$fdate);
        }
        else
        {
        $fdate= "0000-00-00 00:00:00";
        $fromdate .= sprintf( " AND items.createdon BETWEEN '%s'",$fdate);            
        }
        
        $todate = "";
        if(isset($tdate) && $tdate != "")
        {
            $tdate = $date->MakeMySQLDate($tdate);
            $tdate= $tdate." 23:59:59";            
            $todate .= sprintf( " AND '%s'",$tdate);
        }
        else
        {
            $todate .= sprintf( " AND '%s'",$today);            
        }

        if($isdelivered == 1)
        {
            $dfromdate = "";
            if(isset($dfdate) && $dfdate != "")
            {
            $dfdate = $date->MakeMySQLDate($dfdate);
            $dfdate= $dfdate." 00:00:00";
            $dfromdate .= sprintf( " AND items.deliverydate BETWEEN '%s'",$dfdate);
            }
            else
            {
            $dfdate= "0000-00-00 00:00:00";
            $dfromdate .= sprintf( " AND items.deliverydate BETWEEN '%s'",$dfdate);            
            }

            $dtodate = "";
            if(isset($dtdate) && $dtdate != "")
            {
                $dtdate = $date->MakeMySQLDate($dtdate);                                    
                $dtdate= $dtdate." 23:59:59";            
                $dtodate .= sprintf( " AND '%s'",$dtdate);
            }
            else
            {
                $dtodate .= sprintf( " AND '%s'",$today);            
            }
        }
        
        $items = Array();
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d",
            $this->_Customerno, $this->_Customerno);
        $itemsQuery.=$itemnamesearch;
        $itemsQuery.=$recipientsearch;        
        $itemsQuery.=$sigsearch;
        $itemsQuery.=$trackeesearch;  
        $itemsQuery.=$delsearch;  
        $itemsQuery.=$fromdate;
        $itemsQuery.=$todate;
        $itemsQuery.=$dfromdate;
        $itemsQuery.=$dtodate;        
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $item->itemdesc = $row['itemdesc'];            
                $item->trackingno = $row['trackingno'];            
                $item->userid = $row['userid'];            
                $item->trackeeid = $row['trackeeid'];            
                $item->recipientid = $row['recipientid'];            
                $item->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $item->signvalue = "Yes";
                }
                else
                {
                    $item->signvalue = "No";                    
                }
                $item->signature = $row['signature'];            
                $item->signedby = $row['signedby'];                                        
                $item->tname = $row['tname'];          
                $item->recipientname = $row['recipientname'];    
                $item->createdon = date("M j, g:i a", strtotime($row['createdon']));                
                if($row["isdelivered"] == 1)
                {
                    $item->isdeliveredstring = "Delivered";
                    $diff = $this->checktimetaken($row["deliverydate"], $row["createdon"]);
                    $item->timetaken = $diff;
                }
                else
                {
                    $item->isdeliveredstring = "Undelivered";                    
                    $item->timetaken = "";
                }
                if($row['deliverydate'] != "0000-00-00 00:00:00")
                    $item->deliverydate = date("M j, g:i a", strtotime($row['deliverydate']));
                else
                    $item->deliverydate = 'Pending';
                $items[] = $item;
            }
            return $items;            
        }
        return null;        
    }
    
    public function getitemsforcustomer() 
    {
        $items = Array();
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d",
            $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $item->trackingno = $row['trackingno'];            
                $item->userid = $row['userid'];
                $item->createdon = date("M j, g:i a", strtotime($row['createdon']));
                if($row['deliverydate'] != "0000-00-00 00:00:00")
                    $item->deliverydate = date("M j, g:i a", strtotime($row['deliverydate']));
                else
                    $item->deliverydate = 'Pending';
                $item->trackeeid = $row['trackeeid'];            
                $item->recipientid = $row['recipientid'];            
                $item->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $item->signvalue = "Yes";
                }
                else
                {
                    $item->signvalue = "No";                    
                }
                $item->signature = $row['signature'];            
                $item->signedby = $row['signedby'];                                        
                $item->tname = $row['tname'];          
                $item->recipientname = $row['recipientname'];     
                if($row["isdelivered"] == 1)
                {
                    $item->isdeliveredstring = "Delivered";
                    $diff = $this->checktimetaken($row["deliverydate"], $row["createdon"]);
                    $item->timetaken = $diff;
                }
                else
                {
                    $item->isdeliveredstring = "Undelivered";                    
                    $item->timetaken = "";
                }
                $items[] = $item;
            }
            return $items;            
        }
        return null;
    }        
    
    public function getitemsfromphrase($phrase) 
    {
        $items = Array();
        if(isset($phrase))
        {
            $phrasesearch .= sprintf( " AND (items.itemname LIKE '%%%s%%')",$phrase);
        }        
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d",
            $this->_Customerno, $this->_Customerno);
        $itemsQuery.=$phrasesearch;
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $items[] = $item;
            }
            return $items;            
        }
        return null;
    }        
    
    public function getundelivereditemsforcustomer() 
    {
        $items = Array();
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d AND items.isdelivered=0",
            $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $item->trackingno = $row['trackingno'];            
                $item->userid = $row['userid'];          
                $item->createdon = date("M j, g:i a", strtotime($row['createdon']));
                $item->trackeeid = $row['trackeeid'];            
                $item->recipientid = $row['recipientid'];            
                $item->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $item->signvalue = "Yes";
                }
                else
                {
                    $item->signvalue = "No";                    
                }
                $item->signature = $row['signature'];            
                $item->signedby = $row['signedby'];                                        
                $item->tname = $row['tname'];          
                $item->recipientname = $row['recipientname'];     
                $items[] = $item;
            }
            return $items;            
        }
        return null;
    }            
    
    public function get_items_for_trackee($trackeeid) 
    {
        $items = Array();
        $itemsQuery = sprintf("SELECT * FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d && items.trackeeid=%d",
            $this->_Customerno, $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            while ($row = $this->_databaseManager->get_nextRow())            
            {
                $item = new VOItem();
                $item->itemno = $row['itemno'];
                $item->itemname = $row['itemname'];
                $item->itemdesc = $row['itemdesc'];            
                $item->trackingno = $row['trackingno'];            
                $item->userid = $row['userid'];            
                $item->trackeeid = $row['trackeeid'];            
                $item->recipientid = $row['recipientid'];            
                $item->sigreqd = $row['sigreqd'];
                if($row["sigreqd"] == 1)
                {
                    $item->signvalue = "Yes";
                }
                else
                {
                    $item->signvalue = "No";                    
                }
                $item->createdon = date("M j, g:i a", strtotime($row['createdon']));
                if($row['deliverydate'] != "0000-00-00 00:00:00")
                    $item->deliverydate = date("M j, g:i a", strtotime($row['deliverydate']));
                else
                    $item->deliverydate = 'Pending';                
                $item->signature = $row['signature'];            
                $item->signedby = $row['signedby'];                                        
                $item->tname = $row['tname'];          
                $item->recipientname = $row['recipientname'];     
                if($row["isdelivered"] == 1)
                {
                    $item->isdeliveredstring = "Delivered";
                }
                else
                {
                    $item->isdeliveredstring = "Undelivered";                    
                }
                $items[] = $item;
            }
            return $items;            
        }
        return null;
    }            

    private function Update($item)
    {
        $SQL = sprintf( "Update items
                        Set `itemname`='%s',
                        `itemdesc`='%s',
                        `trackingno`='%s',
                        `trackeeid`='%d',
                        `recipientid`='%d',
                        `recipientname`='%s',                        
                        `sigreqd`='%s',
                        `signature`='%s',
                        `devicekey`='%s',                        
                        `signedby`='%s'          
                        WHERE itemno = %d AND customerno = %d",
                        Sanitise::String( $item->itemname),
                        Sanitise::String( $item->itemdesc),
                        Sanitise::String( $item->trackingno),
                        Sanitise::Long( $item->trackeeid),
                        Sanitise::Long( $item->recipientid),
                        Sanitise::String( $item->recipientname),                
                        Sanitise::String( $item->sigreqd),                
                        Sanitise::String( $item->signature), 
                        Sanitise::String( $item->devicekey),                 
                        Sanitise::String( $item->signedby),                                
                        Sanitise::Long( $item->itemno),
                        $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        
        $SQL = sprintf( "Update trackee
                        Set `pushitems`=1
                        WHERE customerno = %d AND trackeeid = %d",
                        $this->_Customerno,                         
                        Sanitise::Long($item->trackeeid));
        $this->_databaseManager->executeQuery($SQL);                                                        
    }

    public function DeleteItem($itemno)
    {
        $SQL = sprintf("DELETE FROM items where itemno = %d and customerno = %d",
                        Sanitise::Long($itemno), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }
    
    public function get_item_for_pdf($id) 
    {
        $item = null;
        $SQL = sprintf("SELECT * FROM `items` INNER JOIN user ON user.userid = items.userid INNER JOIN trackee ON trackee.trackeeid = items.trackeeid where items.customerno=%d AND items.itemno='%d' LIMIT 1",
                $this->_Customerno,
            Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($SQL);		
		
        if ($row = $this->_databaseManager->get_nextRow()) 
        {
            $item = new VOItem();
            $item->itemno = $row['itemno'];
            $item->itemname = $row['itemname'];
            $item->itemdesc = $row['itemdesc'];            
            $item->trackingno = $row['trackingno'];            
            $item->userid = $row['userid'];            
            $item->trackeeid = $row['trackeeid'];            
            $item->recipientid = $row['recipientid'];            
            $item->recipientname = $row['recipientname'];                        
            $item->sigreqd = $row['sigreqd'];            
            $item->signature = $row['signature'];            
            $item->signedby = $row['signedby']; 
            $item->tname = $row['tname']; 
            $item->deliverydate = $row['deliverydate']; 
            $item->isdelivered = $row['isdelivered']; 
            $item->logo = ImagesFolder . "default/elixialogowhite.png";
            $item->realname = $row["realname"];
            if($row['signature']!=NULL)
                $item->sigimage = CustomerSQLite.$row['devicekey']."/signature/".$item->signature;
            else
                $item->sigimage = "images/NoSign.png";
            return $item;            
        }
        return null;
    }
    
    public function get_undelivered_items_for_trackee($trackeeid) 
    {
        $items = Array();
        $itemsQuery = sprintf("SELECT itemNo FROM `items` INNER JOIN trackee ON trackee.trackeeid = items.trackeeid WHERE items.customerno=%d AND trackee.customerno=%d && items.trackeeid=%d AND items.isdelivered = 0",
            $this->_Customerno, $this->_Customerno, Sanitise::Long($trackeeid));
        $this->_databaseManager->executeQuery($itemsQuery);		
		
        if ($this->_databaseManager->get_rowCount() > 0) 
        {
            return TRUE;
        }
        return null;
    }
    
    private function checktimetaken($enddate, $startdate)
    {
        $enddate = strtotime($enddate);
        $startdate = strtotime($startdate);
        $diff = abs($enddate - $startdate); 

        $years   = floor($diff / (365*60*60*24)); 
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));            
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
        $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
        if($months > 0)
        {
            return $months." mon ".$days." days";            
        }
        if($days > 0)
        {
            return $days." days ".$hours." hr";
        }
        else
        {
            if($hours > 0)
            {
                return $hours." hr ".$minutes." min ".$seconds." sec";
            }
            elseif($minutes > 0)
            {
                return $minutes." min ".$seconds." sec";                
            }
            else
            {
                return $seconds." sec";                                
            }
        }        
    }
}