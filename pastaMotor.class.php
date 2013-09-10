<?

class pastaMotor{

	private $mysqli;
	private $mysqli2;
	private $uyeKimlik;

	function __construct($sqlObj,$sqlObj2){
		$this->mysqli=$sqlObj;
		$this->mysqli2=$sqlObj2;
		if(isset($_SESSION["ePosta"])) $this->uyeKimlik=$_SESSION["ePosta"];
		else $this->uyeKimlik=session_id();
	}

	public function  enCokSatanlar($limit=12){
		$limit=(int)$limit;
		$htmlData="";

		$urunCekSql = $this->mysqli->prepare("SELECT urunID,urunAdi,(CASE urunKategori WHEN 1 THEN 'Cupcake' WHEN 2 THEN 'Kurabiye' WHEN 3 THEN 'Pasta' END) as kategorim,urunResim,urunFiyat,promoFiyat,promoDurum,promoYuzde,urunZaman,(SELECT CONCAT(pk_ureticiler.ureticiLogo,'||',pk_ureticiler.uyeID) FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo  FROM pk_urunler WHERE urunDurum=2 ORDER BY (SELECT COUNT(pk_siparisler.sipID) FROM pk_siparisler INNER JOIN pk_siparisUrunler USING(sipID)  WHERE pk_siparisler.sipDurum=2 AND pk_siparisUrunler.urunID=pk_urunler.urunID) DESC LIMIT $limit");
		$urunCekSql->execute();
		$urunCekSql->bind_result($urunID,$urunAdi,$urunKategori,$urunResim,$urunFiyat,$promoFiyat,$promoDurum,$promoYuzde,$urunZaman,$ureticiIDLogo);
		
		
		
		while($urunCekSql->fetch()){

			$opportunitySign="";
			$promoStyle="none";
			$promoSign="";

			$ureticiIDLogoArr=explode('||',$ureticiIDLogo);
			$ureticiID=$ureticiIDLogoArr[1];
			$ureticiLogo=$ureticiIDLogoArr[0];

			//Ürün son bir haftada mı eklendi
			if($urunZaman>strtotime("last week")){
				$opportunitySign="new";
			}
			
			//üründe promo var ise
			if($promoDurum==2){
				$promoStyle="block";
				$opportunitySign="sale";
				$promoSign="%".$promoYuzde;
			}

			$htmlData.='<li class="'.$opportunitySign.'">
							<div>
								<h2>'.$urunAdi.'<br><span style="font-size: 12px;font-style: italic;">'.$urunKategori.'</span></h2>
							</div>
							<div class="imgbox">
								<a class="image-link" href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								<a href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
								<div class="mark">'.$promoSign.'</div>
								<img style="height:200px; width:225px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="">
								<img src="img/pagecurl.png" alt="" style="display: block;height: 120px;margin-left: 105px;margin-top:-136px;">
								<img src="avatar/'.$ureticiLogo.'" alt="" style="display: block;height: 50px;margin-left: 174px;margin-top:-51px;" onerror="this.src=\'img/default_logo.png\'">
				  
							</div>
							<div class="managbox">
								<div>
									<a title=\'Üretici Sayfası\' href="katalog-urunler-sayfasi.php?u='.$ureticiID.'"></a>
									<a title=\'Sepete Ekle\' href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
									<a title=\'Ürün Detay\' href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								</div>
								<div>
									<div>'.$urunFiyat.' TL</div>
									<div style="display:'.$promoStyle.'">'.$promoFiyat.' TL</div>
								</div>
							</div>
						</li>
						';
		}
		$urunCekSql->close();

		return $htmlData;
	}

	public function  benzerUrunler($urunKategori){
		
		$htmlData="";

		$urunCekSql = $this->mysqli->prepare("SELECT urunID,urunAdi,(CASE urunKategori WHEN 1 THEN 'Cupcake' WHEN 2 THEN 'Kurabiye' WHEN 3 THEN 'Pasta' END) as kategorim,urunResim,urunFiyat,promoFiyat,promoDurum,promoYuzde,urunZaman,(SELECT CONCAT(pk_ureticiler.ureticiLogo,'||',pk_ureticiler.uyeID) FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo FROM pk_urunler WHERE urunKategori=? AND urunDurum=2 ORDER BY RAND() LIMIT 3");
		$urunCekSql->bind_param("i",$urunKategori);
		$urunCekSql->execute();
		$urunCekSql->bind_result($urunID,$urunAdi,$urunKategori,$urunResim,$urunFiyat,$promoFiyat,$promoDurum,$promoYuzde,$urunZaman,$ureticiIDLogo);
		
		
		while($urunCekSql->fetch()){

			$opportunitySign="";
			$promoStyle="none";
			$promoSign="";

			$ureticiIDLogoArr=explode('||',$ureticiIDLogo);
			$ureticiID=$ureticiIDLogoArr[1];
			$ureticiLogo=$ureticiIDLogoArr[0];

			//Ürün son bir haftada mı eklendi
			if($urunZaman>strtotime("last week")){
				$opportunitySign="new";
			}
			
			//üründe promo var ise
			if($promoDurum==2){
				$promoStyle="block";
				$opportunitySign="sale";
				$promoSign="%".$promoYuzde;
			}

                                

			$htmlData.='<li class="'.$opportunitySign.'">
								<div>
                                    <h2>'.$urunAdi.'</h2>
                                </div>
                                <div class="imgbox">
                                    <a class="small-image-link" href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
                                    <a class="small" href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
                                    <div class="mark">'.$promoSign.'</div>
                                    <img style="width:166px;height:146px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="">
                                </div>
                                <div class="managbox">
                                    <div>
                                        <a title=\'Üretici Sayfası\' href="katalog-urunler-sayfasi.php?u='.$ureticiID.'"></a>
                                        <a title=\'Ürün Detay\' href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
                                    </div>
                                    <div>
                                        <div>'.$urunFiyat.' TL</div>
									<div style="display:'.$promoStyle.'">'.$promoFiyat.' TL</div>
                                    </div>
                                </div>
                            </li>
						';
		}
		$urunCekSql->close();

		return $htmlData;
	}


