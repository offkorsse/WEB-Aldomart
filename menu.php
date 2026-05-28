<?php
session_start();
include 'koneksi.php';

// ── Proteksi: harus login ──────────────────────────────
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// ── Ambil produk dari database ─────────────────────────
$categories = ['Semua','Makanan','Minuman','Jajanan','Sabun','Lain-lain'];
$catIcons   = [
  'Semua'     => '🏪',
  'Makanan'   => '🍜',
  'Minuman'   => '🥤',
  'Jajanan'   => '🍬',
  'Sabun'     => '🧼',
  'Lain-lain' => '📦',
];

// Tangkap query pencarian dan kategori dari URL (GET)
$searchQuery    = isset($_GET['q'])   ? trim($_GET['q'])   : '';
$activeCategory = isset($_GET['cat']) && $_GET['cat'] !== '' ? trim($_GET['cat']) : 'Semua';

// Build query dengan prepared statement
if ($activeCategory === 'Semua' && $searchQuery === '') {
    $sql    = "SELECT * FROM products ORDER BY id ASC";
    $stmt   = $conn->prepare($sql);
    $stmt->execute();
} elseif ($activeCategory === 'Semua') {
    $sql    = "SELECT * FROM products WHERE nama LIKE ? ORDER BY id ASC";
    $stmt   = $conn->prepare($sql);
    $like   = '%' . $searchQuery . '%';
    $stmt->bind_param('s', $like);
    $stmt->execute();
} elseif ($searchQuery === '') {
    $sql    = "SELECT * FROM products WHERE kategori = ? ORDER BY id ASC";
    $stmt   = $conn->prepare($sql);
    $stmt->bind_param('s', $activeCategory);
    $stmt->execute();
} else {
    $sql    = "SELECT * FROM products WHERE kategori = ? AND nama LIKE ? ORDER BY id ASC";
    $stmt   = $conn->prepare($sql);
    $like   = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $activeCategory, $like);
    $stmt->execute();
}

$result   = $stmt->get_result();
$filtered = [];
while ($row = $result->fetch_assoc()) {
    $filtered[] = $row;
}
$stmt->close();

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ALDOMART – Menu</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">

  <a href="menu.php" class="logo">
    <span style="font-size:22px">🛒</span>
    <span class="logo-text">ALDOMART</span>
  </a>

  <button class="btn-kategori" id="btnKategori" onclick="toggleKategori(event)">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
      <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
    </svg>
    Kategori
    <span class="chevron">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
        <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
      </svg>
    </span>
  </button>

  <div class="cat-dropdown" id="catDropdown">
    <div class="cat-dd-title">Pilih Kategori</div>
    <?php foreach ($categories as $cat):
      $isActive = ($cat === $activeCategory);
      $url = 'menu.php?cat=' . urlencode($cat) . ($searchQuery ? '&q='.urlencode($searchQuery) : '');
    ?>
      <a href="<?= $url ?>" class="cat-dd-item <?= $isActive ? 'active' : '' ?>">
        <span class="cat-dd-icon"><?= $catIcons[$cat] ?? '📦' ?></span>
        <?= htmlspecialchars($cat) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="search-wrap">
    <form method="GET" action="menu.php">
      <?php if ($activeCategory !== 'Semua'): ?>
        <input type="hidden" name="kategori" value="<?= htmlspecialchars($activeCategory) ?>">
      <?php endif; ?>
      <input class="search-input" type="text" name="q"
             placeholder="Cari produk..."
             value="<?= htmlspecialchars($searchQuery) ?>">
      <button class="search-btn" type="submit">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
          <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
      </button>
    </form>
  </div>

  <div class="nav-right">
    <span class="nav-user">👤 <?= htmlspecialchars($_SESSION['user'] ?? 'Tamu') ?></span>

    <a href="admin.php" class="btn-admin-nav" style="display:flex;align-items:center;gap:6px;background:var(--blue-dark);color:white;padding:8px 14px;border-radius:6px;text-decoration:none;font-weight:500;font-size:13px;font-family:'DM Sans',sans-serif;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"></circle>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
      </svg>
      Admin
    </a>

    <a href="logout.php"><button class="btn-logout">Keluar</button></a>
  </div>

