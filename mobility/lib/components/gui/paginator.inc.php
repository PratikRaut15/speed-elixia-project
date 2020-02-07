<?php
require_once ("components/component.inc.php");

class paginator  extends component {
    //put your code here
    private $_pages = 0;
    private $_currentpage=0;

    public function paginator( $pages, $currentpage )
    {
        parent::__construct();

        $this->_pages = $pages;
        $this->_currentpage = $currentpage;

        $this->RegisterScript( SITE_ROOT. "/js/paginator.js");
        $this->RegisterStyleSheet(SITE_ROOT ."/style/paginator.css");

    }

    public function Render()
    {
        parent::Render();
        // Render each category from the selected category to the root.
        if($this->_pages<=1)
        {
            return;
        }

        echo("<div class='paginator'>");
        $page = $this->_currentpage;

        $startpage = floor(($page / 10)) * 10;
        $endpage = $startpage+ 9;
        $priorpage = $startpage- 10;
        if($priorpage<0)
        {
            $priorpage=0;
        }
        if($startpage>0)
        {
            echo(sprintf("<div class='%s'  onclick='pageclick(%d)'><a href='#' onclick='pageclick(%d)'>Start</a></div>","pagetab",0,0 ));
            echo(sprintf("<div class='%s'  onclick='pageclick(%d)'><a href='#' onclick='pageclick(%d)'>prev..</a></div>","pagetab",$priorpage,$priorpage));
        }
        for($page = $startpage; $page< $this->_pages && $page <=$endpage; $page++)
        {
            if($this->_currentpage == $page)
            {
                $selectedcss="selectedpagetab";
            }
            else
            {
                $selectedcss="pagetab";
            }
            echo(sprintf("<div class='%s'  onclick='pageclick(%d)'><a href='#' onclick='pageclick(%d)'>%s</a></div>",$selectedcss,$page,$page,$page+1 ));
        }
        if($this->_pages > $page)
        {
            echo(sprintf("<div class='%s'  onclick='pageclick(%d)'><a href='#' onclick='pageclick(%d)'>more...</a></div>","pagetab",$page,$page ));
        }

        echo("</div>");


    }
}
?>
