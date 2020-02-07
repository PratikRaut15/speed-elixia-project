<?php

class AutoLoader {

    private $path;

    public function __construct($path, $relativePathDots) {
        $this->path = $path;
        $this->relativePathDots = $relativePathDots;
        spl_autoload_register(array($this, 'load'));
    }

    function load($file) {
        if (is_file($this->path . '/' . $file . '.php')) {
            $RELATIVE_PATH_DOTS = $this->relativePathDots;
            require_once( $this->path . '/' . $file . '.php' );
        }
    }

}

if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}

$autoloader_bo = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/bo', $RELATIVE_PATH_DOTS);
$autoloader_model = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/model', $RELATIVE_PATH_DOTS);
$autoloader_system = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/system', $RELATIVE_PATH_DOTS);
$autoloader_comman_function = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/comman_function', $RELATIVE_PATH_DOTS);
$autoloader_components = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/components', $RELATIVE_PATH_DOTS);
$autoloader_exceptions = new AutoLoader($RELATIVE_PATH_DOTS . 'lib/exceptions', $RELATIVE_PATH_DOTS);
?>