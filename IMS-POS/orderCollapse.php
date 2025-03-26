<div class="collapse order-collapse" id="orderDetails<?php echo $order['orderID']; ?>">
    <div class="order-details">
        <!-- Table for Order Details -->
        <table class="table-borderless">
            <tr>
                <!-- Order Description -->
                <td>
                    <strong>Order Description:</strong>
                    <ul style="padding-left: 20px; margin: 0;"> 
                        <li>
                            <?php 
                                echo !empty($order['productName'])
                                        ? htmlspecialchars($order['productName']) . ' (₱' . number_format($order['productPrice'], 2) . ')'
                                        : 'N/A';
                            ?>
                        </li>
                    </ul>
                </td>
                <!-- Order Type -->
                <td>
                    <strong>Order Type:</strong><br>
                    <?php 
                        echo !empty($order['orderClass'])
                                ? htmlspecialchars($order['orderClass']) 
                                : 'N/A'; 
                    ?>
                </td>
                <!-- Add-Ons -->
                <td>
                    <strong>Add-Ons:</strong>
                    <ul style="padding-left: 20px; margin: 0;"> 
                        <?php 
                        if (!empty($order['addonList'])) {
                            $addonItems = explode('<br>', $order['addonList']); 
                            foreach ($addonItems as $addon) {
                                echo "<li>" . $addon . "</li>"; 
                            }
                        } else {
                            echo "<li>No Add-Ons</li>";
                        }
                        ?>
                    </ul>
                </td>
                <!-- Notes/Remarks -->
                <td class="text-break">
                    <strong>Notes/Remarks:</strong><br>
                    <?php 
                        echo !empty($order['orderRemarks'])
                                ? htmlspecialchars($order['orderRemarks'])
                                : 'No remarks.'; 
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>