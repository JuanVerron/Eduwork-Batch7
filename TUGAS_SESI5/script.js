const products = [
  {
    name: "MacBook Air M2",
    price: 18500000,
    category: "Laptop",
    sold: 320,
    description:
      "Performa tinggi dengan chip M2, baterai tahan seharian, layar Liquid Retina.",
    image:
      "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=200&fit=crop",
  },
  {
    name: "ASUS ROG Zephyrus",
    price: 22000000,
    category: "Laptop",
    sold: 185,
    description:
      "Laptop gaming bertenaga RTX 4060, layar 165Hz, cocok untuk gaming serius.",
    image:
      "https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=400&h=200&fit=crop",
  },
  {
    name: "iPhone 15 Pro",
    price: 19999000,
    category: "Smartphone",
    sold: 890,
    description:
      "Chip A17 Pro, kamera 48MP, desain titanium premium dan ringan.",
    image:
      "https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=400&h=200&fit=crop",
  },
  {
    name: "Samsung Galaxy S24",
    price: 14999000,
    category: "Smartphone",
    sold: 740,
    description:
      "Layar Dynamic AMOLED 120Hz, AI Camera, performa flagship terdepan.",
    image:
      "https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400&h=200&fit=crop",
  },
  {
    name: "Xiaomi Redmi Note 13",
    price: 3299000,
    category: "Smartphone",
    sold: 1540,
    description:
      "Layar AMOLED 120Hz, baterai 5000mAh, kamera 200MP di harga terjangkau.",
    image:
      "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=200&fit=crop",
  },
  {
    name: "Sony WH-1000XM5",
    price: 4899000,
    category: "Audio",
    sold: 430,
    description:
      "Noise cancelling terbaik di kelasnya, suara jernih, baterai 30 jam.",
    image:
      "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=200&fit=crop",
  },
  {
    name: "AirPods Pro 2",
    price: 3999000,
    category: "Audio",
    sold: 980,
    description:
      "ANC adaptif, Transparency Mode, chip H2, suara spatial audio.",
    image:
      "https://images.unsplash.com/photo-1606741965509-717762a2f668?w=400&h=200&fit=crop",
  },
  {
    name: "JBL Flip 6",
    price: 1599000,
    category: "Audio",
    sold: 620,
    description:
      "Speaker Bluetooth portabel, waterproof IP67, bass punchy untuk on-the-go.",
    image:
      "https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=200&fit=crop",
  },
  {
    name: "Magic Mouse Apple",
    price: 1299000,
    category: "Aksesoris",
    sold: 210,
    description:
      "Multi-touch surface, desain tipis elegan, koneksi Bluetooth seamless.",
    image:
      "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=200&fit=crop",
  },
  {
    name: "Logitech MX Keys",
    price: 1599000,
    category: "Aksesoris",
    sold: 390,
    description:
      "Keyboard wireless premium, backlit, bisa pairing 3 perangkat sekaligus.",
    image:
      "https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=200&fit=crop",
  },
  {
    name: "Anker USB-C Hub 7-in-1",
    price: 499000,
    category: "Aksesoris",
    sold: 870,
    description:
      "7 port dalam satu hub: HDMI 4K, USB-A, SD card, PD charging 100W.",
    image:
      "https://images.unsplash.com/photo-1625895197185-efcec01cffe0?w=400&h=200&fit=crop",
  },
  {
    name: "Lenovo IdeaPad Slim 5",
    price: 9499000,
    category: "Laptop",
    sold: 460,
    description:
      "Laptop tipis harian, prosesor AMD Ryzen 5, layar IPS Full HD anti-glare.",
    image:
      "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=200&fit=crop",
  },
];

let activeState = {
  kategori: "Semua",
  harga: "Semua",
  sort: "default",
};

const priceRanges = [
  { label: "Semua", min: null, max: null },
  { label: "< 1jt", min: null, max: 999999 },
  { label: "1jt – 5jt", min: 1000000, max: 5000000 },
  { label: "5jt – 15jt", min: 5000001, max: 15000000 },
  { label: "> 15jt", min: 15000001, max: null },
];

