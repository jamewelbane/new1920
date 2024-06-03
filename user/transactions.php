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

$isCartEmpty = 1;

$checkCart = "SELECT * FROM cart WHERE userid = $verifiedUID";
$resultcheckCart = mysqli_query($link, $checkCart);


if (mysqli_num_rows($resultcheckCart) > 0) {
    $isCartEmpty = 0;
}

mysqli_free_result($resultcheckCart);
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.html'; ?>
<style>
    .delete-cart {
        background: transparent;

        border: none;

        color: inherit;

        padding: 10px;

        cursor: pointer;

    }


    .tab-container {
        width: 100%;
        max-width: 800px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

    /* Responsive adjustments */
    @media (max-width: 600px) {

        .tab-container {
            margin-bottom: 300px;
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
                        <h1>Cart</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->


    <?php

    if ($isCartEmpty === 0) { ?>
        <!-- cart -->
        <div class="cart-section mt-150 mb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="cart-table-wrap">
                            <table class="cart-table">
                                <thead class="cart-table-head">
                                    <tr class="table-head-row">
                                        <th class="product-remove"></th>
                                        <th class="product-image">Product Image</th>
                                        <th class="product-name">Name</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-size">Size</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-row-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items-tbody">
                                    <!-- Cart items will be dynamically inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- end cart -->

    <?php
    } else {
    ?>
        <!-- error section -->
        <div class="full-height-section error-section" style="margin-top: 50px;">
            
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 text-center">
                            <div class="tab-container">
                                <div class="tabs">
                                    <button class="tab-button active" onclick="openTab(event, 'Tab1')">Pending</button>
                                    <button class="tab-button" onclick="openTab(event, 'Tab2')">To Ship</button>
                                    <button class="tab-button" onclick="openTab(event, 'Tab3')">Completed</button>
                                </div>
                                <div class="tab-content">
                                    <div id="Tab1" class="tab-pane active">
                                        <h2>Content of Tab 1</h2>
                                        <p>This is the content of the first tab.</p>
                                    </div>
                                    <div id="Tab2" class="tab-pane">
                                        <h2>Content of Tab 2</h2>
                                        <p>This is the content of the second tab.</p>
                                    </div>
                                    <div id="Tab3" class="tab-pane">
                                        <h2>Content of Tab 3</h2>
                                        <p>This is the content of the third tab.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
        <!-- end error section -->
    <?php } ?>


    <?php
    include 'html/logo-brand.html';
    include 'html/footer.php';
    include 'html/copyright.html';


    include 'injectables.html';
    ?>
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



    <!-- checkout button -->
    <script>
        document.getElementById('checkoutButton').addEventListener('click', function() {
            if (confirm('Proceed with checkout?')) {
                // Redirect to payment page
                window.location.href = `payment.php?userid=${<?php echo $_SESSION['userid']; ?>}`;
            }
        });
    </script>

    
</body>

</html>