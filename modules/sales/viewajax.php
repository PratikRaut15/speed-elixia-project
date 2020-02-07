<?php

include_once 'sales_function.php';
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
// SQL server connection information
$sql_details = array(
    'user' => DB_Service_user,
    'pass' => DB_Service_pass,
    'db' => DB_Secsales,
    'host' => 'localhost'
);

$customerno = exit_issetor($_SESSION['customerno']);
$of = retval_issetor($_GET['of']);
$join = '';
$groupby = '';
$customWhere = '';
$action = Null;
if ($of == 'viewcategory') {
    $table = 'category as a';
    $primaryKey = 'categoryid';
    $columns = array(
        array('db' => 'a.categoryname', 'dt' => 0),
        array('db' => 'a.categoryid', 'dt' => 1,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=catedit&catid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.categoryid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletecategory($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewprimarysales') {
    if ($_SESSION['role_modal'] == "sales_representative") {
        $table = 'primary_order as a';
        $join = " inner join " . SPEEDDB . ".user as u on u.userid = a.srid ";
        $customWhere .= " a.srid =" . $_SESSION['userid'] . " AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'prid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'a.deliverydate', 'dt' => 1,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($d) {
                    $st = '';
                    if ($d == 0) {
                        $st = 'SR';
                    } else if ($d == 1) {
                        $st = 'Supervisor';
                    } else if ($d == 2) {
                        $st = 'ASM';
                    }


                    $view = $st;
                    return $view;
                }),
            array('db' => 'a.is_approved', 'dt' => 3,
                'formatter' => function($d) {
                    if ($d == '1') {
                        $status = "Approved";
                    }
                    if ($d == '0') {
                        $status = "Pending";
                    }
                    if ($d == '-1') {
                        $status = "Reject";
                    }
                    $view = $status;
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 4,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=editprisales&prid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 5,
                'formatter' => function($d) {
                    $view = "<a href='javascript:void(0);'><img src='../../images/delete1.png' onclick='delete_primaryorder(" . $d . ");'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == "Supervisor") {
        $srdata = get_salespersons_by_supervisor($_SESSION['userid'], $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = 'primary_order as a';
        $join = " inner join " . SPEEDDB . ".user as u on u.userid = a.srid  ";
        $customWhere .= " a.srid IN (" . $ids . ")  AND a.is_approved<>-1 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'prid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'a.deliverydate', 'dt' => 1,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($d) {
                    $st = '';
                    if ($d == 0) {
                        $st = 'SR';
                    } else if ($d == 1) {
                        $st = 'Supervisor';
                    } else if ($d == 2) {
                        $st = 'ASM';
                    }
                    $view = $st;
                    return $view;
                }),
            array('db' => 'a.is_approved', 'dt' => 3,
                'formatter' => function($d) {
                    if ($d == '1') {
                        $status = "Approved";
                    }
                    if ($d == '0') {
                        $status = "Pending";
                    }
                    if ($d == '-1') {
                        $status = "Reject";
                    }
                    $view = $status;
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 4,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Approve' onclick='approved_stock($e)'  class='btn btn-success'> Approve </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 5,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Reject' onclick='reject_stock($e)'  class='btn btn-danger'> Reject </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 6,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=editprisales&prid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == "ASM") {
        $supdata = get_supervisors_by_asm($_SESSION['userid'], $_SESSION['customerno']);
        $supid = array();
        foreach ($supdata as $row) {
            $supid[] = $row->userid;
        }
        $supids = implode(',', $supid);

        $srdata = get_sr_by_supervisors($_SESSION['userid'], $supids, $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = 'primary_order as a';
        $join = " inner join " . SPEEDDB . ".user as u on u.userid = a.srid  ";
        $customWhere .= " a.srid IN (" . $ids . ")  AND a.is_approved<>-1 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'prid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'a.deliverydate', 'dt' => 1,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($d) {
                    $st = '';
                    if ($d == 0) {
                        $st = 'SR';
                    } else if ($d == 1) {
                        $st = 'Supervisor';
                    } else if ($d == 2) {
                        $st = 'ASM';
                    }
                    $view = $st;
                    return $view;
                }),
            array('db' => 'a.is_approved', 'dt' => 3,
                'formatter' => function($d) {
                    if ($d == '1') {
                        $status = "Approved";
                    }
                    if ($d == '0') {
                        $status = "Pending";
                    }
                    if ($d == '-1') {
                        $status = "Reject";
                    }
                    $view = $status;
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 4,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Approve' onclick='approved_stock($e)'  class='btn btn-success'> Approve </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 5,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Reject' onclick='reject_stock($e)'  class='btn btn-danger'> Reject </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 6,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=editprisales&prid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else {
        $table = 'primary_order as a';
        $join = " inner join " . SPEEDDB . ".user as u on u.userid = a.srid  ";
        $customWhere .= " a.is_approved<>-1 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND   ";
        $primaryKey = 'prid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'a.deliverydate', 'dt' => 1,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($d) {
                    $st = '';
                    if ($d == 0) {
                        $st = 'SR';
                    } else if ($d == 1) {
                        $st = 'Supervisor';
                    } else if ($d == 2) {
                        $st = 'ASM';
                    }
                    $view = $st;
                    return $view;
                }),
            array('db' => 'a.is_approved', 'dt' => 3,
                'formatter' => function($d) {
                    if ($d == '1') {
                        $status = "Approved";
                    }
                    if ($d == '0') {
                        $status = "Pending";
                    }
                    if ($d == '-1') {
                        $status = "Reject";
                    }
                    $view = $status;
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 4,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Approve' onclick='approved_stock($e)'  class='btn btn-success'> Approve </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 5,
                'formatter' => function($e, $row) {
                    $status = $row['is_approved'];
                    if ($status == 0) {
                        $view = "<a style='width:auto;' style='display: inline-flex;' href = 'javascript:void(0);' title='Reject' onclick='reject_stock($e)'  class='btn btn-danger'> Reject </a>";
                    } else {
                        $view = "";
                    }
                    return $view;
                }),
            array('db' => 'a.prid', 'dt' => 6,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=editprisales&prid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    }
} else if ($of == 'viewdeadstock') {
    if ($_SESSION['role_modal'] == "sales_representative") {
        $table = 'deadstock as a';
        $join = "left join shop as sh on sh.shopid = a.shopid left join area as ar on ar.areaid = sh.areaid left join " . SPEEDDB . ".user as distributor on distributor.userid = sh.distributorid  inner join " . SPEEDDB . ".user as u on u.userid = a.srid ";
        $customWhere .= " a.srid =" . $_SESSION['userid'] . "  AND is_asm=0 AND  a.entrytime >= DATE(NOW()) - INTERVAL 7 DAY AND    ";
        $primaryKey = 'stockid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'ar.areaname', 'dt' => 1),
            array('db' => 'sh.shopname', 'dt' => 2),
            array('db' => 'a.stockdate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
//            array('db' => 'a.reason', 'dt' => 4),
            array('db' => 'a.stockid', 'dt' => 4,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=stockedit&stockid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                }),
        );
    } else if ($_SESSION['role_modal'] == "Supervisor") {
        $srdata = get_salespersons_by_supervisor($_SESSION['userid'], $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = 'deadstock as a';
        $join = "left join shop as sh on sh.shopid = a.shopid left join area as ar on ar.areaid = sh.areaid left join " . SPEEDDB . ".user as distributor on distributor.userid = sh.distributorid  inner join " . SPEEDDB . ".user as u on u.userid = a.srid";
        $customWhere .= " a.srid IN (" . $ids . ") AND  a.entrytime >= DATE(NOW()) - INTERVAL 7 DAY AND  ";
        $primaryKey = 'stockid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'ar.areaname', 'dt' => 1),
            array('db' => 'sh.shopname', 'dt' => 2),
            array('db' => 'a.stockdate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
//            array('db' => 'a.reason', 'dt' => 4),
            array('db' => 'a.stockid', 'dt' => 4,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=stockedit&stockid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                }),
        );
    } else if ($_SESSION['role_modal'] == "ASM") {
        $supdata = get_supervisors_by_asm($_SESSION['userid'], $_SESSION['customerno']);
        $supid = array();
        foreach ($supdata as $row) {
            $supid[] = $row->userid;
        }
        $supids = implode(',', $supid);

        $srdata = get_sr_by_supervisors($_SESSION['userid'], $supids, $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = 'deadstock as a';
        $join = "left join shop as sh on sh.shopid = a.shopid left join area as ar on ar.areaid = sh.areaid left join " . SPEEDDB . ".user as distributor on distributor.userid = sh.distributorid  inner join " . SPEEDDB . ".user as u on u.userid = a.srid";
        $customWhere .= " a.srid IN (" . $ids . ") AND  a.entrytime >= DATE(NOW()) - INTERVAL 7 DAY AND  ";
        $primaryKey = 'stockid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'ar.areaname', 'dt' => 1),
            array('db' => 'sh.shopname', 'dt' => 2),
            array('db' => 'a.stockdate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
//            array('db' => 'a.reason', 'dt' => 4),
            array('db' => 'a.stockid', 'dt' => 4,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=stockedit&stockid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                }),
        );
    } else {
        $table = 'deadstock as a';
        $join = "left join shop as sh on sh.shopid = a.shopid left join area as ar on ar.areaid = sh.areaid left join " . SPEEDDB . ".user as distributor on distributor.userid = sh.distributorid  inner join " . SPEEDDB . ".user as u on u.userid = a.srid";
        $customWhere .= " a.entrytime >= DATE(NOW()) - INTERVAL 7 DAY AND  ";
        $primaryKey = 'stockid';
        $groupby = " group by a.stockid";
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'ar.areaname', 'dt' => 1),
            array('db' => 'sh.shopname', 'dt' => 2),
            array('db' => 'a.stockdate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
//            array('db' => 'a.reason', 'dt' => 4),
            array('db' => 'a.stockid', 'dt' => 4,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=stockedit&stockid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                }),
        );
    }
} else if ($of == 'viewentry') {
    if ($_SESSION['role_modal'] == "sales_representative") {
        $table = 'entry as a';
        $join = "left join shop as sh on sh.shopid = a.shopid inner join " . SPEEDDB . ".user as u on u.userid = a.salesid ";
        $customWhere .= " a.salesid =" . $_SESSION['userid'] . "  AND is_asm=0 AND ";
        $primaryKey = 'entryid';
        $groupby = " group by a.entryid";
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($e) {
                    $view = ($e == 1 ? 'ASM' : 'SALES');
                    return $view;
                }),
            //array('db' => 'a.remark', 'dt' => 3)
            array('db' => 'a.entrydate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == "ASM") {
        $salesdata = get_asm_salespersons($_SESSION['userid'], $_SESSION['customerno']);
        $userid = array();
        foreach ($salesdata as $row) {
            $userid[] = $row->userid;
        }
        $ids = implode(',', $userid);
        $table = 'entry as a';
        $join = "left join shop as sh on sh.shopid = a.shopid inner join " . SPEEDDB . ".user as u on u.userid = a.salesid ";
        $customWhere .= " a.salesid IN (" . $ids . ") AND ";
        $primaryKey = 'entryid';
        $groupby = " group by a.entryid";
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($e) {
                    $view = ($e == 1 ? 'ASM' : 'SALES');
                    return $view;
                }),
            // array('db' => 'a.remark', 'dt' => 3)
            array('db' => 'a.entrydate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                })
        );
    } else {
        $table = 'entry as a';
        $join = "left join shop as sh on sh.shopid = a.shopid inner join " . SPEEDDB . ".user as u on u.userid = a.salesid ";
        $primaryKey = 'entryid';
        $groupby = " group by a.entryid";
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.is_asm', 'dt' => 2,
                'formatter' => function($e) {
                    $view = ($e == 1 ? 'ASM' : 'SALES');
                    return $view;
                }),
            //array('db' => 'a.remark', 'dt' => 3)
            array('db' => 'a.entrydate', 'dt' => 3,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                })
        );
    }
} else if ($of == 'vieworders') {
    if ($_SESSION['role_modal'] == "sales_representative") {
        //sales
        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby =" . $_SESSION['userid'] . " AND a.orderStatus = 0 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == 'Supervisor') {
        $srdata = get_salespersons_by_supervisor($_SESSION['userid'], $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby IN (" . $ids . ") AND a.orderStatus = 0 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND  ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } elseif ($_SESSION['role_modal'] == 'ASM') {
        $supdata = get_supervisors_by_asm($_SESSION['userid'], $_SESSION['customerno']);
        $supid = array();
        foreach ($supdata as $row) {
            $supid[] = $row->userid;
        }
        $supids = implode(',', $supid);

        $srdata = get_sr_by_supervisors($_SESSION['userid'], $supids, $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby IN (" . $ids . ") AND AND a.orderStatus = 0  a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else {
        //admin
        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.orderStatus = 0  AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    }
} else if ($of == 'viewsku') {
    $table = 'style as a';
    $primaryKey = 'styleid';
    $columns = array(
        array('db' => 'a.styleno', 'dt' => 0),
        array('db' => 'a.mrp', 'dt' => 1),
        array('db' => 'a.distprice', 'dt' => 2),
        array('db' => 'a.retailprice', 'dt' => 3),
        array('db' => 'a.styleid', 'dt' => 4,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=styleedit&styleid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.styleid', 'dt' => 5,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletestyle($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewstate') {
    $table = 'state as a';
    $primaryKey = 'stateid';
    $columns = array(
        array('db' => 'a.statename', 'dt' => 0),
        array('db' => 'stateid', 'dt' => 1,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=stateedit&stateid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'stateid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletestate($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewdist') {
    $table = 'distributor as a';
    $primaryKey = 'distributorid';
    $columns = array(
        array('db' => 'a.distcode', 'dt' => 0),
        array('db' => 'a.distname', 'dt' => 1),
        array('db' => 'a.distributorid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=distedit&distid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.distributorid', 'dt' => 3,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deletedist($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewarea') {
    $table = 'area as a';
    $primaryKey = 'areaid';
    $join = " inner join " . SPEEDDB . ".user as u on u.userid = a.distributorid ";
    $columns = array(
        array('db' => 'u.realname', 'dt' => 0),
        array('db' => 'a.areaname', 'dt' => 1),
        array('db' => 'a.areaid', 'dt' => 2,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=areaedit&areaid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.areaid', 'dt' => 3,
            'formatter' => function($e) {
                $view = "<a href='javascript:void(0);' onclick='deletearea($e);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            })
    );
} else if ($of == 'viewshop') {
    $table = 'shop as a';
    $join = " left join " . SPEEDDB . ".user as u on u.userid = a.distributorid left join area as ar on ar.areaid = a.areaid ";
    $primaryKey = 'shopid';
    $columns = array(
        array('db' => 'a.shopname', 'dt' => 0),
        array('db' => 'u.realname', 'dt' => 1),
        array('db' => 'ar.areaname', 'dt' => 2),
        array('db' => 'a.phone', 'dt' => 3),
        array('db' => 'a.owner', 'dt' => 4),
        array('db' => 'a.address', 'dt' => 5),
        array('db' => 'a.emailid', 'dt' => 6),
        array('db' => 'a.shopid', 'dt' => 7,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=shopedit&sid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => 'a.shopid', 'dt' => 8,
            'formatter' => function($d) {
                $view = "<a href='javascript:void(0);' onclick='deleteshop($d);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
        array('db' => 'a.shopid', 'dt' => 9)
    );
} else if ($of == 'viewshoptype') {
    $table = ' shoptype as a';
    $primaryKey = 'shid';
    $columns = array(
        array('db' => 'a.shop_type', 'dt' => 0),
        array('db' => ' a.shid', 'dt' => 1,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=stypeedit&stypeid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => ' a.shid', 'dt' => 2,
            'formatter' => function($e) {
                $view = "<a href='javascript:void(0);' onclick='deleteshoptype($e);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
    );
} else if ($of == 'viewattendance') {
    //sales
    $action = 'viewattendance';
    $table = ' attendance as a ';
    $join = " left join " . SPEEDDB . ".`user` as u  on a.userid = u.userid ";
    $primaryKey = 'atid';
    $columns = array(
        array('db' => 'u.realname', 'dt' => 0),
        array('db' => 'u.role', 'dt' => 1),
        array('db' => 'a.createdon', 'dt' => 2),
        array('db' => 'a.updatedon', 'dt' => 3),

        array('db' => 'a.onoff', 'dt' => 4, 'formatter' => function($d, $row){
       return ($d == '0') ? "Absent" : (($d == '1') ? "Present" : "NA");
      }),

        array('db' => 'a.atid', 'dt' => 6,
            'formatter' => function($d) {
                $view = "<a href='sales.php?pg=attendanceedit&atid=$d'><img src='../../images/edit_black.png'/></a>";
                return $view;
            }),
        array('db' => ' a.atid', 'dt' => 7,
            'formatter' => function($e){
                $view = "<a href='javascript:void(0);' onclick='deleteattendance($e);'><img src='../../images/Delete_red.png'/></a>";
                return $view;
            }),
        array('db' => 'a.lat', 'dt' => 8),
        array('db' => 'a.lng', 'dt' => 9),
    );
} else if ($of == 'viewinventory') {
    if ($_SESSION['role_modal'] == "ASM") {
        $supdata = get_supervisors_by_asm($_SESSION['userid'], $_SESSION['customerno']);
        $supid = array();
        foreach ($supdata as $row) {
            $supid[] = $row->userid;
        }
        $supids = implode(',', $supid);

        $srdata = get_sr_by_supervisors($_SESSION['userid'], $supids, $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);
        $distdata = get_sr_by_distdata($ids);
        $distid = array();
        foreach ($distdata as $row) {
            $distid[] = $row->userid;
        }
        $ids = implode(',', $distid);
        $table = ' inventory as a ';
        $join = " left join " . SPEEDDB . ".user as u on u.userid = a.distributorid left join style as st on st.styleid = a.skuid   left join category as c on c.categoryid = st.categoryid  ";
        $customWhere .= " a.distributorid IN (" . $ids . ") AND ";
        $primaryKey = 'distributorid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'c.categoryname', 'dt' => 1),
            array('db' => 'st.styleno', 'dt' => 2),
            array('db' => 'a.quantity', 'dt' => 3),
            array('db' => 'a.stockdate', 'dt' => 4,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.invid', 'dt' => 5,
                'formatter' => function($d) {
                    $view = "<a  href='javascript:void(0);'  onclick='editinvpopup($d);'><img alt='Edit Qty' title='Edit Qty' src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == "Supervisor") {
        $srdata = get_salespersons_by_supervisor($_SESSION['userid'], $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);
        $distdata = get_sr_by_distdata($ids);
        $distid = array();
        foreach ($distdata as $row) {
            $distid[] = $row->userid;
        }
        $ids = implode(',', $distid);
        $table = ' inventory as a ';
        $join = " left join " . SPEEDDB . ".user as u on u.userid = a.distributorid left join style as st on st.styleid = a.skuid   left join category as c on c.categoryid = st.categoryid  ";
        $customWhere .= " a.distributorid IN (" . $ids . ") AND ";
        $primaryKey = 'distributorid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'c.categoryname', 'dt' => 1),
            array('db' => 'st.styleno', 'dt' => 2),
            array('db' => 'a.quantity', 'dt' => 3),
            array('db' => 'a.stockdate', 'dt' => 4,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.invid', 'dt' => 5,
                'formatter' => function($d) {
                    $view = "<a  href='javascript:void(0);'  onclick='editinvpopup($d);'><img alt='Edit Qty' title='Edit Qty' src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == "Distributor") {
        $table = ' inventory as a ';
        $join = " left join " . SPEEDDB . ".user as u on u.userid = a.distributorid left join style as st on st.styleid = a.skuid   left join category as c on c.categoryid = st.categoryid  ";
        $customWhere .= " a.distributorid IN (" . $_SESSION['userid'] . ") AND ";
        $primaryKey = 'distributorid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'c.categoryname', 'dt' => 1),
            array('db' => 'st.styleno', 'dt' => 2),
            array('db' => 'a.quantity', 'dt' => 3),
            array('db' => 'a.stockdate', 'dt' => 4,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.invid', 'dt' => 5,
                'formatter' => function($d) {
                    $view = "<a  href='javascript:void(0);'  onclick='editinvpopup($d);'><img alt='Edit Qty' title='Edit Qty' src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else {
        $table = ' inventory as a ';
        $join = " left join " . SPEEDDB . ".user as u on u.userid = a.distributorid left join style as st on st.styleid = a.skuid  left join category as c on c.categoryid = st.categoryid ";
        $primaryKey = 'distributorid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'c.categoryname', 'dt' => 1),
            array('db' => 'st.styleno', 'dt' => 2),
            array('db' => 'a.quantity', 'dt' => 3),
            array('db' => 'a.stockdate', 'dt' => 4,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.invid', 'dt' => 5,
                'formatter' => function($d) {
                    $view = "<a  href='javascript:void(0);'  onclick='editinvpopup($d);'><img alt='Edit Qty' title='Edit Qty' src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    }
}else if ($of == 'viewonholdorder') {
    if ($_SESSION['role_modal'] == "sales_representative") {
        //sales
        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby =" . $_SESSION['userid'] . " AND a.orderStatus <> 0 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else if ($_SESSION['role_modal'] == 'Supervisor') {
        $srdata = get_salespersons_by_supervisor($_SESSION['userid'], $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby IN (" . $ids . ") AND a.orderStatus <> 0 AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND  ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } elseif ($_SESSION['role_modal'] == 'ASM') {
        $supdata = get_supervisors_by_asm($_SESSION['userid'], $_SESSION['customerno']);
        $supid = array();
        foreach ($supdata as $row) {
            $supid[] = $row->userid;
        }
        $supids = implode(',', $supid);

        $srdata = get_sr_by_supervisors($_SESSION['userid'], $supids, $_SESSION['customerno']);
        $srid = array();
        foreach ($srdata as $row) {
            $srid[] = $row->userid;
        }
        $ids = implode(',', $srid);

        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.addedby IN (" . $ids . ") AND AND a.orderStatus <> 0  a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    } else {
        //admin
        $table = ' secondary_order as a ';
        $join = " inner join " . SPEEDDB . ".`user` as u  on a.addedby = u.userid  left join shop as sh  on a.shopid = sh.shopid  ";
        $customWhere .= " a.orderStatus <> 0  AND a.entrydate >= DATE(NOW()) - INTERVAL 7 DAY AND ";
        $primaryKey = 'soid';
        $columns = array(
            array('db' => 'u.realname', 'dt' => 0),
            array('db' => 'sh.shopname', 'dt' => 1),
            array('db' => 'a.orderdate', 'dt' => 2,
                'formatter' => function($a) {
                    $view = date("d-m-Y G:ia", strtotime($a));
                    return $view;
                }),
            array('db' => 'a.soid', 'dt' => 3,
                'formatter' => function($d) {
                    $view = "<a href='sales.php?pg=orderedit&orderid=$d'><img src='../../images/edit_black.png'/></a>";
                    return $view;
                })
        );
    }
}

$customWhere .= "a.customerno=$customerno and a.isdeleted=0 ";


require( '../routing/ssp.class.php' );

echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $customWhere, $join, $groupby, $action)
);
?>
