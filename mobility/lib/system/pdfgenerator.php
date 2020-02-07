<?php
function hexrgb($hexstr) {
    $int = hexdec($hexstr);

    return array("red" => 0xFF & ($int >> 0x10), "green" => 0xFF & ($int >> 0x8), "blue" => 0xFF & $int);
}

require_once("system/qrcode.class.php");

class ZenventoryPDF extends PDF_Code39
{
    private $caller;

    public function setCaller($caller)
    {
        $this->caller = $caller;
    }

    function Header()
    {
        $this->caller->headercb();
    }

    //Page footer
    function Footer()
    {
        $this->caller->footercb();
    }

    function Dispose()
    {
        $this->caller = null;
    }

    var $angle=0;

    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }

    function DashedHLine($x1, $y1, $length, $width=1, $nb=15)
    {
        $this->SetLineWidth($width);
        $x2 = $x1+$length;
        $y2=$y1;
        $longueur=abs($x1-$x2);
        $hauteur=abs($y1-$y2);
        if($longueur>$hauteur) {
            $Pointilles=($longueur/$nb)/2; // length of dashes
        }
        else {
            $Pointilles=($hauteur/$nb)/2;
        }
        for($i=$x1;$i<=$x2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($x2-1)) {
                    $this->Line($j,$y1,$j+1,$y1); // upper dashes
                }
            }
        }

    }

    function DashedVLine($x1, $y1, $length, $width=1, $nb=15)
    {
        $x2 = $x1;
        $y2=$y1+$length;
        $this->SetLineWidth($width);
        $longueur=abs($x1-$x2);
        $hauteur=abs($y1-$y2);
        if($longueur>$hauteur) {
            $Pointilles=($longueur/$nb)/2; // length of dashes
        }
        else {
            $Pointilles=($hauteur/$nb)/2;
        }

        for($i=$y1;$i<=$y2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($y2-1)) {
                    $this->Line($x1,$j,$x1,$j+1); // left dashes
                }
            }
        }
    }

    function DashedRect($x1, $y1, $x2, $y2, $width=1, $nb=15)
    {
        $this->SetLineWidth($width);
        $longueur=abs($x1-$x2);
        $hauteur=abs($y1-$y2);
        if($longueur>$hauteur) {
            $Pointilles=($longueur/$nb)/2; // length of dashes
        }
        else {
            $Pointilles=($hauteur/$nb)/2;
        }
        for($i=$x1;$i<=$x2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($x2-1)) {
                    $this->Line($j,$y1,$j+1,$y1); // upper dashes
                    $this->Line($j,$y2,$j+1,$y2); // lower dashes
                }
            }
        }
        for($i=$y1;$i<=$y2;$i+=$Pointilles+$Pointilles) {
            for($j=$i;$j<=($i+$Pointilles);$j++) {
                if($j<=($y2-1)) {
                    $this->Line($x1,$j,$x1,$j+1); // left dashes
                    $this->Line($x2,$j,$x2,$j+1); // right dashes
                }
            }
        }
    }


    function _endpage()
    {
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }


}

class pdfgenerator {
    //put your code here
    private $_template;
    private $pdf;
    private $data;
    private $rowdata;
    private $groupdata;
    private $groupby;
    private $callbackfunction;
    private $currentrowdata;

    private $rowY=0;
    private $rowHeight=0;
    private $headerHeight=0;
    private $footerHeight=0;
    private $groupHeaderHeight=0;
    private $bodyHeight=0;
    private $pageCount=0;
    private $rowCount=0;
    private $groupCount=0;
    private $sectionY = 0;

    private $headerTop=0;
    private $footerTop=0;
    private $groupHeaderTop=0;

    private $header;
    private $headerelements;
    private $footer;
    private $summary;
    private $summaryelements;
    private $body;
    private $bodyelements;
    private $summaryvariables;
    private $groupheader;
    private $groupheaderelements;
    private $pagevariables;


    private $newpage=false;
    private $inbody=false;
    private $inheader=false;
    private $infooter=false;
    private $newpagepergroup=false;
    private $activeSection;



