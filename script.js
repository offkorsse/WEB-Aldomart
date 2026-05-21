/* ═══════════════════════════════════════════════
   ALDOMART – script.js  (versi perbaikan stok)
   Keranjang belanja & dropdown kategori (menu.php)
═══════════════════════════════════════════════ */

/* ── Cart state ── */
let cart = JSON.parse(localStorage.getItem('aldomartCart') || '[]');

/**
 * stockMap  : { id(number) → stok_tersedia(number) }
 * Diisi dari stok.php saat halaman dimuat & di-refresh berkala.
 * Stok di sini adalah stok DB DIKURANGI jumlah yang sudah ada di keranjang.
 * Kita simpan stok DB mentah, lalu hitung "sisa" saat diperlukan.
 */
let stockMap = {}; // { id → stok_di_db }

/* ── Ambil stok terkini dari server ── */
async function fetchStock() {
  try {
    const res  = await fetch('stok.php', { cache: 'no-store' });
    const data = await res.json();
    // Konversi key ke Number agar konsisten
    stockMap = {};
    for (const [k, v] of Object.entries(data)) {
      stockMap[Number(k)] = Number(v);
    }
    // Setelah stok diperbarui, re-render agar tombol +/beli menyesuaikan
    renderCart();
    updateBeliButtons();
  } catch (e) {
    console.warn('Gagal fetch stok:', e);
  }
}

/**
 * Hitung berapa unit produk id yang sudah ada di keranjang.
 */
function qtyInCart(id) {
  id = Number(id);
  const item = cart.find(i => Number(i.id) === id);
  return item ? item.qty : 0;
}

/**
 * Stok tersedia = stok DB – qty sudah di keranjang.
 * Jika id belum ada di stockMap, anggap tidak terbatas (belum load).
 */
function availableStock(id) {
  id = Number(id);
  if (!(id in stockMap)) return Infinity; // belum dimuat
  return Math.max(0, stockMap[id] - qtyInCart(id));
}

function saveCart() {
  localStorage.setItem('aldomartCart', JSON.stringify(cart));
}

/* ── Tambah produk ke keranjang ──
   @param id    : integer id produk
   @param name  : string nama produk
   @param price : integer harga
   @param image : string path/URL gambar
*/
function addToCart(id, name, price, image) {
  id    = Number(id);
  price = Number(price);

  // ── Validasi stok sebelum tambah ──
  const avail = availableStock(id);
  if (avail <= 0) {
    showToast('⚠️ Stok ' + name + ' sudah habis!', 'error');
    return;
  }

  const idx = cart.findIndex(i => Number(i.id) === id);
  if (idx >= 0) {
    cart[idx].qty++;
  } else {
    cart.push({ id, name, price, image, qty: 1 });
  }
  saveCart();
  renderCart();
  updateBeliButtons();
  showToast('✅ ' + name + ' ditambahkan ke keranjang');
}

/* ── Ubah jumlah item ── */
function changeQty(id, delta) {
  id = Number(id);
  const idx = cart.findIndex(i => Number(i.id) === id);
  if (idx < 0) return;

  const newQty = cart[idx].qty + delta;

  if (delta > 0) {
    // Tambah: cek stok
    const avail = availableStock(id);
    if (avail <= 0) {
      showToast('⚠️ Stok ' + cart[idx].name + ' sudah mencapai batas!', 'error');
      return;
    }
  }

  if (newQty <= 0) {
    cart.splice(idx, 1);
  } else {
    cart[idx].qty = newQty;
  }

  saveCart();
  renderCart();
  updateBeliButtons();
}

/* ── Kosongkan keranjang ── */
function clearCart() {
  if (cart.length === 0) return;
  if (confirm('Kosongkan keranjang?')) {
    cart = [];
    saveCart();
    renderCart();
    updateBeliButtons();
  }
}

