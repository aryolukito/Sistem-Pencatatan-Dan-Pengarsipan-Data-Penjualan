// validasi_hapus.js
function confirmDeleteTransaksi(id) {
  if (confirm("Anda yakin ingin menghapus data?")) {
    fetch(`../../config/hapus.php?id=${id}&type=transaksi`)
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          alert("Transaksi berhasil dihapus!");
          window.location.href = window.location.href;
        } else if (result === "invalid") {
          alert("Parameter tidak valid!");
        } else {
          alert("Gagal menghapus transaksi!");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat menghapus transaksi!");
      });
  }
}

function confirmDeleteHewan(id_hewan) {
  if (confirm("Anda yakin ingin menghapus data?")) {
    fetch(`../../config/hapus.php?id_hewan=${id_hewan}&type=jenis_hewan`)
      .then((response) => response.text())
      .then((result) => {
        if (result === "success") {
          alert("Data hewan berhasil dihapus!");
          window.location.href = window.location.href;
        } else if (result === "invalid") {
          alert("Data tidak valid!");
        } else {
          alert("Gagal menghapus data hewan!");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat menghapus data hewan");
      });
  }
}

function confirmDeletePengguna(id_pengguna) {
  // Langsung konfirmasi dan kirim ke hapus.php
  if (confirm("Anda yakin ingin menghapus?")) {
    fetch(`../../config/hapus.php?id_pengguna=${id_pengguna}&type=nama`)
      .then((response) => response.text())
      .then((result) => {
        switch (result) {
          case "success":
            alert("Data pengguna berhasil dihapus");
            window.location.href = window.location.href;
            break;
          case "invalid":
            alert("Data tidak valid!");
            break;
          default:
            alert("Gagal menghapus data pengguna");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat menghapus data pengguna");
      });
  }
}