	public function  oneCikanlar(){
		
		$htmlData="";

		$urunCekSql = $this->mysqli->prepare("SELECT urunID,urunAdi,(CASE urunKategori WHEN 1 THEN 'Cupcake' WHEN 2 THEN 'Kurabiye' WHEN 3 THEN 'Pasta' END) as kategorim,urunResim,urunFiyat,promoFiyat,promoDurum,promoYuzde,urunZaman,(SELECT CONCAT(pk_ureticiler.ureticiLogo,'||',pk_ureticiler.uyeID) FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo FROM pk_urunler WHERE urunDurum=2 ORDER BY RAND() LIMIT 12");
		$urunCekSql->execute();
		$urunCekSql->bind_result($urunID,$urunAdi,$urunKategori,$urunResim,$urunFiyat,$promoFiyat,$promoDurum,$promoYuzde,$urunZaman,$ureticiIDLogo);
		
		while($urunCekSql->fetch()){
			$opportunitySign="";
			$promoStyle="none";
			$promoSign="";

			$ureticiIDLogoArr=explode('||',$ureticiIDLogo);
			$ureticiID=$ureticiIDLogoArr[1];
			$ureticiLogo=$ureticiIDLogoArr[0];

			//Ürün son bir haftada mı eklendi
			if($urunZaman>strtotime("last week")){
				$opportunitySign="new";
			}
			
			//üründe promo var ise
			if($promoDurum==2){
				$promoStyle="block";
				$opportunitySign="sale";
				$promoSign="%".$promoYuzde;
			}
			$htmlData.='<li class="'.$opportunitySign.'">
							<div>
								<h2>'.$urunAdi.'<br><span style="font-size: 12px;font-style: italic;">'.$urunKategori.'</span></h2>
							</div>
							<div class="imgbox">
								<a class="image-link" href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								<a href="#" onclick="sepeteEkle('.$urunID.');return false;"></a>
								<div class="mark">'.$promoSign.'</div>
								<img style="height:200px; width:225px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="">
								<img src="img/pagecurl.png" alt="" style=" display: block;height: 120px;margin-left: 105px;margin-top: -136px;">
								<img src="avatar/'.$ureticiLogo.'" alt="" style=" display: block;height: 50px;margin-left: 174px;margin-top: -51px;" onerror="this.src=\'img/default_logo.png\'">
				  
							</div>
							<div class="managbox">
								<div>
									<a title=\'Üretici Sayfası\' href="katalog-urunler-sayfasi.php?u='.$ureticiID.'"></a>
									<a title=\'Sepete Ekle\' href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
									<a title=\'Ürün Detay\' href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								</div>
								<div>
									<div>'.$urunFiyat.' TL</div>
									<div style="display:'.$promoStyle.'">'.$promoFiyat.' TL</div>
								</div>
							</div>
						</li>
						';
		}
		$urunCekSql->close();

		return $htmlData;
	}

	private function temaAdi($temaID){
	
		switch($temaID){
			
			case 1: $temaAdi="Anneler Günü"; break;
			case 2: $temaAdi="Asker Uğurlama & Teskere"; break;
			case 3: $temaAdi="Babalar Günü"; break;
			case 4: $temaAdi="Baby Shower"; break;
			case 5: $temaAdi="Bebek Doğum"; break;
			case 6: $temaAdi="Bekarlığa Veda"; break;
			case 7: $temaAdi="Cadılar Bayramı"; break;
			case 8: $temaAdi="Çocuk & Bebek Doğum Günü"; break;
			case 9: $temaAdi="Dini Bayram"; break;
			case 10: $temaAdi="Diş Buğdayı"; break;
			case 11: $temaAdi="Düğün"; break;
			case 12: $temaAdi="Geçmiş Olsun"; break;
			case 13: $temaAdi="Kına Gecesi"; break;
			case 14: $temaAdi="Kişiye Özel"; break;
			case 15: $temaAdi="Kurumsal"; break;
			case 16: $temaAdi="Mezuniyet"; break;
			case 17: $temaAdi="Milli Bayram"; break;
			case 18: $temaAdi="Nişan"; break;
			case 19: $temaAdi="Öğretmenler Günü"; break;
			case 20: $temaAdi="Özür Dileme"; break;
			case 21: $temaAdi="Paskalya"; break;
			case 22: $temaAdi="Sevgililer Günü"; break;
			case 23: $temaAdi="Sevgiliye"; break;
			case 24: $temaAdi="Söz"; break;
			case 25: $temaAdi="Tebrik"; break;
			case 26: $temaAdi="Terfi"; break;
			case 27: $temaAdi="Teşekkür"; break;
			case 28: $temaAdi="Yetişkin Doğum Günü"; break;
			case 29: $temaAdi="Yılbaşı & Noel"; break;
			case 30: $temaAdi="Yıl Dönümü"; break;
			default: $temaAdi="Diğer";
			
		}
		return $temaAdi;
	}

	private function altKatAdi($altKatID)
	{
	
		switch($altKatID){
			case 101: $altKatAdi="Üzeri Kremalı Süslü"; break;
			case 102: $altKatAdi="Şeker Hamuru ile Süslü"; break;
			case 103: $altKatAdi="Cupcake&Pasta Kulesi"; break;
			case 104: $altKatAdi="Cake Pops"; break;
			case 201: $altKatAdi="Çubuklu"; break;
			case 202: $altKatAdi="Standart"; break;
			case 203: $altKatAdi="Logo Baskılı"; break;
			case 301: $altKatAdi="Üç Boyutlu"; break;
			case 302: $altKatAdi="İki Boyutlu"; break;
			case 303: $altKatAdi="Strafor (Maket) Pastalar"; break;
			case 304: $altKatAdi="CheeseCake"; break;
			case 305: $altKatAdi="Tiramisu"; break;
			default: $altKatAdi="Diğer";
		}
		
		return $altKatAdi;

	}

	public function sepeteEkle($urunID){
		$suan=time();
		$birayonce=time()-(60*60*24*30);
		

		//Var ise güncelleniyor
		$sepetSql = $this->mysqli->prepare("UPDATE pk_sepet SET adet=adet+1 WHERE urunID=? AND uyeID=? AND zaman>? LIMIT 1");
		$sepetSql->bind_param("sss",$urunID,$this->uyeKimlik,$birayonce);
		$sepetSql->execute();
		$affected=$sepetSql->affected_rows;
		$sepetSql->close();
		
		//Yok ise yeni ürün olarak ekleniyor
		if($affected==0){
		
			$sepetSql = $this->mysqli->prepare("INSERT INTO pk_sepet (urunID,adet,zaman,uyeID) VALUES(?,1,?,?) ");
			$sepetSql->bind_param("sss",$urunID,$suan,$this->uyeKimlik);
			$sepetSql->execute();
			$sepetSql->close();
		}

		
	}

