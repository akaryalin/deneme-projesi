<?php 
include 'includes/db.php'; 
include 'includes/header.php'; 
?>

<div class="container">
    <div style="text-align: center; margin: 40px 0;">
        <h2>Üyelik Paketlerimiz</h2>
        <p>Bütçenize ve hedeflerinize en uygun paketi seçin, hemen başlayın.</p>
    </div>

    <div class="paketler">

        <?php
        try {
            $sql = "SELECT * FROM paketler ORDER BY id ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $paketler = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($paketler as $paket) {
                echo '<div class="paket">';
                echo '    <h3>' . htmlspecialchars($paket['paket_adi']) . '</h3>';
                
                echo '    <ul style="text-align: left; margin-bottom: 20px;">';
                $ozellik_listesi = explode('|', $paket['ozellikler']);
                foreach ($ozellik_listesi as $ozellik) {
                    echo '<li style="border-bottom: 1px solid #eee; padding: 8px 0;">&#10003; ' . htmlspecialchars($ozellik) . '</li>';
                }
                echo '    </ul>';
                
                echo '    <div class="fiyat">' . htmlspecialchars($paket['fiyat']) . '</div>';
                // Eğer giriş yapmışsa satın al butonu, yapmamışsa kayıt ol butonu çıkabilir (İleride ekleriz)
                echo '</div>'; 
            }

        } catch (PDOException $e) {
            echo "<div class='error-message'>Paketler yüklenirken hata: " . $e->getMessage() . "</div>";
        }
        ?>

    </div> 
</div>

<?php include 'includes/footer.php'; ?>