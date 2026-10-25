function toggleFilters(value) {
  document.getElementById("filter_date").style.display =
    value === "tanggal" ? "block" : "none";
  document.getElementById("filter_month").style.display =
    value === "bulan" ? "block" : "none";
  document.getElementById("filter_year").style.display =
    value === "tahun" ? "block" : "none";
}