	public function sepetiGuncelle($sepetID,$urunAdet){
		$suan=time();
		$birayonce=time()-(60*60*24*30);
		$toplamFiyat=0;
		$urunToplamFiyat=0;

		if($urunAdet==0){
			//0 adetler siliniyor
			$sepetSql = $this->mysqli->prepare("DELETE FROM pk_sepet  WHERE sepetID=? AND (uyeID=? OR uyeID='".session_id()."') LIMIT 1");
			$sepetSql->bind_param("ss",$sepetID,$this->uyeKimlik);
			$sepetSql->execute();
			$sepetSql->close();
		}
		else{

			//Var ise güncelleniyor
			$sepetSql = $this->mysqli->prepare("UPDATE pk_sepet SET adet=?,zaman=? WHERE sepetID=? AND (uyeID=? OR uyeID='".session_id()."') LIMIT 1");
			$sepetSql->bind_param("ssss",$urunAdet,$suan,$sepetID,$this->uyeKimlik);
			$sepetSql->execute();
			$sepetSql->close();
		}

		//Return verileri hazırlanıyor
		$sepetSql = $this->mysqli->prepare("SELECT pk_sepet.sepetID,pk_sepet.adet,(CASE WHEN pk_urunler.promoDurum=2 THEN pk_urunler.promoFiyat ELSE pk_urunler.urunFiyat END) AS urunFiyatim FROM pk_sepet LEFT JOIN pk_urunler USING(urunID)  WHERE pk_urunler.urunDurum=2 AND (pk_sepet.uyeID=? OR pk_sepet.uyeID='".session_id()."') AND pk_sepet.zaman>?");
		$sepetSql->bind_param("ss",$this->uyeKimlik,$birayonce);
		$sepetSql->execute();
		$sepetSql->bind_result($sepetIDChk,$urunAdet,$urunFiyat);
		while($sepetSql->fetch()){
			if($sepetID==$sepetIDChk) $urunToplamFiyat=$urunAdet*$urunFiyat;
			$toplamFiyat+=$urunAdet*$urunFiyat;
		}
		$sepetSql->close();
		
		$returnStr=$toplamFiyat."||".$urunToplamFiyat;
		return $returnStr;

	}

	public function sepettenCikar($sepetID){

		$sepetSql = $this->mysqli->prepare("DELETE FROM pk_sepet WHERE sepetID=? AND (uyeID=? OR uyeID='".session_id()."') LIMIT 1");
		$sepetSql->bind_param("ss",$sepetID,$this->uyeKimlik);
		$sepetSql->execute();
		$sepetSql->close();
	}


	public function sepetHTML(){

		$sepetHTML="";
		$birayonce=time()-(60*60*24*30);
		$urunToplamFiyat=0;
		$farkliUrunSayisi=0;
		
		
		
		$sepetSql = $this->mysqli->prepare("SELECT pk_sepet.sepetID,pk_urunler.urunResim,pk_urunler.urunAdi,pk_sepet.adet,(CASE WHEN pk_urunler.promoDurum=2 THEN pk_urunler.promoFiyat ELSE pk_urunler.urunFiyat END) AS urunFiyatim FROM pk_sepet LEFT JOIN pk_urunler USING(urunID)  WHERE pk_urunler.urunDurum=2 AND (pk_sepet.uyeID=? OR pk_sepet.uyeID='".session_id()."') AND pk_sepet.zaman>?");
		$sepetSql->bind_param("ss",$this->uyeKimlik,$birayonce);
		$sepetSql->execute();
		$sepetSql->bind_result($sepetID,$urunResim,$urunAdi,$urunAdet,$urunFiyat);
		while($sepetSql->fetch()){
			
			$sepetHTML.='<li>
							<a href="#"><img class="cart-content-img" style="height:50px;width:50px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="'.$urunAdi.'"></a>
							<span>'.$urunAdi.'<br><em>'.$urunAdet.' x '.$urunFiyat.' = '.($urunAdet*$urunFiyat).' TL</em></span>
							<a class="delete-cart-item ir" href="#" onclick="sepettenCikar('.$sepetID.');return false;">Almaktan Vazgeç</a>
						</li>';
			$urunToplamFiyat+=($urunFiyat*$urunAdet);
			$farkliUrunSayisi++;
			
		}
		
		$sepetSql->close();

		$sepetHTMLBas='<div class="ribbon-price" style="background-color:c8140a !important">'.$urunToplamFiyat.' TL</div>
                    <div class="cart-content hiden-cart-content">
                        <ul>';
                            
		$sepetHTMLSon='</ul>
                        <a href="alisveris-sepeti.php" class="view-shopping-cart">Alışveriş Sepeti</a>
                    </div>
                    <div class="ribbon hidden-ribbon"><strong>('.$farkliUrunSayisi.')</strong></div>';
		$sepetHTML=$sepetHTMLBas.$sepetHTML.$sepetHTMLSon;
		return $sepetHTML;
                        
	}

