<?php
require_once("../../lib/components/gui/basedatagrid.php");
class objectdatagrid extends basedatagrid
{
    public function objectdatagrid( &$dataarray = null )
    {
        parent::basedatagrid();
        $this->_data = $dataarray;
    }

    public function getRowCount()
    {
        return count($this->_data);
    }

    function getIdValue( &$row )
    {
        return $this->getFieldValue( $row, $this->_idcol );
    }

    function getFieldValue(&$row, $fieldName )
    {
        return $row->$fieldName;
    }

    function processdata()
    {
        $rowno = 0;
        foreach( $this->_data as $row )
        {
            $this->renderrow($row, $rowno);
            $rowno++;
        }
    }
}
?>