    public function pdfgenerator( $templatefilename,$DataObject, $DataFunction = NULL, $GroupBy="" )
    {
        $this->_template =  simplexml_load_file($templatefilename,"SimpleXMLElement",65536 /*LIBXML_COMPACT*/);
        //var_dump($this->_template);
        $this->data = $DataObject;
        $this->callbackfunction = $DataFunction;

        $this->groupby = $GroupBy;

        $template = $this->_template;

        if(isset($template->attributes()->orientation))
        {
            $aorientation = (string)$template->attributes()->orientation;
        }
        if (isset($aorientation))
        {
           $pageorientation = $aorientation;
        }
        else
        {
            $pageorientation = "P";
        }

        $aunits = (string)$template->attributes()->units;
        if (isset($aunits) && $aunits != "")
        {
           $pageunits = $aunits;
        }
        else
        {
            $pageunits = "in";
        }
		
        $apapersize = (string)$template->attributes()->papersize;
        if (isset($apapersize) && $apapersize != "")
        {
           $papersize = $apapersize;
        }
        else
        {
            $papersize = "letter";
        }

        $newpagepergroup = (string)$template->attributes()->newpagepergroup;
        if (isset($newpagepergroup) && $newpagepergroup=="true")
        {
           $this->newpagepergroup = $newpagepergroup;
        }
        else
        {
            $this->newpagepergroup = "false";
        }

        if(isset($template->attributes()->pagewidth))
        {
            $this->pdf=new ZenventoryPDF($pageorientation,$pageunits,array((double)$template->attributes()->pagewidth,(double)$template->attributes()->pageheight) );
        }
        else
        {
            $this->pdf=new ZenventoryPDF($pageorientation, $pageunits, $papersize );
        }
		
        $this->pdf->AliasNbPages("{totalpages}");
		$this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetAuthor("Zenventory");
		$this->pdf->SetCompression(true);

		$this->pdf->SetSubject((string)$template->attributes()->title);
        $this->pdf->SetMargins((double)$template->attributes()->marginleft,(double)$template->attributes()->margintop,(double)$template->attributes()->marginright);
        $this->pdf->setCaller($this);




        if(isset($template->header)) // Make our own collection of elements.. Directly referencing them was removing them..
        {
            $this->header = $template->header;
            for($i=0;$i<count($this->_template->header->documentelements);$i++)
            {
                $this->headerelements[] = $this->_template->header->documentelements[$i];
            }
        }

        if(isset($template->groupheader)) // Make our own collection of elements.. Directly referencing them was removing them..
        {
            $this->groupheader = $template->groupheader;
            for($i=0;$i<count($this->_template->groupheader->documentelements);$i++)
            {
                $this->groupheaderelements[] = $this->_template->groupheader->documentelements[$i];
            }
        }

        if(isset($template->summary))
        {
            $this->summary = $template->summary;
            for($i=0;$i<count($this->_template->summary->documentelements);$i++)
            {
                $thiselement = $this->_template->summary->documentelements[$i];
                $this->summaryelements[] = $thiselement;
                
                if(isset($thiselement->attributes()->datasourcefield))
                {
                    $fieldname = $this->_template->summary->documentelements[$i]->attributes()->datasourcefield;
                    if(substr($fieldname, 0,1)=="(")
                    {
                        $varname = substr($fieldname,1,strpos($fieldname, ")")-1);
                        $this->summaryvariables[$varname]=0; // Initialise the vars. Only numbers OK!...
                    }
                }
            }
        }
        if(isset($template->footer))
        {
            $this->footer = $template->footer;
            for($i=0;$i<count($this->_template->footer->documentelements);$i++)
            {
                $this->footerelements[] = $this->_template->footer->documentelements[$i];
            }
        }
        if(isset($template->body))
        {
            $this->body = $template->body;
            for($i=0;$i<count($this->_template->body->documentelements);$i++)
            {
                $this->bodyelements[] = $this->_template->body->documentelements[$i];
            }
        }
        else
        {
            $this->body = $template;
            for($i=0;$i<count($this->_template->documentelements);$i++)
            {
                $this->bodyelements[] = $this->_template->documentelements[$i];
            }
        }

        $this->headerHeight = isset($this->header)?(double)$this->header->attributes()->height:0;
        $this->footerHeight = isset($this->footer)?(double)$this->footer->attributes()->height:0;
        $this->groupHeaderHeight = isset($this->groupheader)?(double)$this->groupheader->attributes()->height:0;
        $this->rowHeight =   isset($this->body)?(double)$this->body->attributes()->height:0;
        $this->bodyTop = 0;
        $this->calculatePage();

    }