function formatRupiah(angka) {
  return "Rp " + angka.toLocaleString("id-ID");
}

function applyFilters() {
  let hasil = [...products];

  if (activeState.kategori !== "Semua") {
    hasil = hasil.filter((p) => p.category === activeState.kategori);
  }

  if (activeState.harga !== "Semua") {
    const range = priceRanges.find((r) => r.label === activeState.harga);
    hasil = hasil.filter((p) => {
      const lolosMin = range.min === null || p.price >= range.min;
      const lolosMax = range.max === null || p.price <= range.max;
      return lolosMin && lolosMax;
    });
  }

  if (activeState.sort === "harga-tertinggi") {
    hasil.sort((a, b) => b.price - a.price);
  } else if (activeState.sort === "harga-terendah") {
    hasil.sort((a, b) => a.price - b.price);
  } else if (activeState.sort === "terlaris") {
    hasil.sort((a, b) => b.sold - a.sold);
  }

  renderProducts(hasil);
}

function renderProducts(arrayProduk) {
  const grid = document.getElementById("productGrid");
  const countEl = document.getElementById("productCount");

  countEl.innerHTML = `Menampilkan <span>${arrayProduk.length}</span> produk`;

  if (arrayProduk.length === 0) {
    grid.innerHTML = `
          <div class="col-12 empty-state">
            <i class="bi bi-search"></i>
            <p>Tidak ada produk yang sesuai filter.</p>
          </div>`;
    return;
  }

  grid.innerHTML = "";

  const top3 = [...arrayProduk]
    .sort((a, b) => b.sold - a.sold)
    .slice(0, 3)
    .map((p) => p.name);

  arrayProduk.forEach(function (produk, index) {
    const isTerlaris = top3.includes(produk.name);

    const col = document.createElement("div");
    col.className = "col-sm-6 col-lg-4 product-col";
    col.style.animationDelay = index * 0.07 + "s";

    col.innerHTML = `
          <div class="product-card">
            <img src="${produk.image}" alt="${produk.name}" loading="lazy"/>
            <div class="card-body">
              <div>
                <span class="category-badge">${produk.category}</span>
                ${isTerlaris ? '<span class="terlaris-badge"><i class="bi bi-fire"></i> Terlaris</span>' : ""}
              </div>
              <p class="product-name">${produk.name}</p>
              <p class="product-desc">${produk.description}</p>
              <span class="product-price">${formatRupiah(produk.price)}</span>
              <p class="product-sold">
                <i class="bi bi-bag-check me-1"></i>${produk.sold.toLocaleString("id-ID")} terjual
              </p>
              <button class="btn-cart">
                <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
              </button>
            </div>
          </div>`;

    grid.appendChild(col);
  });
}

function renderFilterKategori() {
  const container = document.getElementById("filterKategori");
  const categories = ["Semua", ...new Set(products.map((p) => p.category))];

  categories.forEach(function (cat) {
    const btn = document.createElement("button");
    btn.className = "filter-btn" + (cat === "Semua" ? " active" : "");
    btn.textContent = cat;

    btn.addEventListener("click", function () {
      activeState.kategori = cat;
      document
        .querySelectorAll("#filterKategori .filter-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      applyFilters();
    });

    container.appendChild(btn);
  });
}

function renderFilterHarga() {
  const container = document.getElementById("filterHarga");

  priceRanges.forEach(function (range) {
    const btn = document.createElement("button");
    btn.className = "filter-btn" + (range.label === "Semua" ? " active" : "");
    btn.textContent = range.label;

    btn.addEventListener("click", function () {
      activeState.harga = range.label;
      document
        .querySelectorAll("#filterHarga .filter-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      applyFilters();
    });

    container.appendChild(btn);
  });
}

document.getElementById("sortSelect").addEventListener("change", function () {
  activeState.sort = this.value;
  applyFilters();
});

renderFilterKategori();
renderFilterHarga();
applyFilters();
