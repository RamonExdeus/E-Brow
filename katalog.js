const kategoriFilter = document.getElementById("kategoriFilter");
const daftarKartu = document.querySelectorAll("[data-kategori]");
const pesanKosong = document.getElementById("pesanKosong");
const searchBarang = document.getElementById("searchBarang");

// Fungsi untuk mencari dan memfilter barang
function filterBarang() {
  const kataKunci = searchBarang.value.toLowerCase();
  const kategoriDipilih = kategoriFilter.value;

  daftarKartu.forEach(function (kartu) {
    const namaBarang = kartu.querySelector(".card-title").textContent.toLowerCase();
    const kategoriBarang = kartu.dataset.kategori;

    const cocokNama = namaBarang.includes(kataKunci);
    const cocokKategori = kategoriDipilih === "semua" || kategoriDipilih === kategoriBarang;

    if (cocokNama && cocokKategori) {
      kartu.style.display = "";
    } else {
      kartu.style.display = "none";
    }
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
