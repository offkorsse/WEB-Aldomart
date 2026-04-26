// ── FILTER KATEGORI ──
const tags = document.querySelectorAll('.tag');
const cards = document.querySelectorAll('.product-card');
const showingText = document.getElementById('showingText');

tags.forEach(tag => {
  tag.addEventListener('click', () => {
    // set active tag
    tags.forEach(t => t.classList.remove('active'));
    tag.classList.add('active');

    const selected = tag.textContent.trim().toLowerCase().replace(' ', '-');

    cards.forEach(card => {
      const category = card.dataset.category;

      if (selected === 'all-items' || category === selected) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });

    updateShowingText();
  });
});

function updateShowingText() {
  const visible = document.querySelectorAll('.product-card:not(.hidden)').length;
  showingText.textContent = `Showing ${visible} of 24 items`;
}

// ── LOAD MORE ──
const loadMoreBtn = document.getElementById('loadMoreBtn');

loadMoreBtn.addEventListener('click', () => {
  // Di sini nanti bisa diganti dengan fetch() ke API backend
  // Contoh:
  // fetch('/api/products?page=2')
  //   .then(res => res.json())
  //   .then(data => renderProducts(data));

  // Sementara: simulasi loading
  loadMoreBtn.textContent = 'Loading...';
  loadMoreBtn.disabled = true;

  setTimeout(() => {
    loadMoreBtn.textContent = 'Load more';
    loadMoreBtn.disabled = false;
    alert('Hubungkan ke API backend untuk memuat lebih banyak produk.');
  }, 800);
});
