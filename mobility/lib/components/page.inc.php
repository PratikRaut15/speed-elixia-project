<?php
require_once("components/parameterisedpage.inc.php");

class page extends parameterisedpage
{
    //put your code here
    public $SiteTitle="";
    public $showpubliccontent=false;
    public $MetaDescription="";
    public $MetaKeyWords="";
    public $GoogleId;
    public $FormName="";
    public $FormAction="";
    public $showform = true;

    public function __construct( &$user )
    {        
        parent::__construct( $user );

    }

    function RegisterComponent( $component )
    {
        parent::RegisterComponent($component);

    }
    
    function Redirect( $toURL )
    {
        // Just wrap up the page redirection code.. make it not ugly.
        header('Location: ' . $toURL );
    }

    

    function RenderHeader()
    {
       // All components are Registered.
       // Set the Root.
       // 
       $this->SetRoot($this);
       //
        // Each component has a js file.
       $this->BufferStart();
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html>
        <head>
            <title><?php echo( $this->SiteTitle);  ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
            <?php
            // Process any extra Styles
            $sheets = $this->GetStyleSheets();
            foreach($sheets as $thisstyle)
            {
                echo('<link href="'.$thisstyle.'" rel="stylesheet" type="text/css" />');
            }


            $scripts = $this->GetScripts();
            foreach($scripts as $thisscript)
            {
                echo('<script src="' .$thisscript.'" type="text/javascript"></script>');
            }
        ?>
        </head>
        <body bgcolor="#000000" link="#000000" alink="#000000" vlink="#000000" >
        <table width="1000px" border="0" cellpadding="0" cellspacing="0" border="#000000" bgcolor="#FFFFFF" align="center">
        <tr>
        <!-- Column for menu -->
            <td id="menucol" width="180px" VALIGN="top">
                <br/>
            <!-- Begin menu table -->
            <?php
            include_once($includeroot."externalmenu.php");
            ?>
            <!-- End table for menu --></td>
            <!-- End column  for menu -->
            <!-- Begin column for content -->
            <td VALIGN="top">
            <div class="mainpage">
    <?php
    }
    function RenderFooter()
    {
    ?>
            </div>
            </td>
        </tr>
        <tr bgcolor="#FFFFFF">
            <td></td>
            <td colspan="2"><div class="copyright">Copyright &copy; <?php echo( date( 'Y' )); ?> Ubiquia Inc. All rights reserved.</div></td>
        </tr>
    </table>
    </body>
    </html>
    <?php
    }
}
?>