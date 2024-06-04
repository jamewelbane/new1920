<?php
session_start();
require_once '../database/connection.php';
require("function/check-login.php");



$isLoggedIn = 0;
if (!check_login_user_universal($link)) {

    $isLoggedIn = 0;
    header("Location: ../index");
    exit;
} else {
    $verifiedUID = $_SESSION['userid'];
    $isLoggedIn = 1;
}



?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

<?php include 'head.html'; ?>
<style>
    .delete-cart {
        background: transparent;

        border: none;

        color: inherit;

        padding: 10px;

        cursor: pointer;

    }


    .body-tab {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        background-color: #f4f4f4;
        min-height: 100vh;
        /* Use padding or margin instead of align-items: center to prevent upward movement */
        padding-top: 20px;
        padding-bottom: 20px;
       
    }

    .tab-container {
        width: 100%;
        max-width: 800px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        margin-top: 20px;
        /* Ensure the tab container has a background color */
        
    }

    .tabs {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 1px solid #ccc;
    }

    .tab-button {
        flex: 1;
        padding: 10px;
        cursor: pointer;
        background-color: #f4f4f4;
        border: none;
        border-bottom: 3px solid transparent;
        outline: none;
        transition: background-color 0.3s, border-bottom-color 0.3s;
        text-align: center;
    }

    .tab-button:hover {
        background-color: #e9e9e9;
    }

    .tab-button.active {
        border-bottom-color: #007BFF;
        background-color: #fff;
    }

    .tab-content {
        border: 1px solid #ccc;
        border-top: none;
        padding: 20px;
        background-color: #fff;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    /* Remove the focus outline */
    .tab-button:focus {
        outline: none;
    }

    /* Responsive adjustments */
    @media only screen and (max-width: 767px) {
        .body-tab {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .tab-container { 
            width: 90%;
            
        }
        .tab-button {
            flex: 100%;
            border-bottom: 1px solid #ccc;
            border-right: none;
            padding: 15px;
        }

        .tabs {
            border-bottom: none;
        }

        .tab-button.active {
            border-right: 3px solid #007BFF;
            border-bottom: none;
            background-color: #fff;
        }

        .tab-content {
            border-top: none;
            border-left: none;
        }

        .cancel_request {
            margin-top: 10px;
        }
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        display: none;
    }

    .table tbody,
    .table tr,
    .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
    }

    .table td {
        text-align: right;
        padding: 8px;
        position: relative;
        border: none;
    }

    .table td:before {
        content: attr(data-label);
        position: absolute;
        left: 8px;
        width: 50%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
    }

    .table td:last-child {
        text-align: center;
    }

    .table td:last-child:before {
        content: none;
    }
</style>

<body>

    <?php

    include 'html/pre-loader.html';
    include("navbar.php");

    ?>

    <!-- search area -->
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <span class="close-btn"><i class="fas fa-window-close"></i></span>
                    <div class="search-bar">
                        <div class="search-bar-tablecell">
                            <h3>Search For:</h3>
                            <input type="text" placeholder="Keywords">
                            <button type="submit">Search <i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search arewa -->

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Checkout, Complete, Enjoy!</p>
                        <h1>Transactions</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->




    <div class="body-tab">

        <div class="tab-container">
            <div class="tabs">
                <button class="tab-button active" onclick="openTab(event, 'Tab1')">Pending</button>
                <button class="tab-button" onclick="openTab(event, 'Tab2')">To Ship</button>
                <button class="tab-button" onclick="openTab(event, 'Tab3')">Completed</button>
            </div>
            <div class="tab-content">
                <div id="Tab1" class="tab-pane">
                    <h2>Pending Orders</h2>
                    <?php include 'function/transaction-table/pending-table.php' ?>
                </div>
                <div id="Tab2" class="tab-pane">
                    <h2>To ship</h2>
                    <?php include 'function/transaction-table/confirmed-table.php' ?>
                </div>
                <div id="Tab3" class="tab-pane">
                    <h2>Completed</h2>
                    <?php include 'function/transaction-table/completed-table.php' ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'html/logo-brand.html';
    include 'html/footer.php';
    include 'html/copyright.html';


    include 'injectables.html';
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the tab pane you want to set as active
            var tabPane = document.getElementById("Tab1");

            // Add the 'active' class to the tab pane
            tabPane.classList.add("active");
        });
    </script>
    <script>
        function openTab(event, tabId) {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => button.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            event.currentTarget.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
    <script>
        function attachDeleteEventListeners() {
            const deleteButtons = document.querySelectorAll('.delete-cart');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cart_id = this.getAttribute('data-id');
                    const confirmDelete = confirm('You are about to delete this item.\nAre you sure?');
                    if (confirmDelete) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'function/delete-cart.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                alert('Deleted!\n' + xhr.responseText);
                                fetchCartItems();
                            }
                        };
                        xhr.send('cart_id=' + cart_id);
                    }
                });
            });
        }

        function fetchCartItems() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "function/fetch-cart.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        var cartItems = response.data;
                        var tbody = document.getElementById('cart-items-tbody');
                        tbody.innerHTML = ''; // Clear existing content

                        var subtotal = 0.00;

                        cartItems.forEach(function(item) {
                            var priceContent = item.discounted_price != item.original_price ?
                                `<span style="text-decoration: line-through;">₱${item.original_price}</span> ₱${item.discounted_price}` :
                                `₱${item.original_price}`;

                            var tr = document.createElement('tr');
                            tr.classList.add('table-body-row');
                            tr.innerHTML = `
                                <td class="product-remove"><button class='delete-cart' data-id="${item.cart_id}"><i class='fas fa-trash-alt'></i></button></td>
                                <td class="product-image"><img src="${item.ImageURL}" alt=""></td>
                                <td class="product-name">${item.prod_name}</td>
                                <td class="product-price">${priceContent}</td>
                                <td class="product-size">${item.prod_size}</td>
                                <td class="product-quantity"><input type="number" value="${item.quantity}" min="1" max="10" onchange="updateQuantity(${item.cart_id}, ${item.product_id}, this.value)"></td>
                                <td class="product-row-total">₱${item.row_total}</td>
                            `;

                            tbody.appendChild(tr);

                            subtotal += parseFloat(item.row_total.replace(/[^0-9.-]+/g, ""));
                        });

                        var shippingFee = 0;
                        var total = subtotal + shippingFee;
                        document.getElementById('subtotal').innerText = `₱${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                        document.getElementById('total').innerText = `₱${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                        // Attach delete button event listeners
                        attachDeleteEventListeners();
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send();
        }

        window.onload = function() {
            var cartIcon = document.getElementById("cart");
            if (cartIcon) {
                cartIcon.style.color = "#F28123";
            }
        };

        document.addEventListener("DOMContentLoaded", function() {
            fetchCartItems();
        });

        window.updateQuantity = function(cartId, productId, quantity) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "function/update-cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        fetchCartItems(); // Refresh cart items to reflect the updated quantity
                    } else {
                        alert(response.message);
                        fetchCartItems();
                    }
                }
            };
            xhr.send("cart_id=" + cartId + "&product_id=" + productId + "&quantity=" + quantity);
        }
    </script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dataTable').DataTable({
                "pageLength": 10, // Sets the number of entries per page
                "lengthChange": false, // Hides the entry dropdown
                "searching": false, // Hides the search box
                "pagingType": "full_numbers" // Adds styling to pagination

            });
        });
    </script>



<script>
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('cancel_request').addEventListener('click', function() {
        if (confirm('Cancel this order?')) {
            
            var orderId = this.getAttribute('data-order_id');
            // Redirect to the cancellation page with the order ID
            window.location.href = 'cancellation.php?order_id=' + orderId;
        }
    });
});
</script>


</body>

</html>