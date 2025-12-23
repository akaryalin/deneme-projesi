<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #9b59b6;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">🎯 İdeal Kilo Hesapla</h2>
        <div class="form-group"><label>Cinsiyet:</label><select id="ideal_cinsiyet" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="erkek">Erkek</option><option value="kadin">Kadın</option></select></div>
        <div class="form-group"><label>Boy (cm):</label><input type="number" id="ideal_boy" placeholder="180"></div>
        <button onclick="hesaplaIdeal()" class="btn" style="width: 100%; background: #9b59b6;">HESAPLA</button>
        <div id="sonucIdeal" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; text-align: center;">
            İdeal Kilon: <strong id="ideal_deger" style="font-size: 2.5rem; color: #9b59b6;"></strong> kg
        </div>
    </div>
</div>
<script>
function hesaplaIdeal() {
    let c = document.getElementById("ideal_cinsiyet").value;
    let b = document.getElementById("ideal_boy").value;
    if(b) {
        let inc = (b / 2.54) - 60;
        let ideal = (c === "erkek") ? 52 + (1.9 * inc) : 49 + (1.7 * inc);
        document.getElementById("ideal_deger").innerText = Math.round(ideal);
        document.getElementById("sonucIdeal").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>