<?php
class columnControl
{
    var $id, $type,$noshowtest,$noshowvalue,$noshowunlessvalue;
}

class hiddencontrol
{
    var $fieldid, $datafieldname,$userownumber;
}

class colorrule
{
    var $fieldtocheck, $operation, $value, $inkcolor, $papercolor, $bold, $cssclass ;
}

abstract class basedatagrid
{
    //put your code here
    private $_columns;
    protected $_fields;
    protected $_fieldtypes;
    private $_formats;
    private $_defaults;
    private $_elsedefaults;
    private $_defaultcompares;
    private $_controls;
    protected $_idcol;
    private $_actions;
    private $_rightactions;
    private $_actionicons;
    private $_actionurl;
    private $_onclick;

    private $_rightactionicons;
    private $_rightactionurl;
    private $_rightonclick;

    private $_includehiddencontrol;
    private $_hiddencontrolid;
    private $_userowno;
    private $_total;
    private $_totalfield;
    private $_showcount;
    private $_countsingularcontext;
    private $_countpluralcontext;
    private $_imagepaths;
    private $_noimagepaths;
    private $_noimagefiles;
    private $_imageprefixes;
    private $_actioncompare;
    private $_noActionIfValue;
    private $_noActionUnlessValue;
    private $_columnCallbacks;

    private $_footer;

    protected $_data;
    private $_NoRowMessage;

    private $_color_rules;
    private $_hiddencontrols;

    public function basedatagrid()
    {
        // Base Constructor.
        $this->_showcount=0;
        $this->_countsingularcontext = "";
        $this->_countpluralcontext = "";
    }

    public function AddColorRule( $fieldtocheck, $operation, $value, $ink, $paper, $bold, $cssclass )
    {
        $colorrule = new colorrule();
        $colorrule->fieldtocheck = $fieldtocheck;
        switch($operation)
        {
            case "<":
            case "<=":
            case "=":
            case ">":
            case ">=":
            case "!=":
                $colorrule->operation = $operation;
                break;
            default:
                $colorrule->operation="=";
        }
        $colorrule->inkcolor = $ink;
        $colorrule->papercolor = $paper;
        $colorrule->value = $value;
        $colorrule->bold = $bold;
        $colorrule->cssclass = $cssclass;

        $this->_color_rules[] = $colorrule;
    }

    public function AddHiddenField( $fieldid, $fieldname, $userownumber=false )
    {
        $hidcontrol = new hiddencontrol();
        $hidcontrol->datafieldname = $fieldname;
        $hidcontrol->fieldid = $fieldid;
        $hidcontrol->userownumber =  $userownumber;
        $this->_hiddencontrols[] =$hidcontrol;

    }

    public function SetCountContext( $singular, $plural )
    {
        $this->_showcount=1;
        $this->_countsingularcontext = $singular;
        $this->_countpluralcontext = $plural;
    }

    public function DisableCounts()
    {
        $this->_showcount=0;
    }

    public function IncludedHiddenControl( $NameStartsWith, $useRowNoForHiddenControl , $datafield = "" )
    {
        $this->_includehiddencontrol = true;
        $this->_hiddencontrolid = $NameStartsWith;
        $this->_userowno = $useRowNoForHiddenControl;
    }

    public function AddAction( $actionCaption, $actionicon, $actionurl,$onclick="",$ActionCompareField="", $NoActionIfValue="",$NoActionUnlessValue="" )
    {
        $this->_actions[] = $actionCaption;
        $this->_actionicons[] = $actionicon;
        $this->_actionurl[] = $actionurl;
        $this->_onclick[] = $onclick;

        if($ActionCompareField!="")
        {
            $this->_actioncompare[$actionicon]=$ActionCompareField;
            $this->_noActionIfValue[$actionicon]=$NoActionIfValue;
            $this->_noActionUnlessValue[$actionicon]=$NoActionUnlessValue;
        }

    }
    public function AddRightAction( $actionCaption, $actionicon, $actionurl,$onclick="",$ActionCompareField="", $NoActionIfValue="",$NoActionUnlessValue="" )
    {
        $this->_rightactions[] = $actionCaption;
        $this->_rightactionicons[] = $actionicon;
        $this->_rightactionurl[] = $actionurl;
        $this->_rightonclick[] = $onclick;

        if($ActionCompareField!="")
        {
            $this->_actioncompare[$actionicon]=$ActionCompareField;
            $this->_noActionIfValue[$actionicon]=$NoActionIfValue;
            $this->_noActionUnlessValue[$actionicon]=$NoActionUnlessValue;
        }

    }

