<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk & Daftar - EBROW</title>
  <link rel="stylesheet" href="assets/css/ebrow-user.css">
  <style>
    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .auth-card {
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      width: 100%;
      max-width: 450px;
      padding: 32px;
      box-shadow: var(--shadow-sm);
    }
    .auth-tabs {
      display: grid;
      grid-template-columns: 1fr 1fr;
      background: var(--bg-cream);
      padding: 5px;
      border-radius: 999px;
      margin: 20px 0 24px;
    }
    .tab-btn {
      padding: 10px;
      border: none;
      background: transparent;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.88rem;
      cursor: pointer;
      color: var(--text-muted);
      transition: 0.2s;
    }
    .tab-btn.active {
      background: var(--primary);
      color: var(--dark);
    }
    .password-wrap { position: relative; }
    .toggle-pw {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--text-muted);
      cursor: pointer;
    }
    .role-note {
      margin-top: 18px;
      padding: 12px;
      background: var(--bg-main);
      border: 1px dashed var(--border);
      border-radius: var(--radius-sm);
      font-size: 0.78rem;
      color: var(--text-muted);
      text-align: center;
    }
  </style>
</head>
<body>

<div class="auth-container">
  <div class="auth-card">
    <div style="text-align:center;">
      <a href="index.php" class="brand">EBROW<span>.</span></a>
      <p style="color:var(--text-muted); font-size:0.88rem; margin-top:6px;">
        Butuh barang? Pinjam aja tanpa harus beli.
      </p>
    </div>

    <!-- Switcher Login & Register dalam 1 Halaman -->
    <div class="auth-tabs">
      <button type="button" class="tab-btn active" id="tabLogin" onclick="switchTab('login')">Masuk</button>
      <button type="button" class="tab-btn" id="tabRegister" onclick="switchTab('register')">Daftar Akun</button>
    </div>

    <!-- FORM LOGIN -->
    <form id="formLogin" action="proses_login.php" method="POST">
      <div class="form-group">
        <label for="loginEmail">Email</label>
        <input type="email" id="loginEmail" name="email" class="form-control" placeholder="contoh@email.com" required>
      </div>

      <div class="form-group">
        <label for="loginPassword">Password</label>
        <div class="password-wrap">
          <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Masukkan password" required>
          <button type="button" class="toggle-pw" onclick="togglePassword('loginPassword', this)">Lihat</button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">
        Masuk Sekarang
      </button>

      <div class="role-note">
        Sistem otomatis membedakan hak akses <strong>Pengguna</strong> dan <strong>Admin</strong> sesuai akun terdaftar.
      </div>
    </form>

    <!-- FORM REGISTER -->
    <form id="formRegister" class="hidden" action="proses_register.php" method="POST">
      <div class="form-group">
        <label for="regNama">Nama Lengkap</label>
        <input type="text" id="regNama" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
      </div>

      <div class="form-group">
        <label for="regEmail">Email</label>
        <input type="email" id="regEmail" name="email" class="form-control" placeholder="contoh@email.com" required>
      </div>

      <div class="form-group">
        <label for="regHp">Nomor WhatsApp / HP</label>
        <input type="tel" id="regHp" name="no_hp" class="form-control" placeholder="0812xxxxxxxx" required>
      </div>

      <div class="form-group">
        <label for="regPassword">Password</label>
        <div class="password-wrap">
          <input type="password" id="regPassword" name="password" class="form-control" placeholder="Buat password (min. 6 karakter)" minlength="6" required>
          <button type="button" class="toggle-pw" onclick="togglePassword('regPassword', this)">Lihat</button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">
        Daftar Akun Baru
      </button>
    </form>
  </div>
</div>

<script>
  function switchTab(tab) {
    const isLogin = tab === 'login';
    document.getElementById('formLogin').classList.toggle('hidden', !isLogin);
    document.getElementById('formRegister').classList.toggle('hidden', isLogin);
    document.getElementById('tabLogin').classList.toggle('active', isLogin);
    document.getElementById('tabRegister').classList.toggle('active', !isLogin);
  }

  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
      input.type = 'text';
      btn.textContent = 'Sembunyi';
    } else {
      input.type = 'password';
      btn.textContent = 'Lihat';
    }
  }
</script>
</body>
</html>