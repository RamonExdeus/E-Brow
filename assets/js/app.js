// Toggle menu mobile
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('menuBtn');
  const mobile = document.getElementById('mobileMenu');
  if (btn && mobile) {
    btn.addEventListener('click', () => mobile.classList.toggle('hidden'));
  }

  // Toggle tab login/register
  document.querySelectorAll('[data-tab-target]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tabTarget;
      document.querySelectorAll('[data-tab]').forEach(p => p.classList.add('hidden'));
      document.querySelectorAll('[data-tab-target]').forEach(b => b.classList.remove('bg-blue-600','text-white'));
      document.querySelector(`[data-tab="${target}"]`)?.classList.remove('hidden');
      btn.classList.add('bg-blue-600','text-white');
    });
  });
});
