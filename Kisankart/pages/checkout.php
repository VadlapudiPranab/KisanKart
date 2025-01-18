<?php
// Start the session to manage the cart
session_start();

// Simulate adding products to the cart dynamically (this would typically happen on the 'Add to Cart' page)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart dynamically via query parameters (e.g., ?add_product=1&product=Apple&price=50&quantity=2)
if (isset($_GET['add_product'])) {
    $product = htmlspecialchars($_GET['product']);
    $price = (float)$_GET['price'];
    $quantity = (int)$_GET['quantity'];

    // Check if the product already exists in the cart
    $product_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product'] === $product) {
            $item['quantity'] += $quantity;
            $product_found = true;
            break;
        }
    }

    // If the product doesn't exist, add it to the cart
    if (!$product_found) {
        $_SESSION['cart'][] = ['product' => $product, 'price' => $price, 'quantity' => $quantity];
    }
}

// Function to calculate the total price
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Capture form submission for order placement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture customer details
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $cart = $_SESSION['cart'];

    // Order confirmation display
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Thank you for your order, <strong>$name</strong>!</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Shipping Address: $address</p>";
    echo "<p>Payment Method: $payment_method</p>";
    echo "<h3>Your Cart:</h3>";
    foreach ($cart as $item) {
        echo "<p>{$item['product']} (Quantity: {$item['quantity']}) - $" . ($item['price'] * $item['quantity']) . "</p>";
    }
    echo "<p><strong>Total: $" . calculateTotal($cart) . "</strong></p>";
    echo "<p>Your order will be processed soon. You will receive a confirmation email shortly.</p>";

    // Clear the session (simulate checkout completion)
    unset($_SESSION['cart']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisan Kart - Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-top: 10px;
        }

        input, select, textarea {
            padding: 10px;
            margin: 5px 0;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        .cart-summary {
            margin-top: 30px;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
        }

        .payment-info {
            margin-top: 30px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>
        <form action="" method="POST">
            <div class="customer-info">
                <h2>Customer Information</h2>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Shipping Address:</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>

            <div class="cart-summary">
                <h2>Your Cart</h2>
                <div class="cart-items">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="cart-item">
                            <span><?= htmlspecialchars($item['product']) ?> (x<?= $item['quantity'] ?>)</span>
                            <span>$<?= $item['price'] * $item['quantity'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="total">
                    <p>Total: $<?= calculateTotal($_SESSION['cart']) ?></p>
                </div>
            </div>

            <div class="payment-info">
                <h2>Payment Information</h2>
                <label for="payment-method">Payment Method:</label>
                <select id="payment-method" name="payment_method">
                    <option value="cod">Cash on Delivery</option>
                    <option value="online">Online Payment</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Place Order</button>
        </form>
    </div>
</body>
</html>              