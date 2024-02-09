<?php
// Include koneksi ke database
include "koneksi.php";

// Menerima data yang dikirimkan melalui metode POST
$data = json_decode(file_get_contents("php://input"), true);

// Mendapatkan nama produk dan jumlah baru dari data yang diterima
$productName = $data['productName'];
$quantity = $data['quantity'];
$productPrice = $data['productPrice'];
$totalPrice = $data['totalPrice'];

// Perbarui jumlah produk dalam database
$queryUpdateCart = "UPDATE cart_info SET quantity_cart = '$quantity', totalharga_cart = '$totalPrice' WHERE namaproduk_cart = '$productName'";
$resultUpdateQuantity = mysqli_query($koneksi, $queryUpdateCart);

// Periksa apakah perbaruan berhasil
if ($resultUpdateQuantity) {
    // Kirim respon HTTP 200 (OK)
    http_response_code(200);
    echo json_encode(array("message" => "Jumlah produk berhasil diperbarui."));
} else {
    // Kirim respon HTTP 500 (Internal Server Error) jika terjadi kesalahan
    http_response_code(500);
    echo json_encode(array("message" => "Gagal memperbarui jumlah produk."));
}
?>
