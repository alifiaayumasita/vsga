<?php
$berkas = "data2.json";
$dataJson = file_get_contents($berkas);
$dataLaundryAll = json_decode($dataJson, true);

// Array daftar Layanan
$daftarLayanan = array(
    "Cuci Setrika",
    "Setrika",
    "Cuci Lipat",
    "Bedcover Kecil",
    "Bedcover Besar",
    "Karpet"
);

$hargaLayanan = array(
    "Cuci Setrika" => 7000,
    "Setrika" => 4000,
    "Cuci Lipat" => 4000,
    "Bedcover Kecil" => 15000,
    "Bedcover Besar" => 20000,
    "Karpet" => 30000
);

function totalHarga($hargaLayanan, $jumlah)
{
    return $hargaLayanan * $jumlah;
}

// Function to delete a row from the JSON file
function deleteRow($index)
{
    global $dataLaundryAll, $berkas;

    unset($dataLaundryAll[$index]);

    $dataJsonBaru = json_encode(array_values($dataLaundryAll));
    file_put_contents($berkas, $dataJsonBaru);
}

// Function to update the JSON file
function updateFile($newData, $index)
{
    global $dataLaundryAll, $berkas;

    $dataLaundryAll[$index] = $newData;
    $dataJsonBaru = json_encode($dataLaundryAll);
    file_put_contents($berkas, $dataJsonBaru);
}

// If the delete button is clicked
if (isset($_POST['delete'])) {
    $index = $_POST['delete'];
    deleteRow($index);
    header("Location: " . $_SERVER['PHP_SELF']);
}

// If the edit button is clicked
if (isset($_POST['edit'])) {
    $index = $_POST['edit'];
    $dataLaundry = $dataLaundryAll[$index];
}

