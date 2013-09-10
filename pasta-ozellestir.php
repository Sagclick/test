<?
require_once("session.php");
require_once("pastaMotor.class.php");
$pastaMotor=new pastaMotor($mysqli,$mysqli2);
require_once('recaptchalib.php');
$publickey = "6LekG-cSAAAAABKoYNczLNNKXXLLVjs0cSpmunml";
$captchaHTML= recaptcha_get_html($publickey);

$urunID=(int)$_GET["i"];
$temaHTML="";
$promoHTML="";
$adImg="";
$sepetHTML=$pastaMotor->sepetHTML();
$temaListeHTML=$pastaMotor->temaListeHTML();
$urunDetayArr=$pastaMotor->urunDetay($urunID);
$sizinIcinSectik=$pastaMotor->sizinIcinSectik();
$benzerUrunler=$pastaMotor->benzerUrunler($urunDetayArr[14]);
$enCokSatanlarTop5=$pastaMotor->enCokSatanlarTop5();
$yorumlarHTML=$pastaMotor->yorumlarHTML($urunID);

if($urunDetayArr[0]=="Cupcake") $adImg="cat-logo-cupcake.png";
else if($urunDetayArr[0]=="Kurabiye") $adImg="cat-logo-kurabiye.png";
else if($urunDetayArr[0]=="Pasta") $adImg="cat-logo-pasta.png";
else $adImg="cat-logo.png";

for($i=2;$i<=4;$i++){
	if($urunDetayArr[$i]!="") $temaHTML.=$urunDetayArr[$i].",";
}

$temaHTML=substr($temaHTML,0,strlen($temaHTML)-1);

if($urunDetayArr[10]==2) $promoHTML='<div>'.$urunDetayArr[11].' TL</div><div>'.$urunDetayArr[12].' TL</div>';
else $promoHTML='<div>'.$urunDetayArr[12].' TL</div>';