	public function sepetListesi(){
		$sepetHTML="";
		$birayonce=time()-(60*60*24*30);
		$urunToplamFiyat=0;
		$maxTeadrik=0;
		
		
		
		$sepetSql = $this->mysqli->prepare("SELECT pk_urunler.urunID,pk_sepet.sepetID,pk_urunler.urunResim,pk_urunler.urunAdi,pk_sepet.adet,(CASE WHEN pk_urunler.promoDurum=2 THEN pk_urunler.promoFiyat ELSE pk_urunler.urunFiyat END) AS urunFiyatim,pk_urunler.urunTedarik,pk_urunler.urunAciklama FROM pk_sepet LEFT JOIN pk_urunler USING(urunID)  WHERE pk_urunler.urunDurum=2 AND (pk_sepet.uyeID=? OR pk_sepet.uyeID='".session_id()."') AND pk_sepet.zaman>?");
		$sepetSql->bind_param("ss",$this->uyeKimlik,$birayonce);
		$sepetSql->execute();
		$sepetSql->bind_result($urunID,$sepetID,$urunResim,$urunAdi,$urunAdet,$urunFiyat,$urunTedarik,$urunAciklama);
		while($sepetSql->fetch()){
			
			$sepetHTML.='<tr><td>
                                            <a href="katalog-urun-goruntule.php?i='.$urunID.'"><img style="width:97px;height:86px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt=""></a>
                                        </td>
                                        <td>
                                            '.$urunAdi.'
                                        </td>
                                        <td>
                                           '.strip_tags($urunAciklama).'
                                        </td>
                                        <td>
                                            <span id="urunFiyat'.$sepetID.'" class="price">'.$urunFiyat.' TL</span>
                                        </td>
                                        <td>
                                            <input id="adet'.$sepetID.'" type="text" size="5" value="'.$urunAdet.'" onblur="urunSepetiGuncelle('.$sepetID.')">
                                        </td>
                                        <td>
                                            <span id="toplamUrunFiyat'.$sepetID.'" class="price">'.($urunFiyat*$urunAdet).' TL</span>
                                        </td>
                                        <td>
                                            <a href="sepettenCikar.php?id='.$sepetID.'" class="delete">Sil</a>
                                        </td>
                                        
                                    </tr>';
			$urunToplamFiyat+=($urunFiyat*$urunAdet);
			if($urunTedarik>$maxTedarik) $maxTedarik=$urunTedarik;
			
		}
		
		$sepetSql->close();
	
		$sepetListeArr[]= $sepetHTML;
		$sepetListeArr[]= $urunToplamFiyat;
		$sepetListeArr[]= $maxTedarik;
		return $sepetListeArr;
                        
	}

