<?php include 'checkpoint_route_functions.php'; ?>

<?php
class VOauto {};

      /*  

       	if(isset($_GET['q']))
        {
           if($_GET['q']!=''){
            $q='%'.$_GET['q'].'%'; 
            }
           $cnt = $_GET['cnt']; 
            
             $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
            $checkpoints = $checkpointmanager->getcheckpointsforcustomer_byId($q);
            if(isset($checkpoints))
            {     
                    echo "<ul class='list'>";
                    foreach ($checkpoints as $checkpoint)
                    {
                            ?>
                             <li onclick="fillchk(<?php echo  $checkpoint->checkpointid; ?>, '<?php echo $checkpoint->cname?>', <?php echo $cnt;?>,<?php echo $checkpoint->cgeolat;?>,<?php echo $checkpoint->cgeolong?>);" value="<?php echo $checkpoint->cname;?>"><?php echo $checkpoint->cname;?></li> 
                            <?php
                    }
                    echo "</ul>";
            }
        }
        
        */


        if(isset($_GET['q']))
        {
            if($_GET['q']!=''){
            $q='%'.$_GET['q'].'%'; 
            }
           $cnt = $_GET['cnt']; 
            
             $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
            $checkpoints = $checkpointmanager->getcheckpointsforcustomer_byId($q);
            if(isset($checkpoints))
            {     
                    $data = array();
                    foreach ($checkpoints as $checkpoint)
                    {
                           ?>
                            <li onclick="fillchk(<?php echo  $checkpoint->checkpointid; ?>, '<?php echo $checkpoint->cname?>', <?php echo $cnt;?>,<?php echo $checkpoint->cgeolat;?>,<?php echo $checkpoint->cgeolong?>);" value="<?php echo $checkpoint->cname;?>"><?php echo $checkpoint->cname;?></li> 
                           <?php
                        
                        /**
                             
                             
                        $chk = new VOauto();
                        $chk->checkpointid = $checkpoint->checkpointid;
                        $chk->cname = $checkpoint->cname;
                        $chk->cnt = $cnt;
                        $chk->cgeolong= $checkpoint->cgeolong;
                        $chk->cgeolat  = $checkpoint->cgeolat;
                        
                        $data[] = $chk;
                             * 
                             */
                        //$list .= $checkpoint->checkpointid."-";
			//$list .= strtolower($checkpoint->cname)."|";
                        //echo $checkpoint->cname."\n";
                        
                    }
                    //$list = substr($list,0,-1);
                    //echo $list;
                    
                   // header('Content-Type: application/json');
                    //echo json_encode($data);
                    //exit();
                    //echo $dataprint;
            }
           
        }
?>