    public function AddVariable($Name, $value)
    {
        if($Name!="")
        {
            $this->pagevariables[$Name] = $value;
        }
    }

    private function calculatePage()
    {

        $this->bodyHeight = $this->pdf->h - ($this->headerHeight + $this->footerHeight);
        
        $this->footerTop = $this->pdf->h - ($this->footerHeight);
        $this->groupHeaderTop = $this->headerHeight;

        $this->bodyTop = $this->groupHeaderTop+ $this->groupHeaderHeight;
    }

    public function SetReportTitle( $reporttitle )
    {
        $this->pdf->SetTitle($reporttitle);
        $this->title = $reporttitle;
    }

    public function headercb()
    {
        if(isset($this->header))
        {
            $this->inbody=false;
            $this->inheader=true;
            $this->infooter=false;
            $this->pdf->SetY(0);
            $this->sectionY = 0;
            $this->rowY=0;
            $head = $this->header;
            $elements = $this->headerelements;
            $this->activeSection = $head;
            $this->pdf->SetFont((string)$this->_template->attributes()->fontname,(string)$this->_template->attributes()->fontbold,(string)$this->_template->attributes()->fontsize);
            if (isset($elements))
            {
            $this->renderElements($this->currentrowdata,$elements);
            }
        }

    }

    public function footercb()
    {
        if(isset($this->footer))
        {
            $this->inbody=false;
            $this->inheader=false;
            $this->infooter=true;
            $this->pdf->SetY($this->footerTop);
            $this->sectionY=$this->footerTop;
            $this->rowY=0;
            $this->activeSection = $this->footer;
            $elements = $this->footerelements;
            $this->pdf->SetFont((string)$this->_template->attributes()->fontname,(string)$this->_template->attributes()->fontbold,(string)$this->_template->attributes()->fontsize);
            if (isset($elements))
            {
            $this->renderElements($this->currentrowdata,$elements);
            }
        }
    }

    private function processSummaryVariables($row)
    {
        if(isset($this->summaryvariables))
        {
            foreach( $this->summaryvariables as $key => $value )
            {
                if(isset($row->$key))
                {
                    $this->summaryvariables[$key]+=( $row->$key );
                }
            }
        }
    }

    private function clearSummaryVariables()
    {
        if(isset($this->summaryvariables))
        {
            foreach( $this->summaryvariables as $key => $value )
            {
                if(isset($row->$key))
                {
                    $this->summaryvariables[$key] = 0;
                }
            }
        }
        $rowCount=0;
    }

    public function rendergroup( $headerrow)
    {
       if( $this->groupCount==0 || strtoupper( $this->newpagepergroup)=="TRUE")
       {
           $this->AddPage( false );
       }
       $this->groupCount++;
        $this->groupdata = $headerrow;
        $grpby = $this->groupby;
       
       $rowdata = $headerrow->$grpby;
       $this->rendergroupheader($this->groupdata);
       $this->renderdata($rowdata);
       $this->clearSummaryVariables();
       
    }

    private function AddPage( $reprintgroup=true)
    {
        
        $this->newpage = false;
        $this->pdf->AddPage();
        $this->pageCount=0;
        $this->pageCount++;
        $this->rowY = 0;
        
        // Group Header top is also the header top as calculated.
        $this->sectionY = $this->groupHeaderTop;
        
        if($reprintgroup)
        {
            // The page overflowed because of the detail. Reprint the same group header.
            $this->rendergroupheader($this->groupdata);
        }
        
    }

    private function rendergroupheader($groupdata)
    {
        if(isset($this->groupheader))
        {
            $elements = $this->groupheaderelements;

            $template = $this->_template;
            if(isset($this->groupheader))
            {
                $height = $this->groupheader->attributes()->height;
            }
            else
            {
                $height=0;
            }
            if(($this->newpage || ($height > $this->bodyHeight)))
            {
                $this->AddPage();
            }

                $this->ingroupheader=true;
                $this->inbody=false;
                $this->inheader=false;
                $this->infooter=false;
                $this->activeSection = $this->groupheader;

                $this->renderBackground($this->activeSection);

            $this->pdf->SetFont((string)$template->attributes()->fontname,(string)$template->attributes()->fontbold,(string)$template->attributes()->fontsize);
            if(isset($elements))
            {
                $this->renderElements(null,$elements);
            }
            $this->rowY += $this->groupHeaderHeight;
        }
    }

