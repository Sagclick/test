<?
require_once("session.php");
require_once("pastaMotor.class.php");
$pastaMotor=new pastaMotor($mysqli,$mysqli2);
$urunID=(int)$_GET["i"];
$temaHTML="";
$promoHTML="";
$adImg="";
$ozellestirFlag="hidden";
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


if($urunDetayArr[0]=="Pasta") $ozellestirFlag="visible";

for($i=2;$i<=4;$i++){
	if($urunDetayArr[$i]!="") $temaHTML.=$urunDetayArr[$i].",";
}

$temaHTML=substr($temaHTML,0,strlen($temaHTML)-1);

if($urunDetayArr[10]==2) $promoHTML='<div>'.$urunDetayArr[11].' TL</div><div>'.$urunDetayArr[12].' TL</div>';
else $promoHTML='<div>'.$urunDetayArr[12].' TL</div>';

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
                    <div class="pr-view-content">
                        <div class="pr-view-img">
                            <div id="products">
                                <!--Product image -->
                                <div class="slides_container">
                                   <a href='javascript:void(0);' class="jqzoomx" rel='nofollow' title="<?=$urunDetayArr[1]?>" style="cursor:default"><div class="mark view-mark" ></div>
								   <img src="<?=urunResimFolder.$urunDetayArr[5]?>"   width="325" height="292" alt="1144953 3 2x" onerror="this.src='img/default_product.png'"></a>
						
                                </div>
                                
                                <ul id="thumblist" class="pagination">
                                    <li><a class="zoomThumbActive" href='javascript:void(0);' rel="nofollow" style="cursor:default">

									<img src="<?=urunResimFolder.$urunDetayArr[5]?>" width="55" height="55" alt="1144953 3 2x" onerror="this.src='img/default_product.png'"></a></li>
									
                                </ul>
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
                                <div style="height: 0px;visibility:<?=$ozellestirFlag?>"><a href="pasta-ozellestir.php?i=<?=$urunDetayArr[7]?>"><img style="width: 85px;margin-left: 250px;margin-top: -60px;" src="img/kendi_pastan2.png"></a></div>
                                
                            </div>
                            <div class="view-sd-footer">
                                <div class="sd-footer-l">
                                    <div class="managbox managbox-view">
                                        <div>
                                            <a title='Üretici Sayfası' href="katalog-urunler-sayfasi.php?u=<?=$urunDetayArr[16]?>"></a>
                                            <a title='Sepete Ekle' href="#" onclick="sepeteEkle(<?=$urunDetayArr[7]?>); return false;"></a>
                                            <a title='Ürün Detay' href="katalog-urun-goruntule.php?i=<?=$urunDetayArr[7]?>"></a>
                                        </div>
                                    </div>
                                    <div class="bstars com-stars" style="<?=$urunDetayArr[15]?>"></div>
                                    <p></p>
                                    <!--<input onclick="open_rev(this)" type="submit" value="Write a riview">-->
                                </div>
                                <div class="sd-footer-r">
                                    <div class="view-price">
                                        <?=$promoHTML?>
                                    </div>
                                    <div class="qantyti"><p></p></div>
                                    <div class="clearfix"></div>
                                    <a class="list-add" href="#" onclick="sepeteEkle(<?=$urunDetayArr[7]?>); return false;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pr-desc-rev">
                        <div id="tabs" >
                            <ul>
                                <li><a onclick="tabs_desc(this)" id="tabs-desc" class="pr-b-active" href="#tabs-1">Üretici Notu</a></li>
                                <li><a onclick="tabs_rev(this)" id="tabs-rev" href="#tabs-2">Yorumlar (<?=$yorumlarHTML[0]?>)</a></li>
                            </ul>
                            <div id="tabs-1" style="min-height: 200px;">
                                <p>
                                    <?=$urunDetayArr[13]?>
                                </p>
                            </div>
                            <div id="tabs-2" style="min-height: 200px;">
                               <?=$yorumlarHTML[1]?>
                             
                            </div>
                        </div>
                    </div>
                    <!--<div class="pr-soc-seti">
                        <div class="facebook"></div>
                        <div class="vk"></div>
                        <div class="tw"></div>
                        <div class="google"></div>
                        <div class="livej"></div>
                        <div class="yandex"></div>
                        <div class="soc-plus"></div>
                    </div>-->
                    <div class="pr-d-r-border"></div>
                    <div class="relate-products">
                        <h3>Benzer Ürünler</h3>
                        <ul class="content-ul">
                           <?=$benzerUrunler?>
                        </ul>

                    </div>
                </article>
            </div><!-- /#wrapper -->
           
           
                <?require ('footer.php')?>
            
            
        </div><!-- /#container -->
    </body>
</html>