/* ── Checkout: kirim ke server, kurangi stok di DB ── */
async function checkout() {
  if (cart.length === 0) {
    alert('Keranjang masih kosong!');
    return;
  }

  // Refresh stok dulu dari server sebelum proses
  await fetchStock();

  // Cek ulang apakah semua item masih mencukupi
  const overItems = cart.filter(item => {
    const dbStock = stockMap[Number(item.id)] ?? 0;
    return item.qty > dbStock;
  });

  if (overItems.length > 0) {
    const names = overItems.map(i => {
      const db = stockMap[Number(i.id)] ?? 0;
      return `• ${i.name}: tersedia ${db}, diminta ${i.qty}`;
    }).join('\n');
    alert('❌ Stok tidak mencukupi untuk:\n' + names + '\n\nKeranjang telah disesuaikan.');
    // Sesuaikan keranjang otomatis
    cart = cart.map(item => {
      const db  = stockMap[Number(item.id)] ?? 0;
      return { ...item, qty: Math.min(item.qty, db) };
    }).filter(item => item.qty > 0);
    saveCart();
    renderCart();
    updateBeliButtons();
    return;
  }

  // Kirim ke checkout.php
  try {
    const res  = await fetch('checkout.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ items: cart })
    });
    const data = await res.json();

    if (data.ok) {
      alert('✅ ' + data.message + '\nTotal: ' + formatRp(totalValue()) + '\n\nTerima kasih sudah berbelanja di ALDOMART 🎉');
      cart = [];
      saveCart();
      // Refresh stok & halaman produk agar stok di kartu langsung terupdate
      await fetchStock();
      renderCart();
      updateBeliButtons();
      // Reload halaman supaya tombol "Stok Habis" muncul jika perlu
      window.location.reload();
    } else {
      alert('❌ ' + data.message);
      // Sinkronisasi ulang stok
      await fetchStock();
      renderCart();
      updateBeliButtons();
    }
  } catch (e) {
    alert('❌ Terjadi kesalahan jaringan. Coba lagi.');
    console.error(e);
  }
}

/* ── Helper hitung total ── */
function totalValue() { return cart.reduce((s, i) => s + i.price * i.qty, 0); }
function totalQty()   { return cart.reduce((s, i) => s + i.qty, 0); }
function formatRp(n)  { return 'Rp ' + n.toLocaleString('id-ID'); }

/* ── Update tombol "+ Tambah" di kartu produk ──
   Setelah stok diperbarui, disable tombol yang stoknya habis.
*/
function updateBeliButtons() {
  document.querySelectorAll('.btn-beli[data-id]').forEach(btn => {
    const id    = Number(btn.dataset.id);
    const avail = availableStock(id);
    if (avail <= 0) {
      btn.disabled          = true;
      btn.textContent       = 'Stok Habis';
      btn.style.opacity     = '0.5';
      btn.style.cursor      = 'not-allowed';
    } else {
      btn.disabled          = false;
      btn.textContent       = '+ Tambah';
      btn.style.opacity     = '';
      btn.style.cursor      = '';
    }
  });
}

/* ── Toast notifikasi ── */
function showToast(msg, type) {
  let toast = document.getElementById('cartToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'cartToast';
    toast.style.cssText = [
      'position:fixed', 'bottom:24px', 'right:24px',
      'padding:10px 18px', 'border-radius:10px',
      'font-size:13px', 'font-family:DM Sans,sans-serif',
      'box-shadow:0 4px 16px rgba(0,0,0,.2)',
      'opacity:0', 'transform:translateY(8px)',
      'transition:all .3s ease', 'z-index:9999',
      'pointer-events:none', 'max-width:300px',
      'color:#fff'
    ].join(';');
    document.body.appendChild(toast);
  }
  toast.textContent        = msg;
  toast.style.background   = (type === 'error') ? '#DC2626' : '#222';
  toast.style.opacity      = '0';
  toast.style.transform    = 'translateY(8px)';

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      toast.style.opacity   = '1';
      toast.style.transform = 'translateY(0)';
    });
  });
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.style.opacity   = '0';
    toast.style.transform = 'translateY(8px)';
  }, 2800);
}