    public function render()
    {
        // If this is a grouped report, then we need to fetch each group and
        // then get the child data as described in the constructor.
       
        // If it's not a grouped report, we behave as normal.

        if($this->groupby != "")
        {
            // We need Data for grouping to work.
            if(!isset( $this->data) &&isset($this->callbackfunction)  )
            {
           // Use a Call back to get the data.
               while(($thisrow = call_user_func($this->callbackfunction)) != null)
               {
                   if(isset($thisrow))
                   {
                       $this->rendergroup($thisrow);
                   }
               }
            }
            else
            {
                if(is_array($this->data))
                {
                    foreach($this->data as $thisrow)
                    {
                        $this->rendergroup($thisrow);
                    }
                }
                else
                {
                    $this->rendergroup($this->data);
                }

            }

        }
        else
        {
            $this->AddPage( false);
            $this->renderdata($this->data);
        }
        $fn = str_replace(" ", " ", $this->title)  . ".pdf";
        $this->pdf->Output( $fn ,"I");
        $this->pdf->Dispose();
    }

    private function renderdata( $rowdata )
    {
       if(isset($rowdata))
       {
           $this->rowdata = $rowdata;
       }

        
        if(!isset( $this->rowdata) &&isset($this->callbackfunction)  )
        {
           // Use a Call back to get the data.
           while(($thisrow = call_user_func($this->callbackfunction)) != null)
           {
               if(isset($thisrow))
               {
                   $elements = $this->bodyelements;
                   $this->renderRow($thisrow,$elements);
               }
           }

        }
        else
        {
            if(is_array($this->rowdata))
            {
                foreach($this->rowdata as $thisrow)
                {
                    $elements = $this->bodyelements;
                    if(isset($elements))
                    {
                    $this->renderRow($thisrow,$elements);
                    }
                }
            }
            else
            {
                $elements = $this->bodyelements;
                if(isset($elements))
                {
                   $this->renderRow($this->rowdata,$elements);
                }
            }

        }
       if(isset($this->summary))
       {
           $elements = $this->summaryelements;
           if (isset($elements))
           {
               $this->renderSummary( $elements);
           }
       }
    }


    private function renderSummary( &$collection )
    {

        $template = $this->_template;
        $height = $this->summary->attributes()->height;
        if(($this->newpage || ($height > $this->bodyHeight+$this->groupHeaderHeight)))
        {
            $this->AddPage();

        }

        $this->ingroupheader=false;
            $this->inbody=true;
            $this->inheader=false;
            $this->infooter=false;
            $this->activeSection = $this->summary;

            $this->renderBackground($this->activeSection);

        $this->pdf->SetFont((string)$template->attributes()->fontname,(string)$template->attributes()->fontbold,(string)$template->attributes()->fontsize);
        if(isset($collection))
        {
            $this->renderElements(null,$collection);
        }
        else
        {
            $this->renderElements(null,$this->summaryelements);
        }

        $this->rowY += $this->rowHeight;

    }

    private function renderRow( $row, &$collection )
    {
        if(isset($row))
        {
            $this->rowCount++;
            $this->processSummaryVariables( $row );
            $this->currentrowdata = $row;
            $template = $this->_template;

            if(($this->newpage || (($this->rowY+$this->rowHeight )> $this->bodyHeight )))
        {
                $this->AddPage();
        }

            $this->ingroupheader=false;
            $this->inbody=true;
            $this->inheader=false;
            $this->infooter=false;
            $this->activeSection = $this->body;

            $this->renderBackground($this->activeSection);

        $this->pdf->SetFont((string)$template->attributes()->fontname,(string)$template->attributes()->fontbold,(string)$template->attributes()->fontsize);
        if(isset($collection))
        {
            $this->renderElements($row,$collection);
        }
        else
        {
            $this->renderElements($row,$this->bodyelements);
        }

        $this->rowY += $this->rowHeight;
        }
        
    }

