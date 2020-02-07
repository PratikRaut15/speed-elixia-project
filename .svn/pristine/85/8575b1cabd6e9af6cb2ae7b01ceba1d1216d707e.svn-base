<?php 
/**
 * This cron is used for upading data in weighBridgeDetails as by consuimg API
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 0);
class cronForWeighBridge
{
    
    function __construct()
    {
        /* Loading manager */
        include_once '../../lib/autoload.php';
        $customerNumberInArray = array(605,617);
        $this->getCustomersWeighBridgeDetails($customerNumberInArray);
    }

    private function getCustomersWeighBridgeDetails($customerNumberInArray)
    {
        $objToggle = new ToggleSwitchManager();
        foreach($customerNumberInArray as $key => $value)
        {
            /**
            * getTransactionIdByCustomerNumber returns array results with keys weighDetailsId,transactionId and customerNo
            */
            $weighDetails = $objToggle->getTransactionIdByCustomerNumber($value);
            $this->getDataByApi($weighDetails);
        }
    }
    private function getDataByApi(array $weighDetails)
    { 
        $values = [];
        $dataArray = json_decode(json_encode($weighDetails), True);
        foreach ($dataArray as $key => $value) {
            $apiData = $this->fetchData($value['transactionId']);
            if(count($apiData) > 0)
            {
                $values[$key] = '('.$value['weighDetailsId'].','.$apiData[0]['Act_Net_Weight'].','.$apiData[0]['Gross_Weight'].','.$apiData[0]['Net_Weight'].','.$apiData[0]['Unladen_Weight'].',"'.$apiData[0]['Updated_by_UL'].'")';
            }
        }
        $implodeValues = implode(',',$values);
        $updateStament = 'INSERT INTO weighBridgeDetails (weighDetailsId, actNetWeight, grossWeight, netWeight, unladenWeight,updatedByUL)
                          VALUES '.$implodeValues.'
                          ON DUPLICATE KEY UPDATE 
                          actNetWeight = VALUES(actNetWeight),
                          grossWeight = VALUES(grossWeight),
                          netWeight = VALUES(netWeight),
                          unladenWeight = VALUES(unladenWeight),
                          updatedByUL = VALUES(updatedByUL)'; 
        $this->updateDataByBulkQuery($updateStament);                  
    }
    private function fetchData($slipNo)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_PORT => "8082",
          CURLOPT_URL => "http://59.163.51.209:8082/vtmsservicev2/NotificationService.svc/GetSlipNoData?SlipNo=".$slipNo."&Location=WEH",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 100,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return json_decode($response,true);
        }
    }
    private function updateDataByBulkQuery($queryStatment)
    {
        $objToggle = new ToggleSwitchManager();
        $objToggle->updateDataFetchedByApi($queryStatment);
    }

}

$cronObject = new cronForWeighBridge();
?>