<?php
session_start();
include 'includes/db.php'; // Veritabanı gerekirse diye
include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px; margin-bottom: 100px;">
    <h2 style="color: var(--accent); text-align: center; margin-bottom: 40px;">🧮 Fitness Hesaplama Araçları</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
        
        <div id="vke" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">⚖️ Vücut Kitle Endeksi</h3>
            <div class="form-group"><label>Boy (cm):</label><input type="number" id="vke_boy" placeholder="180"></div>
            <div class="form-group"><label>Kilo (kg):</label><input type="number" id="vke_kilo" placeholder="80"></div>
            <button onclick="hesaplaVKE()" class="btn" style="width: 100%;">HESAPLA</button>
            <div id="sonucVKE" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: center;">
                <strong id="vke_deger" style="font-size: 1.5rem; color: #fff;"></strong><br>
                <span id="vke_durum"></span>
            </div>
        </div>

<div id="kalori" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">🔥 Günlük Kalori İhtiyacı</h3>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;"><label>Cinsiyet:</label><select id="cal_cinsiyet" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="erkek">Erkek</option><option value="kadin">Kadın</option></select></div>
                <div class="form-group" style="flex:1;"><label>Yaş:</label><input type="number" id="cal_yas" placeholder="25"></div>
            </div>
            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;"><label>Boy:</label><input type="number" id="cal_boy" placeholder="180"></div>
                <div class="form-group" style="flex:1;"><label>Kilo:</label><input type="number" id="cal_kilo" placeholder="80"></div>
            </div>
            <div class="form-group"><label>Aktivite:</label><select id="cal_hareket" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="1.2">Hareketsiz (Masa başı)</option><option value="1.375">Hafif Spor (Haftada 1-3)</option><option value="1.55">Orta Spor (Haftada 3-5)</option><option value="1.725">Ağır Spor (Haftada 6-7)</option></select></div>
            <button onclick="hesaplaKalori()" class="btn" style="width: 100%; background: #e67e22;">HESAPLA</button>
            
            <div id="sonucKalori" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; color:#fff;">
                <div style="display: flex; justify-content: space-between; text-align: center; gap: 10px;">
                    <div style="flex: 1; border-right: 1px solid #444;">
                        <span style="font-size: 0.8rem; color: #f1c40f;">📉 Kilo Ver</span><br>
                        <strong id="cal_ver" style="font-size: 1.2rem; color: #fff;">2000</strong>
                    </div>
                    <div style="flex: 1; border-right: 1px solid #444;">
                        <span style="font-size: 0.8rem; color: #3498db;">🛡️ Koru</span><br>
                        <strong id="cal_koru" style="font-size: 1.2rem; color: #fff;">2500</strong>
                    </div>
                    <div style="flex: 1;">
                        <span style="font-size: 0.8rem; color: #2ecc71;">📈 Kilo Al</span><br>
                        <strong id="cal_al" style="font-size: 1.2rem; color: #fff;">2900</strong>
                    </div>
                </div>
                <p style="text-align: center; font-size: 0.7rem; color: #666; margin-top: 10px; margin-bottom: 0;">*Günlük alman gereken tahmini kalorilerdir.</p>
            </div>
        </div>

        <div id="su" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">💧 Su İhtiyacı</h3>
            <div class="form-group"><label>Kilo (kg):</label><input type="number" id="su_kilo" placeholder="80"></div>
            <button onclick="hesaplaSu()" class="btn" style="width: 100%; background: #3498db;">HESAPLA</button>
            <div id="sonucSu" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: center; color:#fff;">
                Günlük: <strong id="su_deger" style="color:#3498db; font-size:1.5rem;"></strong> Litre
            </div>
        </div>

        <div id="protein" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">🥩 Protein İhtiyacı</h3>
            <div class="form-group"><label>Kilo (kg):</label><input type="number" id="pro_kilo" placeholder="80"></div>
            <div class="form-group"><label>Hedef:</label><select id="pro_hedef" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="0.8">Hareketsiz Yaşam</option><option value="1.5">Spor Yapıyorum</option><option value="2.0">Kas Yapmak İstiyorum</option></select></div>
            <button onclick="hesaplaProtein()" class="btn" style="width: 100%; background: #e74c3c;">HESAPLA</button>
            <div id="sonucPro" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: center; color:#fff;">
                İhtiyacın: <strong id="pro_deger" style="color:#e74c3c; font-size:1.5rem;"></strong> gr/gün
            </div>
        </div>

        <div id="idealkilo" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">🎯 İdeal Kilo</h3>
            <div class="form-group"><label>Cinsiyet:</label><select id="ideal_cinsiyet" style="width:100%; height:45px; background:#000; color:#fff; border:1px solid #444;"><option value="erkek">Erkek</option><option value="kadin">Kadın</option></select></div>
            <div class="form-group"><label>Boy (cm):</label><input type="number" id="ideal_boy" placeholder="180"></div>
            <button onclick="hesaplaIdeal()" class="btn" style="width: 100%; background: #9b59b6;">HESAPLA</button>
            <div id="sonucIdeal" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: center; color:#fff;">
                İdeal Kilon: <strong id="ideal_deger" style="color:#9b59b6; font-size:1.5rem;"></strong> kg
            </div>
        </div>

        <div id="makro" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">🥧 Makro Dağılımı</h3>
            <div class="form-group"><label>Günlük Hedef Kalorin:</label><input type="number" id="makro_kalori" placeholder="2500"></div>
            <button onclick="hesaplaMakro()" class="btn" style="width: 100%; background: #f1c40f; color:#000;">HESAPLA</button>
            <div id="sonucMakro" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: left; color:#fff; font-size:0.9rem;">
                🍞 Karb (%50): <strong style="color:#f1c40f" id="makro_karb"></strong> gr<br>
                🥩 Protein (%30): <strong style="color:#e74c3c" id="makro_pro"></strong> gr<br>
                🥑 Yağ (%20): <strong style="color:#3498db" id="makro_yag"></strong> gr
            </div>
        </div>

        <div id="birrm" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">💪 1 Rep Max (Maksimum Güç)</h3>
            <div class="form-group"><label>Kaldırdığın Ağırlık (kg):</label><input type="number" id="rm_kilo" placeholder="100"></div>
            <div class="form-group"><label>Tekrar Sayısı:</label><input type="number" id="rm_tekrar" placeholder="5"></div>
            <button onclick="hesaplaRM()" class="btn" style="width: 100%; background: #e74c3c;">HESAPLA</button>
            <div id="sonucRM" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: center; color:#fff;">
                Tek Seferde Kaldırabileceğin:<br><strong id="rm_deger" style="color:#e74c3c; font-size:1.5rem;"></strong> kg
            </div>
        </div>

        <div id="nabiz" style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
            <h3 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px;">❤️ Nabız Bölgeleri</h3>
            <div class="form-group"><label>Yaşınız:</label><input type="number" id="nabiz_yas" placeholder="25"></div>
            <button onclick="hesaplaNabiz()" class="btn" style="width: 100%; background: #ff7675;">HESAPLA</button>
            <div id="sonucNabiz" style="display:none; margin-top: 15px; padding: 15px; background: #222; border-radius: 10px; text-align: left; color:#fff; font-size:0.9rem;">
                Maksimum: <span id="nabiz_max"></span> bpm<br>
                🔥 Yağ Yakımı: <strong style="color:#f1c40f" id="nabiz_yag"></strong> bpm<br>
                🏃‍♂️ Kardiyo: <strong style="color:#e74c3c" id="nabiz_kardiyo"></strong> bpm
            </div>
        </div>

    </div>
