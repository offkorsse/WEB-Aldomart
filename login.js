/* ═══════════════════════════════════════════════
   ALDOMART – login.js
   Toggle tampil/sembunyikan password (login.php)
═══════════════════════════════════════════════ */

function togglePw() {
  const pw   = document.getElementById('password');
  const icon = document.getElementById('eye-icon');
  if (!pw || !icon) return;

  if (pw.type === 'password') {
    pw.type = 'text';
    /* Ikon "mata dicoret" – password terlihat */
    icon.innerHTML = `
      <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8
               a18.45 18.45 0 015.06-5.94"/>
      <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8
               a18.5 18.5 0 01-2.16 3.19"/>
      <line x1="1" y1="1" x2="23" y2="23"/>`;
  } else {
    pw.type = 'password';
    /* Ikon "mata terbuka" – password tersembunyi */
    icon.innerHTML = `
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
      <circle cx="12" cy="12" r="3"/>`;
  }
}
