<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 600px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid #e74c3c;">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">🥩 Protein İhtiyacı</h2>
        <div class="form-group"><label>Kilo (kg):</label><input type="number" id="pro_kilo" placeholder="80"></div>
        <div class="form-group"><label>Hedef:</label><select id="pro_hedef" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="0.8">Hareketsiz Yaşam</option><option value="1.5">Spor Yapıyorum</option><option value="2.0">Kas Yapmak İstiyorum</option></select></div>
        <button onclick="hesaplaProtein()" class="btn" style="width: 100%; background: #e74c3c;">HESAPLA</button>
        <div id="sonucPro" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; text-align: center;">
            <strong id="pro_deger" style="font-size: 2.5rem; color: #e74c3c;"></strong> gr/gün
        </div>
    </div>
</div>
<script>
function hesaplaProtein() {
    let k = document.getElementById("pro_kilo").value;
    let h = document.getElementById("pro_hedef").value;
    if(k) {
        document.getElementById("pro_deger").innerText = Math.round(k * h);
        document.getElementById("sonucPro").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>