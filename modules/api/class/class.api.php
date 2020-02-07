<?php
require_once '../../lib/system/Log.php';

    define("SP_INSERT_BOOKING_DETAILS","insert_booking_details");

    class api{
        var $status;
        var $status_time;
   // construct
            function __construct(){
                    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
            }
            // catch if data is called
            function catchdata($arrj){
                    $insert_sql_array = array();
                    $insert_sql_array['payload']=json_encode($arrj);
                    $this->db->insert(API,$insert_sql_array);
                    $jsonp=array();
                    $jsonp['status']="successful";
                    echo json_encode($jsonp);
            }
            // checks for login
            function check_login($username,$password)
            {
                    $sql = "select * from ".TBL_ADMIN_USER." where username='".$username."'";
                    $record = $this->db->query($sql,__FILE__,__LINE__);
                    $row = $this->db->fetch_array($record);
                    $retarray=array();
                    if($username == $row['username'] and sha1($password) == $row['password'])
                    {
                                    $retarray['status']="successful";
                                    $retarray['userkey']=$row['userkey'];
                                    $retarray['customerno']=$row['customerno'];
                                    $retarray['username']=$row['username'];
                                    $retarray['role']=$row['role'];
                    }else{
                                    $retarray['status']="unsuccessful";
                                    $retarray['userkey']="";
                    }
                    echo json_encode($retarray);
            }


            function device_list($customerno){
                            $sql="select  	devices.*,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
                            $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
                            $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
                            $sql.=" WHERE devices.customerno=".$customerno." and vehicle.isdeleted=0 and  driver.isdeleted=0  ";
                            $record = $this->db->query($sql,__FILE__,__LINE__);
                            $json_p=array();
                            $x=0;
                            while($row = $this->db->fetch_array($record))
                            {
                                    $json_p[$x]['deviceid']=$row['deviceid'];
                                    $json_p[$x]['devicekey']=$row['devicekey'];
                                    $json_p[$x]['devicelat']=$row['devicelat'];
                                    $json_p[$x]['devicelong']=$row['devicelong'];
                                    $json_p[$x]['lastupdated']=$row['lastupdated'];
                                    $json_p[$x]['directionchange']=$row['directionchange'];
                                    $json_p[$x]['inbatt']=$row['inbatt'];
                                    $json_p[$x]['hwv']=$row['hwv'];
                                    $json_p[$x]['swv']=$row['swv'];
                                    $json_p[$x]['devicestatus']=$row['status'];
                                    $json_p[$x]['ignition']=$row['ignition'];
                                    $json_p[$x]['powercut']=$row['powercut'];
                                    $json_p[$x]['tamper']=$row['tamper'];
                                    $json_p[$x]['gpsfixed']=$row['gpsfixed'];
                                    $json_p[$x]['onlineoffline']=$row['online/offline'];
                                    $json_p[$x]['gsmstrength']=$row['gsmstrength'];
                                    $json_p[$x]['gsmregister']=$row['gsmregister'];
                                    $json_p[$x]['gprsregister']=$row['gprsregister'];
                                    $json_p[$x]['aci_status']=$row['aci_status'];
                                    $json_p[$x]['satv']=$row['satv'];
                                    $json_p[$x]['vehicleno']=$row['vehicleno'];
                                    $json_p[$x]['extbatt']=$row['extbatt'];
                                    $json_p[$x]['curspeed']=$row['curspeed'];
                                    $json_p[$x]['odometer']=$row['odometer'];
                                    $json_p[$x]['drivername']=$row['drivername'];
                                    $json_p[$x]['driverphoneno']=$row['driverphoneno'];
                                    $x++;

                            }
                            $arr_p=array();
                            $arr_p['status']="successful";
                            $arr_p['result']=$json_p;

                            echo json_encode($arr_p);
            }
            function api_track($skey,$vehicleno){
                            $skey=stripslashes(trim($skey));
                            $vehicleno=stripslashes(trim($vehicleno));
                            if($skey!="" && isset($skey)){
                                            $sql1="select customerno from ".TBL_ADMIN_USER." where 1 and userkey='".$skey."'";
                                            $result1= $this->db->query($sql1,__FILE__,__LINE__);
                                            $row1= $this->db->fetch_array($result1);

                                            if($row1['customerno']!=""){
                                                            $_SESSION['api_customer_id'] =$row1['customerno'];
                                                            header('Location: map.php?vehicleno='.$vehicleno.'');
                                                    }else{
                                                            echo "Customer does not exist";
                                                    }


                            }else{
                            echo "Request denied";
                            die();

                    }

            }
            function api_device_list($vehicleno){

                            if(isset($_SESSION['api_customer_id']) && $_SESSION['api_customer_id']!="" ){
                                            $sql="select  	devices.*,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
                                            $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
                                            $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
                                            $sql.=" WHERE devices.customerno=".$_SESSION['api_customer_id']." and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
                                            $str=array();

                                            if($vehicleno!="null" )
                                            {
                                            $vehicleno=explode(",",$vehicleno);

                                            $count=count($vehicleno);
                                                    for($i=0;$i<$count;$i++)
                                                    {
                                                            if($i==0){
                                                                    $sql.=" and ";
                                                            }

                                                            if($vehicleno[$i]){

                                                                    $sql.=" vehicleno = '".stripslashes(trim($vehicleno[$i]))."'  ";
                                                                    if($count==1){
                                                                    }else{
                                                                            if($i<($count-1))
                                                                            $sql.=" or  ";
                                                                    }



                                                            }
                                                    }



                                            }

                                            //echo $sql;

                                            $record = $this->db->query($sql,__FILE__,__LINE__);
                                            $json_p=array();
                                            //$json_p="null";
                                            $x=0;

                                            while($row = $this->db->fetch_array($record))
                                            {
                                                    if($row['devicelat']!=0 and $row['devicelong']!=0){

                                                            $json_p[$x]['deviceid']=$row['deviceid'];
                                                            $json_p[$x]['vehicleno']=$row['vehicleno'];
                                                            $json_p[$x]['drivername']=$row['drivername'];
                                                            $json_p[$x]['driverphoneno']=$row['driverphoneno'];
                                                            $json_p[$x]['devicelat']=$row['devicelat'];
                                                            $json_p[$x]['devicelong']=$row['devicelong'];
                                                            $json_p[$x]['lastupdated']=$row['lastupdated'];

                                                            $x++;

                                            }


                                            }
                                            $arr_p=array();
                                            $arr_p['status']="successful";
                                            $arr_p['dcount']=$x;

                                            $arr_p['result']=$json_p;

                                            echo json_encode($arr_p);
                            }else{

                            echo '{"status":"failure"}';

                            }






            }

            function event_history_access_handler($skey,$vehicleno,$startdate,$endtime){

                            $skey=stripslashes(trim($skey));
							$endtime=stripslashes(trim($endtime));
							$startdate=stripslashes(trim($startdate));
                            $vehicleno=stripslashes(trim($vehicleno));
                            if($skey!="" && isset($skey)){

											$sql1="select customerno from ".TBL_ADMIN_USER." where 1 and userkey='".$skey."'";
                                            $result1= $this->db->query($sql1,__FILE__,__LINE__);
                                            $row1= $this->db->fetch_array($result1);
											if($endtime!=""){
                                                                                            $_SESSION['endtime'] = $endtime;
											}else{
												$_SESSION['endtime']= "23:59:59";
											}
											if($startdate!=""){
												$_SESSION['startdate']=strtotime($startdate);
											}else{
												$_SESSION['startdate']=strtotime(date("d-m-y h:i:s"));
											}

                                            if($row1['customerno']!=""){
                                                            $_SESSION['api_customer_id'] =$row1['customerno'];
                                                            $sql="select  	devices.*,unit.unitno,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
                                                            $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
                                                            $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
                                                            $sql.=" WHERE devices.customerno=".$_SESSION['api_customer_id']." and
                                                            vehicle.vehicleno='".$vehicleno."' and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
                                                            $result= $this->db->query($sql,__FILE__,__LINE__);
                                                            $row= $this->db->fetch_array($result);
                                                            $_SESSION['api_unitno'] =$row['unitno'];
                                                            header('Location: eventhistory.php');
                                             }else{
                                                            echo "Customer does not exist";
                                             }

                            }else{
                            echo "Request denied";
                            die();

            }
            }


            function event_history_data($devicekey,$startdate,$enddate){
                $customerno = $_SESSION['api_customer_id'];
                $location = "../../customer/".$_SESSION['api_customer_id']."/unitno/".$_SESSION['api_unitno']."/sqlite/".date("Y-m-d",$_SESSION['startdate']).".sqlite";
               $col_array= array();
                if(file_exists($location))
                {

                    $path = "sqlite:$location";
                    $db_sqllite = new PDO($path);
                        if ($db_sqllite) {

                            $query_sqllite ="SELECT * FROM unithistory WHERE lastupdated between '".date("Y-m-d h:i:s",$_SESSION['startdate'])."' and '".date("Y-m-d",$_SESSION['startdate'])." ".$_SESSION['endtime']."'";
                            $result = $db_sqllite->query($query_sqllite);
                            foreach ($result as $row)
                            {
                                // time patch
                                $this->status_time=$row['lastupdated'];
                                // initialisation of a first row
                                if($c==0){
                                $col_array[$i]['stattime']=$this->status_time;
                                $col_array[$i]['loadstatus']=$row['msgkey'];
                                //location query
                                    $query_sqllite_loc='SELECT * FROM devicehistory WHERE id='.$row['uhid'] ;
                                    $result_loc = $db_sqllite->query($query_sqllite_loc);
                                    if(isset($result_loc))
                                    {
                                        foreach ($result_loc as $row_loc)
                                        {
                                        $col_array[$i]['lat']=$row_loc['devicelat'];
                                        $col_array[$i]['long']=$row_loc['devicelong'];
                                        }
                                    }
                                // location query
                                $this->status=$row['msgkey'];

                                }
                                // counter condition for the first initailisation
                                $c++;
                                // change checks
                                if($row['msgkey']!=$this->status){
                                    $col_array[$i]['endtime']=$this->status_time;

                                    //location query
                                    $query_sqllite_loc='SELECT * FROM devicehistory WHERE id='.$row['uhid'] ;
                                    $result_loc = $db_sqllite->query($query_sqllite_loc);
                                    if(isset($result_loc))
                                    {
                                        foreach ($result_loc as $row_loc)
                                        {
                                        $col_array[$i]['lat']=$row_loc['devicelat'];
                                        $col_array[$i]['long']=$row_loc['devicelong'];
                                        }
                                    }
                                    // location query

                                    // end of row
                                    $i++;
                                    //array end  init
                                    $col_array[$i]['stattime']=$this->status_time;
                                    $col_array[$i]['loadstatus']=$row['msgkey'];
                                    $this->status=$row['msgkey'];
                                }
                            }
                            $col_array[$i]['endtime']=$this->status_time;
                            //location query
                                    $query_sqllite_loc='SELECT * FROM devicehistory WHERE id='.$row['uhid'] ;
                                    $result_loc = $db_sqllite->query($query_sqllite_loc);
                                    if(isset($result_loc))
                                    {
                                        foreach ($result_loc as $row_loc)
                                        {
                                        $col_array[$i]['lat']=$row_loc['devicelat'];
                                        $col_array[$i]['long']=$row_loc['devicelong'];
                                        }
                                    }
                            // location query


                        } else {
                            die($err);
                        }
                }else{

                    echo "file not found";

                }
                ?>
                    <table >
                        <tr>
                            <th>Start Time</th>
                            <th>End time Time</th>
                            <th>Location</th>
                            <th>Start Time</th>
                            <th>Duration </th>
                        </tr>
                        <?php
                        foreach ($col_array as $row_changes)
                        {
                        ?>
                        <tr>
                            <td> <?php echo $row_changes['stattime']; ?></td>
                            <td> <?php echo $row_changes['endtime']; ?></td>
                            <td>
                            <?php if($lat !='0' && $long!='0')
                            {
                                            $API = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$row_changes['lat'].",".$row_changes['long']."";
                                            $location = json_decode(file_get_contents("$API&sensor=false"));
                                            @$address = $location->results[0]->formatted_address;
                                            if($location->status == "OVER_QUERY_LIMIT")
                                            {
                                                @$address = "Temporarily Unavailable";
                                            }
                            }
                             echo $address ;?> </td>
                            <td> <?php if($row_changes['loadstatus']==0){echo "done";}else if($row_changes['loadstatus']==1){ echo "unloading";}else if($row_changes['loadstatus']==2){echo "loading";}  ?></td>
                       <td><?php $diff=(strtotime($row_changes['endtime'])-strtotime($row_changes['stattime'])); $this->dateDifference($diff); ?></td>

                        </tr>
                        <?php
                        }
                        ?>
                    </table>
               <?php


            }
                function dateDifference($diff)
                {
                     $str;


                $years   = floor($diff / (365*60*60*24));
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));

                            $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
                $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
                if($days > 0)
                {
                    $str = $years." years";
                }
                else
                {
                    if($hours > 0)
                    {
                        $str.= $hours." hr ".$minutes." min ";
                    }
                    elseif($minutes > 0)
                    {
                       $str.= $minutes." min ";
                    }
                    else
                    {
                       $str.= $seconds." sec ago";
                    }
                }
                    echo 	$str;
                }


		function event_login_access_handler($skey){
				//$skey=stripslashes(trim($skey));
				if($skey!=""){
				$sql1="select * from ".TBL_ADMIN_USER." where 1 and userkey='".$skey."'";
				$result1= $this->db->query($sql1,__FILE__,__LINE__);
				$row1= $this->db->fetch_array($result1);
					if($row1['customerno']!=""){
						$_SESSION['customerno'] =$row1['customerno'];
						$_SESSION['username'] =$row1['username'];
						$_SESSION['realname'] =$row1['realname'];
						$_SESSION['userid'] =$row1['userid'];
						$_SESSION['Session_UserRole'] =$row1['role'];
						$_SESSION['subdir']="/speed/";
						// Setting session variables
						$_SESSION["customercompany"] = $row1["customercompany"];
						$_SESSION["visits_modal"] = $row1['visited'];
						$_SESSION["role_modal"] = $row1['role'];

						$_SESSION["sessionauth"] = $row1['role'];
						$_SESSION["groupid"] = $row1['groupid'];
						header('Location: http://speed.elixiatech.com/modules/realtimedata/realtimedata.php');




					}else{
						echo "request denied";
					}

				}


		}


		function event_login_access_handler_unsub($skey){
				//$skey=stripslashes(trim($skey));
				if($skey!=""){
				$sql1="select *,user.customerno from ".TBL_ADMIN_USER." INNER JOIN customer ON customer.customerno = user.customerno where 1";
				$result1= $this->db->query($sql1,__FILE__,__LINE__);
                                while($row1= $this->db->fetch_array($result1)){
					if(sha1($row1['userkey']) == $skey){
						$_SESSION['customerno'] =$row1['customerno'];
						$_SESSION['username'] =$row1['username'];
						$_SESSION['realname'] =$row1['realname'];
						$_SESSION['userid'] =$row1['userid'];
						$_SESSION['Session_UserRole'] =$row1['role'];
						//$_SESSION['subdir']="/speed/";
                                                // Setting session variables
                                                $_SESSION["customercompany"] = $row1["customercompany"];
                                                $_SESSION["visits_modal"] = $row1['visited'];
                                                $_SESSION["role_modal"] = $row1['role'];
                                                //$_SESSION['Session_User'] = $user;
                                                $_SESSION["sessionauth"] = $row1['role'];
                                                $_SESSION["groupid"] = $row1['groupid'];
                                                if($row1['use_tracking']==0 && $row1['use_warehouse']==1){
                                                    $_SESSION['switch_to']=3;
                                                }
                                                $log = new Log();
                                                if($log->createlog($_SESSION['customerno'], "Logged In", $_SESSION['userid']))
                                                {

                                                }
						header('Location: http://www.speed.elixiatech.com/modules/user/accinfo.php?id=3');
					}
                                }

				}


		}

		function event_alerts(){
			for($i=0;$i<=19;$i++){
				$sql="Select * from ".TBL_VEHICLE." where isdeleted=0 and customerno=".$i." ";
				$result1= $this->db->query($sql,__FILE__,__LINE__);
						while($row = $this->db->fetch_array($result1))
						{
						$sql2="Select * from ".TBL_IGI." where vehicleid=".$row['vehicleid']." and customerno=".$row['customerno']." ";
						$result12= $this->db->query($sql2,__FILE__,__LINE__);
						$row2 = $this->db->fetch_array($result12);
						if($row2['igalertid']==0){
							$insert_sql_array = array();
							$insert_sql_array['vehicleid'] = $row['vehicleid'] ;
							$insert_sql_array['customerno'] = $row['customerno'] ;
							echo "<pre>";
							print_r($insert_sql_array);
							echo "</pre>";
							$this->db->insert(TBL_IGI,$insert_sql_array);
						}
						}

			}
		}

            ///Inserts booking details in the database
            function insert_booking_details($booking_detail)
            {
                $validateUserKeyQuery = "SELECT customerno, userid FROM " . DATABASE_NAME . ".user WHERE userkey=" . $booking_detail->userkey." and isdeleted=0 ";
                $validateUserKeyResult = $this->db->query($validateUserKeyQuery, __FILE__, __LINE__);
                $row = $this->db->fetch_array($validateUserKeyResult);
                $booking_detail->customerno = $row['customerno'];
                $booking_detail->userid = $row['userid'];
                //Prepare parameters
                $sp_params = "'".$booking_detail->bookingrefno."'";
                $sp_params = $sp_params.","."'".$booking_detail->vehicleno."'";
                $sp_params = $sp_params.",".$booking_detail->tripstatus;
                $sp_params = $sp_params.","."'".$booking_detail->expected_tripstarttime."'";
                $sp_params = $sp_params.",".$booking_detail->customerno;
                $sp_params = $sp_params.",".$booking_detail->userid;
                $sp_params = $sp_params.","."@currentbookingid";

                $queryCallInsertSP = "CALL ".SP_INSERT_BOOKING_DETAILS."($sp_params)";
                //Insert the details
                $this->db->query($queryCallInsertSP,__FILE__,__LINE__);
                $resultBooking = $this->db->query("select @currentbookingid as currentBookingId",__FILE__,__LINE__);
                $arrayBookingId = $this->db->fetch_array($resultBooking);

                $returnStatusArray = array();
                if(!empty($arrayBookingId) &&  $arrayBookingId['currentBookingId'] > 0)
                {
                   $returnStatusArray = array("status"=>"success", "bookingid" => $arrayBookingId['currentBookingId']);
                }
                else
                {
                   $returnStatusArray = array("status"=>"failure");
                }
                return $returnStatusArray;
            }
    }
    ?>
