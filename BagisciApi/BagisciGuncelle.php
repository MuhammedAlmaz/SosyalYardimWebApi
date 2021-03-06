<?php
require_once("../HataKodlari.php");
$Sonuc = array();

if (!(
        isset($_POST["BagisciID"]) &&
        isset($_POST["SehirID"]) &&
        isset($_POST["Ad"]) &&
        isset($_POST["Soyad"]) &&
        isset($_POST["EPosta"]) &&
        isset($_POST["Tel"]) &&
        isset($_POST["Adres"])
    ) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $Sonuc["Sonuc"] = "hata";
    $Sonuc["Aciklama"] = $HataKodlari[1];
    $Sonuc["HataKodu"] = 1;
} else {
    require_once("../Guvenlik/VeritabaniBaglanti.php");
    require_once("../Guvenlik/TokenKontrol.php");

    $QueryKullaniciGuncelle = $db->prepare("UPDATE KullaniciBilgileriTablo SET
	KullaniciBilgileriTablo.[SehirTablo.SehirId] = :SehirID,
	KullaniciBilgileriTablo.KullaniciEPosta = :EPosta,
	KullaniciBilgileriTablo.KullaniciAdres = :Adres,
	KullaniciBilgileriTablo.KullaniciTelefonNumarasi = :Tel,
	KullaniciBilgileriTablo.KullaniciAdi = :KullaniciAdi,
	KullaniciBilgileriTablo.KullaniciSoyadi = :KullaniciSoyadi 
WHERE
KullaniciBilgileriTablo.KullaniciId=:KullaniciID");
    $QueryKullaniciGuncelle->bindParam(":KullaniciID",
        $_POST["BagisciID"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":SehirID",
        $_POST["SehirID"],PDO::PARAM_INT);
    $QueryKullaniciGuncelle->bindParam(":EPosta",
        $_POST["EPosta"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":Adres",
        $_POST["Adres"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":Tel",
        $_POST["Tel"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":KullaniciAdi",
        $_POST["Ad"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->bindParam(":KullaniciSoyadi",
        $_POST["Soyad"],PDO::PARAM_STR);
    $QueryKullaniciGuncelle->execute();
    if($QueryKullaniciGuncelle)
    {
        $Sonuc["Sonuc"]="basarili";
        $Sonuc["Aciklama"]="basarili";
        $Sonuc["HataKodu"]=-1;
    }else{
        $Sonuc["Sonuc"]="hata";
        $Sonuc["Aciklama"]=$HataKodlari[4];
        $Sonuc["HataKodu"]=4;
    }

}

print_r(json_encode($Sonuc));
