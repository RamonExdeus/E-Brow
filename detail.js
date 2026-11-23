// Mengambil ID barang dari URL
const params = new URLSearchParams(window.location.search);
const idBarang = params.get("id");

// Data semua barang
const daftarBarang = {
  laptop: {
    nama: "Laptop",
    gambar: "assets/laptop.jpeg",
    kategori: "Elektronik",
    status: "Tersedia",
    deskripsi: "Laptop yang dapat digunakan untuk membantu kegiatan perkuliahan, mengerjakan tugas, dan presentasi.",
    harga: "Rp30.000 / hari",
    kondisi: "Baik",
    jumlah: "2 unit",
  },

  kamera: {
    nama: "Kamera",
    gambar: "assets/kamera.jpeg",
    kategori: "Elektronik",
    status: "Tersedia",
    deskripsi: "Kamera yang dapat digunakan untuk dokumentasi kegiatan, mengambil foto, dan membuat video.",
    harga: "Rp20.000 / hari",
    kondisi: "Baik",
    jumlah: "2 unit",
  },

  speaker: {
    nama: "Speaker",
    gambar: "assets/speaker.jpeg",
    kategori: "Elektronik",
    status: "Tersedia",
    deskripsi: "Speaker yang digunakan untuk mendukung kebutuhan audio dalam presentasi, acara, dan kegiatan lainnya.",
    harga: "Rp15.000 / hari",
    kondisi: "Baik",
    jumlah: "2 unit",
  },

  mic: {
    nama: "Microphone",
    gambar: "assets/mic.jpeg",
    kategori: "Elektronik",
    status: "Tidak tersedia",
    deskripsi: "Microphone yang digunakan untuk membantu penyampaian suara saat presentasi, seminar, dan acara.",
    harga: "Rp15.000 / hari",
    kondisi: "Baik",
    jumlah: "0 unit",
  },

  proyektor: {
    nama: "Proyektor",
    gambar: "assets/proyektor.jpeg",
    kategori: "Elektronik",
    status: "Tersedia",
    deskripsi: "Proyektor yang digunakan untuk menampilkan materi presentasi atau tampilan visual ke layar.",
    harga: "Rp30.000 / hari",
    kondisi: "Baik",
    jumlah: "2 unit",
  },

  tripod: {
    nama: "Tripod",
    gambar: "assets/tripod.jpeg",
    kategori: "Non-Elektronik",
    status: "Tidak tersedia",
    deskripsi: "Tripod yang digunakan sebagai penyangga kamera atau ponsel saat mengambil foto dan video.",
    harga: "Rp10.000 / hari",
    kondisi: "Baik",
    jumlah: "0 unit",
  },

  kursi: {
    nama: "Kursi Lipat",
    gambar: "assets/kursi.jpeg",
    kategori: "Non-Elektronik",
    status: "Tersedia",
    deskripsi: "Kursi lipat yang dapat digunakan sebagai tempat duduk tambahan dalam kegiatan perkuliahan atau acara.",
    harga: "Rp15.000 / hari",
    kondisi: "Baik",
    jumlah: "5 unit",
  },

  meja: {
    nama: "Meja Lipat",
    gambar: "assets/meja.jpeg",
    kategori: "Non-Elektronik",
    status: "Tersedia",
    deskripsi: "Meja lipat yang dapat digunakan untuk mendukung kegiatan belajar, rapat, dan berbagai acara.",
    harga: "Rp20.000 / hari",
    kondisi: "Baik",
    jumlah: "3 unit",
  },

  "papan-tulis": {
    nama: "Papan Tulis",
    gambar: "assets/papan-tulis.jpeg",
    kategori: "Non-Elektronik",
    status: "Tidak tersedia",
    deskripsi: "Papan tulis yang dapat digunakan untuk menulis materi, menyampaikan ide, dan menjelaskan informasi.",
    harga: "Rp25.000 / hari",
    kondisi: "Baik",
    jumlah: "0 unit",
  },

  "stand-poster": {
    nama: "Stand Poster",
    gambar: "assets/stand-poster.jpeg",
    kategori: "Non-Elektronik",
    status: "Tersedia",
    deskripsi: "Stand poster yang digunakan untuk menopang dan menampilkan poster atau informasi dalam kegiatan.",
    harga: "Rp20.000 / hari",
    kondisi: "Baik",
    jumlah: "2 unit",
  },
};

// Mengambil data barang berdasarkan ID
const barang = daftarBarang[idBarang];

// Menampilkan data barang
if (barang) {
  document.getElementById("gambarBarang").src = barang.gambar;
  document.getElementById("gambarBarang").alt = barang.nama;

  document.getElementById("namaBarang").textContent = barang.nama;
  document.getElementById("kategoriBarang").textContent = "Kategori: " + barang.kategori;

  document.getElementById("statusBarang").textContent = barang.status;
  document.getElementById("statusBarang").className = barang.status === "Tersedia" ? "badge bg-success" : "badge bg-danger";

  document.getElementById("deskripsiBarang").textContent = barang.deskripsi;

  document.getElementById("hargaBarang").textContent = "Harga: " + barang.harga;

  document.getElementById("kondisiBarang").textContent = "Kondisi: " + barang.kondisi;

  document.getElementById("jumlahBarang").textContent = "Jumlah: " + barang.jumlah;

  document.getElementById("tombolPeminjaman").href = "form-peminjaman.html?id=" + encodeURIComponent(idBarang);

  document.getElementById("tombolPeminjaman").style.display = barang.status === "Tersedia" ? "inline-block" : "none";
}
