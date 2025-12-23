<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid var(--accent);">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">⚖️ Vücut Kitle Endeksi</h2>
        <div class="form-group"><label>Boy (cm):</label><input type="number" id="vke_boy" placeholder="180"></div>
        <div class="form-group"><label>Kilo (kg):</label><input type="number" id="vke_kilo" placeholder="80"></div>
        <button onclick="hesaplaVKE()" class="btn" style="width: 100%;">HESAPLA</button>
        <div id="sonucVKE" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; text-align: center;">
            <strong id="vke_deger" style="font-size: 2rem; color: #fff;"></strong><br>
            <span id="vke_durum" style="font-size: 1.2rem;"></span>
        </div>
    </div>
</div>
<script>
function hesaplaVKE() {
    let boy = document.getElementById("vke_boy").value / 100;
    let kilo = document.getElementById("vke_kilo").value;
    if(boy && kilo) {
        let vke = (kilo / (boy * boy)).toFixed(1);
        let durum = vke < 18.5 ? "Zayıf" : vke < 25 ? "Normal" : vke < 30 ? "Kilolu" : "Obez";
        let renk = vke < 25 ? "#2ecc71" : "#e74c3c";
        document.getElementById("vke_deger").innerText = vke;
        document.getElementById("vke_durum").innerHTML = `<span style='color:${renk}; font-weight:bold;'>${durum}</span>`;
        document.getElementById("sonucVKE").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>