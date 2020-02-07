<?php

include_once '../../modules/checkpoint/checkpoint_functions.php';


if (isset($_REQUEST['delid'])) {
    deletechk($_REQUEST['delid']);
    header("location: checkpoint.php?id=2");
} else if (isset($_REQUEST['chkId'])) {
    modifychk($_REQUEST);
    header("location: checkpoint.php?id=2");
} else if (isset($_REQUEST['chktype'])) {
    addchktype($_REQUEST);
    header("location: checkpointtype.php?id=2");
} else if (isset($_REQUEST['ctid'])) {
    modifychktype($_REQUEST);
    header("location: checkpointtype.php?id=2");
} else if (isset($_REQUEST['delctid'])) {
    deletechktype($_REQUEST['delctid']);
    header("location: checkpointtype.php?id=2");
} else if (isset($_REQUEST)) {
    addchk($_REQUEST);
    header("location: checkpoint.php?id=2");
}
?>
