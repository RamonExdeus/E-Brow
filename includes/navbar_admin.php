<?php require_once __DIR__ . '/auth.php'; $u = current_user(); ?>
<header class="bg-slate-900 text-white sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-14">
      <a href="/ebrow/pages/admin/dashboard.php" class="font-bold text-lg">EBROW Admin</a>
      <button id="menuBtn" class="md:hidden p-2 rounded hover:bg-slate-800" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <nav class="hidden md:flex md:items-center md:gap-4 text-sm">
        <a href="/ebrow/pages/admin/dashboard.php" class="hover:text-blue-300">Dashboard</a>
        <a href="/ebrow/pages/admin/barang.php" class="hover:text-blue-300">Kelola Barang</a>
        <a href="/ebrow/pages/admin/peminjaman.php" class="hover:text-blue-300">Kelola Peminjaman</a>
        <a href="/ebrow/index.php" class="hover:text-blue-300">Ke Situs</a>
        <a href="/ebrow/actions/auth_action.php?action=logout" class="text-red-300">Logout</a>
      </nav>
    </div>
    <nav id="mobileMenu" class="hidden md:hidden pb-3 text-sm border-t border-slate-700">
      <a href="/ebrow/pages/admin/dashboard.php" class="block py-2">Dashboard</a>
      <a href="/ebrow/pages/admin/barang.php" class="block py-2">Kelola Barang</a>
      <a href="/ebrow/pages/admin/peminjaman.php" class="block py-2">Kelola Peminjaman</a>
      <a href="/ebrow/index.php" class="block py-2">Ke Situs</a>
      <a href="/ebrow/actions/auth_action.php?action=logout" class="block py-2 text-red-300">Logout</a>
    </nav>
  </div>
</header>
