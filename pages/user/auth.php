<?php $pageTitle = 'Login / Register'; include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/navbar.php'; ?>
<?php
$tab = $_GET['tab'] ?? 'login';
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$msg = $_GET['msg'] ?? '';
?>
<main class="flex-1 max-w-md mx-auto w-full px-4 py-8">
  <div class="bg-white border rounded-xl shadow-sm p-5">
    <?php if ($error === 'invalid'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Email atau password salah.</div><?php endif; ?>
    <?php if ($error === 'exists'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Email sudah terdaftar.</div><?php endif; ?>
    <?php if ($error === 'empty'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Semua field wajib diisi.</div><?php endif; ?>
    <?php if ($success === 'registered'): ?><div class="mb-3 text-sm text-green-700 bg-green-50 p-2 rounded">Pendaftaran berhasil. Silakan login.</div><?php endif; ?>
    <?php if ($msg === 'login_required'): ?><div class="mb-3 text-sm text-yellow-800 bg-yellow-50 p-2 rounded">Silakan login terlebih dahulu.</div><?php endif; ?>
    <?php if ($msg === 'forbidden'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Akses ditolak. Halaman khusus admin.</div><?php endif; ?>

    <div class="flex mb-5 border rounded-lg overflow-hidden text-sm">
      <button data-tab-target="login" class="flex-1 py-2 font-medium <?= $tab!=='register'?'bg-blue-600 text-white':'' ?>">Login</button>
      <button data-tab-target="register" class="flex-1 py-2 font-medium <?= $tab==='register'?'bg-blue-600 text-white':'' ?>">Register</button>
    </div>

    <form data-tab="login" method="post" action="/ebrow/actions/auth_action.php" class="space-y-4 <?= $tab==='register'?'hidden':'' ?>">
      <input type="hidden" name="action" value="login">
      <div><label class="block text-sm mb-1">Email</label><input type="email" name="email" class="w-full border rounded-lg px-3 py-2" required></div>
      <div><label class="block text-sm mb-1">Password</label><input type="password" name="password" class="w-full border rounded-lg px-3 py-2" required></div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium">Masuk</button>
      <p class="text-xs text-gray-500 text-center">Hak akses pengguna dan admin dibedakan berdasarkan akun.</p>
    </form>

    <form data-tab="register" method="post" action="/ebrow/actions/auth_action.php" class="space-y-4 <?= $tab!=='register'?'hidden':'' ?>">
      <input type="hidden" name="action" value="register">
      <div><label class="block text-sm mb-1">Nama Lengkap</label><input type="text" name="nama" class="w-full border rounded-lg px-3 py-2" required></div>
      <div><label class="block text-sm mb-1">Email</label><input type="email" name="email" class="w-full border rounded-lg px-3 py-2" required></div>
      <div><label class="block text-sm mb-1">Password</label><input type="password" name="password" class="w-full border rounded-lg px-3 py-2" required></div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium">Daftar</button>
    </form>
  </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
