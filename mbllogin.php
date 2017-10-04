<?php

// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/* $app = new \Slim\Slim(array(
  'mode' => 'development',
  'debug' => true,
  'log.enabled' => true,
  )); */

$app = new \Slim\SlimExtended(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::INFO,
    'exceptions.rabbitMQ' => true,
    'exceptions.rabbitMQ.logging' => \Slim\SlimExtended::LOG_RABBITMQ_FILE,
    'exceptions.rabbitMQ.queue.name' => \Slim\SlimExtended::EXCEPTIONS_RABBITMQ_QUEUE_NAME
        ));

/**
 * "Cross-origion resource sharing" kontrolüne izin verilmesi için eklenmiştir
 * @author Okan CIRAN
 * @since 25.10.2017
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC());
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());

 
 
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/gnlKullaniciMebKoduFindByTcKimlikNo_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vtc = NULL;
     
    if (isset($_GET['tc'])) {
        $stripper->offsetSet('tc', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['tc']));
    }
   
    $stripper->strip();
    if ($stripper->offsetExists('tc')) {
        $vtc = $stripper->offsetGet('tc')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->gnlKullaniciMebKoduFindByTcKimlikNo(array( 
        'url' => $_GET['url'], 
        'tc' => $vtc, 
        ));
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resDataInsert));
}
);

 
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/gnlKullaniciFindForLoginByTcKimlikNo_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vtc = NULL;     
    if (isset($_GET['tc'])) {
        $stripper->offsetSet('tc', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['tc']));
    }
    $vsifre = NULL;
    if (isset($_GET['sifre'])) {
        $stripper->offsetSet('sifre', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sifre']));
    }
   
    $stripper->strip();
    if ($stripper->offsetExists('tc')) {
        $vtc = $stripper->offsetGet('tc')->getFilterValue();
    }
    if ($stripper->offsetExists('sifre')) {
        $vsifre = $stripper->offsetGet('sifre')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->gnlKullaniciFindForLoginByTcKimlikNo(array( 
        'url' => $_GET['url'], 
        'tc' => $vtc,  
        'sifre' => $vsifre, 
        ));
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resDataInsert));
}
);
 

/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/mobilfirstdata_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
   
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
   
    $resDataInsert = $BLL->mobilfirstdata(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        ));
   // $app->response()->header("Content-Type", "application/json");
   // $app->response()->body(json_encode($resDataInsert));
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(
            "OkulKullaniciID" => $menu["OkulKullaniciID"],
            "OkulID" => $menu["OkulID"],
            "KisiID" => $menu["KisiID"],
            "RolID" =>  ($menu["RolID"]),
            "OkulAdi" => html_entity_decode($menu["OkulAdi"]), 
            "MEBKodu" => html_entity_decode($menu["MEBKodu"]), 
            "ePosta" => html_entity_decode($menu["ePosta"]),
            "DersYiliID" =>  ($menu["DersYiliID"]),
            "EgitimYilID" =>  ($menu["EgitimYilID"]),
            "EgitimYili" =>  ($menu["EgitimYili"]), 
            "DonemID" =>  ($menu["DonemID"]), 
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
}
);
  
/**
 * Okan CIRAN
 * @since 26-09-2017 
 */