</div>

<script>
    // 1. VKE Hesaplama
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

   // 2. Kalori Hesaplama (GÜNCELLENMİŞ)
    function hesaplaKalori() {
        let c = document.getElementById("cal_cinsiyet").value;
        let y = document.getElementById("cal_yas").value;
        let b = document.getElementById("cal_boy").value;
        let k = document.getElementById("cal_kilo").value;
        let h = document.getElementById("cal_hareket").value;
        
        if(y && b && k) {
            // Harris-Benedict Formülü
            let bmr = (c === "erkek") ? 88.36 + (13.4 * k) + (4.8 * b) - (5.7 * y) : 447.6 + (9.2 * k) + (3.1 * b) - (4.3 * y);
            
            let koruma = Math.round(bmr * h); // Korumak için
            let verme = koruma - 500;         // Kilo vermek için (-500 kcal)
            let alma = koruma + 400;          // Kilo almak için (+400 kcal)

            // Sonuçları Yazdır
            document.getElementById("cal_koru").innerText = koruma;
            document.getElementById("cal_ver").innerText = verme;
            document.getElementById("cal_al").innerText = alma;
            
            document.getElementById("sonucKalori").style.display = "block";
        }
    }

    // 3. Su Hesaplama
    function hesaplaSu() {
        let k = document.getElementById("su_kilo").value;
        if(k) {
            let su = (k * 0.033).toFixed(1); // Kilo başına 33ml
            document.getElementById("su_deger").innerText = su;
            document.getElementById("sonucSu").style.display = "block";
        }
    }

    // 4. Protein Hesaplama
    function hesaplaProtein() {
        let k = document.getElementById("pro_kilo").value;
        let h = document.getElementById("pro_hedef").value;
        if(k) {
            document.getElementById("pro_deger").innerText = Math.round(k * h);
            document.getElementById("sonucPro").style.display = "block";
        }
    }

    // 5. İdeal Kilo (Robinson Formülü)
    function hesaplaIdeal() {
        let c = document.getElementById("ideal_cinsiyet").value;
        let b = document.getElementById("ideal_boy").value;
        if(b) {
            let inc = (b / 2.54) - 60; // 5 feet üzeri inç
            let ideal = (c === "erkek") ? 52 + (1.9 * inc) : 49 + (1.7 * inc);
            document.getElementById("ideal_deger").innerText = Math.round(ideal);
            document.getElementById("sonucIdeal").style.display = "block";
        }
    }

    // 6. Makro (Standard: 50% Karb, 30% Pro, 20% Yağ)
    function hesaplaMakro() {
        let cal = document.getElementById("makro_kalori").value;
        if(cal) {
            document.getElementById("makro_karb").innerText = Math.round((cal * 0.50) / 4);
            document.getElementById("makro_pro").innerText = Math.round((cal * 0.30) / 4);
            document.getElementById("makro_yag").innerText = Math.round((cal * 0.20) / 9);
            document.getElementById("sonucMakro").style.display = "block";
        }
    }

    // 7. 1RM (Epley Formülü)
    function hesaplaRM() {
        let w = parseFloat(document.getElementById("rm_kilo").value);
        let r = parseFloat(document.getElementById("rm_tekrar").value);
        if(w && r) {
            let rm = w * (1 + (r / 30));
            document.getElementById("rm_deger").innerText = Math.round(rm);
            document.getElementById("sonucRM").style.display = "block";
        }
    }

    // 8. Nabız
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