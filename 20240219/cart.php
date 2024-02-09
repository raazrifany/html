<?php
    include "koneksi.php";

    $queryCart = "SELECT * FROM cart_info";
    $resultCart = mysqli_query($koneksi, $queryCart);
    $countCart = mysqli_num_rows($resultCart);
?>
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

        .navbar{
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            box-shadow: 0px 2px 20px rgba(78, 71, 71, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10000;
            width: 100%;
        }

        .navbar img{
            width: 40px;
            height: 40px;
            margin-left: 15px;
            margin-top: 10px;
            float: left;
        }

        .navbar a{
            float: left;
            color: black; /* warna tulisan */
            text-decoration: none;
            text-align: center;
            padding: 10px 10px;
            font-size: 18;
        margin: 5px;
        }

        .navbar a:hover{
            color: #508952;
        }

        .navbar a.active {
            color: #508952;
            background-color: #e9f1e9;
        }

        .navbar ul {
            position: fixed;
            top: 0;
        }

        .navbar .img-menu img{
            width: 35px;
            height: 35px;
            float: right;
            margin-top: 0px;
            margin-left: -15px;
        }

        .container-search {
            float: left;
            margin: 10px;
        }

        .container-search form {
            display: flex;
        }

        .container-search input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 50px 0 0 50px;
            outline: none;
        }

        .search-button {
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 0 50px 50px 0;
            cursor: pointer;
            width: 38px; /* Mengurangi lebar tombol */
            height: 38px; /* Mengurangi tinggi tombol */
        }

        .search-button img {
            width: 20px; /* Mengurangi lebar gambar di dalam tombol */
            height: 20px; /* Mengurangi tinggi gambar di dalam tombol */
            margin: 0;
        }

        .container-search input[type="text"],
        .search-button {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
        }

        * {
            box-sizing: border-box;
        }

        .cart-container {
            max-width: 800px;
            margin: auto;
            margin-top: 100px;
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
    <div class="navbar">
            <img src="logoikraa.png" />
            <a href="ikraland.php">Ikraland</a>
            <a href="indexcoba.php">Home</a>
            <a href="produkViewUser.php" class="active">Produk</a>
            <a href="#order">Order</a>
            <div class="container-search">
                <form action="#" method="get">
                    <input type="text" name="search" placeholder="Search...">
                    <button type="submit" class="search-button">
                        <img src="dummy/search-icon.png" alt="Search Icon">
                    </button>
                </form>
            </div>
            <a style="float:right" class="img-menu" href="login_user.php"><img src="profile.png" /></a>
            <a style="float:right" class="img-menu" href="#cart"><img src="dummy/shop-cart.png" /></a>
    </div>

    <div class="cart-container">
        <!-- Example Cart Items, replace with dynamic data from your backend -->
        <?php
            if(isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['product_price']) && isset($_POST['product_image'])) {
                $product_id = $_POST['product_id'];
                $product_name = urldecode($_POST['product_name']);
                $product_price = $_POST['product_price'];
                $product_image_base64 = $_POST['product_image'];            
                $product_image_src = 'data:image/*;base64,' . $product_image_base64;

                // Tampilkan item cart baru
                echo '<div class="cart-item">';
                echo '<img src="'.$product_image_src.'" alt="'.$product_name.'" style="width:100px; height: 100px;">';
                echo '<div class="cart-item-details">';
                echo '<h3>'.$product_name.'</h3>';
                echo '<p>Rp. '.number_format($product_price, 0, ',', '.').'</p>';
                echo '</div>';
                echo '<div class="quantity-container">';
                echo '<button class="quantity-button" onclick="decreaseQuantity(event)">-</button>';
                echo '<input class="quantity-input" type="text" value="1" onchange="updateQuantity(event, this)">';
                echo '<button class="quantity-button" onclick="increaseQuantity(event)">+</button>';
                echo '</div>';
                echo '<div class="total-price" id="totalPrice">Rp. '.number_format($product_price, 0, ',', '.').'</div>';
                echo '<button class="remove-item" onclick="removeItem()"><b>Remove</b></button>';
                echo '</div>';
            }
        ?>

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
    let quantity = parseInt(document.querySelector('.quantity-input').value, 10);
    let productPrice = parseFloat('<?php echo $product_price; ?>');

    let totalPrice = quantity * productPrice;
    totalPrice = parseInt(totalPrice); // Menghapus koma desimal

    document.getElementById('totalPrice').innerText = 'Rp. ' + totalPrice.toLocaleString('id-ID');
    }



    </script>

</body>
</html>