/* ── Render keranjang ke DOM ── */
function renderCart() {
  const el   = document.getElementById('cartItems');
  const pill = document.getElementById('cartCountPill');
  if (!el || !pill) return;

  const qty = totalQty();
  pill.textContent = qty;

  if (cart.length === 0) {
    el.innerHTML = `
      <div class="cart-empty">
        <div class="icon">🛒</div>
        <p>Keranjang masih kosong</p>
      </div>`;
    document.getElementById('totalItems').textContent = '0 item';
    document.getElementById('subtotal').textContent   = formatRp(0);
    document.getElementById('totalPrice').textContent = formatRp(0);
    return;
  }

  el.innerHTML = cart.map(item => {
    const id     = Number(item.id);
    const dbStok = stockMap[id] ?? null;
    // Sisa stok yang belum dimasukkan ke keranjang
    const sisa   = dbStok !== null ? Math.max(0, dbStok - item.qty) : null;

    const imgSrc = item.image || '';
    const imgEl  = imgSrc
      ? `<img src="${escHtml(imgSrc)}"
              style="width:40px;height:40px;object-fit:contain;border-radius:6px;
                     background:#f9f9f9;border:1px solid #eee;flex-shrink:0;"
              onerror="this.style.display='none'">`
      : `<span style="font-size:26px">🛒</span>`;

    // Tampilkan indikator stok jika hampir habis
    const stokInfo = (dbStok !== null && dbStok <= 10)
      ? `<div style="font-size:10px;color:#DC2626;margin-top:2px;">Stok DB: ${dbStok}</div>`
      : '';

    // Tombol + disabled jika stok sudah penuh terpakai
    const plusDisabled = (sisa !== null && sisa <= 0)
      ? 'disabled style="opacity:0.35;cursor:not-allowed;"'
      : '';

    return `
      <div class="cart-item">
        <div class="ci-emoji" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          ${imgEl}
        </div>
        <div class="ci-info">
          <div class="ci-name">${escHtml(item.name)}</div>
          <div class="ci-price">${formatRp(item.price)} &times; ${item.qty}</div>
          ${stokInfo}
        </div>
        <div class="ci-qty">
          <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="changeQty(${item.id}, +1)" ${plusDisabled}>+</button>
        </div>
      </div>`;
  }).join('');

  const tv = totalValue();
  document.getElementById('totalItems').textContent = qty + ' item';
  document.getElementById('subtotal').textContent   = formatRp(tv);
  document.getElementById('totalPrice').textContent = formatRp(tv);
}

/* ── Escape HTML untuk cegah XSS di cart render ── */
function escHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

/* ── Dropdown Kategori ── */
function toggleKategori(e) {
  e.stopPropagation();
  const dd = document.getElementById('catDropdown');
  dd.classList.contains('open') ? closeKategori() : openKategori();
}
function openKategori() {
  document.getElementById('catDropdown').classList.add('open');
  document.getElementById('btnKategori').classList.add('open');
  document.getElementById('catOverlay').classList.add('open');
}
function closeKategori() {
  document.getElementById('catDropdown').classList.remove('open');
  document.getElementById('btnKategori').classList.remove('open');
  document.getElementById('catOverlay').classList.remove('open');
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeKategori();
});

/* ── Init saat halaman dimuat ── */
document.addEventListener('DOMContentLoaded', async function() {
  // Muat stok dari server, lalu render keranjang
  await fetchStock();
  renderCart();
  updateBeliButtons();

  // Refresh stok setiap 30 detik (sinkronisasi multi-tab / multi-user)
  setInterval(async () => {
    await fetchStock();
    updateBeliButtons();
  }, 30000);
});
