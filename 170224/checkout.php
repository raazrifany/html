<?php
    include "koneksi.php";

    session_start();

    mysqli_query($koneksi, "SET SESSION wait_timeout = 600"); // Mengatur waktu tunggu koneksi menjadi 600 detik (10 menit)


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menangkap data yang dikirimkan dari produkViewUser.php
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];

        // Tetapkan $product_price sebagai nilai awal $total_harga saat menambahkan item baru ke keranjang
        $total_harga = $product_price;

        // Periksa apakah item sudah ada di keranjang
        $queryCheckCart = "SELECT * FROM cart_info WHERE namaproduk_cart = '$product_name'";
        $resultCheckCart = mysqli_query($koneksi, $queryCheckCart);
        if(mysqli_num_rows($resultCheckCart) > 0) {
            // Jika item sudah ada, tinggal update jumlahnya
            $queryUpdateQuantity = "UPDATE cart_info SET quantity_cart = quantity_cart + 1 WHERE namaproduk_cart = '$product_name'";
            mysqli_query($koneksi, $queryUpdateQuantity);
        } else {
            // Jika item belum ada, tambahkan ke keranjang
            $queryAddToCart = "INSERT INTO cart_info (kategoriproduk_cart, namaproduk_cart, hargaproduk_cart, fotoproduk_cart, quantity_cart, totalharga_cart) VALUES ('$product_id', '$product_name', '$product_price', '$product_image', 1, '$total_harga')";
            mysqli_query($koneksi, $queryAddToCart);
        }
    }
    
    // Ambil data produk dari tabel cart_info
    $queryCart = "SELECT * FROM cart_info";
    $resultCart = mysqli_query($koneksi, $queryCart);

    // Inisialisasi array untuk menyimpan detail produk
    $produkArray = array();

    while ($dataCart = mysqli_fetch_array($resultCart, MYSQLI_ASSOC)) {
        // Ambil data produk dari hasil keranjang belanja
        $product_name = $dataCart['namaproduk_cart'];
        $product_price = $dataCart['hargaproduk_cart'];
        $product_image_src = 'data:image/*;base64,' . $dataCart['fotoproduk_cart'];

        // Format data produk dan tambahkan ke array
        $produkArray[] = array(
            'nama_produk' => $product_name,
            'harga_produk' => $product_price,
            'gambar_produk' => $product_image_src
            // Tambahkan atribut lainnya jika diperlukan
        );
    }

    // Ubah array produk menjadi format JSON
    $produkJSON = json_encode($produkArray);

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
    foreach ($produkArray as $produk) {
        $totalAll += $produk['harga_produk'];
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


        </style>
    </head>
    <body>
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
            <div class="user-data-container">
                <p>Metode Pembayaran ></p>
                <!-- Gantikan dengan data sesuai kebutuhan Anda -->
            </div>
            <br>
            <div class="user-data-container">
                <p>Subtotal untuk produk: Rp. <?php echo number_format($totalAll, 0, ',', '.'); ?></p>
                <p>Subtotal pengiriman</p>
                <p>Biaya Layanan</p>
                <hr>
                <p>Total Pembayaran</p>
                <!-- Gantikan dengan data sesuai kebutuhan Anda -->
                <button class="btn-buat-pesanan">Buat Pesanan</button>
            </div>
        </div>
        <div class="product-data">
    <?php
    while ($dataCart = mysqli_fetch_array($resultCart, MYSQLI_ASSOC)) {
        // Ambil data produk dari hasil keranjang belanja
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
                    <!-- Tambahkan tombol kurang dan input jumlah -->
                    <button class="quantity-button" onclick="decreaseQuantity(event)">-</button>
                    <input class="quantity-input" type="text" value="<?php echo $dataCart['quantity_cart']; ?>" onchange="updateQuantity(event, this)">
                    <!-- Tambahkan tombol tambah -->
                    <button class="quantity-button" onclick="increaseQuantity(event)">+</button>
                </div>
                <div class="total-price" id="totalPrice">Rp. <?php echo number_format($dataCart['totalharga_cart'], 0, ',', '.'); ?></div>
                <!-- Tombol hapus -->
                <button class="remove-item"><a href="deleteCart.php?namaproduk_cart=<?php echo $dataCart['namaproduk_cart']; ?>" style="text-decoration: none; color: black;"><b>Remove</b></a></button>
            </div>
        <?php } ?>
    </div>

    </body>
</html>
<?php
// Setelah Anda selesai dengan pengaturan session, simpan nilai $totalAll di dalam session
$_SESSION['totalAll'] = $totalAll;
?>
