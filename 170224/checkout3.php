<?php
    include "koneksi.php";

    session_start();

    mysqli_query($koneksi, "SET SESSION wait_timeout = 600"); // Mengatur waktu tunggu koneksi menjadi 600 detik (10 menit)

    // Periksa apakah $_POST['produkArray'] tersedia dan berisi data
    if(isset($_POST['produkArray']) && !empty($_POST['produkArray'])) {
        // Terima dan proses data produk dari keranjang belanja
        $produkArray = json_decode($_POST['produkArray'], true);
        if($produkArray) {
            // Data berhasil di-decode
            // Lanjutkan menampilkan detail produk dan total harga
            // Tetapi jangan lakukan pengambilan data produk kembali di sini
        } else {
            // Penanganan kesalahan jika tidak dapat mendekode data JSON
            echo " ";
        }
    } else {
        // Penanganan jika $_POST['produkArray'] tidak tersedia atau kosong
        echo "Tidak ada data produk yang diterima.";
    }

    // Pastikan query Anda mengambil kolom quantity_cart dan totalharga_cart dari tabel cart_info
    $queryCart = "SELECT namaproduk_cart, hargaproduk_cart, fotoproduk_cart, quantity_cart, totalharga_cart FROM cart_info";
    $resultCart = mysqli_query($koneksi, $queryCart);

    // Inisialisasi array untuk menyimpan detail produk
    $produkArrayFromDB = array();

    while ($dataCart = mysqli_fetch_array($resultCart, MYSQLI_ASSOC)) {
        // Ambil data produk dari hasil keranjang belanja
        $product_name = $dataCart['namaproduk_cart'];
        $product_price = $dataCart['hargaproduk_cart'];
        $product_image_src = 'data:image/*;base64,' . $dataCart['fotoproduk_cart'];
        $quantity_cart = isset($dataCart['quantity_cart']) ? $dataCart['quantity_cart'] : ''; // Periksa dan berikan nilai default jika kunci tidak ditemukan
        $totalharga_cart = isset($dataCart['totalharga_cart']) ? $dataCart['totalharga_cart'] : ''; // Periksa dan berikan nilai default jika kunci tidak ditemukan

        // Format data produk dan tambahkan ke array
        $produkArrayFromDB[] = array(
            'nama_produk' => $product_name,
            'harga_produk' => $product_price,
            'gambar_produk' => $product_image_src,
            'quantity_cart' => $quantity_cart,
            'totalharga_cart' => $totalharga_cart
            // Tambahkan atribut lainnya jika diperlukan
        );
    }

    // Ubah array produk menjadi format JSON
    $produkJSON = json_encode($produkArrayFromDB);

    // Menangkap data dari tabel login_info
    $queryUserInfo = "SELECT namauser_login, nohp_login, alamat_login FROM login_info";
    $resultUserInfo = mysqli_query($koneksi, $queryUserInfo);
    $dataUserInfo = mysqli_fetch_array($resultUserInfo);

    // Mendapatkan data nama user, no telp user, dan alamat user
    $nama_user = $dataUserInfo['namauser_login'];
    $no_telp_user = $dataUserInfo['nohp_login'];
    $alamat_user = $dataUserInfo['alamat_login'];

    // Memasukkan data ke tabel checkout_info
    $queryInsertCheckout = "INSERT INTO checkout_info (nama_co, nohp_co, alamat_co, produks_co) VALUES ('$nama_user', '$no_telp_user', '$alamat_user', '$produkJSON')";
    mysqli_query($koneksi, $queryInsertCheckout);

    // Menghitung total harga semua produk
    $totalAll = 0;
    foreach ($produkArrayFromDB as $produk) {
        $totalAll += $produk['totalharga_cart'];
    }

    // Memasukkan total harga ke dalam tabel checkout_info
    $queryUpdateTotal = "UPDATE checkout_info SET totalproduk_co = '$totalAll'";
    mysqli_query($koneksi, $queryUpdateTotal);
?>


