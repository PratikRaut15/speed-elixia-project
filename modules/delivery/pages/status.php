<?php
$statuss = get_status();
//print_r($status);
if(isset($statuss))
{
    require 'panels/viewstatus.php';
    $i = 1;
    foreach($statuss as $status)
    {
        ?>
            <tr>
                <td width="10%"><?php echo $i++;?></td>
                <td><?php echo $status->status?></td>
                <td>
                    <?php
                    if(!$status->customerno == 0)
                    {
                        ?>
                        <a href='statusmaster.php?id=3&sid=<?php echo($status->statusid);?>'><i class='icon-pencil'></i></a>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if(!$status ->customerno == 0)
                    {
                        ?>
                        <a href = 'route.php?delid=<?php echo($status->statusid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a>
                        <?php
                    }
                    ?>
                </td>
            </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
    <?php
}
