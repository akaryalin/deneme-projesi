<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #ff7675;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">❤️ Nabız Bölgeleri</h2>
        <div class="form-group"><label>Yaşınız:</label><input type="number" id="nabiz_yas" placeholder="25"></div>
        <button onclick="hesaplaNabiz()" class="btn" style="width: 100%; background: #ff7675;">HESAPLA</button>
        <div id="sonucNabiz" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; color:#fff;">
            <p>Maksimum: <strong id="nabiz_max"></strong> bpm</p>
            <p>🔥 Yağ Yakımı: <strong style="color:#f1c40f" id="nabiz_yag"></strong> bpm</p>
            <p>🏃‍♂️ Kardiyo: <strong style="color:#e74c3c" id="nabiz_kardiyo"></strong> bpm</p>
        </div>
    </div>
</div>
<script>
function hesaplaNabiz() {
    let y = document.getElementById("nabiz_yas").value;
    if(y) {
        let max = 220 - y;
        document.getElementById("nabiz_max").innerText = max;
        document.getElementById("nabiz_yag").innerText = Math.round(max * 0.60) + " - " + Math.round(max * 0.70);
        document.getElementById("nabiz_kardiyo").innerText = Math.round(max * 0.70) + " - " + Math.round(max * 0.80);
        document.getElementById("sonucNabiz").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>