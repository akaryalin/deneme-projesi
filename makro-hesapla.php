<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #f1c40f;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">🥧 Makro Dağılımı</h2>
        <div class="form-group"><label>Günlük Hedef Kalorin:</label><input type="number" id="makro_kalori" placeholder="2500"></div>
        <button onclick="hesaplaMakro()" class="btn" style="width: 100%; background: #f1c40f; color:#000;">HESAPLA</button>
        <div id="sonucMakro" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; color:#fff;">
            <p>🍞 Karbonhidrat: <strong style="color:#f1c40f" id="makro_karb"></strong> gr</p>
            <p>🥩 Protein: <strong style="color:#e74c3c" id="makro_pro"></strong> gr</p>
            <p>🥑 Yağ: <strong style="color:#3498db" id="makro_yag"></strong> gr</p>
        </div>
    </div>
</div>
<script>
function hesaplaMakro() {
    let cal = document.getElementById("makro_kalori").value;
    if(cal) {
        document.getElementById("makro_karb").innerText = Math.round((cal * 0.50) / 4);
        document.getElementById("makro_pro").innerText = Math.round((cal * 0.30) / 4);
        document.getElementById("makro_yag").innerText = Math.round((cal * 0.20) / 9);
        document.getElementById("sonucMakro").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>