<?php
require_once '../../dompdf/vendor/autoload.php'; // Pastikan library Dompdf sudah terpasang
include('../../config/koneksi.php');

// Ambil nilai filter dari URL
$searchJenisHewan = isset($_GET['search_jenis_hewan']) ? $_GET['search_jenis_hewan'] : '';
$filterOption = isset($_GET['filter_option']) ? $_GET['filter_option'] : '';
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMonth = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$filterYear = isset($_GET['filter_year']) ? $_GET['filter_year'] : '';

// Menentukan periode laporan
$periode = "Semua Periode";
if ($filterOption == 'tanggal' && !empty($filterDate)) {
    $periode = date('d F Y', strtotime($filterDate));
} elseif ($filterOption == 'bulan' && !empty($filterMonth)) {
    $periode = date('F Y', strtotime($filterMonth . "-01"));
} elseif ($filterOption == 'tahun' && !empty($filterYear)) {
    $periode = $filterYear;
}

// Membuat kondisi WHERE untuk filter
$whereConditions = "WHERE t.status = 'final'";
if (!empty($searchJenisHewan)) {
    $whereConditions .= " AND j.jenis_hewan LIKE '%$searchJenisHewan%'";
}
if ($filterOption == 'tanggal' && !empty($filterDate)) {
    $whereConditions .= " AND t.tanggal = '$filterDate'";
} elseif ($filterOption == 'bulan' && !empty($filterMonth)) {
    $whereConditions .= " AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$filterMonth'";
} elseif ($filterOption == 'tahun' && !empty($filterYear)) {
    $whereConditions .= " AND YEAR(t.tanggal) = '$filterYear'";
}

// Query untuk data yang sudah difilter
$query = "SELECT t.id, t.harga, t.jumlah, j.jenis_hewan, t.tanggal FROM transaksi t JOIN hewan j ON t.id_jenis_hewan = j.id_hewan $whereConditions";
$result = $koneksi->query($query);

// Query untuk total pendapatan
$totalQuery = "SELECT SUM(t.harga * t.jumlah) as total_pendapatan FROM transaksi t JOIN hewan j ON t.id_jenis_hewan = j.id_hewan $whereConditions";
$totalResult = $koneksi->query($totalQuery);
$totalData = $totalResult->fetch_assoc();
$totalPendapatan = $totalData['total_pendapatan'] ?? 0;


// Membuat HTML untuk PDF
$html = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #000;
            }
            .header h2 {
                margin: 0;
                font-size: 20px;
            }
            .header p {
                margin: 5px 0;
                font-size: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
                text-align: left;
            }
            td {
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2?>UPT PASAR TERNAK REGIONAL DAN RUMAH POTONG HEWAN KABUPATEN MAJALENGKA</h2>
            <h3>LAPORAN PENJUALAN</h3>
            <p style="font-size: 16px;">Periode: ' . $periode . '</p>
        </div>
        <table>
            <tr>
                <th>No</th>
                <th>Jenis Hewan</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>{$no}</td>
                    <td>{$row['jenis_hewan']}</td>
                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                    <td>{$row['jumlah']}</td>
                    <td>Rp " . number_format($row['harga'] * $row['jumlah'], 0, ',', '.') . "</td>
                  </tr>";
        $no++;
    }
} else {
    $html .= '<tr><td colspan="6" align="center">Tidak ada data</td></tr>';
}

// Tambahkan total pendapatan di bagian bawah tabel
$html .= "<tr>
            <th colspan='4' align='right'>Total Pendapatan</th>
            <th>Rp " . number_format($totalPendapatan, 0, ',', '.') . "</th>
         </tr>";

$html .= '</table></body></html>';

// Ekspor ke PDF
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('laporan_penjualan.pdf', ['Attachment' => false]);
?>