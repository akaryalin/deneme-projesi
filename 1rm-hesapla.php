<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #e74c3c;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">💪 1 Rep Max (Maksimum Güç)</h2>
        <div class="form-group"><label>Kaldırdığın Ağırlık (kg):</label><input type="number" id="rm_kilo" placeholder="100"></div>
        <div class="form-group"><label>Tekrar Sayısı:</label><input type="number" id="rm_tekrar" placeholder="5"></div>
        <button onclick="hesaplaRM()" class="btn" style="width: 100%; background: #e74c3c;">HESAPLA</button>
        <div id="sonucRM" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; text-align: center;">
            Tek seferde kaldırabileceğin:<br>
            <strong id="rm_deger" style="font-size: 2.5rem; color: #e74c3c;"></strong> kg
        </div>
    </div>
</div>
<script>
function hesaplaRM() {
    let w = parseFloat(document.getElementById("rm_kilo").value);
    let r = parseFloat(document.getElementById("rm_tekrar").value);
    if(w && r) {
        document.getElementById("rm_deger").innerText = Math.round(w * (1 + (r / 30)));
        document.getElementById("sonucRM").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>