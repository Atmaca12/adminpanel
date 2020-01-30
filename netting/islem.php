
<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ob_start();
session_start();

include 'baglan.php';
include '../production/fonksiyon.php';

//bikac yerde sorun cıkarsa fonnksiyon.php deki session start komutunu tekrar girmeyi dene


//kullanici login işlemlerii
if (isset($_POST['kullanicigiris'])) {
	$kullanici_mail = htmlspecialchars($_POST['kullanici_mail']);
	$kullanici_password = md5($_POST['kullanici_password']);


	$kullanicisor = $db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_password=:password and kullanici_yetki=:yetki and kullanici_durum=:durum
	");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'password' => $kullanici_password,
		'yetki' => 1,
		'durum' => 1
	));

	$say = $kullanicisor->rowCount();

	if ($say == 1) {
		echo	$_SESSION['userkullanicigiris'] = $kullanici_mail;
		header("Location:../../");
		exit;
	} else {
		header("Location:../../?durum=basarisizgiris");
	}
}




//kullanici hesap bilgileri guncelleme

if (isset($_POST['kullanicigncelle'])) {

	$save = $db->prepare("UPDATE kullanici SET 

	kullanici_mail=:kullanici_mail,
	kullanici_il=:kullanici_il,
	kullanici_ilce=:kullanici_ilce,
	kullanici_gsm=:kullanici_gsm,
	kullanici_adres=:kullanici_adres
	where kullanici_id={$_POST['kullanici_id']}
	");
	$update = $save->execute(array(
		'kullanici_mail' => $_POST['kullanici_mail'],
		'kullanici_il' => $_POST['kullanici_il'],
		'kullanici_ilce' => $_POST['kullanici_ilce'],
		'kullanici_gsm' => $_POST['kullanici_gsm'],
		'kullanici_adres' => $_POST['kullanici_adres']
	));

	if ($update) {
		header("Location:../../hesabim.php?durum=ok");
		exit;
	} else {
		header("Location:../../hesabim.php?durum=no");
		exit;
	}
}












//kullanıcı kayıt işlemleri !!!

if (isset($_POST['kullanicikaydet'])) {

	// ! kotu niyetli kullanıcı zararlı kod girmeye calısırsa htmlspecialchars fonksiyonundan gecirerek hasarı en aza indirirsin !

	$kullanici_adsoyad = htmlspecialchars($_POST['kullanici_adsoyad']);
	$kullanici_mail = htmlspecialchars($_POST['kullanici_mail']);

	$kullanici_passwordone = $_POST['kullanici_passwordone'];
	$kullanici_passwordtwo = $_POST['kullanici_passwordtwo'];


	if ($kullanici_passwordone == $kullanici_passwordtwo) {
		if (strlen($kullanici_passwordone) >= 6) {

			$kullanicisor = $db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
			$kullanicisor->execute(array(
				'mail' => $kullanici_mail
			));

			//donen satır sayısını belirtir
			$say = $kullanicisor->rowCount();

			if ($say == 0) {

				$password = md5($kullanici_passwordone);

				$kullanici_yetki = 1;

				$kullanicikaydet = $db->prepare("INSERT INTO kullanici set
				
				kullanici_adsoyad=:kullanici_adsoyad,
				kullanici_mail=:kullanici_mail,
				kullanici_password=:kullanici_password,
				kullanici_yetki=:kullanici_yetki");

				$insert = $kullanicikaydet->execute(array(
					'kullanici_adsoyad' => $kullanici_adsoyad,
					'kullanici_mail' => $kullanici_mail,
					'kullanici_password' => $password,
					'kullanici_yetki' => $kullanici_yetki
				));

				if ($insert) {
					echo "kayıt basarılı";
				} else {
					header("Location:../../register.php?durum=basarisiz");
					exit;
				}
			} else {
				header("Location:../../register.php?durum=mukerrerkayit");
				exit;
			}
		} else {
			header("Location:../../register.php?durum=eksiksifre");
		}
	} else {
		header("Location:../../register.php?durum=farklisifre");
	}
}










if (isset($_POST['sliderkaydet'])) {

	$uploads_dir = '../../dimg/slider';
	@$tmp_name = $_FILES['slider_resimyol']['tmp_name'];
	@$name = $_FILES['slider_resimyol']['name'];
	//resim isminin benzersiz olması

	$benzersizsayi1 = rand(20000, 32000);
	$benzersizsayi2 = rand(20000, 32000);
	$benzersizsayi3 = rand(20000, 32000);
	$benzersizsayi4 = rand(20000, 32000);
	$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;

	$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;

	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");


	$kaydet = $db->prepare("INSERT INTO slider set
		slider_ad=:slider_ad,
		slider_sira=:slider_sira,
		slider_link=:slider_link,
		slider_resimyol=:slider_resimyol		
		");
	$insert = $kaydet->execute(array(
		'slider_ad' => $_POST['slider_ad'],
		'slider_sira' => $_POST['slider_sira'],
		'slider_link' => $_POST['slider_link'],
		'slider_resimyol' => $refimgyol
	));


	if ($insert) {
		header("Location:../production/slider.php?durum=ok");
		exit;
	} else {
		header("Location:../production/slider.php?durum=no");
		exit;
	}
}



if ($_GET['slidersil'] == "ok") {


	$sil = $db->prepare("DELETE FROM slider where slider_id=:id");
	$kontrol = $sil->execute(array(
		'id' => $_GET['slider_id']
	));



	if ($kontrol) {
		header("Location:../production/slider.php?sil=ok");
		exit;
	} else {
		header("Location:../production/slider.php?sil=no");
		exit;
	}
}



if (isset($_POST['sliderduzenle'])) {


	$uploads_dir = '../../dimg/slider';

	@$tmp_name = $_FILES['slider_resimyol']["tmp_name"];
	@$name = $_FILES['slider_resimyol']["name"];

	$benzersizsayi1 = rand(20000, 32000);
	$benzersizsayi2 = rand(20000, 32000);
	$benzersizsayi3 = rand(20000, 32000);
	$benzersizsayi4 = rand(20000, 32000);
	$benzersizad = $benzersizsayi1 . $benzersizsayi2 . $benzersizsayi3 . $benzersizsayi4;

	$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizad . $name;



	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");


	$duzenle = $db->prepare("UPDATE slider SET
		slider_resimyol=:slider_resimyol,
		slider_ad=:slider_ad,
		slider_link=:slider_link,
		slider_sira=:slider_sira,
		slider_durum=:slider_durum
		WHERE slider_id={$_POST['slider_id']}");

	$update = $duzenle->execute(array(
		'slider_resimyol' => $refimgyol,
		'slider_ad' => $_POST['slider_ad'],
		'slider_link' => $_POST['slider_link'],
		'slider_sira' => $_POST['slider_sira'],
		'slider_durum' => $_POST['slider_durum']


	));



	if ($update) {



		Header("Location:../production/slider.php?durum=ok");
	} else {

		Header("Location:../production/slider.php?durum=no");
	}
}

















if (isset($_POST['logoduzenle'])) {


	$uploads_dir = '../../dimg';

	@$tmp_name = $_FILES['ayar_logo']["tmp_name"];
	@$name = $_FILES['ayar_logo']["name"];

	$benzersizsayi4 = rand(20000, 32000);
	$refimgyol = substr($uploads_dir, 6) . "/" . $benzersizsayi4 . $name;



	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi4$name");


	$duzenle = $db->prepare("UPDATE ayar SET
		ayar_logo=:logo
		WHERE ayar_id=0");
	$update = $duzenle->execute(array(
		'logo' => $refimgyol
	));



	if ($update) {

		$resimsilunlink = $_POST['eski_yol'];
		unlink("../../$resimsilunlink");

		Header("Location:../production/genel-ayar.php?durum=ok");
	} else {

		Header("Location:../production/genel-ayar.php?durum=no");
	}
}






if (isset($_POST['admingiris'])) {

	$kullanici_mail = $_POST['kullanici_mail'];
	$kullanici_password = md5($_POST['kullanici_password']);


	$kullanicisor = $db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_password=:password and kullanici_yetki=:yetki");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'password' => $kullanici_password,
		'yetki' => 5
	));

	echo $say = $kullanicisor->rowCount();

	if ($say == 1) {

		$_SESSION['kullanici_mail'] = $kullanici_mail;
		header("Location:../production/index.php");
		exit;
	} else {

		header("Location:../production/login.php?durum=no");
		exit;
	}
}



if (isset($_POST['genelayarkaydet'])) {

	$kaydet = $db->prepare("UPDATE ayar set 

		ayar_title=:a,
		ayar_description=:b,
		ayar_keywords=:c,
		ayar_author=:d
		where ayar_id=0");

	$update = $kaydet->execute(array(

		'a' => $_POST['ayar_title'],
		'b' => $_POST['ayar_description'],
		'c' => $_POST['ayar_keywords'],
		'd' => $_POST['ayar_author']
	));

	if ($update) {
		header("Location:../production/genel-ayar.php?durum=ok");
		exit;
	} else {
		header("Location:../production/genel-ayar.php?durum=no");
		exit;
	}
}



if (isset($_POST['iletisimayarkaydet'])) {

	//Tablo güncelleme işlemi kodları...
	$ayarkaydet = $db->prepare("UPDATE ayar SET
		ayar_tel=:ayar_tel,
		ayar_gsm=:ayar_gsm,
		ayar_faks=:ayar_faks,
		ayar_mail=:ayar_mail,
		ayar_ilce=:ayar_ilce,
		ayar_il=:ayar_il,
		ayar_adres=:ayar_adres,
		ayar_mesai=:ayar_mesai
		WHERE ayar_id=0");

	$update = $ayarkaydet->execute(array(
		'ayar_tel' => $_POST['ayar_tel'],
		'ayar_gsm' => $_POST['ayar_gsm'],
		'ayar_faks' => $_POST['ayar_faks'],
		'ayar_mail' => $_POST['ayar_mail'],
		'ayar_ilce' => $_POST['ayar_ilce'],
		'ayar_il' => $_POST['ayar_il'],
		'ayar_adres' => $_POST['ayar_adres'],
		'ayar_mesai' => $_POST['ayar_mesai']
	));


	if ($update) {

		header("Location:../production/iletisim-ayarlar.php?durum=ok");
	} else {

		header("Location:../production/iletisim-ayarlar.php?durum=no");
	}
}


if (isset($_POST['apiayarkaydet'])) {

	//Tablo güncelleme işlemi kodları...
	$ayarkaydet = $db->prepare("UPDATE ayar SET
		
		ayar_analystic=:ayar_analystic,
		ayar_maps=:ayar_maps,
		ayar_zopim=:ayar_zopim
		WHERE ayar_id=0");

	$update = $ayarkaydet->execute(array(

		'ayar_analistic' => $_POST['ayar_analistic'],
		'ayar_maps' => $_POST['ayar_maps'],
		'ayar_zopim' => $_POST['ayar_zopim']
	));


	if ($update) {

		header("Location:../production/api-ayarlar.php?durum=ok");
	} else {

		header("Location:../production/api-ayarlar.php?durum=no");
	}
}


if (isset($_POST['hakkimizdakaydet'])) {

	//Tablo güncelleme işlemi kodları...

	/*

	copy paste işlemlerinde tablo ve işaretli satır isminin değiştirildiğinden emin olun!!!

	*/
	$ayarkaydet = $db->prepare("UPDATE hakkimizda SET
		hakkimizda_baslik=:hakkimizda_baslik,
		hakkimizda_icerik=:hakkimizda_icerik,
		hakkimizda_video=:hakkimizda_video,
		hakkimizda_vizyon=:hakkimizda_vizyon,
		hakkimizda_misyon=:hakkimizda_misyon
		WHERE hakkimizda_id=0");

	$update = $ayarkaydet->execute(array(
		'hakkimizda_baslik' => $_POST['hakkimizda_baslik'],
		'hakkimizda_icerik' => $_POST['hakkimizda_icerik'],
		'hakkimizda_video' => $_POST['hakkimizda_video'],
		'hakkimizda_vizyon' => $_POST['hakkimizda_vizyon'],
		'hakkimizda_misyon' => $_POST['hakkimizda_misyon']
	));


	if ($update) {

		header("Location:../production/hakkimizda.php?durum=ok");
	} else {

		header("Location:../production/hakkimizda.php?durum=no");
	}
}


if (isset($_POST['sosyalayarkaydet'])) {

	//Tablo güncelleme işlemi kodları...
	$ayarkaydet = $db->prepare("UPDATE ayar SET
		ayar_facebook=:ayar_facebook,
		ayar_twitter=:ayar_twitter,
		ayar_google	=:ayar_google	,
		ayar_youtube=:ayar_youtube
		WHERE ayar_id=0");

	$update = $ayarkaydet->execute(array(
		'ayar_facebook' => $_POST['ayar_facebook'],
		'ayar_twitter' => $_POST['ayar_twitter'],
		'ayar_google' => $_POST['ayar_google'],
		'ayar_youtube' => $_POST['ayar_youtube']
	));


	if ($update) {

		header("Location:../production/sosyal-ayarlar.php?durum=ok");
	} else {

		header("Location:../production/sosyal-ayarlar.php?durum=no");
	}
}


if (isset($_POST['mailayarkaydet'])) {

	//Tablo güncelleme işlemi kodları...
	$ayarkaydet = $db->prepare("UPDATE ayar SET
		ayar_smtphost=:ayar_smtphost,
		ayar_smtpuser=:ayar_smtpuser,
		ayar_smtppassword	=:ayar_smtppassword	,
		ayar_smtpport=:ayar_smtpport
		WHERE ayar_id=0");

	$update = $ayarkaydet->execute(array(
		'ayar_smtphost' => $_POST['ayar_smtphost'],
		'ayar_smtpuser' => $_POST['ayar_smtpuser'],
		'ayar_smtppassword' => $_POST['ayar_smtppassword'],
		'ayar_smtpport' => $_POST['ayar_smtpport']
	));


	if ($update) {

		header("Location:../production/mail-ayarlar.php?durum=ok");
	} else {

		header("Location:../production/mail-ayarlar.php?durum=no");
	}
}

if (isset($_POST['kullaniciduzenle'])) {

	$kullanici_id = $_POST['kullanici_id'];


	$ayarkaydet = $db->prepare("UPDATE kullanici SET
		kullanici_tc=:kullanici_tc,
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_durum=:kullanici_durum
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update = $ayarkaydet->execute(array(
		'kullanici_tc' => $_POST['kullanici_tc'],
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_durum' => $_POST['kullanici_durum']

	));


	if ($update) {

		header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=ok");
	} else {

		header("Location:../production/kullanici-duzenle.php?kullanici_id$kullanici_id&durum=no");
	}
}

if ($_GET['kullanicisil'] == "ok") {


	$sil = $db->prepare("DELETE FROM kullanici where kullanici_id=:id");
	$kontrol = $sil->execute(array(
		'id' => $_GET['kullanici_id']
	));



	if ($kontrol) {
		header("Location:../production/kullanici.php?sil=ok");
		exit;
	} else {
		header("Location:../production/kullanici.php?sil=no");
		exit;
	}
}


if (isset($_POST['menuduzenle'])) {

	$menu_id = $_POST['menu_id'];

	$menu_seourl = seo($_POST['menu_ad']);


	$ayarkaydet = $db->prepare("UPDATE menu SET 

			menu_ad=:menu_ad,
			menu_detay=:menu_detay,
			menu_url=:menu_url,
			menu_sira=:menu_sira,
			menu_seourl=:menu_seourl,
			menu_durum=:menu_durum
			where menu_id={$_POST['menu_id']}");

	//degisken oldugu zaman suslu parantez içerisinde yazılır  boyle oldugunda =: yapmak yerine =  yapıcaksın yarım saat aradım amk

	$update = $ayarkaydet->execute(array(

		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,
		'menu_durum' => $_POST['menu_durum']


	));


	if ($update) {
		header("Location:../production/menu-duzenle.php?$menu=id&durum=ok");
		exit;
	} else {

		header("Location:../production/menu-duzenle.php?$menu=id&durum=no");
		exit;
	}
}


if ($_GET['menusil'] == "ok") {


	$sil = $db->prepare("DELETE FROM menu where menu_id=:id");
	$kontrol = $sil->execute(array(
		'id' => $_GET['menu_id']
	));



	if ($kontrol) {
		header("Location:../production/menu.php?sil=ok");
		exit;
	} else {
		header("Location:../production/menu.php?sil=no");
		exit;
	}
}



if (isset($_POST['kullaniciduzenle'])) {

	$kullanici_id = $_POST['kullanici_id'];


	$ayarkaydet = $db->prepare("UPDATE kullanici SET
		kullanici_tc=:kullanici_tc,
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_durum=:kullanici_durum
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update = $ayarkaydet->execute(array(
		'kullanici_tc' => $_POST['kullanici_tc'],
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_durum' => $_POST['kullanici_durum']

	));


	if ($update) {

		header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=ok");
	} else {

		header("Location:../production/kullanici-duzenle.php?kullanici_id$kullanici_id&durum=no");
	}
}




if (isset($_POST['menukaydet'])) {


	$menu_seourl = seo($_POST['menu_ad']);


	$ayarekle = $db->prepare("INSERT INTO menu SET
		menu_ad=:menu_ad,
		menu_detay=:menu_detay,
		menu_url=:menu_url,
		
		menu_sira=:menu_sira,
		menu_seourl=:menu_seourl,
		menu_durum=:menu_durum
	
		");

	$insert = $ayarekle->execute(array(
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,

		'menu_durum' => $_POST['menu_durum']

	));


	if ($insert) {

		Header("Location:../production/menu.php?durum=ok");
	} else {
		header("Location:../production/menu.php?durum=no");
	}
}


if (isset($_POST['kategoriduzenle'])) {

	$kategori_id = $_POST['kategori_id'];

	$kategori_seourl = seo($_POST['kategori_ad']);


	$ayarkaydet = $db->prepare("UPDATE kategori SET 

			kategori_ad=:kategori_ad,
			
			kategori_sira=:kategori_sira,
			kategori_seourl=:kategori_seourl,
			kategori_durum=:kategori_durum
			where kategori_id={$_POST['kategori_id']}");

	//degisken oldugu zaman suslu parantez içerisinde yazılır  boyle oldugunda =: yapmak yerine =  yapıcaksın yarım saat aradım amk

	$update = $ayarkaydet->execute(array(

		'kategori_ad' => $_POST['kategori_ad'],

		'kategori_sira' => $_POST['kategori_sira'],
		'kategori_seourl' => $kategori_seourl,
		'kategori_durum' => $_POST['kategori_durum']


	));


	if ($update) {
		header("Location:../production/kategori-duzenle.php?$kategori=id&durum=ok");
		exit;
	} else {

		header("Location:../production/kategori-duzenle.php?$kategori=id&durum=no");
		exit;
	}
}

if ($_GET['kategorisil'] == "ok") {


	$sil = $db->prepare("DELETE FROM kategori where kategori_id=:id");
	$kontrol = $sil->execute(array(
		'id' => $_GET['kategori_id']
	));



	if ($kontrol) {
		header("Location:../production/kategori.php?sil=ok");
		exit;
	} else {
		header("Location:../production/kategori.php?sil=no");
		exit;
	}
}


if (isset($_POST['kategorikaydet'])) {


	$kategori_seourl = seo($_POST['kategori_ad']);


	$ayarekle = $db->prepare("INSERT INTO kategori SET
		kategori_ad=:kategori_ad,
		kategori_sira=:kategori_sira,
		kategori_seourl=:kategori_seourl,
		kategori_durum=:kategori_durum
	
		");

	$insert = $ayarekle->execute(array(
		'kategori_ad' => $_POST['kategori_ad'],
		'kategori_sira' => $_POST['kategori_sira'],
		'kategori_seourl' => $kategori_seourl,

		'kategori_durum' => $_POST['kategori_durum']

	));


	if ($insert) {

		Header("Location:../production/kategori.php?durum=ok");
	} else {
		header("Location:../production/kategori.php?durum=no");
	}
}



if (isset($_POST['urunekle'])) {


	$urun_seourl = seo($_POST['urun_ad']);


	$ayarekle = $db->prepare("INSERT INTO urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
		urun_video=:urun_video,
		urun_keyword=:urun_keyword,
		urun_stok=:urun_stok,
		
		urun_seourl=:urun_seourl,
		urun_durum=:urun_durum
	
		");

	$insert = $ayarekle->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
		'urun_video' => $_POST['urun_video'],
		'urun_keyword' => $_POST['urun_keyword'],
		'urun_stok' => $_POST['urun_stok'],

		'urun_seourl' => $urun_seourl,
		'urun_durum' => $_POST['urun_durum']

	));


	if ($insert) {

		Header("Location:../production/urun.php?durum=ok");
	} else {
		header("Location:../production/urun.php?durum=no");
	}
}


if (isset($_POST['urunduzenle'])) {

	$urun_id = $_POST['urun_id'];

	$urun_seourl = seo($_POST['urun_ad']);


	$ayarekle = $db->prepare("UPDATE  urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
		urun_video=:urun_video,
		urun_onecikar=:urun_onecikar,
		urun_keyword=:urun_keyword,
		urun_stok=:urun_stok,
	
		urun_seourl=:urun_seourl,
		urun_durum=:urun_durum 
		where urun_id={$_POST['urun_id']}
	
		");

	$update = $ayarekle->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
		'urun_video' => $_POST['urun_video'],
		'urun_onecikar' => $_POST['urun_onecikar'],
		'urun_keyword' => $_POST['urun_keyword'],
		'urun_stok' => $_POST['urun_stok'],

		'urun_seourl' => $urun_seourl,
		'urun_durum' => $_POST['urun_durum']

	));


	if ($update) {
		header("Location:../production/urun-duzenle.php?durum=ok&$urun_id=$urun_id");
		exit;
	} else {

		header("Location:../production/urun-duzenle.php?durum=no&$urun_id=$urun_id");
		exit;
	}
}



if ($_GET['urun_one'] == 'ok') {


	$save = $db->prepare("UPDATE urun SET 

	urun_onecikar=:onecikar

	where urun_id={$_GET['urun_id']}
	");
	$update = $save->execute(array(
		'onecikar' => $_GET['urun_onecikar']
	));

	if ($update) {
		header("Location:../production/urun.php?durum=ok");
		exit;
	} else {
		header("Location:..production/urun.php?durum=no");
		exit;
	}
}



?>