    private function renderElements( $row, &$collection)
    {
        if(is_array($collection))
        {
            foreach($collection as $thiselement)
            {
                $this->renderElement($row,$thiselement);
            }
        }
        else
        {
            $this->renderElement($row,$collection);
        }

    }

    private function renderElement($row, $element)
    {
        $viz=true;
        if(isset($element->attributes()->visibilityfield))
        {
            $visibilityfield = $element->attributes()->visibilityfield;
            
            $vizvalue = trim( $this->getValue($element, $row, $visibilityfield) );
           // echo("{" . $visibilityfield . " : " . $vizvalue . " }");
            if($vizvalue=="" || $vizvalue==0 || $vizvalue=="0" )
            {
                $viz=false;
            }
        }
        if($viz)
        {
            switch((string)$element->attributes()->type)
            {
                case "IMAGE":
                   $this->renderImageElement($element, $row);
                    break;
                case "SHAPE":
                   $this->renderShapeElement($element, $row);
                    break;
                case "LINE":
                   $this->renderLineElement($element, $row);
                    break;
                case "CODE39":
                    $this->renderBarcodeElement($element, $row);
                    break;
                case "QRCODE":
                    $this->renderQRCODEElement($element, $row);
                    break;
                case "TEXT":
                    // Fall through.
                default:
                    $this->renderTextElement($element, $row);

            }
        }
    }

    private function renderImageElement($element,$row)
    {
        $template = $this->_template;
        $fieldname = (string)$element->attributes()->datasourcefield;
        if(isset($element->attributes()->angle))
        {
            $angle = $element->attributes()->angle;
        }
        else
        {
            $angle = 0;
        }

        $prefix = (string)$element->attributes()->dataprefix;

        $filename = (isset($row->$prefix)? $row->$prefix:"") . $text = $this->getValue($element,$row);

        if(file_exists($filename))
        {
            $imagex = (double)$element->attributes()->left;
            $imagey = (double)$element->attributes()->top+ $this->rowY+$this->sectionY;
            $imagewidth = (double)$element->attributes()->width;
            $imageheight = (double)$element->attributes()->height;
            if($angle>0 || $angle<0)
            {
                $this->pdf->Rotate($angle,$imagex + ($imagewidth/2),$imagey + ($imageheight/2));
            }
            $this->pdf->Image($filename ,$imagex,$imagey,$imagewidth);
            if($angle>0 || $angle<0)
            {
                $this->pdf->Rotate(0);
            }

        }
    }

    private function setFont($element)
    {

        $fontname = isset($element->attributes()->fontname)?(string)$element->attributes()->fontname:(string)$this->_template->attributes()->fontname;
        $fontsize = isset($element->attributes()->fontsize)?(string)$element->attributes()->fontsize:(string)$this->_template->attributes()->fontsize;
        $fontbold = isset($element->attributes()->fontbold)?(string)$element->attributes()->fontbold:(string)$this->_template->attributes()->fontbold;

        $this->pdf->SetFont($fontname,$fontbold,$fontsize);

    }

