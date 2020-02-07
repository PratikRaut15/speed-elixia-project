<?php
class Date
{
    public static function today()
    {
        $t=getdate();
        return date('Y-m-d',$t[0]);
    }

    public static function MakeMySQLDate( $inDate )
    {
        $date = date_parse($inDate);
        return sprintf("%04d-%02d-%02d", $date['year'], $date['month'], $date['day']);
    }

    public static function MySQLNow()
    {
        $t=getdate();
        return date( 'Y-m-d H:i:s', $t[0] );
    }

    public function add_hours($my_date,$numhours) {
      $date_t = strtotime($my_date.' UTC');
      return gmdate('Y-m-d H:i:s',$date_t + ($numhours*3600));
    }

    public function return_hours($my_date) {
      $date_t = strtotime($my_date.' UTC');
      //return gmdate(speedConstants::DEFAULT_TIME,$date_t);
      return gmdate('H:i',$date_t);
    }

    public function return_hour($my_date,$numhours) {
      $date_t = strtotime($my_date.' UTC');
      return gmdate('H:i:s',$date_t + ($numhours*3600));
    }

    public function add_hours_return_date($my_date,$numhours) {
      $date_t = strtotime($my_date.' UTC');
      return gmdate('Y-m-d',$date_t + ($numhours*3600));
    }

    public function add_hours_return_date_dmy($my_date,$numhours) {
      $date_t = strtotime($my_date.' UTC');
      return gmdate('d-m-Y',$date_t + ($numhours*3600));
    }

}
?>