    public function SetNoDataMessage($Message)
    {
        $this->_NoRowMessage=$Message;
    }

    public function AddIdColumn( $ColumnField )
    {
        $this->_idcol = $ColumnField;
    }

    public function AddImageColumn( $ColumnHeading, $ColumnField, $ImagePath, $ImagePrefix,$noimagePath="",$NoImageFile="",$ImageStoragePath="" )
    {
        $this->AddColumn( $ColumnHeading, $ColumnField, "", "", "","", "", "","","" , "", $ImagePath, $ImagePrefix,$noimagePath,$NoImageFile, $ImageStoragePath);
    }

    public function AddColumnCallback($ColumnHeading, $ColumnField, $ColumnCallback)
    {
        $this->AddColumn( $ColumnHeading, $ColumnField, "", "", "","", "", "","","" , "", "", "","","", "", $ColumnCallback);
    }

    public function AddColumn( $ColumnHeading, $ColumnField, $Format = "", $ControlId = "", $ControlType = "",
        $DefaultCompare="", $DefaultValue="", $ControlCompare="",$NoControlIfValue="",$NoControlUnlessValue="" ,
        $CompareElseValue="",$ImagePath="", $ImagePrefix ="",$NoImagePath="",$NoImageFile="", $ImageStoragePath="", $ColumnCallback="")
    {
        $this->_columns[]=$ColumnHeading;
        $this->_fields[]=$ColumnField;
        $this->_formats[$ColumnField] = $Format;
        //$this->_fieldtypes[$ColumnField] =
        if($ImagePath != "")
        {
            $this->_imagepaths[$ColumnField] = $ImagePath;
            $this->_noimagepaths[$ColumnField] = $NoImagePath;
            $this->_noimagefile[$ColumnField] = $NoImageFile;
            $this->_imagestoratepath[$ColumnField] = $ImageStoragePath;
        }
        if($ImagePrefix != "")
        {
            $this->_imageprefixes[$ColumnField] = $ImagePrefix;

        }

        if($DefaultCompare != "" )
        {
            $this->_defaultcompares[$ColumnField] = $DefaultCompare;
            $this->_defaults[$ColumnField] = $DefaultValue;
            if($CompareElseValue !="")
            {
                $this->_elsedefaults[$ColumnField] = $CompareElseValue;
            }
        }
        
        if( isset($ControlId) && $ControlId != "")
        {
            $control = new columnControl();
            $control->id = $ControlId;
            $control->type = $ControlType;
            $control->noshowcompare = $ControlCompare;
            $control->noshowvalue = $NoControlIfValue;
            $control->noshowunlessvalue = $NoControlUnlessValue;
            $this->_controls[$ColumnField] = $control;
        }
        
        if ( isset($ColumnCallback) && $ColumnCallback != "")
        {
            $this->_columnCallbacks[$ColumnField] = $ColumnCallback;
        }
    }

    public function ColumnToTotal($ColumnField)
    {
        $this->_totalfield = $ColumnField;
    }

    public function GetTotal()
    {
        return $this->_total;
    }

    public function AddCustomFooter( $contents )
    {
        $this->_footer = $contents;
    }

    abstract function getRowCount();
    abstract function getIdValue( &$row );
    abstract function getFieldValue(&$row, $fieldName );
    abstract function processdata();

    private function getFieldValueWithDefaults( &$row, $fieldname, &$ignoreformatting )
    {
        if (isset($this->_columnCallbacks[$fieldname]))
        {
            $value = 'error';
            $c = $this->_columnCallbacks[$fieldname];
            if (is_callable( $c ))
            {
                $instance = $c[0];
                $value = $instance->{$c[1]}($row);
                //call_user_func($c, $row, &$value);
            }
        }
        else
        {
            $value = $this->getFieldValue($row, $fieldname);
            if(isset($this->_defaultcompares[$fieldname]) && ( $this->_defaultcompares[$fieldname] != ""  ) )
            {
                
                if($value == $this->_defaultcompares[$fieldname] || ($value=="" && $this->_defaultcompares[$fieldname]=="''" ) )
                {
                    $value = $this->_defaults[$fieldname];
                    $ignoreformatting=true;// If we've set a static default, we don't want to format it.
                }
                else
                {
                    if(isset($this->_elsedefaults[$fieldname]))
                    {
                        $value = $this->_elsedefaults[$fieldname];
                        $ignoreformatting=true;// If we've set a static default, we don't want to format it.
                    }
                }
            }
        }
        return $value;
    }

