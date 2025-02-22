<?php
// Connect to the database
require_once("../../database/connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $itemName = mysqli_real_escape_string($link, $_POST['itemName']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = mysqli_real_escape_string($link, $_POST['price']);
    $stock = mysqli_real_escape_string($link, $_POST['stock']);
    $sizes = $_POST['sizes']; // Assuming sizes is an array from the multiple select input
    $sizesString = mysqli_real_escape_string($link, implode(',', $sizes)); // Convert sizes array to comma-separated string
    $categoryID = mysqli_real_escape_string($link, $_POST['category']);
    $onSale = isset($_POST['sale-checkbox']) ? 1 : 0;
    $discount = $onSale ? mysqli_real_escape_string($link, $_POST['discount-percentage']) : 0;

    // Handle file upload
    $imageURL = '';
    if (!empty($_FILES['img']['name'][0])) {
        $imageName = basename($_FILES['img']['name'][0]);
        $targetDir = "../../user/assets/product/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['img']['tmp_name'][0], $targetFile)) {
            $imageURL = "assets/product/" . $imageName;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Insert into products table
    $query_products = "INSERT INTO products (prod_name, Description, Price, sizes, ImageURL)
                       VALUES ('$itemName', '$description', '$price', '$sizesString', '$imageURL')";
    if (mysqli_query($link, $query_products)) {
        $productID = mysqli_insert_id($link); // Get the last inserted ID

        // Insert into product_data table
        $query_product_data = "INSERT INTO product_data (product_id, CategoryID, onDiscount, Discount)
                               VALUES ('$productID', '$categoryID', '$onSale', '$discount')";
        if (mysqli_query($link, $query_product_data)) {
            // Insert into product_inventory table for each size
            foreach ($sizes as $size) {
                $size = mysqli_real_escape_string($link, $size);
                $query_inventory = "INSERT INTO product_inventory (product_id, prod_size, stock, out_of_stock_datetime, replenished_datetime)
                                    VALUES ('$productID', '$size', '$stock', NULL, NULL)";
                if (!mysqli_query($link, $query_inventory)) {
                    echo "Error: " . $query_inventory . "<br>" . mysqli_error($link);
                }
            }
            echo "New product has been added successfully!";
        } else {
            echo "Error: " . $query_product_data . "<br>" . mysqli_error($link);
        }
    } else {
        echo "Error: " . $query_products . "<br>" . mysqli_error($link);
    }

    // Close the connection
    mysqli_close($link);
} else {
    echo "Invalid request.";
}
?>
