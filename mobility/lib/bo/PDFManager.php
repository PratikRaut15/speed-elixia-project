<?php
include_once 'lib/system/Validator.php';
include_once 'lib/system/Sanitise.php';
include_once 'lib/system/Date.php';
include_once("constants/constants.php");
include_once("lib/system/DatabaseManager.php");

class Invoice{
// Empty Class    
}
class PDFManager
{

    public function PDFManager()
    {
    }

    public function get_invoice_for_pdf($invoiceid) 
    {
        $entries = array();
        $db = new DatabaseManager();
        $invoice = null;
        $SQL = sprintf("SELECT * FROM `devinvoicedetails` d
            INNER JOIN purchase p ON p.imei = d.value
            WHERE d.devinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
            $invoice = new Invoice();            
            $invoice->value = $row["value"];
            $invoice->description = $row["modelname"]; 
            $invoice->validupto = $row["validupto"];
            $invoice->rate = $row["rate"];
            $entries[] = $invoice;
        }
        return $entries;        
    }
    
    public function get_licinvoice_for_pdf($invoiceid) 
    {
        $entries = array();
        $db = new DatabaseManager();
        $invoice = null;
        $SQL = sprintf("SELECT * FROM `licinvoicedet` l
        INNER JOIN devices d ON d.devicekey = l.value     
        WHERE l.licinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
                $invoice = new Invoice();            
                $invoice->value = $row["value"];
                $invoice->description = "Device Key";
                $invoice->rate = $row["rate"];
                $entries[] = $invoice;
        }
        return $entries;        
    }    
    
    public function get_invoice_root($invoiceid) 
    {
        $db = new DatabaseManager();
        $invoice = null;
        $SQL = sprintf("SELECT * FROM `devinvoice` i 
            INNER JOIN customer c ON i.customerno=c.customerno 
            INNER JOIN team t ON t.teamid = i.teamid             
            WHERE i.devinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
            $invoice = new Invoice();        
            $invoice->totalamount = $row["totalamount"];  
            $invoice->inwords = $row["inwords"];            
            $invoice->devrate = $row["devrate"];            
            $invoice->customerno = $row["customerno"];
            $invoice->customername = $row["customername"]; 
            $invoice->devamount = $row["devamount"];  
            $invoice->teamname = $row["name"];             
            return $invoice;
        }
        return null;        
    } 
    
    public function get_licinvoice_root($invoiceid) 
    {
        $db = new DatabaseManager();
        $invoice = null;
        $SQL = sprintf("SELECT * FROM `licinvoice` i 
            INNER JOIN customer c ON i.customerno=c.customerno 
            INNER JOIN team t ON t.teamid = i.teamid 
            WHERE i.licinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
            $invoice = new Invoice();            
            $invoice->teamname = $row["name"];            
            $invoice->customerno = $row["customerno"];
            $invoice->customername = $row["customername"];             
            $invoice->softamount = $row["softamount"]; 
            $invoice->servicetax = $row["servicetax"];  
            $invoice->totalamount = $row["totalamount"];
            $invoice->startdate = $row["startdate"];
            $invoice->inwords = $row["inwords"];            
            $invoice->discount = $row["discount"];            
            $invoice->pendingamount = $row["pendingamount"];            
            $invoice->subtotal = $row["subtotal"];                        
            return $invoice;
        }
        return null;        
    }        
    
    public function imei_count($invoiceid) 
    {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT count(*) as count FROM `devinvoicedetails` id WHERE id.devinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
            return $row["count"];
        }
        return null;        
    }        
    
    public function lic_count($invoiceid) 
    {
        $db = new DatabaseManager();
        $SQL = sprintf("SELECT count(*) as count FROM `licinvoicedet` id WHERE id.licinvoiceid = %d", $invoiceid);
        $db->executeQuery($SQL);		
		
        while($row = $db->get_nextRow()) 
        {
            return $row["count"];
        }
        return null;
    }            
}