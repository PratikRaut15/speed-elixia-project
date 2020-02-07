<?php
$statuss = get_reasons();
//print_r($status);
if(isset($statuss))
{
    require 'panels/viewreason.php';
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
                        <a href='reasonmaster.php?id=3&sid=<?php echo($status->statusid);?>'><i class='icon-pencil'></i></a>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if(!$status ->customerno == 0)
                    {
                        ?>
                        <a href = 'route.php?delreasonid=<?php echo($status->statusid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a>
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