    private function getValue( $element,$row, $otherfield="" )
    {
        $template = $this->_template;
        $val="";
        $fieldfound=false;

        if($otherfield=="")
        {
            $fieldname = (string)$element->attributes()->datasourcefield;
            if(isset($element->attributes()->altdatasourcefield))
            {
                $altfieldname = (string)$element->attributes()->altdatasourcefield;
            }
            else
            {
                $altfieldname="";
            }
        }
        else
        {
            $fieldname = $otherfield;
            $altfieldname = $otherfield;
        }
        
        $prefix = (string)$element->attributes()->dataprefix;

        if(substr($fieldname, 0,1)=="(")
        {
            $varname = substr($fieldname,1,strpos($fieldname, ")")-1);
            if(isset($element->attributes()->function))
            {
                if($element->attributes()->function=="AVG")
                {
                    $val= $this->summaryvariables[$varname] / $this->rowCount;
                    $fieldfound=true;
                }
                else
                {
                    $val= $this->summaryvariables[$varname];
                    $fieldfound=true;
                }
            }
            else
            {
                $val= $this->summaryvariables[$varname];
                $fieldfound=true;
            }
        }
        if(substr($fieldname, 0,1)=="[")
        {
            $varname = substr($fieldname,1,strpos($fieldname, "]")-1);
            switch($varname)
            {
                case "pageno":
                    $val= $this->pdf->PageNo();
                    $fieldfound=true;
                    break;
                case "pages":
                    $val= "{totalpages}";
                    $fieldfound=true;
                    break;
                case "title":
                    $val= $this->title;
                    $fieldfound=true;
                    break;
                case "rowcount":
                    $val= $this->rowCount;
					$fieldfound=true;
                    break;
                case "date":                    
                    $val= today();
					$fieldfound=true;
                    break;
                case "datetime":
                    $val = now();
					$fieldfound=true;
                    break;
                default:
                    $val= $varname . " not found";
                    break;
            }
            
            // Pull from the page level variables added by the container page.
            if(isset($this->pagevariables))
            {
                if(isset($this->pagevariables[$varname]))
                {
                    $val= $this->pagevariables[$varname];
                    $fieldfound=true;
                }
            }

        }
        if(!$fieldfound)
        {
            // Not a variable, get the value from the row.
                if(isset($element->attributes()->value) && $element->attributes()->value != "")
                {
                $val = $this->getFieldValue($row, $this->groupdata, $prefix) . (string)$element->attributes()->value;
                }
                else
                {
                    if($altfieldname!="")
                    {
                    $fieldval = $this->getFieldValue($row, $this->groupdata, $fieldname);
                    if($fieldval == "")
                        {
                        $val = $this->getFieldValue($row, $this->groupdata, $prefix) . $this->getFieldValue($row, $this->groupdata, $altfieldname);
                    }
                    else
                    {
                        $val =$this->getFieldValue($row, $this->groupdata, $prefix) . $fieldval;
                    }
                    }
                    else
                    {
                    $val = $this->getFieldValue($row, $this->groupdata, $prefix) . $this->getFieldValue($row, $this->groupdata, $fieldname);
                    }
                }
        }

        if(isset($element->attributes()->formatfunction))
        {
            // Support funcitons like
            // Upper
            // Proper etc.
            if( strtoupper( $element->attributes()->formatfunction )=="TIMETAKEN")
            {
                $val = $this->MinsToText($val);
            }
        }

        return $val;
    }

    private function getFieldValue( $source, $altsource, $fieldname)
    {
        if(isset($source->$fieldname))
        {
            return $source->$fieldname;
        }
        else
        {
            if(isset($altsource->$fieldname))
            {
                return $altsource->$fieldname;
            }
        }
        return "";
    }

