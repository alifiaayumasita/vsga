<?php
    $berkas = "data.json";
    $dataJson = file_get_contents($berkas);
    $dataBuahAll = json_decode($dataJson, true);

    //Array Daftar Buah
    $daftarBuah = array("Durian", "Mangga", "Rambutan", "Kelengkeng", "Apel");

    //Array Daftar Harga
    $daftarHargaBuah = array("Durian" => 20000, "Mangga" => 15000, "Rambutan" => 10000, "Kelengkeng" => 25000, "Apel" => 30000);

    function totalHarga($daftarHargaBuah, $jumlah)
    {
        return $daftarHargaBuah * $jumlah;
    }

    function deleteRow($index)
    {
        global $dataBuahAll, $berkas;

        unset($dataBuahAll[$index]);

        $dataJsonBaru = json_encode(array_values($dataBuahAll));
        file_put_contents($berkas, $dataJsonBaru);
    }

    // If the delete button is clicked
    if (isset($_POST['delete'])) {
        $index = $_POST['delete'];
        deleteRow($index);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Toko Buah Segar Abadi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="bg-primary text-white p-3 mb-4">
            <h1 class="mb-0">Toko Buah Segar Abadi</h1>
        </div>
        <form action="" method="post">
            <div class="mb-3">
                <label for="namapembeli" class="form-label">Nama Pembeli</label>
                <input type="text" name="namapembeli" class="form-control" placeholder="Masukan Nama Customer">
            </div>
            <div class="mb-3">
                <label for="namabuah" class="form-label">Nama Buah</label>
                <select class="form-control" id="namabuah" name="namabuah">
                    <?php
                    foreach ($daftarBuah as $db) {
                        // Use htmlspecialchars to prevent XSS attacks
                        echo "<option value='" . htmlspecialchars($db) . "'>" . htmlspecialchars($db) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Beli</label>
                <input type="number" name="jumlah" class="form-control" placeholder="Masukan jumlah yang anda inginkan">
            </div>
            <button type="submit" class="btn btn-primary" id="submit" name="submit">Hitung</button>
        </form>
        <br>
        <h2 class="mt-4">Tabel Harga :</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Buah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Use $key to get the key of the associative array
                // Use $dhb as the value of the associative array
                // Use $no outside the loop to avoid nested loops
                $no = 1;
                foreach ($daftarHargaBuah as $key => $dhb) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $key . "</td>";
                    echo "<td>" . $dhb . "</td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
        <br>
        <h2 class="mt-4">Daftar Penjualan Buah :</h2>

        <?php
        if (isset($_POST['submit'])) {
                    $namapembeli = $_POST['namapembeli'];
                    $namabuah = $_POST['namabuah'];
                    $hargabuah = $daftarHargaBuah[$namabuah];
                    $jumlah = $_POST['jumlah'];
                    $total_harga = totalHarga($daftarHargaBuah[$namabuah], $jumlah);

            $data_input = [$namapembeli, $namabuah, $hargabuah, $jumlah, $total_harga];


            if ($dataBuahAll === null) {
                $dataBuahAll = array();
            }
            array_push($dataBuahAll, $data_input);

            $dataJsonBaru = json_encode($dataBuahAll);

            file_put_contents($berkas, $dataJsonBaru);


            //Menampilkan data yang berhasil disimpan
            echo "<div class='container'>";
            echo "<div class='alert alert-success' role='alert'>";
            echo "Data Berhasil Disimpan";
            echo "</div>";
            echo "</div>";
        }
        ?>

        <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pembeli</th>
                            <th>Nama Buah</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah Beli</th>
                            <th>Total Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataBuahAll as $index => $data) {
                            echo "<tr>";
                            echo "<td>" . ($index + 1) . "</td>";
                            echo "<td>" . $data[0] . "</td>";
                            echo "<td>" . $data[1] . "</td>";
                            echo "<td>" . $data[2] . "</td>";
                            echo "<td>" . $data[3] . "</td>";
                            echo "<td>" . $data[4] . "</td>";
                            echo "<td>";
                            echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                            echo "&nbsp";
                            echo "<button type='submit' name='delete' value='" . $index . "' class='btn btn-danger'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>