<?php
$orders = get_orders();
//print_r($status);

if(!empty($orders))
{
    require 'panels/vieworders.php';
    $i = 1;
    foreach($orders as $order)
    {
        ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $order->order_id;?></td>
            <td><?php echo $order->fullname;?></td>
            <td><?php echo $order->item_count?></td>
            <td><?php echo $order->total?></td>
            <td><?php echo $order->trackingno?></td>
            <td><?php echo $order->transporter_name?></td>
            <td><?php echo $order->lr_no?></td>
            <td><?php echo $order->shipmentstatus?></td>
            <td><?php echo convertDateToFormat($order->created_on,speedConstants::DEFAULT_DATETIME);?></td>
            <td><a href="delivery.php?id=4&odid=<?php echo $order->id;?>">view</a></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
   </table>
  <?php
}
else
{
    echo "No Orders Available";
}
