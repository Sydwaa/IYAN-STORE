// GANTI dengan nomor WhatsApp Anda (awali 62, tanpa +)
const NOMOR_WA = "62882019871272";

const rupiah = n => "Rp " + Number(n).toLocaleString("id-ID");
const esc = s => String(s).replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));
const kode = a => "FF-" + String(a.id).padStart(3, "0");
const PLACEHOLDER = "data:image/svg+xml;utf8," + encodeURIComponent(
  '<svg xmlns="http://www.w3.org/2000/svg" width="360" height="640"><rect width="100%" height="100%" fill="#dfe4ec"/>' +
  '<text x="50%" y="50%" fill="#5a6473" font-family="Arial" font-size="20" text-anchor="middle">Tidak ada screenshot</text></svg>'
);

async function ambilAkun() {
  try {
    const r = await fetch("api.php?aksi=daftar");
    if (!r.ok) throw new Error();
    return await r.json();
  } catch (e) { return null; }
}

(async () => {
  const akunList = await ambilAkun();

  // ====== STATISTIK (halaman utama) ======
  const elTersedia = document.getElementById("jml-tersedia");
  if (elTersedia && akunList) {
    elTersedia.textContent = akunList.filter(a => a.status === "tersedia").length;
    document.getElementById("jml-terjual").textContent = akunList.filter(a => a.status === "terjual").length;
  }

  // ====== HALAMAN STOK ======
  const daftar = document.getElementById("daftar-akun");
  if (!daftar) return;
  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");

  if (!akunList) {
    daftar.innerHTML = '<p class="kosong">Data belum bisa dimuat. Pastikan Apache dan MySQL di XAMPP sudah Start.</p>';
    return;
  }

  function tampilkan(filter) {
    const data = filter === "semua" ? akunList : akunList.filter(a => a.status === filter);
    if (!data.length) { daftar.innerHTML = '<p class="kosong">Belum ada akun di kategori ini.</p>'; return; }
    daftar.innerHTML = data.map(a => {
      const pesan = encodeURIComponent(`Halo, saya mau beli akun ${kode(a)} (${a.nama}) seharga ${rupiah(a.harga)}`);
      const tombol = a.status === "tersedia"
        ? `<a class="tombol" href="https://wa.me/${NOMOR_WA}?text=${pesan}" target="_blank" rel="noopener">Beli lewat WhatsApp</a>`
        : `<span class="tombol mati">Sudah terjual</span>`;
      const src = a.gambar ? "uploads/" + esc(a.gambar) : PLACEHOLDER;
      return `
        <article class="akun ${a.status}">
          <div class="foto">
            <img src="${src}" alt="Screenshot akun ${kode(a)}" loading="lazy">
            <span class="badge ${a.status}">${a.status === "tersedia" ? "Tersedia" : "Terjual"}</span>
          </div>
          <div class="info">
            <h3>${esc(a.nama)}</h3>
            <p class="spek">${kode(a)} · Level ${a.level} · ${esc(a.rank)}</p>
            <p class="spek">${esc(a.skin)}</p>
            <p class="harga">${rupiah(a.harga)}</p>
            ${tombol}
          </div>
        </article>`;
    }).join("");

    daftar.querySelectorAll(".foto").forEach(f => {
      const img = f.querySelector("img");
      img.onerror = () => { img.onerror = null; img.src = PLACEHOLDER; };
      f.onclick = () => { lightboxImg.src = img.src; lightbox.hidden = false; };
    });
  }

  document.getElementById("filter").addEventListener("click", e => {
    if (e.target.tagName !== "BUTTON") return;
    document.querySelectorAll("#filter button").forEach(b => b.classList.remove("aktif"));
    e.target.classList.add("aktif");
    tampilkan(e.target.dataset.status);
  });

  const tutup = () => { lightbox.hidden = true; };
  document.getElementById("tutup").onclick = tutup;
  lightbox.onclick = e => { if (e.target === lightbox) tutup(); };
  document.addEventListener("keydown", e => { if (e.key === "Escape") tutup(); });

  tampilkan("semua");
})();
