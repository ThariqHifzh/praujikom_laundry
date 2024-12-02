<?php
include 'koneksi.php';
session_start();

if (isset($_POST['simpan'])) {
    $nama   = $_POST['customer_name'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    // $_POST: form input name=''
    // $_GET: url ?param='nilai'
    // $_FILES: ngambil nilai dari input type file
    if (!empty($_FILES['foto']['name'])) {
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];

        // png, jpg, jpeg
        $ext = array('png', 'jpg', 'jpeg');
        $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

        // JIKA EXTESI FOTO TIDAK ADA YANG TERDAFTAR DI ARRAY EXTENSI
        if (!in_array($extFoto, $ext)) {
            echo "Ext foto tidak ditemukan";
            die;
        } else {
            // Pindahkan gambar dari tmp folder ke folder yang telah kita buat
            move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

            $insert = mysqli_query($koneksi, "INSERT INTO customer (customer_name, phone, address, foto) VALUES
            ('$nama', '$phone', '$address', '$nama_foto')");
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO customer (customer_name, phone, address) VALUES
            ('$nama', '$phone', '$address')");
    }

    header("location:customer.php?tambah=berhasil");
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$editUser = mysqli_query(
    $koneksi,
    "SELECT * FROM customer WHERE id = '$id'"
);
$rowEdit = mysqli_fetch_assoc($editUser);

if (isset($_POST['edit'])) {
    $nama   = $_POST['customer_name'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    // ubah user kolom apa yang mau di ubah (SET), yang mau di ubah id ke berapa
    $update = mysqli_query($koneksi, "UPDATE customer SET customer_name='$nama', phone='$phone', address='$address' WHERE id='$id'");
    header("location:customer.php?ubah=berhasil");
}

// jika parameternya ada ?delete=nilai parameter
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // mengambil nilai parameter

    // query / perintah hapus
    $delete = mysqli_query($koneksi, "DELETE FROM customer WHERE id ='$id'");
    header("location:customer.php?hapus=berhasil");
}

$queryLevel = mysqli_query($koneksi, "SELECT * FROM level");
?>


<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>Laundry</title>

<meta name="description" content="" />

<?php include 'inc/hea.php'; ?>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <?php include 'inc/side.php'; ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            <?php include 'inc/nav.php'; ?>

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-sm-12" style="margin-left: 100px; margin-top: 20px;">
                            <div class="card">
                                    <div class="card-body">
                                        <legend class="float-none w-auto px-3 fw-bold">
                                            <?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Customer</legend>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama Customer</label>
                                                    <input type="text" class="form-control" name="customer_name" placeholder="Masukkan Nama Anda" required value="<?php echo isset($_GET['edit']) ? $rowEdit['customer_name'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Telpon</label>
                                                    <input type="text" class="form-control" name="phone" placeholder="Masukkan Telpon" required value="<?php echo isset($_GET['edit']) ? $rowEdit['phone'] : '' ?>">
                                                </div>
                                            </div>
                                            
                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Alamat</label>
                                                    <input type="text" class="form-control" name="address" placeholder="Masukkan Alamat" required value="<?php echo isset($_GET['edit']) ? $rowEdit['address'] : '' ?>">
                                                </div>
                                       </div>
                                            <!-- <div class="mb-3 row">
                                                    <div class="col-sm-12">
                                                        <label for="" class="form-label">Foto</label>
                                                        <input type="file" name="foto" >
                                                    </div>
                                                </div> -->
                                            <div class="mb-3">
                                                <button class="btn btn-primary ms-4" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <?php include "inc/footer.php"; ?>
</body>

</html>