    private function isControlColumn( $i )
    {
        $field = $this->_fields[$i-1];
        $control = $this->_controls[$field];
        if(isset($control))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function Render()
    {
        $this->_total=0;
        echo("<table class=\"sortable\" width='100%' align='center' cellpadding='0' cellspacing='0'>");
        $hasActions = (isset($this->_actions));
        $hasRightActions = (isset($this->_rightactions));


        if(isset($this->_columns))
        {
            echo("<thead><tr class='tableHeading'>");
            if($hasActions)
            {
                $colwidth = 30 * count($this->_actions);
                echo("<th class=\"sorttable_nosort\" width='".$colwidth."px' >&nbsp;</th>"); // Action header.
            }
            $i = 0;
            foreach( $this->_columns as $thisColumn )
            {
                $i++;
                if($this->isControlColumn($i) )
                {
                    $sortable = "sorttable_nosort";
                }
                else
                {
                    if(isset($this->_formats[$i]))
                    {
                        $sortable = ""; // Use custom sort.
                    }
                    else
                    {
                        $sortable = "sorttable_alpha";
                    }
                }

                echo("<th class=\"$sortable\">" . $thisColumn . "</th>"); // We might want these sortable.
            }
            if($hasRightActions)
            {
                $colwidth = 30 * count($this->_rightactions);
                echo("<th class=\"sorttable_nosort\" width='".$colwidth."px' >&nbsp;</th>"); // Action header.
            }
            echo("</thead></tr><tbody>");
        }
        $num = $this->getRowCount();

        if($num==0)
        {
           $fieldcount = count($this->_fields) +1;
           echo("<tr><td align='center' colspan='" . $fieldcount . "' >" . $this->_NoRowMessage . "</td></tr>");
        }
        else
        {
            if(isset($this->_data))
            {
                $this->processdata();
                
            }
        }
        echo("</tbody>");
        $countadj = ($num==1)?$this->_countsingularcontext:$this->_countpluralcontext;
        if($this->_showcount ==1)
        {
            if( isset($this->_footer ))
            {
                $fieldcount = count($this->_fields) +1;
                echo("<tfoot><tr><td align='left' colspan='2'>" . $num . " $countadj</td><td align='right' colspan='" . ($fieldcount-2) . "'>" . $this->_footer  . "</td></tr></tfoot>");
            }
            else
            {
                $fieldcount = count($this->_fields);
                echo("<tfoot><tr><td align='left' colspan='0'>" . $num . " $countadj</td></tr></tfoot>");
            }
        }
        else
        {
            if( isset($this->_footer ))
            {
                $fieldcount = count($this->_fields) +1;
                echo("<tfoot><tr><td align='right' colspan='" . ($fieldcount) . "'>" . $this->_footer  . "</td></tr></tfoot>");
            }
        }
        echo("</table>");
    }

    function renderrow( &$row, &$rowno )
    {
        if($rowno % 2 ==0)
        {
            $rowclass="odd";
        }
        else
        {
            $rowclass="even";
        }
        $styleadditional="";
        if(isset($this->_color_rules))
        {
            foreach($this->_color_rules as $thiscolorrule)
            {
                $fieldtocheck = $this->getFieldValue($row, $thiscolorrule->fieldtocheck );
                $fieldtocompare = $this->getFieldValue($row, $thiscolorrule->value );
                $rulematch=false;
                $withcssclass = $thiscolorrule->cssclass;
                switch($thiscolorrule->operation)
                {
                    case "<":
                        $rulematch = ($fieldtocheck < $fieldtocompare);
                        break;
                    case ">":
                        $rulematch = ($fieldtocheck > $fieldtocompare);
                        break;
                    case "<=":
                        $rulematch = ($fieldtocheck <= $fieldtocompare);
                        break;
                    case ">=":
                        $rulematch = ($fieldtocheck >= $fieldtocompare);
                        break;
                    case "=":
                        $rulematch = ($fieldtocheck == $fieldtocompare);
                        break;
                    case "!=":
                        $rulematch = ($fieldtocheck != $fieldtocompare);
                        break;
                }

                if($rulematch && $withcssclass=="")
                {
                    $styleadditional=sprintf("style='background-color: %s; color: %s; font-weight:%s;'", $thiscolorrule->papercolor, $thiscolorrule->inkcolor, $thiscolorrule->bold?"bold":"normal");
                    $rowclass="customrow";
                }
                else
                {
                    if($rulematch)
                    {
                        $rowclass=$withcssclass;
                    }
                }
            }
        }

        echo("<tr class='" . $rowclass . "' $styleadditional >");

        // The tools.
        $i=0;
        $idval = $this->getIdValue( $row );
        if(isset($this->_includehiddencontrol) && $this->_includehiddencontrol==true)
        {
            $crtlid = $this->_hiddencontrolid . ((isset($this->_userowno) && $this->_userowno==true)?$rowno:$idval);
            echo("<input type=\"hidden\" id=\"" . $crtlid . "\"  name=\"" . $crtlid . "\" value=\"" . $idval . "\">" );
        }

        // Add any hidden controls.

        if(isset($this->_hiddencontrols) && count($this->_hiddencontrols)>0)
        {
            foreach($this->_hiddencontrols as $thisControl )
            {
                if($thisControl->userownumber)
                {
                    $ctrlid = $thisControl->fieldid . $rowno;
                }
                else
                {
                    $ctrlid = $thisControl->fieldid . $idval;
                }

                $ctrlval = $this->getFieldValue($row, $thisControl->datafieldname);
                echo("<input type=\"hidden\" id=\"" . $ctrlid . "\"  name=\"" . $ctrlid . "\" value=\"" . $ctrlval . "\">" );
            }
        }
        
if($idval>=0 OR $idval !="")
{
    // Render any Hidden controls for this row
    
    if(isset($this->_actions) && count($this->_actions)>0)
        {
            echo("<td>");

            foreach( $this->_actions as $thisaction )
            {
                $toolicon = $this->_actionicons[$i];

                $allowcontrol=true;
                if(isset($this->_actioncompare[$toolicon])&& $this->_actioncompare[$toolicon] != "")
                {
                    $comparevalue= str_replace("_", "", $this->getFieldValue($row, $this->_actioncompare[$toolicon])) ;
                    if(isset($this->_noActionIfValue[$toolicon])&& $this->_noActionIfValue[$toolicon] !="")
                    {
                        $testval=str_replace("_", "",$this->_noActionIfValue[$toolicon]);
                        if($comparevalue==$testval)
                        {
                            $allowcontrol=false;
                        }
                    }
                    if(isset($this->_noActionUnlessValue[$toolicon])&& $this->_noActionUnlessValue[$toolicon] !="")
                    {
                        $testval=str_replace("_", "",$this->_noActionUnlessValue[$toolicon]);
                        if($comparevalue!=$testval)
                        {
                            $allowcontrol=false;
                        }
                    }
                }
                if($allowcontrol)
                {
                    $toolurl= sprintf($this->_actionurl[$i],$idval);
                    if(isset($this->_onclick[$i]))
                    {
                        if($this->_onclick[$i]!="")
                        {
                            $onclick= "onclick='". sprintf($this->_onclick[$i],$idval) ."'";
                            $toolurl="#";
                        }
                    }
                    else
                    {
                        $onclick="";
                    }
                    echo("<a tclass=\"imagebutton\" href=\"". $toolurl . "\" " . $onclick . "><img class=\"clickimage\" src=\"" . $toolicon . "\" title=\"". $thisaction ."\"  ></a>");
                }
                $i++;
            }
            echo("</td>");
        }
}
else
{
    echo("<td>&nbsp;</td>");
}
        

        // The data
        foreach( $this->_fields as $thisfield )
        {
            if($this->_totalfield == $thisfield)
            {
                $this->_total += $this->getFieldValue($row, $thisfield);
            }
            if(isset($this->_imagepaths[$thisfield]))
            {
                // An image column
                $ignoreformatting=true;
                $fieldval = $this->getFieldValueWithDefaults($row,$thisfield, $ignoreformatting);
                $filename = $this->_imagepaths[$thisfield] . $this->_imageprefixes[$thisfield] . $fieldval;
                if( $fieldval=="" || ($this->_noimagefile[$thisfield]!="" && !file_exists( $this->_imagestoratepath[$thisfield] . $fieldval)))
                {
                    $filename = $this->_noimagepaths[$thisfield] . $this->_noimagefile[$thisfield];
                }
                echo("<td><img src='$filename'/></td>");
            }
            else
            {
                if(!isset($this->_controls[ $thisfield]->id) )
                {
                    $ignoreformatting=false;

                    if( strpos($thisfield,":"))
                    {
                        $fields = explode(":",$thisfield);
                    }
                    else
                    {
                        $fields = null;
                    }
                    $arr = array();
                    $i=0;
                    if(isset($fields))
                    {
                        foreach($fields as $innerfield)
                        {
                            $arr[] = $this->getFieldValueWithDefaults($row,$innerfield, $ignoreformatting);
                            $i++;
                        }
                    }

                    if(count($arr)==0)
                    {
                        $fieldval = $this->getFieldValueWithDefaults($row,$thisfield, $ignoreformatting);
                        $arr[] = $fieldval;
                    }



                    //$fieldval = $this->getFieldValueWithDefaults($row,$thisfield, $ignoreformatting);
                    if(!$ignoreformatting && isset($this->_formats[$thisfield]) && $this->_formats[$thisfield] != "")
                    {
                        $options = explode( ":", $this->_formats[$thisfield]);
                        if(count($options)==2 && ($fieldval==1 || $fieldval==0 ||$fieldval==true || $fieldval==false ))
                        {
                            if($fieldval==1 || $fieldval==true)
                            {
                                $val = $options[0];
                            }
                            else
                            {
                                $val = $options[1];
                            }
                            echo("<td >" . $val . "</td>");
                        }
                        else
                        {
                            if( strpos($this->_formats[$thisfield], "%") !== false)
                            {
                                if(count($arr>1))
                                {
                                    echo("<td >" .(vsprintf( $this->_formats[$thisfield] ,  $arr )) . "</td>");
                                }
                                else
                                {
                                    echo("<td >" .(sprintf( $this->_formats[$thisfield] ,  $fieldval )) . "</td>");
                                }


                                //echo("<td >" .(sprintf( $this->_formats[$thisfield] ,  $fieldval )) . "</td>");
                            }
                            else
                            {
                                echo("<td sorttable_customkey='".$fieldval."'>" . date( $this->_formats[$thisfield] , strtotime( $fieldval )) . "</td>");
                            }
                        }
                    }
                    else
                    {
                        echo("<td>" . $fieldval . "</td>");
                    }
                }
                else
                {
                    // We have a control.
                    $control = $this->_controls[ $thisfield];
                    $renderit=true;
                    if($control->noshowcompare != "" )
                    {
                        $testval = $this->getFieldValue($row, $control->noshowcompare);
                        if($control->noshowvalue != "")
                        {
                            if($testval== $control->noshowvalue)
                            {
                                $renderit=false;
                            }
                        }
                        elseif($control->noshowunlessvalue != "")
                        {
                            if($testval== $control->noshowunlessvalue)
                            {
                                $renderit=true;
                            }
                            else
                            {
                                $renderit=false;
                            }
                        }
                    }

                    if($renderit)
                    {
                        echo("<td><input type='" . $control->type . "' id='" . $control->id . $idval . "' name='" . $control->id . $idval . "'/></td>");
                    }
                    else
                    {
                        echo("<td>&nbsp;</td>");
                    }
                }
            }
        }

        // Right actions.
        $onclick="";
        if(isset($this->_rightactions) && count($this->_rightactions)>0)
        {
            echo("<td>");

            foreach( $this->_rightactions as $thisaction )
            {
                $toolicon = $this->_rightactionicons[$i];

                $allowcontrol=true;
                if(isset($this->_actioncompare[$toolicon])&& $this->_actioncompare[$toolicon] != "")
                {
                    $comparevalue= str_replace("_", "", $this->getFieldValue($row, $this->_actioncompare[$toolicon])) ;
                    if(isset($this->_noActionIfValue[$toolicon])&& $this->_noActionIfValue[$toolicon] !="")
                    {
                        $testval=str_replace("_", "",$this->_noActionIfValue[$toolicon]);
                        if($comparevalue==$testval)
                        {
                            $allowcontrol=false;
                        }
                    }
                    if(isset($this->_noActionUnlessValue[$toolicon])&& $this->_noActionUnlessValue[$toolicon] !="")
                    {
                        $testval=str_replace("_", "",$this->_noActionUnlessValue[$toolicon]);
                        if($comparevalue!=$testval)
                        {
                            $allowcontrol=false;
                        }
                    }
                }
                if($allowcontrol)
                {
                    $toolurl= sprintf($this->_rightactionurl[$i],$idval);
                    if(isset($this->_rightonclick[$i]))
                    {
                        if($this->_rightonclick[$i]!="")
                        {
                            $onclick= "onclick='". sprintf($this->_rightonclick[$i],$idval) ."'";
                            $toolurl="#";
                        }
                    }
                    else
                    {
                        $onclick="";
                    }
                    echo("<a class=\"imagebutton\" href=\"". $toolurl . "\" " . $onclick . "><img class=\"clickimage\" src=\"" . $toolicon . "\" title=\"". $thisaction ."\"  ></a>");
                }
                $i++;
            }
            echo("</td>");
        }

        echo("</tr>");
    }


}
?>
