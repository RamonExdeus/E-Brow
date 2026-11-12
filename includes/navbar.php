<?php require_once __DIR__ . '/auth.php'; $u = current_user(); ?>
<header class="bg-white shadow-sm sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-14">
      <a href="/ebrow/index.php" class="font-bold text-lg text-blue-600">EBROW</a>
      <button id="menuBtn" class="md:hidden p-2 rounded hover:bg-gray-100" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <nav class="hidden md:flex md:items-center md:gap-4 text-sm">
        <a href="/ebrow/index.php" class="hover:text-blue-600">Beranda</a>
        <a href="/ebrow/pages/user/katalog.php" class="hover:text-blue-600">Katalog</a>
        <?php if ($u): ?>
          <a href="/ebrow/pages/user/dashboard.php" class="hover:text-blue-600">Dashboard</a>
          <?php if (($u['role'] ?? '') === 'admin'): ?><a href="/ebrow/pages/admin/dashboard.php" class="hover:text-blue-600">Admin</a><?php endif; ?>
          <span class="text-gray-500">Hai, <?= e($u['nama']) ?></span>
          <a href="/ebrow/actions/auth_action.php?action=logout" class="text-red-600 hover:underline">Logout</a>
        <?php else: ?>
          <a href="/ebrow/pages/user/auth.php" class="hover:text-blue-600">Login</a>
        <?php endif; ?>
      </nav>
    </div>
    <nav id="mobileMenu" class="hidden md:hidden pb-3 text-sm border-t">
      <a href="/ebrow/index.php" class="block py-2 hover:text-blue-600">Beranda</a>
      <a href="/ebrow/pages/user/katalog.php" class="block py-2 hover:text-blue-600">Katalog</a>
      <?php if ($u): ?>
        <a href="/ebrow/pages/user/dashboard.php" class="block py-2 hover:text-blue-600">Dashboard</a>
        <?php if (($u['role'] ?? '') === 'admin'): ?><a href="/ebrow/pages/admin/dashboard.php" class="block py-2 hover:text-blue-600">Admin</a><?php endif; ?>
        <a href="/ebrow/actions/auth_action.php?action=logout" class="block py-2 text-red-600">Logout (<?= e($u['nama']) ?>)</a>
      <?php else: ?>
        <a href="/ebrow/pages/user/auth.php" class="block py-2 hover:text-blue-600">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