    private function renderTextElement($element,$row)
    {
        $text = $this->getValue($element,$row);
        if(isset($element->attributes()->dateformat) && (string)$element->attributes()->dateformat!="")
        {
            $timestamp = strtotime($text);
            $text = date((string)$element->attributes()->dateformat,$timestamp);
        } else if(isset($element->attributes()->dataformat) && (string)$element->attributes()->dataformat!="")
        {
            $text=sprintf((string)$element->attributes()->dataformat, $text);
            
        }
        $wrapped=false;

        $leftval=null;

        if(isset($element->attributes()->left))
        {
            $leftval = (double)$element->attributes()->left;
        }
        if(isset($element->attributes()->top))
        {
            $topval = (double)$element->attributes()->top + $this->rowY + $this->sectionY;
        }
        else
        {
            $topval = $this->rowY;
        }

        $align = (string)$element->attributes()->align;

        $this->setFont($element);
        if(isset($leftval))
        {
            $this->pdf->SetXY($leftval,$topval);
        }

        if( !isset($leftval) && ($this->pdf->GetX() + (double)$element->attributes()->width) > ($this->pdf->w - $this->pdf->rMargin) )
        {
            if($this->inheader )
            {
                $this->rowY += (double)$element->attributes()->height;
                $this->pdf->SetY((double)$element->attributes()->top + $this->rowY + $this->sectionY);
                $y = (double)$element->attributes()->top + $this->rowY + $this->sectionY + (double)$element->attributes()->height + ((string)$element->attributes()->bottomborder=="Y"?1:0);

                if( $y > $this->bodyTop)
                {
                    $diff = $y - $this->bodyTop ;
                    $this->headerHeight += $diff;
                    $this->calculatePage();
                }
            }
            else if($this->ingroupheader)
            {

                    $this->rowY += $element->attributes()->height;
                    if(((double)$element->attributes()->top + $this->rowY + $this->sectionY + $this->rowHeight)>$this->footerTop)
                    {
                        $this->pdf->AddPage();
                        $this->pageCount++;
                        $this->rowY = 0;
                        $this->sectionY = $this->bodyTop;
                        $this->activeSection = $this->body;

                    }
                    $wrapped=true;
                    $this->pdf->SetY((double)$element->attributes()->top + $this->rowY + $this->sectionY);



                /*$this->rowY += (double)$element->attributes()->height;
                $this->pdf->SetY((double)$element->attributes()->top + $this->rowY + $this->sectionY);
                $y = (double)$element->attributes()->top + $this->rowY + $this->sectionY + (double)$element->attributes()->height + ((string)$element->attributes()->bottomborder=="Y"?1:0);

                if( $y > $this->bodyTop)
                {
                    $diff = $y - $this->bodyTop ;
                    $this->groupHeaderHeight += $diff;
                   // $this->calculatePage();
                }*/
            }
            else
            {
                if( $this->inbody )
                {
                    $this->rowY += $this->rowHeight;
                    if(((double)$element->attributes()->top + $this->rowY + $this->sectionY + $this->rowHeight)>$this->footerTop)
                    {
                        $this->pdf->AddPage();
                        $this->pageCount++;
                        $this->rowY = 0;
                        $this->sectionY = $this->bodyTop;
                        $this->activeSection = $this->body;
                        
                    }
                    $wrapped=true;
                    $this->pdf->SetY((double)$element->attributes()->top + $this->rowY + $this->sectionY);
                    
                }
            }
        }
        $backcolor=null;
        $borders=null;
        $this->setbackground($element,$row,$borders,$backcolor);
        if($wrapped)
        {
            $this->renderBackground($this->activeSection);
        }

        if(isset($element->attributes()->wrap) &&  $element->attributes()->wrap=="Y")
        {
            $this->pdf->MultiCell((double)$element->attributes()->width,(double)$element->attributes()->height,$text,$borders,$align,(isset($backcolor)?true:false));
        }
        else
        {
            $this->pdf->Cell((double)$element->attributes()->width,(double)$element->attributes()->height,$text ,$borders,0,$align,(isset($backcolor)?true:false));
        }
    }

    private function renderBackground( $section )
    {
        $oldx = $this->pdf->GetX();
        $oldy = $this->pdf->GetY();

        $this->setbackground($section,"",$borders,$backcolor);
        if(isset($borders) || isset($backcolor))
        {
            $left = $this->pdf->lMargin;
            $width = $this->pdf->w - ($this->pdf->lMargin+$this->pdf->rMargin);
            $height = $section->attributes()->height;
            $top = $this->rowY + $this->sectionY;
            $this->pdf->SetXY($left,$top);
            $this->pdf->Cell($width,$height,"" ,$borders,0,"",(isset($backcolor)?true:false));
            $this->pdf->SetXY($oldx,$oldy);
        }
    }

    private function renderShapeElement($element,$row)
    {
        if(isset($element->attributes()->left))
        {
            $leftval = (double)$element->attributes()->left;
        }
        if(isset($element->attributes()->top))
        {
            $topval = (double)$element->attributes()->top + $this->rowY + $this->sectionY;
        }
        else
        {
            $topval = $this->rowY;
        }

        if(isset($leftval))
        {
            $this->pdf->SetXY($leftval,$topval);
        }
        $this->setbackground($element,$row,$borders,$backcolor);
        $this->pdf->Cell((double)$element->attributes()->width,(double)$element->attributes()->height,"" ,$borders,0,$align,(isset($backcolor)?true:false));
    }

