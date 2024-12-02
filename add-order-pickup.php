<?php
include 'koneksi.php';
session_start();

$queryCustomer = mysqli_query($koneksi, "SELECT * FROM customer");
$id = isset($_GET['ambil']) ? $_GET['ambil'] : '';
$queryTransDetail = mysqli_query($koneksi, "SELECT customer.customer_name, customer.phone, customer.address, trans_order.order_code, trans_order.order_pay, trans_order.order_change, trans_order.order_end_date, trans_order.id_customer, trans_order.order_date, trans_order.order_status, type_of_service.service_name, type_of_service.price, trans_order_detail.* FROM trans_order_detail 
LEFT JOIN type_of_service ON type_of_service.id = trans_order_detail.id_service 
LEFT JOIN trans_order ON trans_order.id = trans_order_detail.id_order 
LEFT JOIN customer ON trans_order.id_customer = customer.id 
WHERE trans_order_detail.id_order = '$id'");
$row = [];
while ($dataTrans = mysqli_fetch_assoc($queryTransDetail)) {
    $row[] = $dataTrans;
}

$queryPaket = mysqli_query($koneksi, "SELECT * FROM type_of_service");
$rowPaket = [];
while ($data = mysqli_fetch_assoc($queryPaket)) {
    $rowPaket[] = $data;
}

$queryTransPickup = mysqli_query($koneksi, "SELECT * FROM trans_laundry_pickup WHERE id_order ='$id'");

if (isset($_POST['simpan_transaksi'])) {
    $id_customer = $_POST['id_customer'];
    $id_order = $_POST['id_order'];
    $order_pay = $_POST['order_pay'];
    $order_change = $_POST['order_change'];

    $pickup_date = date("Y-m-d");

    // insert ke table trans_laundry_pickup
    $insert =  mysqli_query($koneksi, "INSERT INTO trans_laundry_pickup (id_order, id_customer, pickup_date) 
    VALUES ('$id_order','$id_customer', '$pickup_date')");

    // ubah status order jadi satu=sudah diambil
    $updateTransOrder = mysqli_query($koneksi, "UPDATE trans_order SET order_pay = '$order_pay', 
        order_change = '$order_change',  order_status = 1  WHERE id = '$id_order'");

    header("location:order.php?tambah=berhasil");
}


// No Invoice
// 001, jika ada auto increment id = 1 = 002, selain itu 001
// MAX : terbesar MIN : terkecil
$queryInvoice = mysqli_query($koneksi, "SELECT MAX(id) AS no_invoice FROM trans_order");
// Jika didalam table trans order ada datanya
$str_unique = "TR";
$date_now = date("dmy");
if (mysqli_num_rows($queryInvoice) > 0) {
    $rowInvoice = mysqli_fetch_assoc($queryInvoice);
    $incrementPlus = $rowInvoice['no_invoice'] + 1;
    $code = $str_unique . "/" . $date_now . "/" . "000" . $incrementPlus;
} else {
    $code = $str_unique . "/" . $date_now . "/" . "000" . "0001";
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
                    <?php if (isset($_GET['ambil'])): ?>
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row" style="margin-left: 150px;">
                                <div class="col-sm-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5 class="fw-bold text-primary">Pengambilan Laundry <h5 class="fw-bold"><?php echo $row[0]['customer_name'] ?></h5>
                                                    </h5>
                                                </div>
                                                <div class="col-sm-6" align="right">
                                                    <a href="order.php" class="btn btn-secondary"><i class="ti ti-arrow-left"></i></a>
                                                    <a href="print.php?id=<?php echo $id ?>" class="btn btn-success"><i class="ti ti-printer"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Data Transaksi</h5>
                                        </div>
                                        <?php include 'helper.php' ?>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>No Invoice</th>
                                                    <td><?php echo $row[0]['order_code'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Laundry</th>
                                                    <td><?php echo $row[0]['order_date'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td><?php echo changeStatus($row[0]['order_status']) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Data Customer</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">

                                                <tr>
                                                    <th>Nama</th>
                                                    <td><?php echo $row[0]['customer_name'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Telpon</th>
                                                    <td><?php echo $row[0]['phone'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Alamat</th>
                                                    <td><?php echo $row[0]['address'] ?></td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Detail Transaksi</h5>
                                        </div>
                                        <div class="card-body">
                                            <form action="" method="post">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Paket</th>
                                                            <th>Qty</th>
                                                            <th>Harga</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $no = 1;
                                                        $total = 0;
                                                        foreach ($row as $key => $value): ?>
                                                            <tr>
                                                                <td><?php echo $no++ ?></td>
                                                                <td><?php echo $value['service_name'] ?></td>
                                                                <td><?php echo $value['qty'] ?></td>
                                                                <td><?php echo "Rp " . number_format($value['price']) ?></td>
                                                                <td><?php echo "Rp " . number_format($value['subtotal']) ?></td>
                                                            </tr>
                                                            <?php
                                                            $total = $total + $value['subtotal'];
                                                            ?>
                                                        <?php endforeach ?>
                                                        <tr>
                                                            <td colspan="4" align="center">
                                                                <strong>Total Keseluruhan</strong>
                                                            </td>
                                                            <td>
                                                                <strong><?php echo "Rp " . number_format($total) ?></strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="center">
                                                                <strong>Dibayar</strong>
                                                            </td>
                                                            <td>
                                                                <strong>
                                                                    <?php if (mysqli_num_rows($queryTransPickup)): ?>
                                                                        <?php $rowTransPickup = mysqli_fetch_assoc($queryTransPickup); ?>
                                                                        <input type="text" name="order_pay" placeholder="Dibayar" class="form-control" value="<?php echo "Rp " . number_format($rowTransPickup['order_pay']) ?>" readonly>
                                                                    <?php else: ?>
                                                                        <input type="text" name="order_pay" placeholder="Dibayar" class="form-control" value="<?php echo isset($_POST['order_pay']) ? $_POST['order_pay'] : '' ?>">
                                                                    <?php endif ?>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="center">
                                                                <strong>Kembalian</strong>
                                                            </td>
                                                            <?php
                                                            if (isset($_POST['proses_kembalian'])) {
                                                                $total = $_POST['total'];
                                                                $dibayar = $_POST['order_pay'];

                                                                $kembalian = 0;
                                                                $kembalian = $dibayar - $total;
                                                            };
                                                            ?>
                                                            <td>
                                                                <strong>
                                                                    <?php if (mysqli_num_rows($queryTransPickup) > 0): ?>
                                                                        <input type="text" name="order_change" placeholder="Kembalian" class="form-control" value="<?php echo "Rp " . number_format($rowTransPickup['order_change']) ?>" readonly>
                                                                    <?php else: ?>
                                                                        <input type="text" name="order_change" placeholder="Kembalian" class="form-control" value="<?php echo isset($kembalian) ? $kembalian : 0 ?>" readonly>
                                                                    <?php endif ?>
                                                                    <input type="hidden" name="total" value="<?php echo $total ?>">
                                                                    <input type="hidden" name="id_customer" value="<?php echo $row[0]['id_customer'] ?>">
                                                                    <input type="hidden" name="id_order" value="<?php echo $row[0]['id_order'] ?>">
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php if ($row[0]['order_status'] == 0): ?>
                                                            <tr>
                                                                <td colspan="5" align="right">
                                                                    <button class="btn btn-primary" name="proses_kembalian">Hitung</button>
                                                                    <button class="btn btn-secondary" name="simpan_transaksi">Simpan</button>
                                                                </td>
                                                            </tr>
                                                        <?php endif ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <legend class="float-none w-auto px-3 fw-bold">
                                                    <?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Transaksi</legend>

                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Kategori</label>
                                                    <select name="id_customer" id="" class="form-control">
                                                        <option value="">-- Pilih Customer --</option>

                                                        <!-- option yang datanya di ambil dari table kategori -->
                                                        <?php while ($rowCustomer = mysqli_fetch_assoc($queryCustomer)): ?>
                                                            <option value="<?php echo $rowCustomer['id'] ?>">
                                                                <?php echo $rowCustomer['customer_name'] ?></option>
                                                        <?php endwhile ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3 mt-3 row">
                                                    <div class="col-sm-6">
                                                        <label for="" class="form-label">No Invoice</label>
                                                        <input type="text" class="form-control" name="order_code"
                                                            value="<?php echo $code ?>"
                                                            readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="" class="form-label">Tanggal Order</label>
                                                        <input type="date" class="form-control" name="order_date" required value="<?php echo isset($_GET['edit']) ? $rowEdit['order_date'] : '' ?>">
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <legend class="float-none w-auto px-3 fw-bold">Detail Transaksi</legend>

                                                <div class="mb-3 row">
                                                    <div class="col-sm-3">
                                                        <label for="" class="form-label">Paket</label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <select name="id_service[]" id="" class="form-control">
                                                            <option value="">-- Pilih Paket --</option>
                                                            <?php foreach ($rowPaket as $key => $value) { ?>

                                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['service_name'] ?></option>

                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3 row">
                                                    <div class="col-sm-3">
                                                        <label for="" class="form-label">Qty</label>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" placeholder="Qty" name="qty[]">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <div class="col-sm-3">
                                                        <label for="" class="form-label">Paket</label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <select name="id_service[]" id="" class="form-control">
                                                            <option value="">-- Pilih Paket --</option>
                                                            <?php foreach ($rowPaket as $key => $value) { ?>

                                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['service_name'] ?></option>

                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-3 row">
                                                    <div class="col-sm-3">
                                                        <label for="" class="form-label">Qty</label>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" placeholder="Qty" name="qty[]">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <button class="btn btn-primary ms-4" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif ?>
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