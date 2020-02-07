<?php
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
            //find location
            function location($lat,$long,$customerno,$usegeolocation)
            {
                $address = NULL;
                if($lat !='0' && $long!='0')
                {
                    if($usegeolocation==1)
                    {
                        $API = "http://www.speed.elixiatech.com/location.php?lat=".$lat."&long=".$long."";
                        $location = json_decode(file_get_contents("$API&sensor=false"));
                        @$address = "Near ".$location->results[0]->formatted_address;
                                            if($location->results[0]->formatted_address==""){
                                                    $GeoCoder_Obj = new GeoCoder($customerno);
                                                    $address=$GeoCoder_Obj->get_location_bylatlong($lat,$long);
                                            }
                    }
                    else
                    {
                        $address=$this->get_location_bylatlong($lat,$long,$customerno);
                    }
                }
                return $address;
            }

            public function get_location_bylatlong($lat,$long,$customerno)
            {
                $latint = floor($lat);
                $longint = floor($long);

                $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = ".$latint." AND `longfloor` = ".$longint." HAVING distance <2 AND customerno = ".$customerno." ORDER BY distance LIMIT 0,1 ";
                $record = $this->db->query($geoloc_query,__FILE__,__LINE__);
                $record_counts = $this->db->query($geoloc_query,__FILE__,__LINE__);
                $row_count = $this->db->num_rows($record_counts);
                if ($row_count > 0)
                {
                    while ($row = $this->db->fetch_array($record))
                    {
                        if($row['distance']>1 ){
                                                $location_string = round($row['distance'], 2)." Km from ".$row['location'].", ".$row['city'].", ".$row['state'];
                                        }else{
                                                $location_string= "Near ".$row['location'].", ".$row['city'].", ".$row['state'];
                                        }

                    }
                    return $location_string;
                }
                else{

                    $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( ".$lat."- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( ".$lat." * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( ".$long." - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = ".$latint." AND `longfloor` = ".$longint." HAVING distance <10 ORDER BY distance LIMIT 0,1 ";
                    $records = $this->db->query($geolocation_query,__FILE__,__LINE__);
                $record_countss = $this->db->query($geolocation_query,__FILE__,__LINE__);
                $row_counts = $this->db->num_rows($record_countss);
                    if ($row_counts > 0)
                    {
                        while ($row = $this->db->fetch_array($records))
                        {
                            if($row['distance']>1 ){
                                                    $location_string = round($row['distance'], 2)." Km from ".$row['location'].", ".$row['city'].", ".$row['state'];
                                            }else{
                                                    $location_string= "Near ".$row['location'].", ".$row['city'].", ".$row['state'];
                                            }

                        }
                        return $location_string;
                    }else{
                                    return "google temporarily down";
                            }
                    return null;
                }
            }
            //calculate distance
            function distance($customerno,$unitno)
            {
                $date = date('Y-m-d');
                $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                $Data = $this->odometerfromSqlite($location);
                if($Data!=0)
                {
                    $lastodometer = $Data['last'];
                    $firstodometer = $Data['first'];
                    $distance = $lastodometer / 1000 - $firstodometer / 1000;
                    $distancekm = round($distance, 2);
                }
                else{
                    $distancekm = 0;
                }
                return $distancekm;

                 //$location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            }

            function odometerfromSqlite($location)
            {
                try
                {
                    $DRMS = array();
                    $DRMS['first'] = 0;
                    $DRMS['last'] = 0;
                    if(file_exists($location))
                    {
                        $path = "sqlite:$location";
                        $dbs = new PDO($path);
                        //$query = "SELECT * from vehiclehistory ORDER BY vehiclehistory.lastupdated desc LIMIT 0,1";
                        $Query = "SELECT (SELECT odometer FROM vehiclehistory ORDER BY odometer LIMIT 1) as 'first',(SELECT odometer FROM vehiclehistory ORDER BY odometer DESC LIMIT 1) as 'last'";
                        $sobj = $dbs->prepare($Query);
                        $sobj->execute();

                        /* Fetch all of the remaining rows in the result set */

                        $result = $sobj->fetchAll();
                        $DRMS['first'] = $result[0]['first'];
                        $DRMS['last'] = $result[0]['last'];

                    }
                }
                catch(PDOException $e)
                {
                    $e->getMessage();
                }
                return $DRMS;
            }
            // difference in time
            function getduration($EndTime, $StartTime)
            {
//                echo $EndTime.'_'.$StartTime.'<br>';
                $idleduration = strtotime($EndTime) - strtotime($StartTime);
                $years = floor($idleduration / (365 * 60 * 60 * 24));
                $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                if($years>0 || $months>0){
                    $diff = date('Y-m-d', strtotime($StartTime));
                }
                else if($days>0){
                    $diff = $days.' Days '.$hours.' hrs and '.$minutes.' mins';
                }
                else if($hours>0){
                    $diff = $hours.' hrs and '.$minutes.' mins';
                }
                else{
                    $diff = $minutes.' mins';
                }
                return $diff;
            }
            // checks for login
            function check_login($username,$password)
            {
                    $sql = "select *,".TBL_ADMIN_CUSTOMER.".customerno as customer_no, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = ".TBL_ADMIN_USER.".userid
                            ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel from ".TBL_ADMIN_USER." INNER JOIN ".TBL_ADMIN_CUSTOMER." ON ".TBL_ADMIN_CUSTOMER.".customerno = ".TBL_ADMIN_USER.".customerno INNER JOIN `android_version` where username='".$username."'";
                    $record = $this->db->query($sql,__FILE__,__LINE__);
                    $row = $this->db->fetch_array($record);
                    $retarray=array();
                    if($username == $row['username'] and $password == $row['password'] AND $row['grpdel'] != 1)
                    {
                                    $retarray['status']="successful";
                                    $retarray['userkey']=$row['userkey'];
                                    $retarray['customerno']=$row['customer_no'];
                                    $retarray['username']=$row['username'];
                                    $retarray['customername']=$row['customercompany'];
                                    $retarray['version']=$row['version'];
                                    $retarray['role']=$row['role'];
                    $today = date("Y-m-d H:i:s");
                    $sql ="UPDATE user SET lastlogin_android='".$today."' where userkey = '".$row['userkey']."' AND customerno= '".$row['customer_no']."' LIMIT 1";
                    $this->db->query($sql,__FILE__,__LINE__);
                    }else{
                                    $retarray['status']="failure";
                                    $retarray['version']='';
                                    $retarray['customername']=null;
                                    $retarray['userkey']=0;
                    }
                    echo json_encode($retarray);
					return $retarray;
            }

			function check_userkey($userkey){
			$sql = "select * from ".TBL_ADMIN_USER." where userkey='".$userkey."'";
                    $record = $this->db->query($sql,__FILE__,__LINE__);
                    $row = $this->db->fetch_array($record);
                    $retarray=array();
                    if($row['userkey']!="")
                    {
                                    $retarray['status']="successful";
                                    $retarray['customerno'] = $row["customerno"];
                    }else{
                                    $retarray['status']="unsuccessful";

                    }
                    return $retarray;
			}
            function device_list($userkey){

			$validation=$this->check_userkey($userkey);
			$arr_p=array();
			$arr_p['status']="unsuccessful";

					if($validation['status']=="successful")
					{
					// successful
                                        $customerno = $validation["customerno"];
                                       $groupidsql = "SELECT * ,groupman.isdeleted as gmdel,groupman.groupid as gmgrpid, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = user.userid
                                                ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel FROM user
                                                LEFT OUTER JOIN groupman ON groupman.userid = user.userid
                                                WHERE user.userkey =$userkey
                                                ORDER BY groupman.gmid DESC";
                                        $recordgrp = $this->db->query($groupidsql,__FILE__,__LINE__);
						$groupids=array();
						while($rowgrp = $this->db->fetch_array($recordgrp))
						{
                                                    if($rowgrp['gmdel']==0){
                                                        $groupid = $rowgrp['gmgrpid'];
                                                        $groupids[] = $groupid;
                                                    }
                                                }
                                                $firstgroup = array_shift(array_values($groupids));

                                                $sql = "SELECT vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                    devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid <>10
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' ORDER BY devices.lastupdated DESC";
						$record = $this->db->query($sql,__FILE__,__LINE__);
						$json_p=array();
						$x=0;
						while($row = $this->db->fetch_array($record))
						{
                                                    if($firstgroup == ''){
								$json_p[$x]['vehicleid']=$row['vehicleid'];
								$json_p[$x]['vehicleno']=$row['vehicleno'];
								$json_p[$x]['location']=$this->location($row['devicelat'],$row['devicelong'],$row['customer_no'], $row['use_geolocation']);
								$json_p[$x]['unitno']=$row['unitno'];
								$json_p[$x]['lastupdated']=$row['lastupdated'];
								$json_p[$x]['drivername']=$row['drivername'];
								$json_p[$x]['driverphone']=$row['driverphone'];
								$json_p[$x]['ignition']=$row['ignition'];
								$json_p[$x]['curspeed']=$row['curspeed'];
								$json_p[$x]['distance']=$this->distance($row['customer_no'],$row['unitno']);
                                                                if($row['acsensor'] == 1)
                                                                {
                                                                    if($row['digitalio'] == 0)
                                                                    {
                                                                        if($row["is_ac_opp"] == 0)
                                                                        {
                                                                            $json_p[$x]['acstatus']= "1";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p[$x]['acstatus']= "0";
                                                                        }
                                                                    }
                                                                    else
                                                                        if($row["is_ac_opp"] == 0)
                                                                        {
                                                                            $json_p[$x]['acstatus']= "0";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p[$x]['acstatus']= "1";
                                                                        }
                                                                }
                                                                else{
                                                                    $json_p[$x]['acstatus']= "-1";
                                                                }
                                                                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                                                                if($row["stoppage_flag"] == '1')
                                                                {
                                                                $json_p[$x]['ignstatus']= "Running since $diff";
                                                                }
                                                                else if($row["stoppage_flag"] == '0')
                                                                {
                                                                $json_p[$x]['ignstatus']= "Idle since $diff";
                                                                }
                                                                $json_p[$x]['extbatt'] = round($row['extbatt']/100,2);
                                                                $json_p[$x]['inbatt'] = $row['inbatt']/1000;
                                                                $json_p[$x]['tamper'] = $row['tamper'];
                                                                $json_p[$x]['powercut'] = $row['powercut'];
                                                                $json_p[$x]['gsmstrength'] = round($row['gsmstrength']/31*100);
								$json_p[$x]['devicelat']=$row['devicelat'];
								$json_p[$x]['devicelong']=$row['devicelong'];
								$json_p[$x]['digital']=$row['digital'];
								$json_p[$x]['stoppage_flag']=$row['stoppage_flag'];
								$json_p[$x]['overspeed_limit']=$row['overspeed_limit'];
								$x++;
                                                    }
                                                    else if(in_array($row['veh_grpid'], $groupids)){
								$json_p[$x]['vehicleid']=$row['vehicleid'];
								$json_p[$x]['vehicleno']=$row['vehicleno'];
								$json_p[$x]['location']=$this->location($row['devicelat'],$row['devicelong'],$row['customer_no'],$row['use_geolocation']);
								$json_p[$x]['unitno']=$row['unitno'];
								$json_p[$x]['lastupdated']=$row['lastupdated'];
								$json_p[$x]['drivername']=$row['drivername'];
								$json_p[$x]['driverphone']=$row['driverphone'];
								$json_p[$x]['ignition']=$row['ignition'];
								$json_p[$x]['curspeed']=$row['curspeed'];
								$json_p[$x]['stoppage_flag']=$row['stoppage_flag'];
								$json_p[$x]['distance']=$this->distance($row['customer_no'],$row['unitno']);
                                                                if($row['acsensor'] == '1')
                                                                {
                                                                    if($row['digitalio'] == '0')
                                                                    {
                                                                        if($row["is_ac_opp"] == '0')
                                                                        {
                                                                            $json_p[$x]['acstatus']= "1";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p[$x]['acstatus']= "0";
                                                                        }
                                                                    }
                                                                    else
                                                                        if($row["is_ac_opp"] == '0')
                                                                        {
                                                                            $json_p[$x]['acstatus']= "0";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p[$x]['acstatus']= "1";
                                                                        }
                                                                }
                                                                else{
                                                                    $json_p[$x]['acstatus']= "-1";
                                                                }
                                                                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                                                                if($row["stoppage_flag"] == '1')
                                                                {
                                                                $json_p[$x]['ignstatus']= "Running since $diff";
                                                                }
                                                                else if($row["stoppage_flag"] == '0')
                                                                {
                                                                $json_p[$x]['ignstatus']= "Idle since $diff";
                                                                }
                                                                $json_p[$x]['extbatt'] = round($row['extbatt']/100,2);
                                                                $json_p[$x]['inbatt'] = $row['inbatt']/1000;
                                                                $json_p[$x]['tamper'] = $row['tamper'];
                                                                $json_p[$x]['powercut'] = $row['powercut'];
                                                                $json_p[$x]['gsmstrength'] = round($row['gsmstrength']/31*100);
								$json_p[$x]['devicelat']=$row['devicelat'];
								$json_p[$x]['devicelong']=$row['devicelong'];
								$json_p[$x]['digital']=$row['digital'];
								$json_p[$x]['overspeed_limit']=$row['overspeed_limit'];
								$x++;
                                                    }
						}

							$arr_p['status']="successful";
							$arr_p['result']=$json_p;


					}else{
							$arr_p['status']="unsuccessful";

					}

                 echo json_encode($arr_p);
                 return json_encode($arr_p);


            }

            function device_list_details($userkey,$vehicleid){

			$validation=$this->check_userkey($userkey);
			$arr_p=array();
			$arr_p['status']="unsuccessful";

					if($validation['status']=="successful")
					{
					// successful
                                        $customerno = $validation["customerno"];
                                                $sql = "SELECT vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                    devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid <>10
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' AND vehicle.vehicleid = $vehicleid ORDER BY devices.lastupdated DESC";
						$record = $this->db->query($sql,__FILE__,__LINE__);
						$json_p=array();
						while($row = $this->db->fetch_array($record))
						{
								$json_p['vehicleid']=$row['vehicleid'];
								$json_p['vehicleno']=$row['vehicleno'];
								$json_p['location']=$this->location($row['devicelat'],$row['devicelong'],$row['customer_no'], $row['use_geolocation']);
								$json_p['unitno']=$row['unitno'];
								$json_p['lastupdated']=$row['lastupdated'];
								$json_p['drivername']=$row['drivername'];
								$json_p['driverphone']=$row['driverphone'];
								$json_p['ignition']=$row['ignition'];
								$json_p['curspeed']=$row['curspeed'];
								$json_p['distance']=$this->distance($row['customer_no'],$row['unitno']);
                                                                if($row['acsensor'] == 1)
                                                                {
                                                                    if($row['digitalio'] == 0)
                                                                    {
                                                                        if($row["is_ac_opp"] == 0)
                                                                        {
                                                                            $json_p['acstatus']= "1";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p['acstatus']= "0";
                                                                        }
                                                                    }
                                                                    else
                                                                        if($row["is_ac_opp"] == 0)
                                                                        {
                                                                            $json_p['acstatus']= "0";
                                                                        }
                                                                        else
                                                                        {
                                                                            $json_p['acstatus']= "1";
                                                                        }
                                                                }
                                                                else{
                                                                    $json_p['acstatus']= "-1";
                                                                }
                                                                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                                                                if($row["stoppage_flag"] == '1')
                                                                {
                                                                $json_p['ignstatus']= "Running since $diff";
                                                                }
                                                                else if($row["stoppage_flag"] == '0')
                                                                {
                                                                $json_p['ignstatus']= "Idle since $diff";
                                                                }
                                                                $json_p['extbatt'] = round($row['extbatt']/100,2);
                                                                $json_p['inbatt'] = $row['inbatt']/1000;
                                                                $json_p['tamper'] = $row['tamper'];
                                                                $json_p['powercut'] = $row['powercut'];
                                                                $json_p['gsmstrength'] = round($row['gsmstrength']/31*100);
								$json_p['devicelat']=$row['devicelat'];
								$json_p['devicelong']=$row['devicelong'];
								$json_p['digital']=$row['digital'];
								$json_p['stoppage_flag']=$row['stoppage_flag'];
								$json_p['overspeed_limit']=$row['overspeed_limit'];
						}

							$arr_p['status']="successful";
							$arr_p['result']=$json_p;


					}else{
							$arr_p['status']="unsuccessful";

					}

                 echo json_encode($arr_p);
                 return json_encode($arr_p);


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
						$_SESSION['subdir']="/speed/";
                                                // Setting session variables
                                                $_SESSION["customercompany"] = $row1["customercompany"];
                                                $_SESSION["visits_modal"] = $row1['visited'];
                                                $_SESSION["role_modal"] = $row1['role'];
                                                //$_SESSION['Session_User'] = $user;
                                                $_SESSION["sessionauth"] = $row1['role'];
                                                $_SESSION["groupid"] = $row1['groupid'];

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

    }
    ?>
