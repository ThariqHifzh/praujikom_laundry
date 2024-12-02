<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['name'])) {

    header("location:login.php?error=access-failed");
}

// untuk memfilter data
$tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
$tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT customer.customer_name, trans_order.* FROM trans_order LEFT JOIN customer ON customer.id=trans_order.id_customer WHERE 1";

if ($tanggal_dari != '') {
    $query .= " AND order_date >= '$tanggal_dari'";
}

if ($tanggal_sampai != '') {
    $query .= " AND order_date <= '$tanggal_sampai'";
}

// jika status tidak kosong
if ($status != '') {
    $query .= " AND order_status = '$status'";
}


$query .= " ORDER BY trans_order.id DESC";

$queryTrans = mysqli_query($koneksi, $query);
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
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row" style="margin-left: 150px;">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header fw-bold" style="font-size: x-large;">Transaksi Laundry</div>
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <!-- filter data transaksi -->
                                        <form action="" method="get">
                                            <div class="mb-3 d-flex">
                                                <div class="col-sm-3">
                                                    <label for="">Tanggal Dari</label>
                                                    <input type="date" name="tanggal_dari" class="form-control">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="">Tanggal Sampai</label>
                                                    <input type="date" name="tanggal_dari" class="form-control">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="">Status</label>
                                                    <select name="status" id="" class="form-control">
                                                        <option value="">--Pilih Status--</option>
                                                        <option value="0">Baru</option>
                                                        <option value="1">Sudah Dikembalikan</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 mt-4">
                                                    <button name="filter" class="btn  btn-primary"><i class="ti ti-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <table class=" table table-bordered bg-transparent">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Transaksi</th>
                                                    <th>Nama Costumer</th>
                                                    <th>Tanggal Laundry</th>
                                                    <th>Status</th>
                                                    <?php if ($_SESSION['id_level'] == 1) : ?>
                                                        <th>Settings</th>
                                                    <?php endif ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                while ($rowTrans = mysqli_fetch_assoc($queryTrans)): ?>
                                                    <tr>
                                                        <td><?php echo $no++ ?></td>
                                                        <td><?php echo $rowTrans['order_code'] ?></td>
                                                        <td><?php echo $rowTrans['customer_name'] ?></td>
                                                        <td><?php echo $rowTrans['order_date'] ?></td>
                                                        <td>
                                                            <?php switch ($rowTrans['order_status']) {
                                                                case '1':
                                                                    $badge = "<span class='badge bg-primary'>Sudah Dikembalikan</span>";
                                                                    break;

                                                                default:
                                                                    $badge = "<span class='badge bg-warning'>Baru</span>";
                                                                    break;
                                                            }
                                                            echo $badge; ?>
                                                        </td>
                                                        <?php if ($_SESSION['id_level'] == 1) : ?>
                                                            <td>
                                                                <a class="btn btn-primary "
                                                                    href="add-order.php?detail=<?php echo $rowTrans['id'] ?>"><i class="ti ti-eye"></i>
                                                                </a>
                                                                <a target="_blank" class="btn btn-warning "
                                                                    href="print.php?id=<?php echo $rowTrans['id'] ?>"><i class="ti ti-printer"></i></a>
                                                            </td>
                                                        <?php endif ?>
                                                    </tr>
                                                <?php endwhile ?>

                                            </tbody>
                                        </table>
                                        <?php if ($_SESSION['id_level'] == 2) : ?>
                                            <td>
                                                <a href="print-semua.php" type="submit" class=" btn-primary text-white px-4" name="simpan" value="Hitung"
                                                    style="border-radius: 20px; margin-top: 50px; margin-left: 600px;">Print</a>
                                            </td>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <?php include 'inc/footer.php' ?>
</body>

</html>