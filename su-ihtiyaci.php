<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #3498db;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">💧 Su İhtiyacı</h2>
        <div class="form-group"><label>Kilonuz (kg):</label><input type="number" id="su_kilo" placeholder="80"></div>
        <button onclick="hesaplaSu()" class="btn" style="width: 100%; background: #3498db;">HESAPLA</button>
        <div id="sonucSu" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; text-align: center;">
            <span style="color:#aaa;">Günlük içmen gereken:</span><br>
            <strong id="su_deger" style="font-size: 2.5rem; color: #3498db;"></strong> Litre
        </div>
    </div>
</div>
<script>
function hesaplaSu() {
    let k = document.getElementById("su_kilo").value;
    if(k) {
        document.getElementById("su_deger").innerText = (k * 0.033).toFixed(1);
        document.getElementById("sonucSu").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>