<html>
    <head>
        <title>Checkout Now!</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            height: 100vh;
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


        .user-data {
            float: right;
            width: 30%; /* Menetapkan lebar div user-data */
            margin-left: 20px; /* Menambahkan margin kanan */
        }

        .user-data-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Menambahkan shadow */
        }

        .user-data-container h3 {
            margin-top: 0; /* Menghapus margin atas pada elemen h3 */
        }

        .user-data-container p {
            margin: 5px 0; /* Menambahkan margin pada elemen p */
        }

        .product-data{
            
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

        .btn-buat-pesanan {
            background-color: #4CAF50; /* Warna latar belakang hijau */
            color: white; /* Warna teks putih */
            padding: 10px 20px; /* Padding atas dan bawah 10px, kiri dan kanan 20px */
            border: none; /* Tanpa border */
            border-radius: 5px; /* Border radius 5px */
            cursor: pointer; /* Mengubah kursor menjadi pointer saat diarahkan */
            text-decoration: none; /* Tanpa dekorasi teks */
            font-size: 16px; /* Ukuran teks 16px */
            margin-top: 20px; /* Jarak atas 20px */
        }

        .dropdown {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        /* Style untuk tombol dropdown */
        .dropdown-button {
            background-color: #fff;
            color: black;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Menambahkan shadow */

        }

        /* Style untuk konten dropdown */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        /* Style untuk tautan dropdown */
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            cursor: pointer;
        }

        /* Style ketika tautan dihover */
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Tampilkan dropdown konten saat tombol dihover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Style untuk gambar kecil disamping tulisan */
        .dropdown-content img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }

        </style>
    </head>
    <body>
        <div class="navbar">
            <img src="logoikraa.png" />
            <a href="ikraland.php">Ikraland</a>
            <a href="indexcoba.php">Home</a>
            <a href="produkViewUser.php">Produk</a>
            <a href="#order">Order</a>
            <!-- <div class="container-search">
                <form action="#" method="get">
                    <input type="text" name="search" placeholder="Search...">
                    <button type="submit" class="search-button">
                        <img src="dummy/search-icon.png" alt="Search Icon">
                    </button>
                </form>
            </div>
            <a style="float:right" class="img-menu" href="login_user.php"><img src="profile.png" /></a> -->
            <a href="cart.php" style="float:right" class="img-menu"><img src="dummy/shop-cart.png" /></a>
        </div>
        <div class="user-data">
            <div class="user-data-container">
                <h3>Detail Pengiriman</h3>
                <hr>
                <p><b><?php echo $nama_user; ?></b></p>
                <p><?php echo $no_telp_user; ?></p>
                <p><?php echo $alamat_user; ?></p>
                <!-- Gantikan dengan data sesuai kebutuhan Anda -->
            </div>
            <br>
            <div class="dropdown">
                <p class="dropdown-button">Metode Pembayaran ></p>
                <div class="dropdown-content">
                    <a href="#"><img src="cod-icon.png" alt="COD"> Cash on Delivery</a>
                    <a href="#"><img src="dana-icon.png" alt="Dana"> Dana</a>
                    <a href="#"><img src="shopeepay-icon.png" alt="ShopeePay"> ShopeePay</a>
                    <a href="#"><img src="bank-icon.png" alt="Transfer Bank"> Transfer Bank</a>
                </div>
            </div>
            <br>
            <div class="user-data-container">
                <h3>Rincian Pembayaran</h3>
                <p>Subtotal untuk produk: Rp. <?php echo number_format($totalAll, 0, ',', '.'); ?></p>
                <p>Subtotal pengiriman : 20.000</p>
                <p>Biaya Layanan: 5.000</p>
                <hr>
                <p>Total Pembayaran : <?php echo number_format($totalAll + 20000 + 5000, 0, ',', '.');;?></p>
                <!-- Gantikan dengan data sesuai kebutuhan Anda -->
                <button class="btn-buat-pesanan">Buat Pesanan</button>
            </div>
        </div>
        <div class="product-data">
    <?php
    // Tampilkan detail produk dan total harga
    foreach ($produkArrayFromDB as $produk) {
        echo "<div class='cart-item'>";
        echo "<img src='" . $produk['gambar_produk'] . "' alt='" . $produk['nama_produk'] . "' style='width:100px; height:100px;'>";
        echo "<div class='cart-item-details'>";
        echo "<h3>" . $produk['nama_produk'] . "</h3>";
        echo "<p>Rp. " . number_format($produk['harga_produk'], 0, ',', '.') . "</p>";
        echo "</div>";
        // Tampilkan tombol-tombol untuk menyesuaikan jumlah
        echo "<div class='quantity-container'>";
        // Tampilkan tombol-tombol untuk mengurangi jumlah
        // Tampilkan input jumlah
        echo "<input class='quantity-input' type='text' value='" . $produk['quantity_cart'] . "' onchange='updateQuantity(event, this)'>";
        // Tampilkan tombol-tombol untuk menambah jumlah
        echo "</div>";
        // Tampilkan total harga untuk produk tersebut
        echo "<div class='total-price' id='totalPrice'>Rp. " . number_format($produk['totalharga_cart'], 0, ',', '.') . "</div>";
        // Tampilkan tombol untuk menghapus produk dari keranjang belanja
        echo "</div>";
    }
    ?>
</div>
    </body>
</html>
<?php
// Setelah Anda selesai dengan pengaturan session, simpan nilai $totalAll di dalam session
$_SESSION['totalAll'] = $totalAll;
?>
