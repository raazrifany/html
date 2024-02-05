<?php
    include "koneksi.php";

    $querySeller = "SELECT * FROM produk";
    $resultSeller = mysqli_query($koneksi, $querySeller);
    $countSeller = mysqli_num_rows($resultSeller);
?>
<html>
    <head>
        <title>Product for Customer</title>
        <link rel="stylesheet" href="produkViewUser.css"/>
    </head>
    <body>
        <!-- navbar-->
        <div class="navbar">
            <img src="logoikraa.png" />
            <a href="ikraland.php">Ikraland</a>
            <a href="indexcoba.php">Home</a>
            <a href="cobaproduk.php" class="active">Produk</a>
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

        <div class="main">

            <h1>IKRALAND</h1>
            <hr>

            <h2>PRODUK</h2>

            <div id="myBtnContainer">
                <button class="btn active" onclick="filterSelection('all')"> Show all</button>
                <button class="btn" onclick="filterSelection('frozen')"> Frozen</button>
                <button class="btn" onclick="filterSelection('marinasi')"> Marinasi</button>
                <button class="btn" onclick="filterSelection('dine-in')"> Dine In</button>
            </div>

            <!-- Portfolio Gallery Grid -->
            <div class="row">
                <?php
                if($countSeller > 0){
                    while($dataSeller = mysqli_fetch_array($resultSeller, MYSQLI_NUM))
                    {
                ?>
                <div class="column  <?php echo $dataSeller[1]; ?>">
                    <div class="content">
                        <?php
                        // Menampilkan gambar dari database
                        $foto_produk  = $dataSeller[4];
                        $gambarBase64 = base64_encode($foto_produk);
                        $gambarSrc = 'data:image/*;base64,' . $gambarBase64;
                        ?>
                        <img src="<?php echo $gambarSrc; ?>" alt="<?php echo $dataSeller[3]; ?>" style="width:100%" />
                        <h4 class="product-title"><?php echo $dataSeller[0];?></h4>
                        <p class="price">Rp. <?php echo number_format($dataSeller[2], 0, ',', '.'); ?></p>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $dataSeller[0]; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $dataSeller[3]; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $dataSeller[2]; ?>">
                            <input type="hidden" name="product_image" value="<?php echo base64_encode($dataSeller[4]); ?>">
                            <button class="add-to-cart" type="submit">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php
                }
            }else{
                
            }
            ?>
                
            <!-- END GRID -->
            </div>

        <!-- END MAIN -->
        </div>

        <script>
filterSelection("all")
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("column");
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
    w3RemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
  }
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}


// Add active class to the current button (highlight it)
var btnContainer = document.getElementById("myBtnContainer");
var btns = btnContainer.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function(){
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>

    </body>
    <footer>
        <div class="footer-left" style="float: left;">
            <a href="https://maps.app.goo.gl/UjeZJopd95Kv8B248"><p><b>IKRALAND</b><br>626J+WFG, Jl. Nasional 5, Padanaan, Kec. Paseh, Kabupaten Sumedang, Jawa Barat 45381<br>Jawa Barat<br>0878-8801-3463</p></a>
        </div>
        <div class="footer-right" style="float: right;">
            <table>
                <tr>
                    <td width="30px"><a href="https://web.whatsapp.com/"><img src="dummy/footer-wa.png" width="20px" height="20px"/></a></td>
                    <td width="30px"><a href="https://www.instagram.com/ikraland.wisataedukasi?utm_source=ig_web_button_share_sheet&igsh=OGQ5ZDc2ODk2ZA==/"><img src="dummy/footer-instagram.png" width="20px" height="20px"/></a></td>
                    <td width="30px"><a href="https://www.tiktok.com/@ikradokterunpad?is_from_webapp=1&sender_device=pc"><img src="dummy/footer-tiktok.png" width="20px" height="20px"/></a></td>
                    <td width="30px"><a href="https://youtube.com/@ikradokterunpad?si=zPgU-9YqsNJGIT50"><img src="dummy/footer-youtube.png" width="20px" height="20px"/></a></td>
                </tr>
            </table>
        </div>
        <hr>
        <p align="center">&copy; Copyright 2024 by Ikraland. All Rights Reserved.</p>
    </footer>
</html>