</nav>

<div id="catOverlay" onclick="closeKategori()"></div>

<div class="layout">

  <main class="main">

    <div class="page-header">
      <h2>
        <?= ($activeCategory === 'Semua') ? 'Semua Produk' : htmlspecialchars($activeCategory) ?>
        <?= $searchQuery ? ' &mdash; &ldquo;'.htmlspecialchars($searchQuery).'&rdquo;' : '' ?>
      </h2>
      <span class="result-count"><?= count($filtered) ?> produk ditemukan</span>
    </div>

    <?php if ($activeCategory !== 'Semua' || $searchQuery): ?>
    <div class="filter-chips">
      <?php if ($activeCategory !== 'Semua'): ?>
        <span class="active-chip">
          <?= $catIcons[$activeCategory] ?? '📦' ?> <?= htmlspecialchars($activeCategory) ?>
          <a href="menu.php<?= $searchQuery ? '?q='.urlencode($searchQuery) : '' ?>">✕</a>
        </span>
      <?php endif; ?>
      <?php if ($searchQuery): ?>
        <span class="active-chip" style="background:#374151; color:white;">
          🔍 &ldquo;<?= htmlspecialchars($searchQuery) ?>&rdquo;
          <a href="menu.php<?= $activeCategory !== 'Semua' ? '?cat='.urlencode($activeCategory) : '' ?>" style="color:white;">✕</a>
        </span>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="product-grid">
      <?php if (empty($filtered)): ?>
        <div class="empty-state">
          <div class="icon" style="font-size:40px; margin-bottom:10px;">🔍</div>
          <p>Produk tidak ditemukan.</p>
        </div>
      <?php else: ?>
        <?php foreach ($filtered as $p): ?>
          <div class="product-card">
            <div class="card-img">
              <span class="card-badge"><?= htmlspecialchars($p['kategori']) ?></span>
              <img src="<?= htmlspecialchars($p['gambar']) ?>"
                   alt="<?= htmlspecialchars($p['nama']) ?>"
                   style="max-width:100%; max-height:100%; object-fit:contain;"
                   onerror="this.src='https://placehold.co/200x200?text=No+Image'">
            </div>
            <div class="card-body">
              <div class="card-name"><?= htmlspecialchars($p['nama']) ?></div>
              <div class="card-price"><?= rupiah($p['harga']) ?></div>
              <?php if ($p['stok'] <= 0): ?>
                <button class="btn-beli" data-id="<?= (int)$p['id'] ?>"
                        disabled style="opacity:0.5; cursor:not-allowed;">Stok Habis</button>
              <?php else: ?>
                <button class="btn-beli" data-id="<?= (int)$p['id'] ?>"
                  onclick='addToCart(
                    <?= (int)$p["id"] ?>,
                    <?= json_encode($p["nama"]) ?>,
                    <?= (int)$p["harga"] ?>,
                    <?= json_encode($p["gambar"]) ?>
                  )'>
                  + Tambah
                </button>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>

  <aside class="cart-panel">

    <div class="cart-head">
      <h3>🛒 Keranjang</h3>
      <span class="cart-count-pill" id="cartCountPill">0</span>
    </div>

    <div class="cart-items" id="cartItems">
      <div class="cart-empty">
        <div class="icon">🛒</div>
        <p>Keranjang masih kosong</p>
      </div>
    </div>

    <div class="cart-foot">
      <div class="summary-row">
        <span>Total item</span>
        <span id="totalItems">0 item</span>
      </div>
      <div class="summary-row">
        <span>Subtotal</span>
        <span id="subtotal">Rp 0</span>
      </div>
      
      <div class="summary-row total">
        <span>Total</span>
        <span id="totalPrice">Rp 0</span>
      </div>
      <button class="btn-checkout" onclick="checkout()">Bayar Sekarang</button>
      <button class="btn-clear" onclick="clearCart()">Kosongkan Keranjang</button>
    </div>

  </aside>

</div>

<script src="script.js"></script>
</body>
</html>