$app->get("/mobilMenu_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers();
    
     
    $vParent = 0;
    if (isset($_GET['ParentID'])) {
        $stripper->offsetSet('ParentID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['ParentID']));
    }
    $vRolID = NULL;
    if (isset($_GET['RolID'])) {
        $stripper->offsetSet('RolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['RolID']));
    }
    
    $stripper->strip(); 
    if ($stripper->offsetExists('ParentID')) 
        {$vParent = $stripper->offsetGet('ParentID')->getFilterValue(); }    
    if ($stripper->offsetExists('RolID')) 
        {$vRolID = $stripper->offsetGet('RolID')->getFilterValue(); }  
    
    $resDataMenu = $BLL->mobilMenu(array('ParentID' => $vParent,      
                                            'RolID' => $vRolID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "ID" => $menu["ID"],
            "MenuID" => $menu["MenuID"],
            "ParentID" => $menu["ParentID"],
            "MenuAdi" => html_entity_decode($menu["MenuAdi"]),
            "Aciklama" => html_entity_decode($menu["Aciklama"]),
            "URL" => $menu["URL"],
            "SubDivision" => $menu["SubDivision"],
            "ImageURL" => $menu["ImageURL"], 
            "divid" => $menu["divid"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 

/**
 * Okan CIRAN
 * @since 26-09-2017 
 */
$app->get("/gnlKisiOkulListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vkisiId = '-1';     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
   
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    $resDataMenu = $BLL->gnlKisiOkulListesi(array(      
                                             'kisiId' => $vkisiId, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "OkulID" => $menu["OkulID"], 
             "OkulAdi" => html_entity_decode($menu["OkulAdi"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersProgrami_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
     $vOkulID = NULL;     
    if (isset($_GET['OkulID'])) {
        $stripper->offsetSet('OkulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['OkulID']));
    }
   
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('OkulID')) {
        $vOkulID = $stripper->offsetGet('OkulID')->getFilterValue();
    }
   
    $resDataInsert = $BLL->ogretmenDersProgrami(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'OkulID' => $vOkulID,  
        ));
 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array( 
            
            "HaftaGunu" => $menu["HaftaGunu"],
            "DersSirasi" => $menu["DersSirasi"],
            "SinifDersID" => $menu["SinifDersID"], 
            "DersKodu" => html_entity_decode($menu["DersKodu"]), 
            "DersAdi" => html_entity_decode($menu["DersAdi"]), 
            "SinifKodu" => html_entity_decode($menu["SinifKodu"]), 
             "Aciklama" => html_entity_decode($menu["Aciklama"]), 
         
            "SubeGrupID" =>  ($menu["SubeGrupID"]),
            "BaslangicSaati" =>  ($menu["BaslangicSaati"]),
            "BitisSaati" =>  ($menu["BitisSaati"]), 
            "DersBaslangicBitisSaati" =>  ($menu["DersBaslangicBitisSaati"]), 
            "SinifOgretmenID" =>  ($menu["SinifOgretmenID"]),
            "DersHavuzuID" =>  ($menu["DersHavuzuID"]),
            "SinifID" =>  ($menu["SinifID"]),
            "DersID" =>  ($menu["DersID"]),
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
} 
);
  
/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersProgramiDersSaatleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vsinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }
     $vtarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    }
     
   
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('tarih')) {
        $vtarih = $stripper->offsetGet('tarih')->getFilterValue();
    }
   
    $resDataInsert = $BLL->ogretmenDersProgramiDersSaatleri(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'sinifID' => $vsinifID, 
        'tarih' => $vtarih,  
        ));
 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            
            "BaslangicSaati" => $menu["BaslangicSaati"],
            "BitisSaati" => $menu["BitisSaati"],
            "DersSirasi" => $menu["DersSirasi"], 
            "DersKodu" => html_entity_decode($menu["DersKodu"]), 
            "DersAdi" => html_entity_decode($menu["DersAdi"]), 
            "Aciklama" => html_entity_decode($menu["Aciklama"]),  
            "DersID" =>  ($menu["DersID"]),
            "HaftaGunu" =>  html_entity_decode($menu["HaftaGunu"]),
                        
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
} 
);

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersPrgDersSaatleriOgrencileri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vsinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }
    $vtarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    }
    $vdersSirasi= NULL;     
    if (isset($_GET['dersSirasi'])) {
        $stripper->offsetSet('dersSirasi', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['dersSirasi']));
    } 
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }             
                
           
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('tarih')) {
        $vtarih = $stripper->offsetGet('tarih')->getFilterValue();
    }
    if ($stripper->offsetExists('dersSirasi')) {
        $vdersSirasi = $stripper->offsetGet('dersSirasi')->getFilterValue();
    }
     if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
   
    $resDataInsert = $BLL->ogretmenDersPrgDersSaatleriOgrencileri(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'sinifID' => $vsinifID, 
        'tarih' => $vtarih,  
        'dersSirasi' => $vdersSirasi,  
        'dersYiliID' => $vdersYiliID,  
        ));
 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "OgrenciID" => $menu["OgrenciID"],
            "Tarih" => $menu["Tarih"],
            "DersSirasi" => $menu["DersSirasi"], 
            "DersYiliID" =>  ($menu["DersYiliID"]),
            "Numarasi" => html_entity_decode($menu["Numarasi"]), 
            "Adi" => html_entity_decode($menu["Adi"] ), 
            "Soyadi" => html_entity_decode($menu["Soyadi"]),  
            "TCKimlikNo" =>  html_entity_decode($menu["TCKimlikNo"]),
            "CinsiyetID" =>  html_entity_decode($menu["CinsiyetID"]),
            "DevamsizlikKodID" =>  html_entity_decode($menu["DevamsizlikKodID"]),
            "Aciklama" =>  html_entity_decode($menu["Aciklama"]), 
                        
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
} 
);

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenVeliRandevulari_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
     
                
           
    $stripper->strip();
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->ogretmenVeliRandevulari(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
         
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
           
            "VeliRandevuID" => $menu["VeliRandevuID"],
            "SinifOgretmenID" => $menu["SinifOgretmenID"],
            "VeliID" => $menu["VeliID"], 
            "BasZamani" =>  ($menu["BasZamani"]),
            "BitZamani" =>  ($menu["BitZamani"]), 
            "Aciklama" => html_entity_decode($menu["Aciklama"]), 
            "Onay" =>  ($menu["Onay"]),  
            "Ogretmen_Adi" =>  html_entity_decode($menu["Ogretmen_Adi"]),
            "Ogretmen_Soyadi" =>  html_entity_decode($menu["Ogretmen_Soyadi"]),
            "Ogrenci_Adi" =>  html_entity_decode($menu["Ogrenci_Adi"]),
            "Ogrenci_Soyadi" =>  html_entity_decode($menu["Ogrenci_Soyadi"]), 
            
             "Veli_Adi" =>  html_entity_decode($menu["Veli_Adi"]),
            "Veli_Soyadi" =>  html_entity_decode($menu["Veli_Soyadi"]), 
            
             "DersAdi" =>  html_entity_decode($menu["DersAdi"]),
            "Ders_Ogretmen" =>  html_entity_decode($menu["Ders_Ogretmen"]), 
                        
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
} 
);





$app->run();
