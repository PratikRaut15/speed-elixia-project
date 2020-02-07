<?php
require_once ("../../../components/component.inc.php");

class printerhelper  extends component {
    //put your code here
    private $_targetpage="";
    private $_targetparameter = "id";
    private $_printstartedjsfunctionname = "";
    private $_printcompletedjsfunctionname = "";
    public function printerhelper( $targetpage, $targetparameter, $printstartedjs, $printcompletedjs )
    {
        parent::__construct();
        $this->_targetpage = $targetpage;
        $this->_targetparameter = $targetparameter;
        $this->_printstartedjsfunctionname = $printstartedjs;
        $this->_printcompletedjsfunctionname = $printcompletedjs;
    }

    public function Render()
    {
        parent::Render();

        ?>
<script>
var isloaded=false;
function appletloaded()
{
  isloaded=true;
}

function isActive()
{
  return isloaded;
}
<?php
if($this->_printstartedjsfunctionname!='printstarted' )
{
?>
function printstarted( noinqueue )
{
    <?php
    if($this->_printstartedjsfunctionname !="")
    {
        echo($this->_printstartedjsfunctionname . "(noinqueue);");
    }
    ?>
}
<?php
}

if($this->_printcompletedjsfunctionname!='printcompleted')
{
?>
function printcompleted( noinqueue )
{
    <?php
    if($this->_printcompletedjsfunctionname !="")
    {
        echo($this->_printcompletedjsfunctionname . "(noinqueue);");
    }
    ?>
}
<?php } ?>

function printNamedPrinter( parameter, printer, copies, attempt)
{
    // The target url passed to this control should contain the argument list.
    //
    if(attempt===undefined){attempt=1;}

    if(attempt>3)
    {
        return;
    }

    if(document.PrinterHelper != null)
    {
        if(!isActive())
            {
            attempt++
            setTimeout("printNamedPrinter(" + parameter +", "+ printer + ", "+ copies + ", "+ attempt +")",500);
            return;
            }
        try
        {
            var dt = new Date().valueOf();
            document.PrinterHelper.printpdf('<?php echo(ACTUAL_SITE_ROOT); ?>/<?php echo($this->_targetpage);  ?>?<?php echo($this->_targetparameter);  ?>=' + parameter + "&t=" + dt ,copies,printer);
        }
        catch(e)
        {
           alert(e.toString());
        }
    }
}

function print( parameter, copies, attempt )
{
    // The target url passed to this control should contain the argument list.
    //
    if(attempt===undefined){attempt=1;}

    if(attempt>3)
    {
        return;
    }

    if(document.PrinterHelper != null)
    {
        if(!isActive())
            {
            attempt++
            setTimeout("print(" + parameter + ", "+ copies + ", "+ attempt +")",500);
            return;
            }
        try
        {
            var dt = new Date().valueOf();
            document.PrinterHelper.printpdf('<?php echo(ACTUAL_SITE_ROOT); ?>/<?php echo($this->_targetpage);  ?>?<?php echo($this->_targetparameter);  ?>=' + parameter + "&t=" + dt ,copies,"Zebra");
        }
        catch(e)
        {
           alert(e.toString());
        }
    }
}
</script>
<?php
        echo("<APPLET name='PrinterHelper' initial_focus='false' id='PrinterHelper' archive='" . SITE_ROOT . "/applets/UbiquiaPrinterApplet.jar, " . SITE_ROOT . "/applets/PDFRenderer.jar' code='PrinterApplet' width=0 height=0 ></APPLET>");
    }

}
?>