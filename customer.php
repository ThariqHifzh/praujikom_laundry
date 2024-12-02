<?php
session_start();
include 'koneksi.php';
// munculkan / pilih sebuah atau semua kolom dari table user
$queryCustomer = mysqli_query($koneksi, "SELECT * FROM customer");
// mysqli_fetch_assoc($query) = untuk menjadikan hasil query menjadi sebuah data (object, array)

// jika parameternya ada ?delete=nilai parameter
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // mengambil nilai parameter

    // query / perintah hapus
    $delete = mysqli_query($koneksi, "DELETE FROM customer WHERE id ='$id'");
    header("location:customer.php?hapus=berhasil");
}
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
    data-assets-path="assets/"
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
                            <div class="col-sm-12" style="margin-left: 100px;">
                                <div class="card">
                                    <div class="card-header fw-bold" style="font-size: x-large;">Data Customer</div>
                                    <div class="card-body">
                                        <?php if (isset($_GET['hapus'])): ?>
                                            <div class="alert alert-primary" role="alert">Data berhasil dihapus</div>
                                        <?php endif ?>
                                        <div class="mb-3" align="right">
                                            <a href="add-customer.php" class="btn btn-primary">Tambah</a>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Customer</th>
                                                    <th>Telpon</th>
                                                    <th>Alamat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)): ?>
                                                    <tr>
                                                        <td><?php echo $no++ ?></td>
                                                        <td><?php echo $rowCustomer['customer_name'] ?></td>
                                                        <td><?php echo $rowCustomer['phone'] ?></td>
                                                        <td><?php echo $rowCustomer['address'] ?></td>
                                                        <td>
                                                            <a class="btn btn-success btn"
                                                                href="add-customer.php?edit=<?php echo $rowCustomer['id'] ?>"><i
                                                                class="ti ti-edit"></i></a>

                                                            <a class="btn btn-danger btn"
                                                                onclick="return confirm('Apakah Anda Yakin untuk Menghapus Data Ini?')"
                                                                href="customer.php?delete=<?php echo $rowCustomer['id'] ?>"><i
                                                                class="ti ti-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile ?>
                                            </tbody>
                                        </table>
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