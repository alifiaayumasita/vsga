<?php
$daftarJurusan = array("Purwokerto", "Purworejo", "Semarang", "Jogja", "Surabaya");

$daftarHarga = array("Purwokerto" => 100000, "Purworejo" => 150000, "Semarang" => 180000, "Jogja" => 200000, "Surabaya" => 250000);

$daftarDiskon = array("Purwokerto" => 0.05, "Purworejo" => 0.06, "Semarang" => 0.07, "Jogja" => 0.08, "Surabaya" => 0.1);

function totalHarga($daftarHarga, $jumlahpenumpang, $jurusan)
{
    $index = array_search($jurusan, $daftarHarga);
    return $daftarHarga[$jurusan] * $jumlahpenumpang;
}

function totalDiskon($jumlahpenumpang, $diskon, $totalHarga)
{
    if ($jumlahpenumpang > 5) {
        $totalDiskon = $totalHarga * $diskon;
        return $totalDiskon;
    } else {
        return 0;
    }
}

function totalBayar($jumlahpenumpang, $totalHarga, $totalDiskon)
{
    return ($jumlahpenumpang * $totalHarga) - $totalDiskon;
}

function deleteRow($index, &$dataOrderAll)
{
    unset($dataOrderAll[$index]);

    $dataJsonBaru = json_encode(array_values($dataOrderAll));
    file_put_contents("data-order.json", $dataJsonBaru);
}

$dataOrderAll = array();

if (isset($_POST['submit'])) {
    // code to process form submission and populate $dataOrder
    $noktp = $_POST['noktp'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hari = $_POST['hari'];
    $tanggal = $_POST['tanggal'];
    $jurusan = $_POST['jurusan'];
    $jumlahpenumpang = $_POST['jumlahpenumpang'];

    $totalHarga = totalHarga($daftarHarga, $jumlahpenumpang, $jurusan);
    $totalDiskon = totalDiskon($jumlahpenumpang, $daftarDiskon[$jurusan], $totalHarga);
    $totalBayar = totalBayar($jumlahpenumpang, $totalHarga, $totalDiskon);

    $dataOrder = array(
        "no_ktp" => $noktp,
        "nama" => $nama,
        "alamat" => $alamat,
        "hari" => $hari,
        "tanggal" => $tanggal,
        "jurusan" => $jurusan,
        "jumlah_penumpang" => $jumlahpenumpang,
        "total_harga" => $totalHarga,
        "total_diskon" => $totalDiskon,
        "total_bayar" => $totalBayar
    );

    $berkas = "data-order.json";
    if (file_exists($berkas)) {
        $dataJson = file_get_contents($berkas);
        $dataOrderAll = json_decode($dataJson, true);
    }

    array_push($dataOrderAll, $dataOrder);
	$dataJsonBaru = json_encode($dataOrderAll);
	file_put_contents($berkas, $dataJsonBaru);
}

if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    $dataJson = file_get_contents("data-order.json");
    $dataOrderAll = json_decode($dataJson, true);
    deleteRow($index, $dataOrderAll);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pemesanan Tiket</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Form Pemesanan Tiket</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="noktp" class="form-label">Nomor KTP:</label>
                <input type="text" id="noktp" name="noktp" class="form-control">
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" id="nama" name="nama" class="form-control">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <textarea id="alamat" name="alamat" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="hari" class="form-label">Hari Keberangkatan:</label>
                <input type="text" id="hari" name="hari" class="form-control">
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Keberangkatan:</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control">
            </div>
            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan:</label>
                <select id="jurusan" name="jurusan" class="form-control">
                    <?php foreach ($daftarJurusan as $jurusan) { ?>
                        <option value="<?php echo $jurusan; ?>"><?php echo $jurusan; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlahpenumpang" class="form-label">Jumlah Penumpang:</label>
                <input type="number" id="jumlahpenumpang" name="jumlahpenumpang" min="1" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Pesan</button>
        </form>

        <h2 class="mt-4">Daftar Pemesanan Tiket</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nomor KTP</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Hari Keberangkatan</th>
                    <th>Tanggal Keberangkatan</th>
                    <th>Jurusan</th>
                    <th>Jumlah Penumpang</th>
                    <th>Total Harga</th>
                    <th>Total Diskon</th>
                    <th>Total Bayar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataOrderAll as $index => $dataOrder) { ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $dataOrder["no_ktp"]; ?></td>
                        <td><?php echo $dataOrder["nama"]; ?></td>
                        <td><?php echo $dataOrder["alamat"]; ?></td>
                        <td><?php echo $dataOrder["hari"]; ?></td>
                        <td><?php echo $dataOrder["tanggal"]; ?></td>
                        <td><?php echo $dataOrder["jurusan"]; ?></td>
                        <td><?php echo $dataOrder["jumlah_penumpang"]; ?></td>
                        <td><?php echo $dataOrder["total_harga"]; ?></td>
                        <td><?php echo $dataOrder["total_diskon"]; ?></td>
                        <td><?php echo $dataOrder["total_bayar"]; ?></td>
                        <td><a href="?hapus=<?php echo $index; ?>" class="btn btn-danger">Hapus</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>