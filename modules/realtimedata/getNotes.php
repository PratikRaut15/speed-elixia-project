<?php
    //  task : "getNotes",
    //  vehicleId : vehicleId,
    //  customerno : customerno
    session_start();
    include_once   '../../lib/bo/VehicleManager.php';
    if(isset($_POST['task']) && $_POST['task'] == 'getNotes'){
        !empty($_POST['vehicleId']) ? $vehicleId = trim($_POST['vehicleId']) : $vehicleId = "";
        !empty($_POST['customerno']) ? $customerno = trim($_POST['customerno']) : $customerno = "";

        if(empty($customerno) || empty($vehicleId)){
            echo "Incomplete Data Send Across";
            exit;
        }else{
           ?>
        
            <div class="row" >
                <div class="container">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 well">
                    <a class="well pull-right Modalclose"><b>X</b></a>
                        <center>
                            <form action="" method="POST" role="form">
                                
                                <legend class="h3">Notes</legend>
                                
                            
                                <div class="form-group purple-border">
                                    <label for="exampleFormControlTextarea4">Enter the Notes</label>
                                    <textarea class="form-control" id="vehicleId_<?php echo $vehicleId;?>"  rows="3"></textarea>
                                </div>

                                <button type="button" id="btnSubmitForNotes" class="btn btn-primary btnSubmitForNotes" customerno = "<?php echo $customerno;?>" vehicleId="<?php echo$vehicleId;?>" >Submit</button>
                            
                            </form>



                            <?php 
                                $vm = new VehicleManager($customerno);
                                $arrData = array();
                                $arrData = $vm->getRecentNotes($customerno,$vehicleId);
                                ?>

                                <div class="notestable">
                                <h3>Recent Notes</h3>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>User</th>
                                                    <th>Notes</th>
                                                    <th>Date</th>
                                                    <!-- <th>UpdatedBy</th> -->
                                                </tr>
                                            </thead>
                                        <tbody class="notesBody" rowCount="<?php echo $rowcount;?>">
                                        
                                     <?php 
                                        if(!empty($arrData)) :
                                            $i = 0;
                                            $rowcount = count($arrData); 
                                         ?>   
                                            
                                            
                                          <?php   foreach($arrData as $key => $val):
                                                $i++;
                                          ?>
                                
                                            <tr class="notesrow">
                                                <td class="notesColumn"><center> <?php echo $i;?> </center></td>
                                                <td><center><?php echo $val['createdBy'];?> </center></td>
                                                <td><center> <?php echo $val['note'];?> </center></td>
                                                <td><center><?php echo $val['updatedOn'];?></center></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </center>
                        
                    </div>
                    <div class="col-md-4">  </div>
                </div>
            </div>  

<?php }
    }


    if(isset($_POST['task']) && $_POST['task'] == 'submitNotes'){
        !empty($_POST['vehicleId']) ? $vehicleId = trim($_POST['vehicleId']) : $vehicleId = "";
        !empty($_POST['customerno']) ? $customerno = trim($_POST['customerno']) : $customerno = "";
        !empty($_POST['notes']) ? $notes = trim($_POST['notes']) : $notes = "";
        if(empty($customerno) || empty($vehicleId) || empty($notes)){
            echo "Please enter All the Fields";
            exit;
        } else{
            
            $vm = new VehicleManager($customerno);
            if(isset($_SESSION['userid'])){
                $userid = $_SESSION['userid'];
            }
            $vm->insertNotes($vehicleId,$customerno,$notes,$userid);
        } 
    }   


?>