	public function urunDetay($urunID){
			
		$urunDetaySql = $this->mysqli->prepare("SELECT (CASE WHEN pk_urunler.urunKategori=1 THEN 'Cupcake' WHEN pk_urunler.urunKategori=2 THEN 'Kurabiye' ELSE 'Pasta' END) as kategorim,
		pk_urunler.urunAdi,
		pk_urunler.urunTema1,pk_urunler.urunTema2,pk_urunler.urunTema3,
		CASE WHEN pk_urunler.urunResim='' THEN 'default.png' ELSE pk_urunler.urunResim END,
		pk_urunler.urunAciklama,
		pk_urunler.urunID,
		pk_ureticiler.adSoyad,
		pk_urunler.urunTedarik,
		pk_urunler.promoDurum,
		pk_urunler.promoFiyat,
		pk_urunler.urunFiyat,
		pk_urunler.ureticiNotu,
		pk_urunler.urunKategori,
		(SELECT ROUND(AVG(anketDeger),0) FROM pk_anketler WHERE urunID=? AND anketOnay=2) as anketDeger,
		(SELECT pk_ureticiler.uyeID FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo
		FROM pk_urunler LEFT JOIN pk_ureticiler ON pk_urunler.ureticiID=pk_ureticiler.uyeID WHERE pk_urunler.urunDurum=2 AND pk_urunler.urunID=?");
		$urunDetaySql->bind_param("ss",$urunID,$urunID);
		$urunDetaySql->execute();
		$urunDetaySql->bind_result($urunKategori,$urunAdi,$urunTema1,$urunTema2,$urunTema3,$urunResim,$urunAciklama,$urunID,$adSoyad,$urunTedarik,$promoDurum,$promoFiyat,$urunFiyat,$ureticiNotu,$urunKategoriSql,$anketDeger,$ureticiID);
		$urunDetaySql->fetch();
		$urunDetaySql->close();

		if($anketDeger==5) $star="1";
		else if($anketDeger==4) $star="-17";
		else if($anketDeger==3) $star="-35";
		else if($anketDeger==2) $star="-53";
		else if($anketDeger==1) $star="-71";
		else $star="-35";
		$anketDegerStyle=' margin-left:15px !important;background: url(\'img/stars.png\') '.$star.'px 0  !important;';

		$returnArr=array($urunKategori,$urunAdi,$this->temaAdi($urunTema1),$this->temaAdi($urunTema2),$this->temaAdi($urunTema3),$urunResim,$urunAciklama,$urunID,$adSoyad,$urunTedarik,$promoDurum,$promoFiyat,$urunFiyat,$ureticiNotu,$urunKategoriSql,$anketDegerStyle,$ureticiID);
		return $returnArr;
	}

	public function sizinIcinSectik(){
	
		$urunDetaySql = $this->mysqli->prepare("SELECT pk_urunler.urunResim,pk_urunler.urunAdi,(CASE WHEN pk_urunler.promoDurum=2 THEN pk_urunler.promoFiyat ELSE pk_urunler.urunFiyat END) AS urunFiyatim,pk_urunler.urunID
		FROM pk_urunler WHERE pk_urunler.urunDurum=2 ORDER BY RAND() LIMIT 1");
		$urunDetaySql->execute();
		$urunDetaySql->bind_result($urunResim,$urunAdi,$urunFiyat,$urunID);
		$urunDetaySql->fetch();
		$urunDetaySql->close();
		$returnArr=array($urunResim,$urunAdi,$urunFiyat,$urunID);
		return $returnArr;
	
	}

	public function enCokSatanlarTop5(){

		$enCokSatanlarHTML="";
		$urunDetaySql = $this->mysqli->prepare("SELECT pk_urunler.urunResim,pk_urunler.urunAdi,(CASE WHEN pk_urunler.promoDurum=2 THEN pk_urunler.promoFiyat ELSE pk_urunler.urunFiyat END) AS urunFiyatim,pk_urunler.urunID,pk_urunler.urunTema1
		FROM pk_urunler WHERE pk_urunler.urunDurum=2 ORDER BY (SELECT COUNT(pk_siparisler.sipID) FROM pk_siparisler INNER JOIN pk_siparisUrunler USING(sipID) WHERE pk_siparisler.sipDurum=2 AND pk_siparisUrunler.urunID=pk_urunler.urunID) DESC LIMIT 5");
		$urunDetaySql->execute();
		$urunDetaySql->bind_result($urunResim,$urunAdi,$urunFiyat,$urunID,$temaID);
		while($urunDetaySql->fetch()){
		
			$enCokSatanlarHTML.='<li>
                                            <!--Block SPECIALS images -->
                                            <a href="katalog-urun-goruntule.php?i='.$urunID.'"><img src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt=""style="width:95px"></a>
                                            <div class="sk-product-descr">
                                                <a href="katalog-urun-goruntule.php?i='.$urunID.'">'.$urunAdi.'<br><span style="font-size: 12px;font-style: italic;">'.$this->temaAdi($temaID).'</span></a>
                                            </div>
                                            <div class="sk-product-price">
                                                <a href="katalog-urun-goruntule.php?i='.$urunID.'">'.$urunFiyat.' TL</a>
                                            </div>
                                        </li>';
		}
		$urunDetaySql->close();
		return $enCokSatanlarHTML;
	
	}

	public function temaListeHTML(){
	
		$temaListeHTML='<li><a href="katalog-urunler-sayfasi.php?k=1">Anneler Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=2">Asker Uğurlama</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=3">Babalar Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=4">Baby Shower</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=5">Bebek Doğum</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=6">Bekarlığa Veda</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=7">Cadılar Bayramı</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=8">Çocuk Doğum Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=9">Dini Bayram</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=10">Diş Buğdayı</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=11">Düğün</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=12">Geçmiş Olsun</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=13">Kına Gecesi</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=14">Kişiye Özel</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=15">Kurumsal</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=16">Mezuniyet</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=17">Milli Bayram</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=18">Nişan</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=19">Öğretmenler Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=20">Özür Dileme</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=21">Paskalya</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=22">Sevgililer Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=23">Sevgiliye</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=24">Söz</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=25">Tebrik</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=26">Terfi</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=27">Teşekkür</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=28">Yetişkin Doğum Günü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=29">Yılbaşı & Noel</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=30">Yıldönümü</a></li>
                            <li><a href="katalog-urunler-sayfasi.php?k=31">Diğer</a></li> ';
		return $temaListeHTML;
	}

	private function urunArama($ureticiSql,$temaSql,$altKatSql,$aramaSql,$siralama,$baslangic,$limit,$sayfa,$urlSayfalamaAddy){
		$urunAramaHTML="";

		$urunCekSql = $this->mysqli->prepare("SELECT  urunID
		FROM pk_urunler WHERE $ureticiSql $temaSql $altKatSql pk_urunler.urunAdi LIKE ? AND pk_urunler.urunDurum=2");
		$urunCekSql->bind_param("s",$aramaSql);
		$urunCekSql->execute();
		$urunCekSql->store_result();
		$toplamUrun=$urunCekSql->num_rows;
		$urunCekSql->close();

		$urunCekSql = $this->mysqli->prepare("SELECT  urunID,urunAdi,(CASE urunKategori WHEN 1 THEN 'Cupcake' WHEN 2 THEN 'Kurabiye' WHEN 3 THEN 'Pasta' END) as kategorim,urunResim,urunFiyat,promoFiyat,promoDurum,promoYuzde,urunZaman,(SELECT CONCAT(pk_ureticiler.ureticiLogo,'||',pk_ureticiler.uyeID) FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo	FROM pk_urunler WHERE $ureticiSql $temaSql $altKatSql pk_urunler.urunAdi LIKE ? AND pk_urunler.urunDurum=2 ORDER BY ? DESC LIMIT ?,?");
		$urunCekSql->bind_param("ssss",$aramaSql,$siralama,$baslangic,$limit);
		$urunCekSql->execute();
		$urunCekSql->bind_result($urunID,$urunAdi,$urunKategori,$urunResim,$urunFiyat,$promoFiyat,$promoDurum,$promoYuzde,$urunZaman,$ureticiIDLogo);
		
		$i=3;
		while($urunCekSql->fetch()){
			
			$opportunitySign="";
			$promoStyle="none";
			$promoSign="";

			$ureticiIDLogoArr=explode('||',$ureticiIDLogo);
			$ureticiID=$ureticiIDLogoArr[1];
			$ureticiLogo=$ureticiIDLogoArr[0];

			//Ürün son bir haftada mı eklendi
			if($urunZaman>strtotime("last week")){
				$opportunitySign="new";
			}
			
			//üründe promo var ise
			if($promoDurum==2){
				$promoStyle="block";
				$opportunitySign="sale";
				$promoSign="%".$promoYuzde;
			}
			if($i%3==0) $urunAramaHTML.='<div class="form-row">
                                <ul class="content-ul">';

			$urunAramaHTML.='<li class="'.$opportunitySign.'">
							<div>
								<h2>'.$urunAdi.'<br><span style="font-size: 12px;font-style: italic;">'.$urunKategori.'</span></h2>
							</div>
							<div class="imgbox">
								<a class="image-link" href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								<a href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
								<div class="mark">'.$promoSign.'</div>
								<img style="height:200px; width:225px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="">
								<img src="img/pagecurl.png" alt="" style=" display: block;height: 120px;margin-left: 105px;margin-top: -124px;">
								<img src="avatar/'.$ureticiLogo.'" alt="" style=" display: block;height: 50px;margin-left: 174px;margin-top: -51px;"	onerror="this.src=\'img/default_logo.png\'">
				  
							</div>
							<div class="managbox">
								<div>
									<a title=\'Üretici Sayfası\' href="katalog-urunler-sayfasi.php?u='.$ureticiID.'"></a>
									<a title=\'Sepete Ekle\' href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
									<a title=\'Ürün Detay\' href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
								</div>
								<div>
									<div>'.$urunFiyat.' TL</div>
									<div style="display:'.$promoStyle.'">'.$promoFiyat.' TL</div>
								</div>
							</div>
						</li>
						';
			$i++;
			
			if($i%3==0 && ($i-2)==$toplamUrun) $urunAramaHTML.='<div class="form-row">
                                <ul class="content-ul">';
			
		}
		$urunAramaHTML.='</div>
                    </div><!--/..entry-content-->
                    <div class="pr-pager">
                        <p>Toplam '.$toplamUrun.' ürün bulundu</p>
                        <div class="pr-elm">
                         ';
		$urunAramaHTML.=$this->sayfalama($toplamUrun,$limit,$sayfa,$urlSayfalamaAddy);
		$urunCekSql->close();

		return $urunAramaHTML;
	
	}

	private function urunAramaAlternate($ureticiSql,$temaSql,$altKatSql,$aramaSql,$siralama,$baslangic,$limit,$sayfa,$urlSayfalamaAddy){
		$urunAramaHTML='<ul class="content-ul-list">';
		
		$urunCekSql = $this->mysqli->prepare("SELECT  urunID
		FROM pk_urunler WHERE $ureticiSql $temaSql $altKatSql pk_urunler.urunAdi LIKE ? AND pk_urunler.urunDurum=2");
		$urunCekSql->bind_param("s",$aramaSql);
		$urunCekSql->execute();
		$urunCekSql->store_result();
		$toplamUrun=$urunCekSql->num_rows;
		$urunCekSql->close();

		$urunCekSql = $this->mysqli->prepare("SELECT  urunID,urunAdi,(CASE urunKategori WHEN 1 THEN 'Cupcake' WHEN 2 THEN 'Kurabiye' WHEN 3 THEN 'Pasta' END) as kategorim,urunResim,urunFiyat,promoFiyat,promoDurum,promoYuzde,urunZaman,urunAciklama,(SELECT CONCAT(pk_ureticiler.ureticiLogo,'||',pk_ureticiler.uyeID) FROM pk_ureticiler WHERE pk_ureticiler.uyeID=pk_urunler.ureticiID LIMIT 1) AS ureticiIDLogo,(SELECT ROUND(AVG(anketDeger),0) FROM pk_anketler WHERE urunID=pk_urunler.urunID AND anketOnay=2) as anketDeger
		FROM pk_urunler WHERE $ureticiSql $temaSql $altKatSql pk_urunler.urunAdi LIKE ? AND pk_urunler.urunDurum=2 ORDER BY ? DESC LIMIT ?,?");
		$urunCekSql->bind_param("ssss",$aramaSql,$siralama,$baslangic,$limit);
		$urunCekSql->execute();
		$urunCekSql->bind_result($urunID,$urunAdi,$urunKategori,$urunResim,$urunFiyat,$promoFiyat,$promoDurum,$promoYuzde,$urunZaman,$urunAciklama,$ureticiIDLogo,$anketDeger);
		
		while($urunCekSql->fetch()){
			if($anketDeger==5) $star="1";
			else if($anketDeger==4) $star="-17";
			else if($anketDeger==3) $star="-35";
			else if($anketDeger==2) $star="-53";
			else if($anketDeger==1) $star="-71";
			else $star="-35";

			$ureticiIDLogoArr=explode('||',$ureticiIDLogo);
			$ureticiID=$ureticiIDLogoArr[1];
			$ureticiLogo=$ureticiIDLogoArr[0];

			$anketDegerStyle=' margin-left:15px !important;background: url(\'img/stars.png\') '.$star.'px 0  !important;';

			$opportunitySign="";
			$promoStyle="none";
			$promoSign="";

			//Ürün son bir haftada mı eklendi
			if($urunZaman>strtotime("last week")){
				$opportunitySign="new";
			}
			
			//üründe promo var ise
			if($promoDurum==2){
				$promoStyle="block";
				$opportunitySign="sale";
				$promoSign="%".$promoYuzde;
			}
			

             $urunAramaHTML.=' <li class="'.$opportunitySign.'"><div></div>
                            <div class="img-list">
                                <div class="mark"></div>
                                <a href="katalog-urun-goruntule.php?i='.$urunID.'"><img style="width:226px;height:200px" src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  alt="">
                                            <img src="img/pagecurl.png" alt="" style=" display: block;height: 120px;margin-left: 105px;margin-top: -124px;">
                 							<img src="avatar/'.$ureticiLogo.'" alt="" style=" display: block;height: 50px;margin-left: 174px;margin-top: -51px;" onerror="this.src=\'img/default_logo.png\'"> 
                        </a>
                            </div>
                            <div class="content-list">
                                <h4>'.$urunAdi.'<br><span style="font-size: 12px;font-style: italic;">'.$urunKategori.'</span></h4>
                                <div class="estars list-stars" style="'.$anketDegerStyle.'"></div>
                                <p>'.$urunAciklama.'</p>
                            </div>
                            <div class="list-block-3">
                                <div class="list-price">
                                    <div>'.$urunFiyat.' TL</div>
									<div style="display:'.$promoStyle.'">'.$promoFiyat.' TL</div>
                                </div>
                                <a class="list-add" href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
                                <div class="managbox managbox-list"> 
                                    <div>
                                        <a title=\'Üretici Sayfası\' href="katalog-urunler-sayfasi.php?u='.$ureticiID.'"></a>
                                        <a title=\'Sepete Ekle\' href="#" onclick="sepeteEkle('.$urunID.'); return false;"></a>
                                        <a title=\'Ürün Detay\' href="katalog-urun-goruntule.php?i='.$urunID.'"></a>
                                    </div>
                                </div>
                            </div>
                        </li>';
						

		}
		$urunAramaHTML.='</ul>
                    <div class="pr-pager">
                        <p>Toplam '.$toplamUrun.' ürün bulundu</p>
                        <div class="pr-elm">
                         ';
		$urunAramaHTML.=$this->sayfalama($toplamUrun,$limit,$sayfa,$urlSayfalamaAddy);
		$urunCekSql->close();

		return $urunAramaHTML;
	
	}

	public function aramaSanitizasyon($GET,$tip){
		
			$tema=(int)$GET["k"];
			$kategori=(int)$GET["kk"];
			$arama=$this->mysqli->real_escape_string($GET["a"]);
			$siralama=(int)$GET["s"];
			$sayfa=(int)$GET["b"];
			$limit=(int)$GET["l"];
			$uretici=(int)$GET["u"];
			

			if($tema<1 || $tema>31) $tema=0;
			
			if($kategori!=1 && $kategori!=2 && $kategori!=3) $kategori=0;
			
			if($siralama==1) $siralama="pk_urunler.urunSatis";
			else if($siralama==2) $siralama="pk_urunler.promoYuzde";
			else $siralama="pk_urunler.urunZaman";
			
			if($sayfa<=0) $sayfa=1;
			
			if($limit!=12 && $limit!=24 && $limit!=48) $limit=12;

			if($uretici>0) $ureticiSql=" ureticiID=$uretici AND ";
			else $ureticiSql="";
			
			$baslangic=($sayfa-1)*$limit;
			$altKategoriler=explode("-",$altKategori);
			$altKatSql="";
			if($kategori==1){
				if(((int)$_GET["101"]==1 && (int)$_GET["102"]==1 && (int)$_GET["103"]==1 && (int)$_GET["104"]==1) || ((int)$_GET["101"]==0 && (int)$_GET["102"]==0 && (int)$_GET["103"]==0 && (int)$_GET["104"]==0)) $altKatSql.="urunKategori=".$kategori.' AND ';
				else $altKatSql.='(altKat101='.(((int)$_GET["101"]==0) ? -1 : 1).' OR altKat102='.(((int)$_GET["102"]==0) ? -1 : 1).' OR altKat103='.(((int)$_GET["103"]==0) ? -1 : 1).' OR altKat104='.(((int)$_GET["104"]==0) ? -1 : 1).') AND ';
			}
			else if($kategori==2){
				if(((int)$_GET["201"]==1 && (int)$_GET["202"]==1 && (int)$_GET["203"]==1) || ((int)$_GET["201"]==0 && (int)$_GET["202"]==0 && (int)$_GET["203"]==0)) $altKatSql.="urunKategori=".$kategori.' AND ';
				else $altKatSql.='(altKat201='.(((int)$_GET["201"]==0) ? -1 : 1).' OR altKat202='.(((int)$_GET["202"]==0) ? -1 : 1).' OR altKat203='.(((int)$_GET["203"]==0) ? -1 : 1).') AND ';
			}
			else if($kategori==3){
				if(((int)$_GET["301"]==1 && (int)$_GET["302"]==1 && (int)$_GET["303"]==1 && (int)$_GET["304"]==1  && (int)$_GET["305"]==1) || ((int)$_GET["301"]==0 && (int)$_GET["302"]==0 && (int)$_GET["303"]==0 && (int)$_GET["304"]==0  && (int)$_GET["305"]==0)) $altKatSql.="urunKategori=".$kategori.' AND ';
				else $altKatSql.='(altKat301='.(((int)$_GET["301"]==0) ? -1 : 1).' OR altKat302='.(((int)$_GET["302"]==0) ? -1 : 1).' OR altKat303='.(((int)$_GET["303"]==0) ? -1 : 1).' OR altKat304='.(((int)$_GET["304"]==0) ? -1 : 1).' OR altKat305='.(((int)$_GET["305"]==0) ? -1 : 1).') AND ';
			}
			
			$temaSql="";
			if($tema>0){
				$temaSql.="(urunTema1=$tema OR urunTema2=$tema OR urunTema3=$tema) AND ";
			}
			$aramaSql="%$arama%";
			
			$urlSayfalamaAddy="";
			foreach($GET as $key=>$value){
				if($key!="b") $urlSayfalamaAddy.="&$key=$value";
			}
			

		if($tip==1) $urunAramaHTML=$this->urunArama($ureticiSql,$temaSql,$altKatSql,$aramaSql,$siralama,$baslangic,$limit,$sayfa,$urlSayfalamaAddy);
		else if($tip==2) $urunAramaHTML=$this->urunAramaAlternate($ureticiSql,$temaSql,$altKatSql,$aramaSql,$siralama,$baslangic,$limit,$sayfa,$urlSayfalamaAddy);
		return $urunAramaHTML;
	}


	
	private function sayfalama($sayi, $limit, $sayfa,$urlSayfalamaAddy){

			$toplam_sayfa=ceil($sayi/$limit);
		
			$sayfalamalarim="";
			
			if($sayfa!=1){
				$sayfa_once=$sayfa-1;
				$sayfalamalarim.="<a  class='pg-prev pg-end'  href=\"?b=$sayfa_once$urlSayfalamaAddy\"></a>";
			}
			if($sayfa<=5)
			{
				for($i=1;$i<=10;$i++){
					
					if($i==$sayfa) $sayfalamalarim.="<a  class='pg-list  pg-active' >".$i."</a>";
					else $sayfalamalarim.="<a  class='pg-list' href=\"?b=$i$urlSayfalamaAddy\">".$i."</a>";
					
					if($i==$toplam_sayfa) break;
				}
			}
			elseif($toplam_sayfa-$sayfa<5)
			{
				for($i=$toplam_sayfa-10;$i<=$toplam_sayfa;$i++){
				
					if($i==$sayfa) $sayfalamalarim.="<a  class='pg-list  pg-active'>".$i."</a>";
					else $sayfalamalarim.="<a   class='pg-list' href=\"?b=$i$urlSayfalamaAddy\">".$i."</a>";
				}
			
			}
			else
			{
				for($i=$sayfa-4;$i<=$sayfa+5;$i++){
				
					if($i==$sayfa) $sayfalamalarim.="<a  class='pg-list  pg-active'>".$i."</a>";
					else $sayfalamalarim.="<a  class='pg-list' href=\"?b=$i$urlSayfalamaAddy\">".$i."</a>";
				}
			
			}
			
				if($sayfa!=$toplam_sayfa){
				$sayfa_sonra=$sayfa+1;
				$sayfalamalarim.="<a class='pg-next' href=\"?b=$sayfa_sonra$urlSayfalamaAddy\"></a>";
			}
			
			if($sayi == 0) $sayfalamalarim = 1;
			
		return $sayfalamalarim;
	}
	
	public function oncekiSiparislerim($baslangic){
		
		$baslangic=($baslangic-1)*20;
		
		$oncekiSiparislerimHTML="";
		$siparisCekSql = $this->mysqli->prepare("SELECT sipID FROM pk_siparisler WHERE sipUyeID=?");
		$siparisCekSql->bind_param("s",$this->uyeKimlik);
		$siparisCekSql->execute();
		$siparisCekSql->store_result();
		$toplamSiparis=$siparisCekSql->num_rows;
		$siparisCekSql->close();

	
		$siparisCekSql = $this->mysqli->prepare("SELECT  sipID,sipZaman,sipTarih,(CASE sipTeslimat WHEN 1 THEN 'Kendim Alacağım' ELSE 'Pasta Kapınızda' END) AS teslimat,(CASE sipDurum WHEN 1 THEN 'Beklemede' WHEN 2 THEN 'Onaylandı' ELSE 'İptal Edildi' END) as durum,(SELECT SUM(urunFiyat*urunAdet) FROM pk_siparisUrunler WHERE pk_siparisUrunler.sipID=pk_siparisler.sipID GROUP BY sipID) as toplamFiyat,(CASE sipMod WHEN 1 THEN 'Kredi Kartı' ELSE 'Havale' END) as sipMod FROM pk_siparisler WHERE sipUyeID=? ORDER BY sipZaman DESC LIMIT ?,20");
		$siparisCekSql->bind_param("si",$this->uyeKimlik,$baslangic);
		$siparisCekSql->execute();
		$siparisCekSql->bind_result($sipID,$sipZaman,$sipTarih,$teslimat,$durum,$toplamFiyat,$sipMod);
		
		
		while($siparisCekSql->fetch()){
				$oncekiSiparislerimHTML.='<tr>
                                <td>'.$sipID.'</td>
                                <td>'.date("d-m-Y H:i",$sipZaman).'</td>
                                <td>'.date("d-m-Y H:i",$sipTarih).'<br/>('.$teslimat.')</td>
                                <td>'.$toplamFiyat.' TL</td>
                                <td>'.$durum.'<br/>('.$sipMod.')</td>
								<td><a href="eski-siparisler-detay.php?sID='.$sipID.'"><img height="30p" src="img/magnifier.png"/></a></td>
                                
                            </tr>';
		}

		$siparisCekSql->close();

		$oncekiSiparislerim[]=$oncekiSiparislerimHTML;
		$oncekiSiparislerim[]=$this->sayfalama($toplamSiparis,"20", $baslangic,"");
	
		return $oncekiSiparislerim;
				
	}

	public function oncekiSiparisDetay($sipID){
			
		$oncekiSiparisDetayHTML="";
		$siparisCekSql = $this->mysqli->prepare("SELECT pk_urunler.urunID,pk_urunler.urunAdi,pk_urunler.urunResim,pk_siparisUrunler.urunAdet,pk_siparisUrunler.urunFiyat FROM pk_urunler INNER JOIN pk_siparisUrunler USING(urunID) INNER JOIN pk_siparisler USING(sipID) WHERE pk_siparisler.sipUyeID=? AND pk_siparisUrunler.sipID=?");
		$siparisCekSql->bind_param("si",$this->uyeKimlik,$sipID);
		$siparisCekSql->execute();
		$siparisCekSql->bind_result($urunID,$urunAdi,$urunResim,$urunAdet,$urunFiyat);
		
		
		while($siparisCekSql->fetch()){
				$oncekiSiparisDetayHTML.='<tr>

                                <td><img src="'.urunResimFolder.$urunResim.'" onerror="this.src=\'img/default_product.png\'"  width="68" alt="'.$urunAdi.'"></td>
                                <td>'.$urunAdi.'</td>
                                <td>'.$urunAdet.'</td>
                                <td>'.$urunFiyat.' TL</td>
								<td><a href="katalog-urun-yorum-sayfasi.php?i='.$urunID.'">Siparişi Değerlendir</a></td>
                            </tr>';
		}

		$siparisCekSql->close();
						

		return $oncekiSiparisDetayHTML;
	}

	public function yorumlarHTML($urunID){
		
		$yorumlarHTML="";

		$yorumlarSql = $this->mysqli->prepare("SELECT anketYorum,anketDeger,anketZaman,(SELECT adSoyad FROM pk_uyeler WHERE ePosta=pk_anketler.yorumcuID LIMIT 1) FROM pk_anketler WHERE urunID=? AND anketOnay=2 ORDER BY anketZaman DESC");
		$yorumlarSql->bind_param("s",$urunID);
		$yorumlarSql->execute();
		$yorumlarSql->bind_result($anketYorum,$anketDeger,$anketZaman,$yorumcuAdi);
		
		$star=0;
		$toplamYorum=0;
		while($yorumlarSql->fetch()){

			if($anketDeger==5) $star="1";
			else if($anketDeger==4) $star="-17";
			else if($anketDeger==3) $star="-35";
			else if($anketDeger==2) $star="-53";
			else if($anketDeger==1) $star="-71";
			

			$yorumlarHTML.='<div class="comment">
                                    <p>'.$yorumcuAdi.'</p>
                                    <time datetime="'.date("m-d-Y H:i",$anketZaman).'">'.date("m-d-Y H:i",$anketZaman).'</time>
                                    <div class="bstars com-stars" style="background: url(\'img/stars.png\') '.$star.'px 0 no-repeat !important;"></div>
                                    <p>'.$anketYorum.'</p>
                                </div>
								';
			$toplamYorum++;
		}

		$yorumlarSql->close();
		$yorumlar[]=$toplamYorum;
		$yorumlar[]=$yorumlarHTML;
		return $yorumlar;
	}

	public function yorumIzin($urunID){
	
		$anketZaman=time();

		$yorumChk = $this->mysqli->prepare("SELECT urunID FROM pk_urunler WHERE urunID=? AND urunDurum=2 AND (SELECT yorumFlag FROM pk_siparisUrunler WHERE urunID=? AND sipID=(SELECT MAX(sipID) FROM pk_siparisler WHERE sipUyeID=? AND sipTarih<? AND sipDurum=2))=0 LIMIT 1");
		$yorumChk->bind_param("ssss",$urunID,$urunID,$this->uyeKimlik,$anketZaman);
		$yorumChk->execute();
		$yorumChk->store_result();
		$yorumOnay=$yorumChk->num_rows;
		$yorumChk->close();

		return $yorumOnay;
	}
	public function ureticilerHTML(){
		$ureticilerHTML="";

		$ureticiChk = $this->mysqli->prepare("SELECT uyeID,ureticiLogo FROM pk_ureticiler WHERE durum=1 ORDER BY RAND() LIMIT 27");
		$ureticiChk->execute();
		$ureticiChk->bind_result($ureticiID,$ureticiLogo);
		while($ureticiChk->fetch()){
			$ureticilerHTML.='
			<li><a href="http://www.pastakapinizda.com/katalog-urunler-sayfasi.php?u='.$ureticiID.'"><img src="avatar/'.$ureticiLogo.'" alt="" onerror="this.src=\'img/default_logo.png\'"></a></li>';
		}
		$ureticiChk->close();

		return $ureticilerHTML;
		
	}

	public function ureticiBilgi($ureticiID){
	
		$ureticiChk = $this->mysqli->prepare("SELECT adSoyad,ureticiLogo,(SELECT ROUND(AVG(anketDeger),0) FROM pk_anketler INNER JOIN pk_urunler USING(urunID)  WHERE pk_urunler.ureticiID=? AND anketOnay=2) as anketDeger  FROM pk_ureticiler WHERE uyeID=? AND durum=1 LIMIT 1");
		$ureticiChk->bind_param("ii",$ureticiID,$ureticiID);
		$ureticiChk->execute();
		$ureticiChk->bind_result($ureticiAdi,$ureticiLogo,$ureticiDeger);
		$ureticiChk->fetch();
		$ureticiChk->close();
		$ureticiBilgi["ad"]=$ureticiAdi;
		$ureticiBilgi["logo"]=$ureticiLogo;
		$ureticiBilgi["deger"]=$ureticiDeger;
		return $ureticiBilgi;
		
	}
}
?>
