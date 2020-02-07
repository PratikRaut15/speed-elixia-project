<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
//include_once $RELATIVE_PATH_DOTS . "lib/comman_function/reports_func.php";
include_once $RELATIVE_PATH_DOTS . "lib/bo/GroupManager.php";


$GroupManager = new GroupManager($_SESSION['customerno']);
if(!empty($_SESSION['userid']))
    $userId = $_SESSION['userid'];
else
    $userId = '';

    $userGroups = $GroupManager->getallgroupsbysequence();
    
?>


<link rel="stylesheet" type="text/css" href="../../scripts/jquery-ui/jquery-ui.css">
<script src="../../scripts/jquery-ui/jquery-ui.js"></script>
<style>


    #sortable1, #sortable2 {
        border: 2px solid #65686D;
        width: 142px;
        min-height: 20px;
        list-style-type: none;
        margin: 3px;
        padding: 5px ;
        float: left;
        cursor:pointer
    }
    #sortable1 li, #sortable2 li {
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 1.2em;
        width: 120px;
    }
    .sucmsg{
        color:green;
        font-size: 10px;
    }


    /* Absolute Center Spinner */

/* loader css */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 1500ms infinite linear;
  -moz-animation: spinner 1500ms infinite linear;
  -ms-animation: spinner 1500ms infinite linear;
  -o-animation: spinner 1500ms infinite linear;
  animation: spinner 1500ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

/* loader css */
</style>
<script>

    $(function () {
        $('body').click(function () {
            $('#msg').css('display', 'none');
        });

        $("#sortable1, #sortable2").sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    });
    function submit() {
        var default_idsInOrder = [];
        var idsInOrder = [];
        $("ul#sortable2 li").each(function () {
            var list = $(this).attr('id');
            if (list != 0) {
                idsInOrder.push(list)
            }
        });
        var seq = idsInOrder.join(',');

        $("ul#sortable1 li").each(function () {
            var deflist = $(this).attr('id');
            if (deflist != 0) {
                default_idsInOrder.push(deflist)
            }
        });
        var defaultseq = default_idsInOrder.join(',');

        var data = "seq=" + escape(seq) + "&defaultseq=" + escape(defaultseq) + "&action=sequenceupdate";
        jQuery.ajax({
            url: "pages/groupseq_ajax.php",
            type: 'POST',
            data: data,
            success: function (result) {
                if (result == 'ok') {
                    alert('Group sequence Sucessfully updated');
                    $('#msg').css('display', 'table-row');
                    $(".sucmsg").html('Group sequence Sucessfully updated.');
                }
            },beforeSend : function(result){
                $('.loading').show();
             }
        }).done(function(){
            $('.loading').hide();
        }) ;
    }
</script>
<h4 style="margin:5px;">Sorting sequence of <?php echo "group List" ?> for realtimedata page</td></h4>
<div class="loading" style="display:none;">
    <!-- <img src="../../images/loader/loader.gif" class="img-responsive" alt="Image" height="100%"> -->
</div>

<table>
    <thead>
        
        <tr id="msg"><td colspan="2" class="sucmsg" style="text-align: center;"></td></tr>
        <tr>
            <th id="formheader">Default order <?php //echo $vehicle_ses; ?> list</th>
            <th id="formheader">Customize order <?php //echo $vehicle_ses; ?> list</th>
        </tr>
    </thead>
    <tr>
        <td>
            <ul id="sortable1" class="connectedSortable ">
                <?php
                if (isset($userGroups)) {
                    
                    foreach ($userGroups as $groups) {
                        if ($groups->sequence == 0) {
                            echo "<li class='ui-state-default' id='" . $groups->groupid . "'>" . $groups->groupname . "</li>";
                        }
                    }
                }
                ?> 
            </ul>
        </td>    
        <td>

            <ul id="sortable2" class="connectedSortable ">
                <?php
                if (isset($userGroups)) {
                    foreach ($userGroups as $groups) {
                        if ($groups->sequence != 0) {
                            echo "<li class='ui-state-default' id='" . $groups->groupid . "'>" . $groups->groupname . "</li>";
                        }
                    }
                }
                ?>

            </ul>
        </td> 
    </tr> 
    <tr>
        <td colspan="5">  
            <input type="submit" class='btn-primary' style="margin: 5px;" value="Update" onclick="submit()">
        </td>
    </tr>
</table>
