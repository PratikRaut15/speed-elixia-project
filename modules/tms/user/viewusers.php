<center>
  <div class='container' >
    <div style="float:right;">
      <a href="tms.php?pg=add-user"> <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;">Add User <img src="../../images/show.png"></button></a>
    </div>
    <?php
    if (isset($_GET['delid']) && $_GET['delid']) {
     deluser($_GET['delid']);
    }

    include 'panels/viewusers.php';
    $users = getusersbygrp();
    if (isset($users) && count($users) > 0) {
     ?>
     <?php
     foreach ($users as $user) {
      if ($_SESSION["use_maintenance"] == '1' && $_SESSION["use_hierarchy"] == '1' && ( $user->roleid != '5' && $user->roleid != '7' && $user->roleid != '9' && $user->roleid != '11' )) {
       $heirarchy_name = get_heirarchy_name($user->roleid, $user->heirarchy_id);
      } else {
       $heirarchy_name = "";
      }
      ?>
      <tr>

        <td><?php echo $user->realname; ?></td>
        <td><?php echo $user->username; ?></td>
        <?php if ($_SESSION["use_maintenance"] == '1' && $_SESSION["use_hierarchy"] == '1') { ?>
         <td><?php
           echo $user->role;
           if ($heirarchy_name == '') {
            echo ' (' . $heirarchy_name . ') ';
           } else {
            echo $heirarchy_name;
           }
           ?></td>
        <?php } else { ?>
         <td><?php echo $user->role; ?></td>
        <?php } ?>
        <td><?php echo $user->email; ?></td>
        <td><?php echo $user->phone; ?></td>
        <td>
          <a href = 'tms.php?pg=edit-user&uid=<?php echo $user->userid; ?>'>
            <i class='icon-pencil'></i>
          </a>
        </td>
        <td>
          <?php
          if ($user->username != $_SESSION['username']) {
           ?>
           <a href = 'tms.php?pg=view-users&delid=<?php echo($user->userid); ?>' onclick="return confirm('Are you sure you want to delete?');">
             <i class='icon-trash'></i> </a>
             <?php
            }
            ?>
        </td>
      </tr>
      <?php
     }
    } else
     echo '<tr><td colspan="7">No Users Created</td></tr>';
    ?>
    </tbody>
    </table>
  </div>
</center>