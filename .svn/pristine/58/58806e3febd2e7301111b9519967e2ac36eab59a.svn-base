<?php
include_once ("../lib/components/gui/basedatagrid.php");
class datagrid extends basedatagrid
{
    //put your code here

    public function datagrid( &$data )
    {
       parent::basedatagrid();
       $this->_data = $data;
    }

    public function getRowCount()
    {
        return @mysql_num_rows($this->_data);
    }

    function getIdValue( &$row )
    {
        return $this->getFieldValue( $row, $this->_idcol );
    }

    function getFieldValue(&$row, $fieldName )
    {
        return $row[$fieldName];
    }

    function processdata()
    {
        $rowno = 0;
        while ( $row = mysql_fetch_array($this->_data))
        {
            $this->renderrow($row, $rowno);
            $rowno++;
        }
    }
}
?>
