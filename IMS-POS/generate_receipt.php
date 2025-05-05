<?php
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;

// Get the order data from POST
$orderData = json_decode(file_get_contents('php://input'), true);

if (isset($orderData['orderItems']) && is_string($orderData['orderItems'])) {
    $orderData['orderItems'] = json_decode($orderData['orderItems'], true);
}

// Generate the HTML for the receipt
$html = generateReceiptHTML($orderData);
$html = str_replace('₱', '&#8369;', $html); 

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="receipt_'.date('Ymd-His').'.pdf"');
echo $dompdf->output();


// Output the generated PDF
$dompdf->stream("receipt.pdf", ["Attachment" => false]);

function generateReceiptHTML($order) {
    $itemsHTML = '';
    foreach ($order['orderItems'] as $item) {
        $variation = $item['productVariationName'] ? ' (' . $item['productVariationName'] . ')' : '';
        $itemsHTML .= '
        <tr>
            <td><span style="font-weight: bold;">' . $item['menuName'] . '(' . $item['productCategory'] . ')</span></td>
            <td></td>
        </tr>
        <tr>
            <td> ' . $item['productName'] . $variation . '</td>
            <td>' . $item['productQuantity'] . ' x ₱' . number_format($item['productPrice'], 2) . '</td>
        </tr>';
        
        foreach ($item['productAddons'] as $addon) {
            $itemsHTML .= '
            <tr>
                <td>+ ' . $addon['addonName'] . '</td>
                <td>' . $item['productQuantity'] . ' x ₱' . number_format($addon['addonPrice'], 2) . '</td>
            </tr>';
        }
        $itemsHTML .= '
            <tr>
                <td></td>
                <td style="font-weight: bold;">₱' . number_format($item['productTotal'], 2) . '</td>
            </tr>
            <br />
        ';
    }

    return '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Receipt</title>
        <style>
            body {
                font-family: "DejaVu Sans", "Trebuchet MS", sans-serif;
                width: 100%;
            }
            .header, .footer {
                text-align: center;
                font-size: 14px;
                margin-bottom: 10px;
            }
            .header {
                font-weight: bold;
            }
            .details {
                font-size: 12px;
                margin-bottom: 10px;
            }
            .items {
                width: 100%;
                font-size: 12px;
                margin-bottom: 10px;
            }
            .items td {
                padding: 2px 0;
            }
            .items td:last-child {
                text-align: right;
            }
            .total {
                font-size: 14px;
                text-align: left;
                margin-bottom: 10px;
                font-weight: bold;
            }
            .footer {
                font-size: 12px;
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            Austin\'s Cafe & Gastro Pub<br>
            <br>
            <span style="font-weight:normal;">
            Sitio Looban, Tabang Plaridel, 3004<br>
            Bulacan<br>
            CHRISTIAN G MENDOZA - Prop.<br>
            TIN: 434-872-844-000
            </span>
        </div>
        <div class="details">
            BILL<br>
            Order: ' . $order['orderNumber'] . ' - ' . date('g:i A', strtotime($order['orderTime'])) . '<br>
            Employee: ' . $order['employeeID'] . '<br>
            POS: POS 1<br>
            Order Type: ' . $order['orderType'] . '<br>
            Customer Name:' . ($order['customerName'] ? htmlspecialchars($order['customerName']) : 'Unknown') . '<br>
            Senior Citizen/PWD: '. ($order['discountCardID'] ? 'Yes' : 'No') .'<br>
            Payment Mode: '.$order['paymentMode'].'<br>
            ' . (
                ($order['paymentMode'] == "GCash" || $order['paymentMode'] == "PayMaya")
                    ? 'Reference Number: ' . $order["payReferenceNumber"] . '<br>'
                    : ''
            ) . '
        </div>
        <hr style="border: 1px dashed;" />
        <span style="font-size: 12px">Order Items</span>
        <hr style="border: 1px dashed;" />
        <table class="items">
            ' . $itemsHTML . '
        </table>
        <hr style="border: 1px dashed;" />
        <div class="total">
            <table class="items" style="width: 100%;">
                <tr>
                    <td>Sub Total:</td>
                    <td>₱' . number_format($order['subTotal'], 2) . '</td>
                </tr>
                ' . (
                ($order['discountCardID'])
                    ? '<tr><td>Discount: </td><td>₱' . number_format(($order["subTotal"] - $order["totalAmount"]),2) . '</td></tr>'
                    : ''
                ) . '
                <tr>
                    <td>Amount Due:</td>
                    <td>₱' . number_format($order['totalAmount'], 2) . '</td>
                </tr>
                <tr>
                    <td>Amount Paid:</td>
                    <td>₱' . number_format($order['amountPaid'], 2) . '</td>
                </tr>
                <tr>
                    <td>Change:</td>
                    <td>₱' . number_format($order['changeAmount'], 2) . '</td>
                </tr>
            </table>
        </div>
        ' . ($order['additionalNotes'] ? '
        <div class="details">
            Notes: ' . htmlspecialchars($order['additionalNotes']) . '
        </div>' : '') . '
        <div class="footer">
            THANK YOU!<br>
            ' .$order['orderDate'] . '
        </div>
    </body>
    </html>';
}

error_log(print_r($orderData, true));
?>