<?php
    include "koneksi.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menangkap data yang dikirimkan dari produkViewUser.php
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];

        // Periksa apakah item sudah ada di keranjang
        $queryCheckCart = "SELECT * FROM cart_info WHERE namaproduk_cart = '$product_name'";
        $resultCheckCart = mysqli_query($koneksi, $queryCheckCart);
        if(mysqli_num_rows($resultCheckCart) > 0) {
            // Jika item sudah ada, tinggal update jumlahnya
            $queryUpdateQuantity = "UPDATE cart_info SET quantity_cart = quantity_cart + 1 WHERE namaproduk_cart = '$product_name'";
            mysqli_query($koneksi, $queryUpdateQuantity);
        } else {
            // Jika item belum ada, tambahkan ke keranjang
            $queryAddToCart = "INSERT INTO cart_info (kategoriproduk_cart, namaproduk_cart, hargaproduk_cart, fotoproduk_cart, quantity_cart) VALUES ('$product_id', '$product_name', '$product_price', '$product_image', 1)";
            mysqli_query($koneksi, $queryAddToCart);
        }
    }
    
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
        
        if ($countCart > 0) {
            while ($dataCart = mysqli_fetch_array($resultCart, MYSQLI_ASSOC)) {
                $product_id = $dataCart['kategoriproduk_cart'];
                $product_name = $dataCart['namaproduk_cart'];
                $product_price = $dataCart['hargaproduk_cart'];
                $product_image_src = 'data:image/*;base64,' . $dataCart['fotoproduk_cart'];
        ?>

                <div class="cart-item">
                    <img src="<?php echo $product_image_src; ?>" alt="<?php echo $product_name; ?>" style="width:100px; height: 100px;">
                    <div class="cart-item-details">
                        <h3><?php echo $product_name; ?></h3>
                        <p>Rp. <?php echo number_format($product_price, 0, ',', '.'); ?></p>
                    </div>
                    <div class="quantity-container">
                        <button class="quantity-button" onclick="decreaseQuantity(event)">-</button>
                        <!-- Ubah bagian input quantity menjadi seperti berikut -->
                        <input class="quantity-input" type="text" value="<?php echo $dataCart['quantity_cart']; ?>" onchange="updateQuantity(event, this)">
                        <button class="quantity-button" onclick="increaseQuantity(event)">+</button>
                    </div>
                    <div class="total-price" id="totalPrice">Rp. <?php echo number_format($dataCart['totalharga_cart'], 0, ',', '.'); ?></div>
                    <button class="remove-item"><a href="deleteCart.php?namaproduk_cart=<?php echo $dataCart['namaproduk_cart']; ?>" style="text-decoration: none; color: black;"><b>Remove</b></a></button>
                </div>
                <?php
            }
        } else {
            // Tampilkan pesan jika keranjang kosong
            echo "<p>Keranjang belanja kosong.</p>";
        }
        ?>
        <!-- Total Price -->
        <div class="total-price">Total Price: $55.00</div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Panggil fungsi untuk memperbarui tampilan keranjang belanja dari local storage
        updateCartFromLocalStorage();

        // Panggil fungsi untuk mengupdate total harga
        updateTotalPrice();
    });
    // Fungsi untuk menyimpan data keranjang belanja ke local storage
    function saveCartToLocalStorage(cartData) {
        localStorage.setItem('cart', JSON.stringify(cartData));
    }

    // Fungsi untuk memperbarui tampilan keranjang belanja dari local storage
    function updateCartFromLocalStorage() {
        let cartData = JSON.parse(localStorage.getItem('cart'));
        if (cartData) {
            Object.keys(cartData).forEach(function(productName) {
                let quantity = cartData[productName];
                let inputElement = document.querySelector('.cart-item-details h3:contains("' + productName + '")').nextElementSibling.querySelector('.quantity-input');
                inputElement.value = quantity;
                updateTotalPrice();
            });
        }
    }

    // Fungsi untuk mengurangi jumlah produk
    function decreaseQuantity(event) {
        let inputElement = event.target.parentElement.querySelector('.quantity-input');
        let currentValue = parseInt(inputElement.value, 10);
        if (currentValue > 1) {
            inputElement.value = currentValue - 1;
            updateTotalPrice(); // Panggil updateTotalPrice setiap kali jumlah produk diubah
            updateQuantity(inputElement);
        }
    }

    // Fungsi untuk menambah jumlah produk
    function increaseQuantity(event) {
        let inputElement = event.target.parentElement.querySelector('.quantity-input');
        let currentValue = parseInt(inputElement.value, 10);
        inputElement.value = currentValue + 1;
        updateTotalPrice(); // Panggil updateTotalPrice setiap kali jumlah produk diubah
        updateQuantity(inputElement);
    }

    // Fungsi untuk mengirim total harga ke backend
    function updateQuantity(input) {
        // Mendapatkan nilai jumlah produk dan harga produk
        let currentValue = parseInt(input.value, 10);
        let productName = input.parentElement.parentElement.querySelector('.cart-item-details h3').innerText;
        let productPrice = parseFloat(input.parentElement.parentElement.querySelector('.cart-item-details p').innerText.replace('Rp. ', '').replace('.', '').replace(',', '.'));

        // Menghitung total harga
        let totalPrice = currentValue * productPrice;

        // Membuat objek data untuk dikirim ke backend
        let data = {
            'productName': productName,
            'quantity': currentValue,
            'productPrice': productPrice,
            'totalPrice': totalPrice // Menambahkan total harga ke data yang dikirim
        };

        // Kirim permintaan AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'updateCart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Berhasil melakukan update, mungkin Anda ingin melakukan sesuatu di sini
                console.log('Jumlah produk berhasil diperbarui.');
            } else {
                // Gagal melakukan update
                console.error('Gagal memperbarui jumlah produk.');
            }
        };
        xhr.send(JSON.stringify(data));
    }


    // Fungsi untuk mengupdate total harga
    function updateTotalPrice() {
        let totalPriceElements = document.querySelectorAll('.total-price');
        totalPriceElements.forEach(function(element) {
            let quantity = parseInt(element.parentElement.querySelector('.quantity-input').value, 10);
            let productPrice = parseFloat(element.parentElement.querySelector('.cart-item-details p').innerText.replace('Rp. ', '').replace('.', '').replace(',', '.'));
            let totalPrice = quantity * productPrice;
            element.innerText = 'Rp. ' + totalPrice.toLocaleString('id-ID');
        });
    }


    // Panggil fungsi untuk memperbarui tampilan keranjang belanja dari local storage
    updateCartFromLocalStorage();

    // Panggil fungsi untuk mengupdate total harga
    updateTotalPrice();
    </script>

</body>
</html>
