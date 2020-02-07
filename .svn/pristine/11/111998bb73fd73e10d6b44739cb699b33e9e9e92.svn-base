<?php
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
$permit = 0777;
$objCustomerManager = new CustomerManager();
$arrCustomers = array();
$arrCustomers = $objCustomerManager->getcustomerdetail();

if (isset($arrCustomers) && !empty($arrCustomers)) {
    foreach ($arrCustomers as $customer) {
        $customerNo = $customer->customerno;

        $relativepath = "../..";
        if (!is_dir($relativepath . '/customer/' . $customerNo)) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerNo, 0777, true) or die("Could not create customoer directory");
            chmod($relativepath . '/customer/' . $customerNo, $permit);
        }
        if (!is_dir($relativepath . '/customer/' . $customerNo . '/unitno')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerNo . '/unitno', 0777, true) or die("Could not create unit directory");
            chmod($relativepath . '/customer/' . $customerNo . '/unitno', $permit);
        }
        if (!is_dir($relativepath . '/customer/' . $customerNo . '/reports')) {
            // Directory doesn't exist.
            mkdir($relativepath . '/customer/' . $customerNo . '/reports', 0777, true) or die("Could not create reports directory");
            chmod($relativepath . '/customer/' . $customerNo . '/reports', $permit);
        }

        $objUnitManager = new UnitManager($customerNo);
        $arrUnits = $objUnitManager->getunits();
        if (isset($arrUnits) && !empty($arrUnits)) {
            foreach ($arrUnits as $unit) {
                $unitNo = $unit->unitno;
                $unitPath = $relativepath . '/customer/' . $customerNo. '/unitno';

                if (!is_dir($unitPath . '/' . $unitNo)) {
                    // Directory doesn't exist.
                    mkdir($unitPath . '/' . $unitNo, 0777, true) or die("Could not create ".$unitNo." directory");
                    chmod($unitPath . '/' . $unitNo, $permit);
                }
                $unitNoPath = $unitPath . '/' . $unitNo;
                if (!is_dir($unitNoPath . '/' . 'sqlite')) {
                    // Directory doesn't exist.
                    mkdir($unitNoPath . '/' . 'sqlite', 0777, true) or die("Could not create ".$unitNo."/sqlite directory");
                    chmod($unitNoPath . '/' . 'sqlite', $permit);
                }

            }
        }

        die();
    }
}

?>
