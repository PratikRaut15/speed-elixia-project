<?php
class component {
    //put your code here

    public $_scripts;
    public $_stylesheets;
    public $_components;
    public $_root;

    function __construct()
    {
        $this->_scripts = array();
        $this->_stylesheets = array();
        $this->_components = array();
    }

    public function SetRoot( $root )
    {
        $this->_root = $root;
        if(isset($this->_components))
        {
            foreach($this->_components as $thisComponent)
            {
                $thisComponent->SetRoot( $root);
            }
        }
    }

    function UserCheck( $user, $rolecodes )
    {
        // If the user is supposed to be here.
        // Return True.

        if(isset($user))
        {
            if($rolecodes == "")
            {
                // No permissions required for this page, just be logged in.
                return true;
            }

            if(isset($user->roles))
            {
                if(is_array($rolecodes))
                {
                    $allowedroles = $rolecodes;
                }
                else
                {
                    $allowedroles = explode(",",$rolecodes);
                }

                foreach($user->roles as $thisrole)
                {
                    foreach($allowedroles as $thisallowedrole)
                    {
                        if($thisrole->rolecode == $thisallowedrole)
                        {
                            return true;
                        }
                    }
                }
            }
        }

        // If not, Return False.
        return false;
    }

    public function GetStyleSheets()
    {
        $styles = array();

        if(isset($this->_components))
        {
            foreach($this->_components as $thiscomponent)
            {
                $componentstyles = $thiscomponent->GetStyleSheets();
                $styles = array_merge($styles,$componentstyles);
            }
        }
        if( isset($this->_stylesheets))
        {
            $styles = array_merge($styles,$this->_stylesheets);
        }

        return array_unique($styles);

    }

    public function GetScripts()
    {
        $scripts = array();

        if(isset($this->_components))
        {
            foreach($this->_components as $thiscomponent)
            {
                $componentscripts = $thiscomponent->GetScripts();
                $scripts = array_merge($scripts,$componentscripts);
            }
        }
        if( isset($this->_scripts))
        {
            $scripts = array_merge($scripts,$this->_scripts);
        }

        return array_unique($scripts);

    }


    public function RegisterComponent( $component )
    {
        $this->_components[] = $component;
    }

    public function RegisterScript( $scriptname )
    {

        $this->_scripts[] = $scriptname;
        $this->_scripts = array_unique($this->_scripts);
    }

    public function RegisterStyleSheet( $sheetname)
    {
        $this->_stylesheets[] = $sheetname;
        $this->_stylesheets = array_unique($this->_stylesheets);
    }


    public function Render()
    {
// No code in here...
    }
}
?>