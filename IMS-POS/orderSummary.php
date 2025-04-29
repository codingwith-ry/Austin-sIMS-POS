
<?php
    session_start();
    $employeeID = $_SESSION['employeeID'];
    
$active = "menu";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include 'links.php'?>
</head>
<body>
    
<?php include 'verticalNav.php'?>

<div id="mainContent">
    <div class="bg-white p-4 rounded shadow-sm" style="width: 100%;">
        <div class="mb-1">
            <h4 class="fw-bold">Order Summary</h4>
        </div>
        <hr />
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <p class="mb-1">Order Type:</p>
                <h2 class="fw-bold" id="orderType"></h2>
            </div>
            <div class="text-end">
                <p class="mb-1" id="orderDate"></p>
                <p id="orderTime"></p>
                <p id="employeeID" style="display: none;"><?php echo $employeeID; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-6 p-3">
                <h6 class="fw-bold mb-3">Order Details</h6>
                <div id="orderItemsContainer"></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Sub Total</span>
                    <span id="subTotal"></span>
                </div>
                <div id="discountElements" class="d-none justify-content-between fw-bold p-4 pt-0">
                    <span>Discount(SC/PWD)</span>
                    <span id="discountValue"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Amount Due</span>
                    <span id="amountDue"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Amount Paid</span>
                    <span id="amountPaid"></span>
                </div>
                <div class="d-flex justify-content-between fw-bold p-4 pt-0">
                    <span>Change</span>
                    <span id="changeAmount"></span>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="col-6 p-3">
                <div class="mb-3">
                    <label for="customerName" class="form-label fw-bold">Customer Name</label>
                    <input type="text" class="form-control" id="customerName" placeholder="Juan Dela Cruz">
                </div>

                <hr>
                <div class="form-floating">
                    <textarea class="form-control" id="additionalNotes" style="height: 100px"></textarea>
                    <label for="additionalNotes">Additional Notes</label>
                </div>
                <hr>
                <div class="mb-3">
                    <div>
                        <input class="form-check-input mt-0" type="checkbox" id="seniorPwdCheckbox">
                        <label class="form-check-label fw-bold" for="seniorPwdCheckbox">
                            Senior Citizen / PWD
                        </label>
                    </div>
                </div>
                <div id="seniorPwdFields" style="display: none;">
                    <div class="mb-3">
                        <label for="scPwdId" class="form-label fw-bold">SC/PWD ID Number</label>
                        <input type="text" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control" id="discountCardID" placeholder="Enter ID number">
                    </div>
                </div>
                <hr />
                <h6 class="fw-bold mb-3">Select a payment method</h6>
                <select class="form-select mb-4" id="paymentMethod">
                    <option selected>Cash</option>
                    <option>GCash</option>
                    <option>PayMaya</option>
                </select>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-success text-light" id="basic-addon1">₱</span>
                    <input id="amountPaidElem" type="text" pattern="\d*\.?\d{0,2}" 
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..{0,2}).*/g, '$1')" class="form-control" placeholder="100.00" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div id="referenceNumberField" style="display: none;">
                    <label for="referenceNumber" class="form-label fw-bold">Reference Number</label>
                    <input type="text" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control mb-3" id="payReferenceNumber" placeholder="Enter reference number">
                </div>


                <div class="d-flex justify-content-between">
                    <button class="btn btn-success flex-grow-1 me-2" id="payNowBtn">Pay Now</button>
                    <button class="btn btn-danger flex-grow-1 ms-2" id="cancelOrderBtn">Back to Menu</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load order items from localStorage
    const orderItems = JSON.parse(localStorage.getItem('orderItems')) || [];
    const orderType = localStorage.getItem('orderType');
    const orderItemsContainer = document.getElementById('orderItemsContainer');
    const subTotalElement = document.getElementById('subTotal');
    const discountElement = document.getElementById('discountValue');
    const amountDueElement = document.getElementById('amountDue');
    const amountPaidElement = document.getElementById('amountPaid');
    const changeAmountElement = document.getElementById('changeAmount');
    const employeeID = document.getElementById('employeeID').textContent;
    const paymentMethodElem = document.getElementById('paymentMethod');
    const amountPaidElem = document.getElementById('amountPaidElem');
    const discountCardID = document.getElementById('discountCardID').innerText;
    const payReferenceNumber = document.getElementById('payReferenceNumber').innerText;
    let totalAmount = 0;
    let amountPaid = 0;

    const seniorPwdCheckbox = document.getElementById('seniorPwdCheckbox');
    const seniorPwdFields = document.getElementById('seniorPwdFields');
    const discountElements = document.getElementById('discountElements');

    // Toggle visibility of the input fields based on the checkbox state
    

    const referenceNumberField = document.getElementById('referenceNumberField');

    // Toggle visibility of the reference number field based on the selected payment method
    paymentMethodElem.addEventListener('change', function () {
        if (paymentMethodElem.value === 'GCash' || paymentMethodElem.value === 'PayMaya') {
            referenceNumberField.style.display = 'block';
        } else {
            referenceNumberField.style.display = 'none';
        }
    });

    paymentMethodElem.addEventListener('change', function() {
        if(paymentMethodElem.value === 'GCash' || paymentMethodElem.value === 'PayMaya') {
            Swal.fire({
            title: 'Payment Method',
            html: '<p>Please scan the QR code to pay.</p><img src="resources/nachos.jpg" alt="QR Code" style="width: 200px; height: 200px;">',
            icon: 'info'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the payment
                }
            });
        }
    });

    amountPaidElem.addEventListener('input', function() {
        amountPaid = parseFloat(amountPaidElem.value) || 0;
        amountPaidElement.textContent = `₱${amountPaid.toFixed(2)}`;
        changeAmountElement.textContent = `₱${(amountPaid - totalAmount).toFixed(2)}`;
    });

    
    function formatPriceInput(input) {
        input.addEventListener('blur', function() {
            let value = this.value.trim();
            
            if (value === '') return;
    
            // If there's no decimal point, add '.00'
            if (!value.includes('.')) {
                this.value = value + '.00';
            }
            // If there's a decimal point but no numbers after it, add '00'
            else if (value.endsWith('.')) {
                this.value = value + '00';
            }
            // If there's only one digit after decimal point, add '0'
            else if (/\.\d$/.test(value)) {
                this.value = value + '0';
            }
        });
    }

    if(amountPaidElem) {
        formatPriceInput(amountPaidElem);
    }


    orderItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('border', 'rounded', 'p-4', 'mb-2');
        itemElement.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">${item.menuName} (${item.productCategory})</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">${item.productQuantity} × ${item.productName}${item.productVariationName? '('+item.productVariationName+')': ""} </span>
                <span class="">₱${(item.productPrice * item.productQuantity).toFixed(2)} </span>
            </div>
            <div id="addonsSection">
                ${item.productAddons.map(addon => `
                    <div id="addonSumRow" class="d-flex justify-content-between mb-2">
                        <span>+${addon.addonName}</span>
                        <span>₱${(addon.addonPrice * item.productQuantity).toFixed(2)}</span>
                    </div>
                `).join('')}
            </div>
            <hr />
            <div class="d-flex"><span class="fw-bold ms-auto">₱${item.productTotal.toFixed(2)}</span></div>
        `;
        orderItemsContainer.appendChild(itemElement);
        totalAmount += item.productTotal;
    });

    subTotal = totalAmount;
    amountPaidElement.textContent = `₱${amountPaid.toFixed(2)}`;
    subTotalElement.textContent = `₱${subTotal.toFixed(2)}`;
    discountCalculation = 0;
    amountDueElement.textContent = `₱${totalAmount.toFixed(2)}`; 
    changeAmountElement.textContent = `₱${(amountPaid - totalAmount).toFixed(2)}`;

    seniorPwdCheckbox.addEventListener('change', function () {
        if (this.checked) {
            totalAmount = subTotal; // Reset totalAmount to subTotal when checkbox is checked
            seniorPwdFields.style.display = 'block';
            discountElements.classList.remove('d-none');
            discountElements.classList.add('d-flex');

            discountCalculation = totalAmount * 0.2; // Assuming a 20% discount for SC/PWD
            totalAmount -= discountCalculation;
            discountElement.textContent = `-₱${discountCalculation.toFixed(2)}`; // Assuming a 20% discount for SC/PWD
            amountDueElement.textContent = `₱${totalAmount.toFixed(2)}`;
            changeAmountElement.textContent = `₱${(amountPaid - totalAmount).toFixed(2)}`;
        } else {
            totalAmount = subTotal; // Reset totalAmount to subTotal when checkbox is unchecked
            seniorPwdFields.style.display = 'none';
            discountElements.classList.add('d-none');
            discountElements.classList.remove('d-flex');
            amountDueElement.textContent = `₱${totalAmount.toFixed(2)}`; 
            changeAmountElement.textContent = `₱${(amountPaid - totalAmount).toFixed(2)}`;
        }
    });

    document.getElementById('orderType').textContent = orderType;

    // Set current date and time
    const now = new Date();
    document.getElementById('orderDate').textContent = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('orderTime').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

    // Cancel order button
    document.getElementById('cancelOrderBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Payment preferences will not be saved.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Menu.php';
            }
        });
    });

    // Pay now button
    document.getElementById('payNowBtn').addEventListener('click', function() {
        const customerName = document.getElementById('customerName').value;
        const additionalNotes = document.getElementById('additionalNotes').value;

        if(!customerName) {
            Swal.fire({
                icon: 'warning',
                title: 'Customer Name Required',
                text: 'Please enter the customer name.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(!amountPaidElem.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Amount Paid Required',
                text: 'Please enter the amount paid.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(amountPaid < totalAmount) {
            Swal.fire({
                icon: 'warning',
                title: 'Insufficient Amount Paid',
                text: 'Please enter a valid amount paid.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(amountPaid > 100000) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Amount Paid',
                text: 'Please enter a valid amount paid.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(amountPaid < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Amount Paid',
                text: 'Please enter a valid amount paid.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(seniorPwdCheckbox.checked && !document.getElementById('discountCardID').value) {
            Swal.fire({
                icon: 'warning',
                title: 'SC/PWD ID Required',
                text: 'Please enter the SC/PWD ID.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(seniorPwdCheckbox.checked && document.getElementById('discountCardID').value.length < 8) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid SC/PWD ID',
                text: 'Please enter a valid SC/PWD ID.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }else if(seniorPwdCheckbox.checked && document.getElementById('discountCardID').value.length > 16) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid SC/PWD ID',
                text: 'Please enter a valid SC/PWD ID.',
                confirmButtonColor: '#3085d6',
            });
            return;
        } else{
            processValid = true;
            if(paymentMethodElem.value === 'GCash' || paymentMethodElem.value === 'PayMaya') {
                if(!document.getElementById('payReferenceNumber').value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Reference Number Required',
                        text: 'Please enter the reference number.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        processValid = false;
                    });
                    return;
                    
                }
                
                if(document.getElementById('payReferenceNumber').value.length < 12) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Reference Number',
                        text: 'RN is too short. Please enter a valid reference number.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        processValid = false;
                    });
                    return;

                }    
                
                if(document.getElementById('payReferenceNumber').value.length > 13) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Reference Number',
                        text: 'RN is too long. Please enter a valid reference number.',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        processValid = false;
                    });
                    return;
                }
            }
            
            if(processValid == true){
                Swal.fire({
                    title: 'Confirm Payment',
                    text: "Proceed with the payment?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Proceed'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Call checkIDs.php to get the orderNumber and salesOrderNumber
                        fetch('scripts/checkIDs.php')
                            .then(response => response.json())
                            .then(data => {
                                const orderNumber = data.orderNumber;
                                const salesOrderNumber = data.salesOrderNumber;

                                const formatter = new Intl.DateTimeFormat('en-CA', {
                                    timeZone: 'Asia/Manila',
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit'
                                });

                                // Format the date
                                const parts = formatter.formatToParts(new Date());
                                const year = parts.find(part => part.type === 'year').value; // Extract year
                                const month = parts.find(part => part.type === 'month').value; // Extract month
                                const day = parts.find(part => part.type === 'day').value; // Extract day
                            

                                const now = new Date(); // Current date and time
                                const hours = String(now.getHours()).padStart(2, '0');   // "18" (24-hour format)
                                const minutes = String(now.getMinutes()).padStart(2, '0'); // "30"
                                const seconds = String(now.getSeconds()).padStart(2, '0'); // "00"

                                const dateNow  = `${year}-${month}-${day}`;
                                const timeNow = `${hours}:${minutes}:${seconds}`;

                                const orderObj = {
                                    orderNumber: orderNumber,
                                    salesOrder: salesOrderNumber,
                                    employeeID: employeeID,
                                    orderType: orderType,
                                    orderDate: dateNow,
                                    orderTime: timeNow,
                                    customerName: customerName,
                                    orderItems: orderItems,
                                    subTotal: subTotal,
                                    totalAmount: totalAmount,
                                    amountPaid: amountPaid,
                                    changeAmount: amountPaid - totalAmount,
                                    additionalNotes: additionalNotes,
                                    discountCardID: seniorPwdCheckbox.checked ? document.getElementById('discountCardID').value : null,
                                    payReferenceNumber: paymentMethodElem.value === 'GCash' || paymentMethodElem.value === 'PayMaya' ? document.getElementById('payReferenceNumber').value : null,
                                    paymentMode: document.getElementById('paymentMethod').value
                                };
                                

                                console.log('Order Object:', orderObj);


                                // Inside your payNowBtn click event handler, replace the Swal.fire with this:
                                Swal.fire({
                                    title: 'Payment Successful!',
                                    html: generateReceiptHTML(orderObj),
                                    icon: 'success',
                                    showConfirmButton: true,
                                    confirmButtonText: 'Print Receipt',
                                    showCancelButton: true,
                                    cancelButtonText: 'Close',
                                    customClass: {
                                        popup: 'receipt-popup',
                                        htmlContainer: 'receipt-html-container'
                                    },
                                    width: '600px'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Send orderObj to the server to insert into the database
                                        fetch('scripts/post_orderRecord.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                            },
                                            body: JSON.stringify(orderObj)
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status === 'success') {
                                                // Generate receipt and open in a new tab
                                                fetch('generate_receipt.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                    },
                                                    body: JSON.stringify(orderObj)
                                                })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok');
                                                    }
                                                    return response.blob();
                                                })
                                                .then(blob => {
                                                    // Create a URL for the PDF blob
                                                    const pdfUrl = URL.createObjectURL(blob);
                                                    
                                                    // Open the PDF in a new tab
                                                    window.open(pdfUrl, '_blank');
                                                    
                                                    // Clean up by revoking the blob URL
                                                    URL.revokeObjectURL(pdfUrl);
                                                    window.location.href = 'Menu.php';
                                                    localStorage.removeItem('orderItems');
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    Swal.fire('Error', 'Failed to generate receipt', 'error');
                                                });
                                            } else {
                                                Swal.fire('Error', 'Failed to insert order into database', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            Swal.fire('Error', 'Failed to insert order into database', 'error');
                                        });
                                    }
                                });

                                // Add this function to generate the receipt HTML
                                function generateReceiptHTML(order) {
                                    return `
                                        <div class="receipt-container" style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto;">
                                            <div class="receipt-header" style="text-align: center; margin-bottom: 20px;">
                                                <h2 style="margin: 0;">Austin's Cafe & Gastro Pub</h2>
                                                <p style="margin: 5px 0; font-size: 14px;">Sitio Looban, Tabang Plaridel, 3004<br> Bulacan<br></p>
                                                <p style="margin: 5px 0; font-size: 14px;">CHRISTIAN G MENDOZA - Prop.<br></p>
                                                <p style="margin: 5px 0; font-size: 14px;">TIN: 434-872-844-000</p>
                                            </div>
                                            <div class="receipt-info" style="margin-bottom: 15px;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Order #:</strong></span>
                                                    <span>${order.orderNumber}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Employee ID:</strong></span>
                                                    <span>${order.employeeID}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Date:</strong></span>
                                                    <span>${order.orderDate}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Time:</strong></span>
                                                    <span>${order.orderTime}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Type:</strong></span>
                                                    <span>${order.orderType}</span>
                                                </div>
                                                ${order.customerName ? `
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span><strong>Customer:</strong></span>
                                                    <span>${order.customerName}</span>
                                                </div>
                                                ` : ''}
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 18px;">
                                                    <span><strong>Payment Method:</strong></span>
                                                    <span><strong>${order.paymentMode}</strong></span>
                                                </div>
                                            </div>
                                            <hr style="border-top: 1px dashed; margin: 15px 0;">
                                            <div class="receipt-items" style="margin-bottom: 15px;">
                                                <h4 style="margin-bottom: 10px; text-align: center;">Order Items</h4>
                                                <hr style="border: 1px dashed;" />
                                                ${order.orderItems.map(item => `
                                                    <div style="margin-bottom: 20px;">
                                                        <div style="font-size: 14px; text-align: left;">
                                                            ${item.menuName} (${item.productCategory})
                                                        </div>
                                                        <div style="display: flex; justify-content: space-between; font-weight: bold;">
                                                            <span>${item.productQuantity} × ${item.productName}</span>
                                                            <span>₱${(item.productPrice * item.productQuantity).toFixed(2)}</span>
                                                        </div>
                                                        ${item.productAddons.length > 0 ? `
                                                            <div style="margin-left: 20px; font-size: 14px;">
                                                                ${item.productAddons.map(addon => `
                                                                    <div style="display: flex; justify-content: space-between;">
                                                                        <span>+ ${addon.addonName}</span>
                                                                        <span>₱${(addon.addonPrice * item.productQuantity).toFixed(2)}</span>
                                                                    </div>
                                                                `).join('')}
                                                            </div>
                                                        ` : ''}
                                                    </div>
                                                `).join('')}
                                            </div>
                                            <hr style="border: 1px dashed;" />
                                            <div class="receipt-totals" style="margin-bottom: 15px;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                    <span><strong>Total Amount:</strong></span>
                                                    <span>₱${order.totalAmount.toFixed(2)}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                    <span><strong>Amount Paid:</strong></span>
                                                    <span>₱${order.amountPaid.toFixed(2)}</span>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 18px;">
                                                    <span><strong>Change:</strong></span>
                                                    <span><strong>₱${order.changeAmount.toFixed(2)}</strong></span>
                                                </div>
                                            </div>
                                            ${order.additionalNotes ? `
                                                <div class="receipt-notes" style="margin-top: 20px; font-size: 14px;">
                                                    <p><strong>Notes:</strong> ${order.additionalNotes}</p>
                                                </div>
                                            ` : ''}
                                            <hr />
                                            <div class="receipt-footer" style="text-align: center; font-size: 14px; color: #666;">
                                            </div>
                                        </div>
                                    `;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to get order IDs', 'error');
                            });
                    }
                });
            }
        }

        
    });
});
</script>

</body>
</html>
</script>

</body>
</html>