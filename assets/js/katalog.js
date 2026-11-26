const kategoriFilter = document.getElementById("kategoriFilter");
const daftarKartu = document.querySelectorAll("[data-kategori]");
const pesanKosong = document.getElementById("pesanKosong");
const searchBarang = document.getElementById("searchBarang");

// Fungsi untuk mencari dan memfilter barang
function filterBarang() {
  const kataKunci = searchBarang.value.toLowerCase();
  const kategoriDipilih = kategoriFilter.value;

  daftarKartu.forEach(function (kartu) {
    const namaBarang = kartu.dataset.nama || "";
    const kodeBarang = kartu.dataset.kode || "";
    const kategoriBarang = kartu.dataset.kategori;

    const cocokNama = namaBarang.includes(kataKunci) || kodeBarang.includes(kataKunci);
    const cocokKategori = kategoriDipilih === "semua" || kategoriDipilih === kategoriBarang;

    kartu.style.display = cocokNama && cocokKategori ? "" : "none";
  });

  // Memeriksa apakah ada barang yang ditemukan
  const adaBarang = Array.from(daftarKartu).some(function (kartu) {
    return kartu.style.display !== "none";
  });

  // Menampilkan pesan jika barang tidak ditemukan
  pesanKosong.style.display = adaBarang ? "none" : "block";
}

// Menjalankan fungsi ketika kategori berubah
kategoriFilter.addEventListener("change", filterBarang);

// Menjalankan fungsi ketika pengguna mengetik
searchBarang.addEventListener("input", filterBarang);
