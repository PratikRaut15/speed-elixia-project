<?php
function createtabs($list,$pageid=null,$pagename)
{
    $html[0] = "<ul id='tabnav'>";
    $counter = 0;
    foreach ($list as $item)
    {
        if($pageid==NULL & isset($counter) && $counter==0)
        {
            if($item['ext_id_value']!=NULL)
            {
                $html[0] .= genitem('class="selected"',$pagename,$item['id'],$item['name'],$item['ext_id_name'],$item['ext_id_value'],$item['role']);
            }
            else
            {
                $html[0] .= genitem('class="selected"',$pagename,$item['id'],$item['name'],NULL,NULL,$item['role']);
            }
            foreach ($item['include_pages'] as $inc_page)
            {
                $html[$counter+1]=$inc_page;
                $counter+=1;
            }
        }
        else if($pageid==$item['id'])
        {
            if($item['ext_id_value']!=NULL)
            {
                $html[0] .= genitem('class="selected"',$pagename,$item['id'],$item['name'],$item['ext_id_name'],$item['ext_id_value'],$item['role']);
                
            }
            else
            {
                $html[0] .= genitem('class="selected"',$pagename,$item['id'],$item['name'],NULL,NULL,$item['role']);
            }
            foreach ($item['include_pages'] as $inc_page)
            {
                $html[$counter+1]=$inc_page;
                $counter+=1;
            }
            
        }
        else
        {
            $html[0] .= genitem(NULL,$pagename,$item['id'],$item['name'],NULL,NULL,$item['role']);
        }
    }
    $html[0] .='</ul>';
    return $html;
}
function genitem($class=NULL,$pagename,$id,$name,$ext_id=NULL,$ext_id_value=NULL,$role)
{
    $tab='';
    $userrole = $_SESSION['Session_UserRole'];
    $link = $pagename.'.php?id='.$id;
    if(isset($role) && $role==$userrole)
    {
        $tab = "<li><a $class href='$link'>$name</a></li>";
        if($class!=NULL)
        {
            if($ext_id!=NULL)
            {
                $link = "$pagename.php?id=$id&$ext_id=$ext_id_value";
                $tab = "<li><a $class href='$link'>$name</a></li>";
            }
        }
    }
    else
    {
        $tab = "<li><a $class href='$link'>$name</a></li>";
        if($class!=NULL)
        {
            if($ext_id!=NULL)
            {
                $link = "$pagename.php?id=$id&$ext_id=$ext_id_value";
                $tab = "<li><a $class href='$link'>$name</a></li>";
            }
        }
    }
    return $tab;
}
?>
