<?php
final class Sanitise {

    /**
     * @param String $string
     * @return String
     */
    public static function /* String */ String (/* String */ $string, $default="") {
        $sanitised = null;

        if(!isset($string))
        {
            $string= $default;
        }

        $sanitised = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitised = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);

        return $sanitised;
    }

    public static function /* Long */ Long (/* String */ $string, $default=0) {
        $sanitised = null;

        $sanitised = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitised = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
        $sanitised = ($sanitised != "") ? intval($sanitised) : $default;
        return $sanitised;
    }

    public static function /* Float */ Float (/* String */ $string, $default=0) {
        $sanitised = null;

        //$sanitised = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        //$sanitised = filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
        $sanitised = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $sanitised = ($sanitised != "") ? floatval($sanitised) : $default;
        return $sanitised;


    }




    public static function Date( $string, $default="")
    {
        if(!isset($string))
        {
            // No point trying to convert to a date.
            return $default;
        }

        $date = date_parse($string);
        return sprintf("%04d-%02d-%02d", $date['year'], $date['month'], $date['day']);

    }

    public static function DateTime( $string, $default="")
    {
        if(!isset($string))
        {
            // No point trying to convert to a date.
            return $default;
        }

        $date= date_parse($string);
        return sprintf("%04d-%02d-%02d %02d:%02d:%02d", $date['year'], $date['month'], $date['day'], $date['hour'], $date['minute'], $date['second']);
    }
}
?>