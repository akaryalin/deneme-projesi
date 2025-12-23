<?php include 'includes/header.php'; ?>
<div class="container" style="margin-top: 50px; max-width: 700px;">
    <a href="index.php" class="btn" style="background:#333; margin-bottom:20px;">&larr; Geri Dön</a>
    <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid var(--accent);">
        <h2 style="color: #fff; text-align: center; margin-bottom: 20px;">🔥 Günlük Kalori İhtiyacı</h2>
        <div style="display:flex; gap:10px;">
            <div class="form-group" style="flex:1;"><label>Cinsiyet:</label><select id="cal_cinsiyet" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="erkek">Erkek</option><option value="kadin">Kadın</option></select></div>
            <div class="form-group" style="flex:1;"><label>Yaş:</label><input type="number" id="cal_yas" placeholder="25"></div>
        </div>
        <div style="display:flex; gap:10px;">
            <div class="form-group" style="flex:1;"><label>Boy:</label><input type="number" id="cal_boy" placeholder="180"></div>
            <div class="form-group" style="flex:1;"><label>Kilo:</label><input type="number" id="cal_kilo" placeholder="80"></div>
        </div>
        <div class="form-group"><label>Aktivite:</label><select id="cal_hareket" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="1.2">Hareketsiz</option><option value="1.375">Hafif Spor</option><option value="1.55">Orta Spor</option><option value="1.725">Ağır Spor</option></select></div>
        <button onclick="hesaplaKalori()" class="btn" style="width: 100%; background: #e67e22;">HESAPLA</button>
        <div id="sonucKalori" style="display:none; margin-top: 20px; padding: 20px; background: #222; border-radius: 10px; color:#fff;">
            <div style="display: flex; justify-content: space-between; text-align: center;">
                <div style="flex:1; border-right:1px solid #444;"><span style="color:#f1c40f;">📉 Ver</span><br><strong id="cal_ver" style="font-size:1.5rem;"></strong></div>
                <div style="flex:1; border-right:1px solid #444;"><span style="color:#3498db;">🛡️ Koru</span><br><strong id="cal_koru" style="font-size:1.5rem;"></strong></div>
                <div style="flex:1;"><span style="color:#2ecc71;">📈 Al</span><br><strong id="cal_al" style="font-size:1.5rem;"></strong></div>
            </div>
        </div>
    </div>
</div>
<script>
function hesaplaKalori() {
    let c = document.getElementById("cal_cinsiyet").value;
    let y = document.getElementById("cal_yas").value;
    let b = document.getElementById("cal_boy").value;
    let k = document.getElementById("cal_kilo").value;
    let h = document.getElementById("cal_hareket").value;
    if(y && b && k) {
        let bmr = (c === "erkek") ? 88.36 + (13.4 * k) + (4.8 * b) - (5.7 * y) : 447.6 + (9.2 * k) + (3.1 * b) - (4.3 * y);
        let koru = Math.round(bmr * h);
        document.getElementById("cal_koru").innerText = koru;
        document.getElementById("cal_ver").innerText = koru - 500;
        document.getElementById("cal_al").innerText = koru + 400;
        document.getElementById("sonucKalori").style.display = "block";
    }
}
</script>
<?php include 'includes/footer.php'; ?>