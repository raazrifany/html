<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
        }

        header {
            background-color: #508952;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .cart-container {
            max-width: 800px;
            margin: auto;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 20px; /* Menambahkan padding */
            margin: 10px 0;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box; /* Menetapkan box-sizing */
        }

        .cart-item img {
            width: 100px; /* Ukuran maksimal gambar */
            height: 100px; /* Ukuran maksimal gambar */
            margin-right: 10px;
            object-fit: cover; /* Mempertahankan rasio aspek 1:1 */
        }

        .cart-item-details {
            flex: 1;
            padding-right: 10px; /* Menambahkan padding di sebelah kanan */
        }

        .cart-item-details h3 {
            margin: 0;
        }

        .quantity-container {
            display: flex;
            align-items: center;
            margin-top: 10px; /* Menambahkan margin di atas tombol-tombol */
        }

        .quantity-button {
            background-color: #ddead1;
            color: #4b6043;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-right: 5px; /* Menambahkan margin di antara tombol-tombol */
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: 0;
            margin: 0px 0px 0px 0px; /* Menambahkan margin di antara input dan tombol */
        }

        .total-price {
            font-weight: bold;
            margin-top: 10px;
            text-align: right; /* Mengatur teks ke kanan */
            margin-left: 50px; /* Memanfaatkan margin-left auto untuk meletakkan di kanan */
        }

        .remove-item {
            background-color: #e2e4e3;
            color: black;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 50px; /* Menambahkan margin di antara tombol-tombol */
        }
    </style>
    <title>Shopping Cart</title>
</head>
<body>

    <header>
        <h1>Shopping Cart</h1>
    </header>

    <div class="cart-container">
        <!-- Example Cart Items, replace with dynamic data from your backend -->
        <div class="cart-item">
            <img src="binbin.jpeg" alt="Product 1">
            <div class="cart-item-details">
                <h3>Product 1</h3>
                <p>Price: $20.00</p>
            </div>
            <div class="quantity-container">
                <button class="quantity-button" onclick="decreaseQuantity(event)">-</button>
                <input class="quantity-input" type="text" value="1" onchange="updateQuantity(event, this)">
                <button class="quantity-button" onclick="increaseQuantity(event)">+</button>
            </div>
            <div class="total-price">$40.00</div>
            <button class="remove-item" onclick="removeItem()"><b>Remove</b></button>
        </div>

        <div class="cart-item">
            <img src="ijong.jpeg" alt="Product 2">
            <div class="cart-item-details">
                <h3>Product 2</h3>
                <p>Price: $15.00</p>
            </div>
            <div class="quantity-container">
                <button class="quantity-button" onclick="decreaseQuantity(event)">-</button>
                <input class="quantity-input" type="text" value="1" onchange="updateQuantity(event, this)">
                <button class="quantity-button" onclick="increaseQuantity(event)">+</button>
            </div>
            <div class="total-price">$15.00</div>
            <button class="remove-item" onclick="removeItem()"><b>Remove</b></button>
        </div>
        <!-- End of Example Cart Items -->
        <div class="total-price">Total Price: $55.00</div>
    </div>

    <script>
        function decreaseQuantity() {
            let inputElement = event.target.parentElement.querySelector('.quantity-input');
            let currentValue = parseInt(inputElement.value, 10);
            if (currentValue > 1) {
                inputElement.value = currentValue - 1;
                updateTotalPrice();
            }
        }

        function increaseQuantity() {
            let inputElement = event.target.parentElement.querySelector('.quantity-input');
            let currentValue = parseInt(inputElement.value, 10);
            inputElement.value = currentValue + 1;
            updateTotalPrice();
        }

        function updateQuantity(event, input) {
            let currentValue = parseInt(input.value, 10);
            if (currentValue < 1) {
                input.value = 1;
            }
            updateTotalPrice();
        }



        function updateTotalPrice() {
            let cartItems = document.querySelectorAll('.cart-item');
            let totalPrice = 0;

            cartItems.forEach(item => {
                let quantity = parseInt(item.querySelector('.quantity-input').value, 10);
                let price = parseFloat(item.querySelector('.cart-item-details p:first-child').innerText.replace('Price: $', ''));
                totalPrice += quantity * price;
            });

            document.querySelector('.total-price').innerText = 'Total Price: $' + totalPrice.toFixed(2);
        }

    </script>

</body>
</html>
