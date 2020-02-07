<?php
require_once ("../../lib/components/component.inc.php");
class menu  extends component  {
    public function __construct()
    {
        parent::__construct();
    }

    private $_selectedTab;

    function SelectTab( $PageTab )
    {
        $this->_selectedTab = $PageTab;
    }

    function tabSelected($PageTab, $Tab )
	{

		$SelectedClass=" id='selectedMenu' ";
		// Is the tab selected?
		if(isset($PageTab))
		{
			
            // This is the tab variable for each page.
			if($PageTab == $Tab)
			{

				return $SelectedClass;
			}
		}
		else
		{
			// Try to get it from session.
			$thisPageTab = getSelectedTab();
			if(isset($thisPageTab))
			{
				if ($thisPageTab == $Tab)
				{
					return $SelectedClass;
				}

			}
		}
		return "";
	}
    //put your code here
    public function Render()
    {

        echo('<div id="menubar">');

    if( IsLoggedIn())
    {
        $class=$this->tabSelected($this->_selectedTab,DASHBOARD_TAB);

        echo('<a '.$class.' href="' . SITE_ROOT . '/dashboard/dashboard.php">Dashboard</a>');
    }
    else
    {

        echo('<a href="' . PARENT_SITE . '">Home</a>');

        echo('<a href="' . SIGNUP_URL . '">Sign up</a>');
        echo('<a href="' . REQUEST_PRICE_URL . '">Pricing</a>');
    }

    $useReports = 0;

    $class=$this->tabSelected($this->_selectedTab,PURCHASING_TAB);
    if( HasRole( BUYER_ROLE . "," . CUSTOMER_ROLE )){echo('<a '.$class.'  href="' . SITE_ROOT . '/purchasing/purchaseorder.php">Purchasing</a>');$useReports = 1;}
    $class=$this->tabSelected($this->_selectedTab,RECEIVING_TAB);
    if( HasRole( RECEIVER_ROLE )){echo('<a '.$class.'  href="' . SITE_ROOT . '/receiving/receivingmain.php">Receiving</a>');$useReports = 1;}
    $class=$this->tabSelected($this->_selectedTab,INVENTORY_TAB);
    if( HasRole( STOCK_ROLE  . "," . CUSTOMER_ROLE  )){echo('<a  '.$class.' href="' . SITE_ROOT . '/inventory/inventorymain.php">Inventory</a>');$useReports = 1;}
    $class=$this->tabSelected($this->_selectedTab,ORDERS_TAB);
    if( HasRole( SALES_ROLE . "," . PACK_ROLE . "," . PICKER_ROLE . "," . CUSTOMER_ROLE  ))
    {
        if(GetClientId(0)<=0)
        {
            echo('<a '.$class.'  href="' . SITE_ROOT . '/orders/ordersmain.php">Order Fulfillment</a>');
        }
        else
        {
            echo('<a '.$class.'  href="' . SITE_ROOT . '/orders/ordersmain.php">Orders</a>');
        }
        $useReports = 1;
    }
    $class=$this->tabSelected($this->_selectedTab,REPORTS_TAB);
    if( $useReports == 1){echo('<a '.$class.'  href="' . SITE_ROOT . '/reports/reportsmain.php">Reports</a>');}

    echo("</div>");

    }
}
?>