    private function renderLineElement($element, $row)
    {
        if(isset($element->attributes()->left))
        {
            $leftval = (double)$element->attributes()->left;
        }
        if(isset($element->attributes()->top))
        {
            $topval = (double)$element->attributes()->top + $this->rowY + $this->sectionY;
        }
        else
        {
            $topval = $this->rowY;
        }


        if(isset($leftval))
        {
            $this->pdf->SetXY($leftval,$topval);
        }
        $lengthval=0;
        if(isset($element->attributes()->length))
        {
            $lengthval = (double)$element->attributes()->length;
        }
        $thicknessval=0.2;
        if(isset($element->attributes()->thickness))
        {
            $thicknessval = (double)$element->attributes()->thickness;
        }
        $orientationval='H';
        if(isset($element->attributes()->orientation))
        {
            $orientationval = $element->attributes()->orientation;
        }
        $dashesval=15;
        if(isset($element->attributes()->dashes))
        {
            $dashesval = (double)$element->attributes()->dashes;
        }

        if($orientationval=="H")
        {
            $this->pdf->DashedHLine($leftval, $topval, $lengthval, $thicknessval, $dashesval);
        }
        else
        {
            $this->pdf->DashedVLine($leftval, $topval, $lengthval, $thicknessval, $dashesval);
        }
    }

private function setbackground($element,$row, &$borders, &$backcolor)
{
    if(!isset($element))
    {
        return false;
    }
    $borders="";
    $borders .= (isset($element->attributes()->topborder)&&$element->attributes()->topborder=="Y")?"T":"";
    $borders .= (isset($element->attributes()->leftborder)&&$element->attributes()->leftborder=="Y")?"L":"";
    $borders .= (isset($element->attributes()->rightborder)&&$element->attributes()->rightborder=="Y")?"R":"";
    $borders .= (isset($element->attributes()->bottomborder)&&$element->attributes()->bottomborder=="Y")?"B":"";

    if(isset($element->attributes()->allborders)&&$element->attributes()->allborders=="Y")
    {
        $borders = "1";
    }
    if($borders=="")
    {
        $borders="0";
    }
    if(isset($element->attributes()->backcolor))
    {
        $backcolor =(string)$element->attributes()->backcolor;
        if(isset($element->attributes()->altbackcolor))
        {
            $altbackcolor = (string)$element->attributes()->altbackcolor;
            $backcolor = ($this->rowCount % 2==0)?$backcolor:$altbackcolor;
        }

        if(substr( $backcolor,0,1)=="#")
        {
            $backcolor = substr($backcolor,1);
        }

        $colors = hexrgb($backcolor);

        $this->pdf->SetFillColor($colors['red'],$colors['green'],$colors['blue']);
    }
    
}

    private function renderBarcodeElement($element,$row)
    {
        $text = $this->getValue($element,$row);

        $this->setFont($element);

        if(isset($element->attributes()->dataformat) && (string)$element->attributes()->dataformat!="")
        {
            $text=sprintf((string)$element->attributes()->dataformat, $text);
        }

        $this->pdf->Code39((double)$element->attributes()->left,(double)$element->attributes()->top+ $this->rowY+ $this->sectionY , $text ,(double)$element->attributes()->width,(double)$element->attributes()->height,0);
    }

    private function renderQRCODEElement( $element, $row)
    {
        $text = $this->getValue($element,$row);
        $this->setFont($element);

        if(isset($element->attributes()->dataformat) && (string)$element->attributes()->dataformat!="")
        {
            $text=sprintf((string)$element->attributes()->dataformat, $text);
        }

        $qrcode = new QRcode($text, 'H'); // error level : L, M, Q, H
        $qrcode->displayFPDF( $this->pdf ,(double)$element->attributes()->left,(double)$element->attributes()->top+ $this->rowY+ $this->sectionY, (double)$element->attributes()->width);//, $background, $color)
    }

    private function int_divide($x, $y) {
    if ($x == 0) return 0;
    if ($y == 0) return FALSE;
    return ($x - ($x % $y)) / $y;
    }

    private function pluralize( $val,$singlular )
    {
        if($val>1)
        {
            return $singlular . "s";
        }
        else
        {
            return $singular;
        }
    }

    private function MinsToText( $mins )
    {
        // We have some minutes. Let's convert it to human readable text.
        $minsportion = $mins % 60;
        $hours = round( $mins/60);
        $hoursportion = $hours % 24;
        $days = round( $hours/24);

        $text = "";

        if($days>0)
        {
            $text .= ($text!=""?" ":""). $days . " " . $this->pluralize($days,"day");
        }
        if($days>0)
        {
            $text .= ($text!=""?" ":"").$hoursportion . " " . $this->pluralize($hoursportion,"hour");
        }
        if($days>0)
        {
            $text .= ($text!=""?" ":"").$minsportion . " " . $this->pluralize($minsportion,"minute");
        }
        return $text;

    }

}
?>