// If the AJAX update request is received
if (isset($_POST['update'])) {
    $index = $_POST['index'];
    $nama = $_POST['nama'];
    $date = $_POST['date'];
    $jumlah = $_POST['jumlah'];
    $jenisLayanan = $_POST['jenisLayanan'];
    $keterangan = $_POST['keterangan'];
    $total_harga = totalHarga($hargaLayanan[$jenisLayanan], $jumlah);

    $dataLaundry = [$nama, $date, $jumlah, $jenisLayanan, $hargaLayanan[$jenisLayanan], $total_harga, $keterangan];

    updateFile($dataLaundry, $index);
    exit(); // Stop further execution
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Laundry</title>
    <link rel="stylesheet" type="text/css" href="npm i bootstrap-icons">
</head>

<body>
    <div class="container">
        <div align="center">
            <br>
            <h1>Bintang Laundry</h1>
            <br>
            <form action="" method="post">
                <table width="900px">
                    <div class="form-group">
                        <tr>
                            <td><label>Nama</label></td>
                            <td>: &nbsp</td>
                            <td colspan="4"><input type="text" class="form-control" name="nama" placeholder="Nama Customer"></input></td>
                        </tr>
                        <tr>
                            <td><label>Tanggal</label></td>
                            <td>: &nbsp</td>
                            <td colspan="4"><input type="datetime-local" class="form-control" name="date"></input><td>
                        </tr>
                        <tr>
                            <td><label>Jumlah / Berat</label></td>
                            <td>: &nbsp</td>
                            <td><input type="number" class="form-control" name="jumlah" placeholder="Jumlah Timbangan dalam Kg atau Pcs"></td>
                            <td>&nbspJenis Layanan</td>
                            <td>: &nbsp</td>
                            <td>
                                <select name="jenisLayanan" class="form-control">
                                    <!-- perulangan untuk menampilkan jenis layanan -->
                                    <?php
                                    foreach ($daftarLayanan as $dl) {
                                        echo "<option value='" . $dl . "'>" . $dl . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>: &nbsp</td>
                            <td colspan="4"><textarea class="form-control" name="keterangan" placeholder="Masukan Keterangan Pesanan"></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: center;"><input type="submit" name="submit" class="form-control"></td>
                        </tr>
                    </div>
                </table>
            </form>
        </div><br>
    </div>

    <?php
    //Menampung inputan ke array sementara
    if (isset($_POST['submit'])) {
        $nama = $_POST['nama'];
        $date = $_POST['date'];
        $jumlah = $_POST['jumlah'];
        $jenisLayanan = $_POST['jenisLayanan'];
        $keterangan = $_POST['keterangan'];
        $total_harga = totalHarga($hargaLayanan[$jenisLayanan], $jumlah);

        $dataLaundry = [$nama, $date, $jumlah, $jenisLayanan, $hargaLayanan[$jenisLayanan], $total_harga, $keterangan];

        //Menambahkan data laundry ke array data laundry all
        array_push($dataLaundryAll, $dataLaundry);

            // Mengubah data menjadi format JSON
        $dataJsonBaru = json_encode($dataLaundryAll);

        // Menyimpan data ke file json
        file_put_contents($berkas, $dataJsonBaru);

        //Menampilkan data yang berhasil disimpan
        echo "<div class='container'>";
        echo "<div class='alert alert-success' role='alert'>";
        echo "Data Berhasil Disimpan";
        echo "</div>";
        echo "</div>";
    }
    ?>

    <div class="container">
    <h3>Riwayat Pesanan Laundry : </h3>
    <div class="col-md-4">
        <table>
            <tr>
                <td><input type="text" class="form-control" id="cari" placeholder="Cari Data Inputan"></td>    
                <td  class="form-control"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg></td>
            </tr>
            <tr><br></tr>
            <tr>
                <?php
                    $totalPendapatan = 0;
                    foreach ($dataLaundryAll as $laundry) {
                        $totalPendapatan += $laundry[5];
                    }

                    echo "<h5>  Total Pendapatan : Rp. " . number_format($totalPendapatan, 0, ',', '.') . "</h3><br>";
                ?>
            </tr>
        </table>
    </div>
    <table width="900px" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jumlah / Berat</th>
                <th>Jenis Layanan</th>
                <th>Total Harga</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dataLaundryAll as $index => $dataLaundry) {
            ?>
                <tr>
                    <td><?php echo $index + 1 ?></td>
                    <td><?php echo $dataLaundry[0] ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($dataLaundry[1])) ?></td>
                    <td><?php echo $dataLaundry[2] ?></td>
                    <td><?php echo $dataLaundry[3] ?></td>
                    <td><?php echo number_format($dataLaundry[5]) ?></td>
                    <td><?php echo $dataLaundry[6] ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="edit" value="<?php echo $index ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="delete" value="<?php echo $index ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <!-- Bootstrap Modal for Edit Data -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Inputan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="editNama">Nama:</label>
                            <input type="text" class="form-control" id="editNama" name="nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="editTanggal">Tanggal:</label>
                            <input type="datetime-local" class="form-control" id="editTanggal" name="date">
                        </div>
                        <div class="form-group">
                            <label for="editJumlah">Jumlah / Berat:</label>
                            <input type="number" class="form-control" id="editJumlah" name="jumlah">
                        </div>
                        <div class="form-group">
                            <label for="editJenisLayanan">Jenis Layanan:</label>
                            <select name="jenisLayanan" class="form-control" id="editJenisLayanan">
                                <?php
                                foreach ($daftarLayanan as $dl) {
                                    echo "<option value='" . $dl . "'>" . $dl . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editKeterangan">Keterangan:</label>
                            <textarea class="form-control" name="keterangan" id="editKeterangan"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateData">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    // Fungsi untuk mencari data pada tabel
    function cariData() {
        // Deklarasi variabel
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("cari");
        filter = input.value.toUpperCase();
        table = document.getElementById("tabel-input");
        tr = table.getElementsByTagName("tr");

        // Looping untuk membandingkan data pada setiap baris
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Event untuk memanggil fungsi cariData() setiap kali tombol diketikkan
    document.getElementById("cari").addEventListener("keyup", cariData);

    // Variable to store the rowIndex when the "Edit" button is clicked
    var rowIndex;

    // Function to handle the "Edit" button click event
    $("tbody").on("click", "button.btn-warning", function (event) {
        event.preventDefault();

        // Get the parent row of the clicked "Edit" button
        var row = $(this).closest("tr");

        // Get the index of the row in the table
        rowIndex = row.index();

        // Get the data from the table cells of the selected row
        var nama = row.find("td:nth-child(2)").text();
        var tanggal = row.find("td:nth-child(3)").text();
        var jumlah = row.find("td:nth-child(4)").text();
        var jenisLayanan = row.find("td:nth-child(5)").text();
        var keterangan = row.find("td:nth-child(7)").text();

        // Update the values in the edit modal form
        $("#editNama").val(nama);
        $("#editTanggal").val(tanggal.replace(" ", "T")); // Convert date format to match input type datetime-local
        $("#editJumlah").val(jumlah);
        $("#editJenisLayanan").val(jenisLayanan);
        $("#editKeterangan").val(keterangan);

        // Show the edit modal
        $("#editModal").modal("show");
    });

    // AJAX call to update data when "Update" button is clicked
    $("#updateData").on("click", function () {
        // Get updated data from the edit form fields
        var updatedNama = $("#editNama").val();
        var updatedTanggal = $("#editTanggal").val().replace("T", " "); // Convert date format back to match data format
        var updatedJumlah = $("#editJumlah").val();
        var updatedJenisLayanan = $("#editJenisLayanan").val();
        var updatedKeterangan = $("#editKeterangan").val();
        var updatedTotalHarga = totalHarga(<?php echo json_encode($hargaLayanan); ?>[updatedJenisLayanan], updatedJumlah);

        // Make an AJAX request to update the data on the server
        $.ajax({
                method: "POST",
                url: "<?php echo $_SERVER['PHP_SELF']; ?>", // Send the AJAX request to the same PHP page
                data: {
                    update: true, // Indicate that this is an update request
                    index: rowIndex, // Pass the captured rowIndex to the server
                    nama: updatedNama,
                    date: updatedTanggal,
                    jumlah: updatedJumlah,
                    jenisLayanan: updatedJenisLayanan,
                    keterangan: updatedKeterangan,
                    total_harga: updatedData.total_harga
                },
                success: function (updatedData) {
                    // Update the table row with the updated data
                    var tableRow = $("tbody tr").eq(rowIndex);

                    tableRow.find("td:nth-child(2)").text(updatedData.nama);
                    tableRow.find("td:nth-child(3)").text(updatedData.date);
                    tableRow.find("td:nth-child(4)").text(updatedData.jumlah);
                    tableRow.find("td:nth-child(5)").text(updatedData.jenisLayanan);
                    tableRow.find("td:nth-child(6)").text(updatedData.total_harga);
                    tableRow.find("td:nth-child(7)").text(updatedData.keterangan);

                    $("#editModal").modal("hide");
                },
                error: function (xhr, status, error) {
                // Handle error
                console.log("Error:", error);
            }
        });

        // Close the edit modal
        $("#editModal").modal("hide");
    });
</script>
</html>