if(isset($_SESSION["ePosta"])){
	$uyeHTML='<input type="hidden" name="Uyelik" value="Üye"/>
							<li id="li_1" style="display:inline-block; width:158px">
								<label class="description" for="element_1">Ad Soyad</label>
								<div>
									<input id="element_1" name="Ad" class="element text large" type="text" maxlength="255" value="'.$uyeAdi.'" readonly/> 
								</div> 
							</li>		

							<li id="li_4"  style="display:inline-block;width:158px;margin-left:15px;">
								<label class="description" for="element_4">Cep Telefonu</label>
								<div>
									<input id="element_4" name="cep_telefonu" class="element text large" type="text" maxlength="255" value="'.$cepTel.'" readonly/> 
								</div> 
							</li>		
		
							<li id="li_5"  style="width:255px;display: block;">
							<label class="description" for="element_5">Adres </label>
								<div>
									'.$uyeAdres.'
								</div> 
							</li>';
}
else{
	$uyeHTML='<input type="hidden" name="Uyelik" value="Üye Değil"/>
							<li id="li_1" style="display:inline-block; width:158px">
								<label class="description" for="element_1">Ad Soyad</label>
								<div>
									<input id="element_1" name="Ad" class="element text large" type="text" maxlength="255" value=""/> 
								</div> 
							</li>		

							<li id="li_4"  style="display:inline-block;width:158px;margin-left:15px;">
								<label class="description" for="element_4">Cep Telefonu</label>
								<div>
									<input id="element_4" name="cep_telefonu" class="element text large" type="text" maxlength="255" value=""/> 
								</div> 
							</li>		
		
							<li id="li_5"  style="width:255px;display: block;">
							<label class="description" for="element_5">Adres </label>
								<div>
									<textarea id="element_5" name="Adres" class="element textarea small"  style="width: 280px;"></textarea> 
								</div> 
							</li>';
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <meta charset="utf-8">
        <meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1, minimum-scale=0.1, maximum-scale=10,">
        <title>ÜRÜN GÖRÜNTÜLE - PASTA KAPINIZDA</title>
        <link rel="stylesheet" href="css/style.css" media="screen">
        <link rel="stylesheet" href="css/home-page.css" media="screen">
        <link rel="stylesheet" href="css/ribbon-guideline.css" media="screen">
        <link rel="stylesheet" href="css/Catalog-products.page.css" media="screen">
        <link rel="stylesheet" href="css/Catalog-products-list.page.css" media="screen">
        <link rel="stylesheet" href="css/Products-comparison.page.css" media="screen">
        <link rel="stylesheet" href="css/Catalog-product-view.css" media="screen">
        <link rel="stylesheet" href="css/catalog-product-view-reviws.css" media="screen">
        
                
        <link rel='stylesheet' media='screen and (min-width: 101px) and (max-width: 850px)' href='css/medium.css' />
        
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="js/jquery.carouFredSel-6.1.0-packed.js"></script>
        <script src="js/slides-pr.min.jquery.js"></script>
        <script src="js/jquery.selectbox.min.js"></script>
        <script src="js/jquery.jqzoom-core.js"></script>
        <script src="js/slides.min.jquery.js"></script>
        <script src="js/plugins.js"></script>
		<script>
		function sepeteEkle(urunID){
			
			try{
				ajaxRequest = new XMLHttpRequest();
			} 
			catch (e){
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
						alert("Hata!");
						return false;
					}
				}
			}

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){

					location.reload(false);
					
				}
				
			}
			queryString="id="+urunID;
			ajaxRequest.open("POST", "sepeteEkle.php", true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(queryString);
		}
		function sepettenCikar(sepetID){
			
			try{
				ajaxRequest = new XMLHttpRequest();
			} 
			catch (e){
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
						alert("Hata!");
						return false;
					}
				}
			}

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){

					location.reload(false);
					
				}
				
			}
			queryString="id="+sepetID;
			ajaxRequest.open("POST", "sepettenCikar.php", true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send(queryString);
		}
		</script>
    </head>
    <body class="no-js front-page">
        <script>
            $(document).ready(function() {
                $('.jqzoom').jqzoom({
                    zoomType: 'standard',
                    lens:true,
                    preloadImages: false,
                    alwaysOn:false
                });

            });
        </script>
		<script type="text/javascript">
		var RecaptchaOptions = {
		   lang : 'tr',
		};
		var RecaptchaOptions = {
			theme : 'white'
		};

		</script>
        <div id="container">
      
                <?require ('header.php')?>
          
            <div class="clearfix"></div>
            <!--Category logo image -->
            <div class="category-logo" style="background: url('img/<?=$adImg?>') no-repeat center center; background-size: cover;">
                <div class="cat-logo-top"><div class="ctlb-line"></div></div>
                <div class="cat-title">
                    <div class="cat-title-cont"><?=$urunDetayArr[0]?></div>
                </div>
                <a href="#" class="cat-logo-close"></a>
                <div class="cat-logo-bottom"><div class="ctlb-line"></div></div>
            </div>
            <!-- Main slider -->
            <div id="wrapper">
                <aside id="sidebar">
                    <nav class="left-sb-menu">
                        <div class="left-sb-menu-title">TEMALAR</div>
                        <ul style="height: 235px;overflow-y: scroll;"> 
                           <?=$temaListeHTML?>             
                                                        
                        </ul>
                    </nav>
                    <div class="widget-area">
                        <ul class="xoxo">
                            <li class="block widget widget_whats-new">
                                <h3 class="widget-title">SİZİN İÇİN SEÇTİK</h3>
                                <div class="widget-content">
                                    <!--Block WAHT'S NEW images -->
                                    <div class="imgbox">
                            			<a class="image-link" href="katalog-urun-goruntule.php?i=<?=$sizinIcinSectik[3]?>"></a>
                            			<a href="#" onclick="sepeteEkle(<?=$sizinIcinSectik[3]?>); return false;" style="bottom: 80px;"></a>
                            			<div class="mark"></div>
                            			<img src="<?=urunResimFolder.$sizinIcinSectik[0]?>" alt=""style="width:200px" onerror="this.src='img/default_product.png'" >
              
                        			</div>
                                    <div class="sk-product-descr" style="margin:0px;">
                                        <a href="#"><?=$sizinIcinSectik[1]?></a>
                                    </div>
                                    <div class="sk-product-price">
                                        <a href="#"><?=$sizinIcinSectik[2]?> TL</a>
                                    </div>
                                </div>
                            </li>

                            <li class="block widget widget_specials">
                                <h3 class="widget-title">EN ÇOK SATANLAR</h3>
                                <div class="widget-content">
                                    <ul>
                                        <?=$enCokSatanlarTop5?>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    

                    
                   
                                   
                </aside>
                <article class="pr-view" style="margin-left: 40px;">
                    <p><a href="#">Ürünler</a> / <a href="#"><?=$urunDetayArr[0]?></a></p>
                    <header class="entry-header">
                        <h1 class="entry-title"><?=$urunDetayArr[1]?><br><span style="font-size: 12px;font-style: italic;"><?=$temaHTML?></span></h1>
                    </header>
                    <div class="pr-view-content" style="height:390px">
                        <div class="pr-view-img">
                            <div id="products">
                                <!--Product image -->
                                <div class="slides_container">
                                   <a href='javascript:void(0);' class="jqzoomx" rel='nofollow'  title="<?=$urunDetayArr[1]?>" style="cursor:default"><div class="mark view-mark" ></div>
								   <img src="<?=urunResimFolder.$urunDetayArr[5]?>"   width="325" height="292" alt="1144953 3 2x" onerror="this.src='img/default_product.png'"></a>
						
                                </div>
                            </div>
                        </div>
                        <div class="pr-view-sd">
                            <div class="view-sd-header">
                                <?=$urunDetayArr[6]?>
                            </div>
                            <div class="view-sd-content">
                                <div>
                                <p>Ürün ID: <span> <?=$urunDetayArr[7]?></span></p>
                                <p>Üreticisi : <span> <?=$urunDetayArr[8]?> </span></p>
                                <p>Tedarik Süresi: <span><?=($urunDetayArr[9]==0) ? "Aynı Gün" : $urunDetayArr[9]." Gün"; ?></span></p>
                                </div>
                                <div style="height: 0px;"></div>
                                
                            </div>

                        </div>
                    </div>

                    <div class="pr-d-r-border" style="margin-bottom:50px;margin-top:-50px"></div>

                    
                    <div id="form_container" style="overflow: auto;">
	
		
							<form id="form_651362" class="appnitro"  method="post" action="formPastaOzellestir.php">
							<?=$uyeHTML?>
							<li id="li_6" class="notranslate leftHalf       focused"  style="display:inline-block;">
								<label class="description" style="width:220px;" id="element_6" for="element_6"> Pandispanya </label>
									<div>
										<select id="element_6" style="width:220px;" name="pandispanya" class="field select medium" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="3"> 
											<option value="pandispanya_oldugu_gibi" selected="selected">Olduğu Gibi Bırak</option>
											<option value="vanilyali_sunger_kek">Vanilyalı Kek</option>
											<option value="kakaolu_sunger_kek">Kakao Kek</option>
											<option value="limonlu_sunger_kek">Limonlu Kek</option>
											<option value="portakalli_sunger_kek">Portakallı Kek</option>
											<option value="meyveli_sunger_kek">Karışık Meyveli Kek</option>
											<option value="brownieli_sunger_kek">Brownie'li Kek</option>
											<option value="havuclu_sunger_kek">Havuçlu Kek</option>																						
										</select>
									</div>
							</li>

							<li id="li_7" class="notranslate leftHalf       focused"  style="display:inline-block;margin-left:10px;">
								<label class="description" style="width:220px;" id="element_7" for="element_7">Pasta Kreması</label>
									<div>
										<select id="element_7" style="width:220px;" name="krema" class="field select medium" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="3"> 
											<option value="krema_oldugu_gibi" selected="selected">Olduğu Gibi Bırak</option>											
											<option value="kremsanti">Krem Şanti</option>
											<option value="vanilyali_pastaci_kremasi">Vanilyalı Pastacı Kreması</option>
											<option value="cikolatali_ganaj_krema">Çikolatalı Ganaj Krema</option>							
										</select>
									</div>
							</li>
							<li id="li_8" class="notranslate leftHalf       focused"  style="display:inline-block;margin-left:10px;">
								<label class="description" style="width:220px;" id="element_8" for="element_8"> Kişi Sayısı </label>
									<div>
										<select id="element_8" style="width:220px;" name="kisi_sayisi" class="field select medium" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="3"> 
											<option value="kisi_oldugu_gibi" selected="selected">Olduğu Gibi Bırak</option>
											<option value="10">10 kişilik</option>
											<option value="15">15 kişilik</option>
											<option value="20">20 kişilik</option>							
											<option value="25">25 kişilik</option>							
										</select>
									</div>
							</li>

							<li  style="display: block;"></li>


							<li id="li_9" class="notranslate       focused"  style="display:inline-block;width: 220px;vertical-align: top;">
								<fieldset style="height: 315px;">
									<!--[if !IE | (gte IE 8)]-->
									<legend id="element_9" class="description" style="display: block;font-size: 11px;color: #989898;font-style: italic;margin-bottom: 7px;">
									Meyve Seçimi
									</legend>
									<!--[endif]-->
									<!--[if lt IE 8]>
									<label id="title1" class="desc">
									Meyve Seçimi
									<![endif]-->
										<div>
											<span>
												<input id="Field1" name="Meyve_1" type="checkbox" class="field checkbox" value="Ananas" tabindex="1" onchange="handleInput(this);">
												<label class="choice" for="Field1" style="    display: inline-block;">Ananas</label>
											</span>
											<br/>
											<span>
												<input id="Field2" name="Meyve_2" type="checkbox" class="field checkbox" value="Böğürtlen" tabindex="2" onchange="handleInput(this);">
												<label class="choice" for="Field2" style="    display: inline-block;">Böğürtlen</label>
											</span>
											<br/>
											<span>
												<input id="Field3" name="Meyve_3" type="checkbox" class="field checkbox" value="Çilek" tabindex="3" onchange="handleInput(this);">
												<label class="choice" for="Field3" style="    display: inline-block;">Çilek</label>
											</span>
											<br/>
											<span>
												<input id="Field4" name="Meyve_4" type="checkbox" class="field checkbox" value="Frambuaz" tabindex="4" onchange="handleInput(this);">
												<label class="choice" for="Field4" style="    display: inline-block;">Frambuaz</label>
											</span>
											<br/>
											<span>
												<input id="Field5" name="Meyve_5" type="checkbox" class="field checkbox" value="Kiwi" tabindex="5" onchange="handleInput(this);">
												<label class="choice" for="Field5" style="    display: inline-block;">Kiwi</label>
											</span>
											<br/>
											<span>
												<input id="Field6" name="Meyve_6" type="checkbox" class="field checkbox" value="Muz" tabindex="6" onchange="handleInput(this);">
												<label class="choice" for="Field6" style="    display: inline-block;">Muz</label>
											</span>
											<br/>
											<span>
												<input id="Field7" name="Meyve_7" type="checkbox" class="field checkbox" value="Şeftali" tabindex="7" onchange="handleInput(this);">
												<label class="choice" for="Field7" style="    display: inline-block;">Şeftali</label>
											</span>
											<br/>
											<span>
												<input id="Field8" name="Meyve_8" type="checkbox" class="field checkbox" value="Vişne" tabindex="8" onchange="handleInput(this);">
												<label class="choice" for="Field8" style="    display: inline-block;">Vişne</label>
											</span>
											<br/>
											<span>
												<input id="Field9" name="Meyve_9" type="checkbox" class="field checkbox" value="Yabanmersini" tabindex="9" onchange="handleInput(this);">
												<label class="choice" for="Field9" style="    display: inline-block;">Yabanmersini</label>
											</span>
											<br/>
											<span>
												<input id="Field10" name="Meyve_10" type="checkbox" class="field checkbox" value="meyve_oldugu_gibi" tabindex="10" onchange="handleInput(this);">
												<label class="choice" for="Field10" style="    display: inline-block;">Olduğu Gibi Bırak</label>
											</span>

										</div>
									</fieldset>
								</li>


							<li id="li_10" class="notranslate       focused"  style="display:inline-block;margin-left:10px;width: 220px;vertical-align: top;">
								<fieldset style="height: 315px;">
									<!--[if !IE | (gte IE 8)]-->
									<legend id="element_10" class="description"  style="display: block;font-size: 11px;color: #989898;font-style: italic;margin-bottom: 7px;">
									Yağlı Tohum Seçimi
									</legend>
									<!--[endif]-->
									<!--[if lt IE 8]>
									<label id="title1" class="desc">
									Yağlı Tohum Seçimi
									<![endif]-->
										<div>
											<span>
												<input id="Field1" name="Tohum_1" type="checkbox" class="field checkbox" value="Antepfıstığı" tabindex="1" onchange="handleInput(this);">
												<label class="choice" for="Field1" style="    display: inline-block;">Antepfıstığı</label>
											</span>
											<br/>
											<span>
												<input id="Field2" name="Tohum_2" type="checkbox" class="field checkbox" value="Badem" tabindex="2" onchange="handleInput(this);">
												<label class="choice" for="Field2" style="    display: inline-block;">Badem</label>
											</span>
											<br/>
											<span>
												<input id="Field3" name="Tohum_3" type="checkbox" class="field checkbox" value="Ceviz" tabindex="3" onchange="handleInput(this);">
												<label class="choice" for="Field3" style="    display: inline-block;">Ceviz</label>
											</span>
											<br/>
											<span>
												<input id="Field4" name="Tohum_4" type="checkbox" class="field checkbox" value="Fındık" tabindex="4" onchange="handleInput(this);">
												<label class="choice" for="Field4" style="    display: inline-block;">Fındık</label>
											</span>
											<br/>
											<span>
												<input id="Field5" name="Tohum_5" type="checkbox" class="field checkbox" value="Krokan" tabindex="5" onchange="handleInput(this);">
												<label class="choice" for="Field5" style="    display: inline-block;">Krokan</label>
											</span>
											<br/>
											<span>
												<input id="Field6" name="Tohum_6" type="checkbox" class="field checkbox" value="tohum_oldugu_gibi" tabindex="6" onchange="handleInput(this);">
												<label class="choice" for="Field6" style="    display: inline-block;">Olduğu Gibi Bırak</label>
											</span>											

										</div>
									</fieldset>
								</li>

							<li id="li_11" class="notranslate       focused"  style="display:inline-block;margin-left:10px;width: 220px;vertical-align: top;">
								<fieldset style="height: 315px;">
									<!--[if !IE | (gte IE 8)]-->
									<legend id="element_11" class="description"  style="display: block;font-size: 11px;color: #989898;font-style: italic;margin-bottom: 7px;">
									Parça Çikolata Seçimi
									</legend>
									<!--[endif]-->
									<!--[if lt IE 8]>
									<label id="title1" class="desc">
									Parça Çikolata Seçimi
									<![endif]-->
										<div>
											<span>
												<input id="Field1" name="Cikolata_1" type="checkbox" class="field checkbox" value="Bitter" tabindex="1" onchange="handleInput(this);">
												<label class="choice" for="Field1" style="    display: inline-block;">Bitter Parça Çikolata</label>
											</span>
											<br/>
											<span>
												<input id="Field2" name="Cikolata_2" type="checkbox" class="field checkbox" value="Sütlü" tabindex="2" onchange="handleInput(this);">
												<label class="choice" for="Field2" style="    display: inline-block;">Sütlü Parça Çikolata</label>
											</span>
											<br/>
											<span>
												<input id="Field3" name="Cikolata_3" type="checkbox" class="field checkbox" value="Beyaz" tabindex="3" onchange="handleInput(this);">
												<label class="choice" for="Field3" style="    display: inline-block;">Beyaz Parça Çikolata</label>
											</span>
											<br/>
											<span>
												<input id="Field4" name="Cikolata_4" type="checkbox" class="field checkbox" value="cikolata_oldugu_gibi" tabindex="4" onchange="handleInput(this);">
												<label class="choice" for="Field4" style="    display: inline-block;">Olduğu Gibi Bırak</label>
											</span>


										</div>
									</fieldset>
								</li>



							<li style="display: block;"><label>* Meyve, Yağlı tohum ve Parça Çikolata  seçeneklerinin tedarik durumu ve ücretlendirilmesi mevsimlere göre değişiklik gösterebilir.</label></li>
							<li style="display: block;height:10px;"></li>



							<li id="li_12" style="display: block;">
								<label class="description" for="element_12">Özel Pasta 'ya Ait Notlar </label>
								<div>
									<textarea id="element_12" name="ozel_pasta_notlari" class="element textarea small" style="width: 655px;height: 100px;"></textarea> 
								</div> 
							</li>

	

							<li class="buttons"  style="display: block;">
								     <input type="hidden" name="form_id" value="<?=mt_rand()?>" />
									
									<?=$captchaHTML?>
									
									<input style="margin-top:25px" id="saveForm" class="button_text" type="submit"  value="Gönder" />
							</li>
								</ul>
						</form>	

					</div>
                    
                    
                    
                    
                </article>
            </div><!-- /#wrapper -->
           
           
                <?require ('footer.php')?>
            
            
        </div><!-- /#container -->
    </body>
</html>
