<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 */
class MblLogin extends \DAL\DalSlim {

    /**     
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {             
        } catch (\PDOException $e /* Exception $e */) {             
        }
    }

    /** 
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017  
     * @param array | null $args  
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try { 
        } catch (\PDOException $e /* Exception $e */) {   
        }
    }

    /** 
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try { 
        } catch (\PDOException $e /* Exception $e */) { 
        }
    }

    /** 
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
     * @param array | null $args  
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try { 
        } catch (\PDOException $e /* Exception $e */) { 
        }
    }
    
    /**
     * 
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkTempControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');            
            $sql = "     
                        SELECT id,pkey,sf_private_key_value_temp ,root_id FROM (
                            SELECT id, 	
                                CRYPT(sf_private_key_value_temp,CONCAT('_J9..',REPLACE('".$params['pktemp']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pktemp']."','*','/')) AS pkey,	                                
                                sf_private_key_value_temp , root_id
                            FROM info_users WHERE active=0 AND deleted=0) AS logintable
                        WHERE pkey = TRUE
                    ";  
            $statement = $pdo->prepare($sql);
          //  $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {        
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * 
     * @author Okan CIRAN
     * @ public key e ait bir private key li kullanıcı varsa True değeri döndürür.  !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "              
                    SELECT id,pkey,sf_private_key_value FROM (
                            SELECT COALESCE(NULLIF(root_id, 0),id) AS id, 	
                                CRYPT(sf_private_key_value,CONCAT('_J9..',REPLACE('".$params['pk']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pk']."','*','/')) AS pkey,	                                
                                sf_private_key_value
                            FROM info_users WHERE active=0 AND deleted=0) AS logintable
                        WHERE pkey = TRUE
                    "; 
            $statement = $pdo->prepare($sql);            
        //    $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {       
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    
    
    
    /** 
     * @author Okan CIRAN
     * @ login için mebkodunu döndürür   !! 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKullaniciMebKoduFindByTcKimlikNo($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "          
                exec PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo=  ".$params['tc']."
                 ";
            
            /*
             * 
               UPDATE
                GNL_Kullanicilar
                SET
                 Sifre='1YTr63O9Mdeg54DZefZg16g=='
             * 
             */
            $statement = $pdo->prepare($sql);            
      //      echo debugPDO($sql, $parameters);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
  
    
    /** 
     * @author Okan CIRAN
     * @ login için user id döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKullaniciFindForLoginByTcKimlikNo($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $mebKoduValue = NULL;
            $mebKodu = $this->gnlKullaniciMebKoduFindByTcKimlikNo(array('tc' => $params['tc']));
            if ((isset($mebKodu['resultSet'][0]['MEBKodu']) && $mebKodu['resultSet'][0]['MEBKodu'] != "")) {                                    
                        $mebKoduValue = $mebKodu['resultSet'][0]['MEBKodu'];
                    }  
            if ((isset($params['sifre']) && $params['sifre'] != "")) {                                    
                        $sifre = $params['sifre'];
                        if ($params['sifre'] =='12345')
                                {$sifre ='1YTr63O9Mdeg54DZefZg16g==';}
                        
                    }  
            $sql = "    
            DECLARE  @KisiID uniqueidentifier ; 

            EXEC [dbo].[PRC_GNL_Kullanici_Find_For_Login_ByTcKimlikNo]
		@KisiID = @KisiID OUTPUT,
		@MEBKodu = ".intval($mebKoduValue).",
		@TcKimlikNo = ".$params['tc'].",
		@Sifre = N'".$sifre."' ;  
            
            SELECT @KisiID as KisiID,   concat(kk.[Adi],' ' ,kk.[Soyadi] ) as adsoyad,   kk.[TCKimlikNo] 
            FROM  [dbo].[GNL_Kisiler] kk where  kk.[KisiID] = @KisiID ; 
                 ";
            
            /*
             * 
               UPDATE
                GNL_Kullanicilar
                SET
                 Sifre='1YTr63O9Mdeg54DZefZg16g=='
             * 
             */
            $statement = $pdo->prepare($sql);            
         //echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /** 
     * @author Okan CIRAN
     * @ login olan userin rol bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKisiTumRollerFindByID($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $sql = "   
                DECLARE @return_value int;

                EXEC @return_value = [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]
                    @KisiID =  '".$params['kisiId']."' ;

                SELECT  
                    null as KisiID , 
                    'LUTFEN SEÇİNİZ...' as adsoyad,
                    '' as [TCKimlikNo]  

                union 
                SELECT 'Return Value' = @return_value;
 
                 "; 
            $statement = $pdo->prepare($sql);            
         //echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
  
  
    /** 
     * @author Okan CIRAN
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilfirstdata($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $sql = "  
                    set nocount on;
                    IF OBJECT_ID('tempdb..#okimobilfirstdata') IS NOT NULL DROP TABLE #okimobilfirstdata; 
    
                    CREATE TABLE #okimobilfirstdata
                                    (
                                            [OkulKullaniciID]  [uniqueidentifier],
                                            [OkulID] [uniqueidentifier], 
                                            [KisiID] [uniqueidentifier],
                                            [RolID]  int,
                                            [RolAdi] varchar(100)  
                                    ) ;
                   
                    INSERT #okimobilfirstdata  EXEC  [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]  @KisiID= '".$params['kisiId']."' ;
                      
                        SELECT  
                            null AS OkulKullaniciID ,
                            null AS OkulID,
                            null AS KisiID,
                            -1 AS RolID, 
                            'LÜTFEN SEÇİNİZ...' AS OkulAdi,
                            '' AS MEBKodu,
                            '' AS ePosta,
                             null AS DersYiliID,
                            '' AS EgitimYilID, 
                            '' AS EgitimYili,
                            0 AS DonemID 

                        UNION  	 

                        SELECT  
                            sss.[OkulKullaniciID] ,
                            sss.[OkulID],
                            sss.[KisiID],
                            sss.[RolID], 
                            concat(oo.[OkulAdi], ' / (',rr.[RolAdi],')' ) as OkulAdi,
                            oo.[MEBKodu],
                            oo.[ePosta],
                            DY.DersYiliID,
                            DY.EgitimYilID, 
                            EY.EgitimYili,
                            DY.DonemID 
                    FROM #okimobilfirstdata sss
                    inner join [dbo].[GNL_Okullar] oo ON oo.[OkulID] = sss.[OkulID] 
                    inner join GNL_DersYillari DY ON DY.OkulID = sss.OkulID and DY.AktifMi =1 
                    inner join GNL_EgitimYillari EY ON EY.EgitimYilID = DY.EgitimYilID AND DY.AktifMi = 1
                    inner join [GNL_Roller] rr ON rr.[RolID] =  sss.[RolID];
		    SET NOCOUNT OFF;

                 "; 
            $statement = $pdo->prepare($sql);   
   //  echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
      /** 
     * @author Okan CIRAN
     * @ login olan userin menusunu dondurur  !!
     * @version v 1.0  27.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilMenu($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $parent=0;
            if ((isset($params['ParentID']) && $params['ParentID'] != "")) {           
                $parent = $params['ParentID'];               
            }
            
            $sql = "   
                   SELECT [ID]
                        ,[MenuID]
                        ,[ParentID]
                        ,[MenuAdi]
                        ,[Aciklama]
                        ,[URL]
                        ,[RolID]
                        ,[SubDivision] 
                        ,[ImageURL] 
                        ,[divid] 
                    FROM [dbo].[GNL_Mobil_Menuleri]
                    where active = 0 AND deleted = 0 AND 
                        [RolID] = ".intval($params['RolID'])."  
                       /* AND [ParentID] = ".intval($parent)." */
                    order by MenuID
                 "; 
            $statement = $pdo->prepare($sql);            
     //   echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /** 
     * @author Okan CIRAN
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKisiOkulListesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $sql = "   
                    SELECT DISTINCT  dbo.GNL_Okullar.OkulID, GNL_OKULLAR.OkulAdi   
                    FROM GNL_Kullanicilar 
                    INNER JOIN GNL_OkulKullanicilari ON GNL_Kullanicilar.KisiID = GNL_OkulKullanicilari.KisiID 
                    INNER JOIN GNL_OkulKullaniciRolleri ON GNL_OkulKullanicilari.OkulKullaniciID = GNL_OkulKullaniciRolleri.OkulKullaniciID
                    INNER JOIN GNL_ModulMenuleri ON GNL_OkulKullaniciRolleri.RolID IN (SELECT * FROM dbo.SPLIT(GNL_ModulMenuleri.Roller,','))
                    INNER JOIN GNL_Moduller ON GNL_Moduller.ModulID = GNL_ModulMenuleri.ModulID
                    INNER JOIN dbo.GNL_Okullar ON dbo.GNL_OkulKullanicilari.OkulID = dbo.GNL_Okullar.OkulID
                    WHERE GNL_Kullanicilar.KisiID ='".$params['kisiId']."' 
                    order by GNL_OKULLAR.OkulAdi  
                 "; 
            $statement = $pdo->prepare($sql);            
         //echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
  
     /** 
     * @author Okan CIRAN
     * @ login olan ogretmenin ders programı   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgrami($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $sql = "  
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmp') IS NOT NULL DROP TABLE #tmp; 
            CREATE TABLE #tmp (
 
		DersYiliID [uniqueidentifier] ,
		OkulID [uniqueidentifier]  ,
		DonemID  [int] ,
		TedrisatID [int],
		TakdirTesekkurHesapID  [int]    ,
		OnKayitTurID  [int]  ,
                EgitimYilID  [int]  ,
		Donem1BaslangicTarihi [datetime]  ,
		Donem1BitisTarihi [datetime]  ,
		Donem2BaslangicTarihi [datetime]  ,
		Donem2BitisTarihi [datetime]  ,
		Donem1AcikGun [decimal](18, 4)  ,
		Donem2AcikGun [decimal](18, 4)  ,
		YilSonuHesapla [bit] ,
		DevamsizliktanBasarisiz [bit]  ,
		SorumlulukSinavSayisi [tinyint],
		DevamsizlikSabahOgleAyri  [bit] ,
		YilSonuPuanYuvarlansin [bit],
                EgitimYili [varchar](50),
		OkulDurumPuani [decimal](18, 4),
		YilSonuNotYuvarlansin  [bit],
		YilSonuPuanSinavSonraYuvarlansin  [bit],
		YilSonuNotSinavSonraYuvarlansin  [bit],
		AktifMi [bit]    ); 

            INSERT  INTO #tmp
            EXEC dbo.[PRC_GNL_DersYili_Find] @OkulID = '".$params['OkulID']."'  
 
            SELECT  
                -1 AS HaftaGunu,
                -1 AS DersSirasi, 
                null AS SinifDersID ,
                null  AS DersAdi,
                null  AS DersKodu,
                null  AS SinifKodu,
                null  AS SubeGrupID,
                null  AS BaslangicSaati,
                null  AS BitisSaati,
                null  AS DersBaslangicBitisSaati,
                null  AS SinifOgretmenID,
                null  AS DersHavuzuID,
                null  AS SinifID,
                null  AS DersID, 
                null  AS Aciklama1,
                'LÜTFEN SEÇİNİZ...' AS Aciklama,
                null  AS DersYiliID,
                null  AS DonemID, 
                null  AS EgitimYilID  

            union  

            (SELECT 
                DP.HaftaGunu,
		DP.DersSirasi,
		DP.SinifDersID,
		DRS.DersAdi, 
		DH.DersKodu, 
		SNF.SinifKodu,
		SNF.SubeGrupID,
		DS.BaslangicSaati,
		DS.BitisSaati,
		dbo.GetFormattedTime(BaslangicSaati, 1) + ' - ' + dbo.GetFormattedTime(BitisSaati, 1) AS DersBaslangicBitisSaati,
		SO.SinifOgretmenID,
		DH.DersHavuzuID,
		SNF.SinifID,
		DRS.DersID,
		(CASE WHEN ISNULL(DS.BaslangicSaati,'')<>'' AND ISNULL(DS.BitisSaati,'')<>'' THEN
				CAST(DS.DersSirasi AS NVARCHAR(2)) + '. ' + 
				DRS.DersAdi + ' (' + 
				CONVERT(VARCHAR(5),DS.BaslangicSaati,108) + '-' + CONVERT(VARCHAR(5),DS.BitisSaati,108) + ')'
			 ELSE
				CAST(DP.DersSirasi AS NVARCHAR(2)) + '. ' + DRS.DersAdi
			 END) AS Aciklama1 ,
                         concat(SNF.SinifKodu,' - ', DRS.DersAdi ) as Aciklama,   
			 #tmp.DersYiliID,
			 #tmp.DonemID,
			 #tmp.EgitimYilID
            FROM GNL_DersProgramlari DP
		INNER JOIN GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
            INNER JOIN GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
							AND SO.OgretmenID = '".$params['kisiId']."'
		INNER JOIN GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID --AND SNF.DersYiliID = @DersYiliID
		INNER JOIN GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
		INNER JOIN GNL_Dersler DRS ON DH.DersID = DRS.DersID
		LEFT JOIN  GNL_DersSaatleri DS ON DS.DersYiliID = SNF.DersYiliID AND DS.SubeGrupID = SNF.SubeGrupID AND DS.DersSirasi = DP.DersSirasi
		inner join #tmp on #tmp.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmp.DonemID 
            ) ORDER BY HaftaGunu, BaslangicSaati,DersSirasi, DersAdi ;  

            SET NOCOUNT OFF;

                 "; 
            $statement = $pdo->prepare($sql);   
    // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
     
    /** 
     * @author Okan CIRAN
     * @ login olan ogretmenin ders saatleri   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgramiDersSaatleri($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $sql = "  
            SET NOCOUNT ON;   
            IF OBJECT_ID('tempdb..#ogretmenDersSaatleri') IS NOT NULL DROP TABLE #ogretmenDersSaatleri; 
    
            CREATE TABLE #ogretmenDersSaatleri (
                    BaslangicSaati datetime, 
                    BitisSaati datetime,
                    DersSirasi integer, 
                    DersAdi varchar(100), 
                    DersKodu varchar(100),
                    Aciklama varchar(100),
                    DersID [uniqueidentifier] ,
                    HaftaGunu integer 
                            ) ; 
							 
            INSERT #ogretmenDersSaatleri      exec dbo.PRC_GNL_DersProgrami_Find_forOgretmenDersSaatleri 
                    @OgretmenID='".$params['kisiId']."',
                    @SinifID='".$params['sinifID']."',
                    @Tarih='".$params['tarih']."' ;  
                        
            SELECT     
                null as BaslangicSaati , 
                null as BitisSaati ,
                null as DersSirasi , 
                null as DersAdi , 
                null as DersKodu ,
                'LÜTFEN SEÇİNİZ...' as Aciklama,
                null as DersID ,
                -1 as HaftaGunu 

            UNION 
 
            SELECT  
                sss.BaslangicSaati , 
                sss.BitisSaati ,
                sss.DersSirasi , 
                sss.DersAdi , 
                sss.DersKodu ,
                sss.Aciklama,
                sss.DersID ,
                sss.HaftaGunu 
            FROM #ogretmenDersSaatleri sss;
 
            SET NOCOUNT OFF;  
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /** 
     * @author Okan CIRAN
     * @ login olan ogretmenin ders saatlerindeki sınıflardaki ögrenci listesi   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersPrgDersSaatleriOgrencileri($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
            /*
            exec dbo.PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                @SinifID='F4201B97-B073-4DD7-8891-8091C3DC82CF',
                @Tarih='2017-09-29 00:00:00',
                @DersSirasi=1,
                @DersYiliID='fc4675fc-dafb-4af6-a3c2-7acd22622039',
                @OgretmenID='17A68CAA-1A13-460A-BEAA-FB483AC82F7B' 
             
             */ 
            
            $sql = "  
            SET NOCOUNT ON;   
            IF OBJECT_ID('tempdb..#tmpe') IS NOT NULL DROP TABLE #tmpe; 
            CREATE TABLE #tmpe ( 
				OgrenciID [uniqueidentifier] ,
				Tarih [datetime]  ,
				DersSirasi  [int] ,
				DersYiliID [uniqueidentifier],
				Numarasi  [int]  , 
				Adi [varchar](50),
				Soyadi [varchar](50),  
				TCKimlikNo  [varchar](50) , 
				CinsiyetID  [int]  ,
				DevamsizlikKodID [int] , 
				Aciklama [varchar](200)  
		    );  
		 
                INSERT  INTO #tmpe 
                exec dbo.PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                    @SinifID='".$params['sinifID']."',
                    @Tarih='".$params['tarih']."' ,
                    @DersSirasi='".$params['dersSirasi']."',
                    @DersYiliID='".$params['dersYiliID']."', 
                    @OgretmenID='".$params['kisiId']."'  ;  
                        

                SELECT 
                    tt.OgrenciID,
                    tt.Tarih,
                    tt.Numarasi  ,   
                    UPPER(concat(tt.Adi , ' ', tt.Soyadi)) AS adsoyad ,
                    tt.CinsiyetID ,
                    tt.DevamsizlikKodID,
                    tt.Aciklama,
                    tt.DersSirasi,
                    tt.DersYiliID,
                    ff.Fotograf
                FROM #tmpe  tt
                LEFT JOIN GNL_Fotograflar ff on ff.KisiID =tt.OgrenciID ; 

            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    
    /** 
     * @author Okan CIRAN
     * @ login olan ogretmenin velilerle olan randevu listesi.  !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenVeliRandevulari($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
           
            $sql = "  
            SET NOCOUNT ON;   

            EXEC [dbo].[PRC_VLG_VeliRandevu_FindByOgretmenID]
		  @OgretmenID='".$params['kisiId']."' ; 

            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
  
      /**   
     * @author Okan CIRAN
     * @ devamsızlık  kayıt  !!
     * @version v 1.0  05.10.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertDevamsizlik($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();

            $OgretmenID = '-1';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = '-2';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SinifID = NULL;
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DersID = NULL;
            if ((isset($params['DersID']) && $params['DersID'] != "")) {
                $DersID = $params['DersID'];
            }
            $SinifDersID = NULL;
            if ((isset($params['SinifDersID']) && $params['SinifDersID'] != "")) {
                $SinifDersID = $params['SinifDersID'];
            }
            $DersSirasi = NULL;
            if ((isset($params['DersSirasi']) && $params['DersSirasi'] != "")) {
                $DersSirasi = $params['DersSirasi'];
            } 
            $DonemID = NULL;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            } 
            $OkulOgretmenID = NULL;
            if ((isset($params['OkulOgretmenID']) && $params['OkulOgretmenID'] != "")) {
                $OkulOgretmenID = $params['OkulOgretmenID'];
            } 
            $Tarih = NULL;
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $Tarih = $params['Tarih'];
            } 
            $XmlData = NULL;
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $XmlData = $params['XmlData'];
            } 
          
             
            $sql = " 
                 

                exec dbo.PRC_GNL_OgrenciDevamsizlikSaatleri_SaveXML 
                    @DersYiliID='" . $OgretmenID . "',
                    @Tarih='" . $Tarih . "', 
                    @DersSirasi=" . intval($DersSirasi) . " ,
                    @XmlData= '" . $XmlData . "',
                    @SinifDersID='" . $SinifDersID . "' ; 

    
                exec PRC_GNL_SaveOgretmenDevamsizlikGirisiLog 
                    @OgretmenID= '" . $OgretmenID . "',
                    @DersYiliID= '" . $DersYiliID . "',
                    @SinifID='" . $SinifID . "',
                    @DersID= '" . $DersID . "',
                    @DersSirasi=" . intval($DersSirasi) . " ; 
                 
 
                exec PRC_GNL_OgretmenDevamKontrol_Save 
                    @OgretmenID='" . $OgretmenID . "', 
                    @Tarih='" . $Tarih . "',
                    @DersSirasi=" . intval($DersSirasi) . ",
                    @SinifDersID='" . $SinifDersID . "',
                    @DonemID=" . intval($DersSirasi) . " ; 

                exec PRC_GNL_SinifDevamsizlikKayitlari_Save 
                    @OkulOgretmenID='" . $OkulOgretmenID . "',
                    @SinifID='" . $SinifID . "',
                    @YoklamaTarihi='" . date("Y-m-d H:i:s") . "',
                    @KayitTarihi='" . date("Y-m-d H:i:s") . "';
 
                    ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $result = $statement->execute();
            $insertID =1;
            $errorInfo = $statement->errorInfo(); 
          
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            $pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * 
     * @author Okan CIRAN
     * @   tablosundan public key i döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getPK($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
      
            /**
             * @version kapatılmıs olan kısımdaki public key algoritması kullanılmıyor.
             */
            /*      $sql = "          
            SELECT                
                REPLACE(REPLACE(ARMOR(pgp_sym_encrypt(a.sf_private_key_value, 'Bahram Lotfi Sadigh', 'compress-algo=1, cipher-algo=bf'))
	,'-----BEGIN PGP MESSAGE-----

',''),'
-----END PGP MESSAGE-----
','') as public_key1     ,

                substring(ARMOR(pgp_sym_encrypt(a.sf_private_key_value, 'Bahram Lotfi Sadigh', 'compress-algo=1, cipher-algo=bf')),30,length( trim( sf_private_key))-62) as public_key2, 
        */      
            ///crypt(:password, gen_salt('bf', 8)); örnek bf komut
                  $sql = "   
                        
                SELECT       
                     REPLACE(TRIM(SUBSTRING(crypt(sf_private_key_value,gen_salt('xdes')),6,20)),'/','*') AS public_key 
                FROM info_users a              
                INNER JOIN sys_acl_roles sar ON sar.id = a.role_id AND sar.active=0 AND sar.deleted=0 
                WHERE a.username = :username 
                    AND a.password = :password   
                    AND a.deleted = 0 
                    AND a.active = 0 
                
                                 ";

            $statement = $pdo->prepare($sql);
            $statement->bindValue(':username', $params['username'], \PDO::PARAM_STR);
            $statement->bindValue(':password', $params['password'], \PDO::PARAM_STR);
          //  echo debugPDO($sql, $parameters);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {      
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    
    
       /** 
     * @author Okan CIRAN
     * @ login olan veli / yakın ın ögrenci listesi   !!
     * @version v 1.0  09.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function veliOgrencileri($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
             
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#ogrenciIdBul') IS NOT NULL DROP TABLE #ogrenciIdBul; 
    
            CREATE TABLE #ogrenciIdBul
                (
                        OgrenciID  [uniqueidentifier]  
                ) ;

            INSERT #ogrenciIdBul exec PRC_GNL_OgrenciYakinToOgrenciID_Find @YakinID='".$params['kisiId']."' ; 
            
            SELECT * FROM ( 
                SELECT 
                    NULL AS OgrenciID,
                    NULL AS SinifID,
                    NULL AS DersYiliID,
                    NULL AS SinifKodu,
                    NULL AS SinifAdi, 
                    NULL AS Numarasi, 
                    NULL AS OgrenciOkulBilgiID,
                    NULL AS KisiID,
                    NULL AS CinsiyetID,
                    NULL AS Adi,
                    NULL AS Soyadi,
                    'LÜTFEN SEÇİNİZ...' AS Adi_Soyadi,
                    NULL AS TCKimlikNo,
                    NULL AS ePosta, 
                    NULL AS OkulID,
                    NULL AS OgrenciSeviyeID,
                    NULL AS Fotograf
                UNION
                SELECT 
                    GOS.[OgrenciID],
                    SINIF.SinifID,
                    SINIF.DersYiliID,
                    SINIF.SinifKodu,
                    SINIF.SinifAdi, 
                    OOB.[Numarasi], 
                    OOB.OgrenciOkulBilgiID,
                    KISI.[KisiID],
                    KISI.[CinsiyetID],
                    KISI.[Adi],
                    KISI.[Soyadi],
                    KISI.[Adi] + ' ' + KISI.[Soyadi] AS Adi_Soyadi,
                    KISI.[TCKimlikNo],
                    KISI.[ePosta], 
                    DY.OkulID,
                    GOS.[OgrenciSeviyeID],
                    fo.[Fotograf]		
                FROM 
                        GNL_OgrenciSeviyeleri GOS
                INNER JOIN 
                        GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID)
                INNER JOIN 
                        GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID)
                INNER JOIN 
                        GNL_Siniflar SINIF ON (SINIF.SinifID = GOS.SinifID)
                INNER JOIN 
                        GNL_DersYillari DY ON DY.DersYiliID = SINIF.DersYiliID
                INNER JOIN 
                        GNL_OgrenciOkulBilgileri OOB ON OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID 
                LEFT JOIN GNL_Fotograflar fo on fo.KisiID = GOS.OgrenciID
                WHERE 
                        GOS.OgrenciID in (SELECT distinct OgrenciID FROM #ogrenciIdBul) 
                AND 
                        SINIF.DersYiliID ='".$params['dersYiliID']."'
            ) as assss 
            ORDER BY Numarasi; 

            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
  
       
    /** 
     * @author Okan CIRAN
     * @ login olan veli / yakın ın ögrenci listesi   !!
     * @version v 1.0  09.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciDevamsizlikListesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
             
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#ogrenciIdDevamsizlikTarih') IS NOT NULL DROP TABLE #ogrenciIdDevamsizlikTarih; 
    
                CREATE TABLE #ogrenciIdDevamsizlikTarih
                (    
                    OgrenciDevamsizlikID [uniqueidentifier] ,  
                    DersYiliID  [uniqueidentifier] ,    
                    OgrenciID [uniqueidentifier] ,    
                    DevamsizlikKodID int ,   
                    DevamsizlikPeriyodID int ,  
                    Tarih datetime ,   
                    Aciklama varchar(100),  
                    rownum int    
                ) ;
 
                INSERT INTO #ogrenciIdDevamsizlikTarih  (OgrenciDevamsizlikID, 
                             DersYiliID, OgrenciID,
                             DevamsizlikKodID,  DevamsizlikPeriyodID,  
                             Tarih,  Aciklama,rownum )

                SELECT 
                             OgrenciDevamsizlikID, 
                             DersYiliID,  
                             OgrenciID,
                             DevamsizlikKodID, 
                             DevamsizlikPeriyodID,  
                             Tarih, 
                             Aciklama, 
                             ROW_NUMBER() OVER(ORDER BY Tarih) AS rownum 
                 FROM GNL_OgrenciDevamsizliklari 
                     WHERE 
                             DersYiliID = '".$params['dersYiliID']."' AND 
                             OgrenciID ='".$params['kisiId']."'; 
                
                SELECT 
                    tt.OgrenciDevamsizlikID, 
                    tt.DersYiliID,  
                    tt.OgrenciID,
                    
                    tt.DevamsizlikPeriyodID,  
                    cast(tt.Tarih as date) as Tarih, 
                    tt.Aciklama, 
                    tt.rownum ,
                    concat(cast(tt.DevamsizlikKodID as varchar(2)),' - ', dd.DevamsizlikAdi) as DevamsizlikAdi,
                    cast(cast(dd.GunKarsiligi as numeric(10,2)) as varchar(5)) as GunKarsiligi
                FROM #ogrenciIdDevamsizlikTarih tt
                LEFT JOIN [dbo].[GNL_DevamsizlikKodlari] dd on dd.DevamsizlikKodID = tt.DevamsizlikKodID;
 

                SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
  
    
    /** 
     * @author Okan CIRAN
     * @ login olan kurum yöneticileri için şube listesi   !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumyoneticisisubelistesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
            $DersYiliID = '0F17DCF7-CCCC-CCCC-CCCC-D4CCFF77E487';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $sql = "  
            SET NOCOUNT ON;    
            SELECT * FROM ( 
                SELECT     
                    null as SinifID,
                    null as DersYiliID,
                    -1 as SeviyeID,
                    '-1' as SinifKodu,
                    null as SinifAdi,
                    null as Sanal,
                    null as SubeGrupID,
                    null as SeviyeKodu,
                    null as SinifOgretmeni,
                    null as MudurYardimcisi,
                    'LÜTFEN SEÇİNİZ...' as Aciklama 

            UNION  
               
                SELECT 
                    S.SinifID,
                    S.DersYiliID,
                    S.SeviyeID,
                    S.SinifKodu,
                    S.SinifAdi,
                    S.Sanal,
                    S.SubeGrupID,
                    SEV.SeviyeKodu,
                    concat( gks.Adi,' ',gks.Soyadi ) As SinifOgretmeni,
                    concat(gkm.Adi,' ',gkm.Soyadi ) As MudurYardimcisi,
                    concat(S.SinifAdi ,' - ', gks.Adi+' '+gks.Soyadi )  as Aciklama
                FROM GNL_Siniflar S
                INNER JOIN GNL_Seviyeler SEV ON S.SeviyeID = SEV.SeviyeID
                LEFT JOIN GNL_SinifOgretmenleri SO ON (S.SinifID = SO.SinifID AND SO.OgretmenTurID=1)
                LEFT JOIN GNL_SinifOgretmenleri MY ON (S.SinifID = MY.SinifID AND MY.OgretmenTurID=2)
                LEFT JOIN GNL_Kisiler gks on gks.KisiID=SO.OgretmenID 
                LEFT JOIN GNL_Kisiler gkm on gkm.KisiID=MY.OgretmenID
                WHERE S.DersYiliID = '".$DersYiliID."'
                AND S.Sanal < (CASE WHEN 1 = 0 THEN 2 ELSE 1 END)
                 ) as fdsa
                ORDER BY SeviyeID, SinifKodu;
 
            SET NOCOUNT OFF;   
                 "; 
            $statement = $pdo->prepare($sql);   
        // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
      
    /** 
     * @author Okan CIRAN
     * @ login olan kurum yöneticisinin sectiği subedeki ögrencilistesi  !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kysubeogrencilistesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
           $SinifID =  'CCCC3986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $sql = "  
             SET NOCOUNT ON; 
                SELECT 
                    NULL AS OgrenciSeviyeID, 
                    NULL AS OgrenciID, 
                    NULL AS SinifID, 
                    NULL AS OgrenciArsivTurID,  
                    NULL AS OgrenciID,  
                    NULL AS Numarasi,  
                    NULL AS KisiID, 
                    NULL AS CinsiyetID, 
                    NULL AS Adi, 
                    NULL AS Soyadi, 
                    NULL AS TCKimlikNo, 
                    NULL AS ePosta, 
                    NULL AS Yasamiyor, 	
                    NULL AS OdendiMi,  	
                    NULL AS SeviyeID ,
                    NULL AS Fotograf,
                    'LÜTFEN SEÇİNİZ' as Aciklama

                UNION

                SELECT 
                    GOS.[OgrenciSeviyeID], 
                    GOS.[OgrenciID], 
                    GOS.[SinifID], 
                    GOS.[OgrenciArsivTurID], 
                    OGR.[OgrenciID], 
                    OOB.[Numarasi],
                    KISI.[KisiID], 
                    KISI.[CinsiyetID], 
                    KISI.[Adi], 
                    KISI.[Soyadi], 
                    KISI.[TCKimlikNo], 
                    KISI.[ePosta], 
                    KISI.[Yasamiyor], 	
                    dbo.FNC_GNL_AdayKayitUcretOdendiMi(GOS.[OgrenciID],DY.DersYiliID) AS OdendiMi,  	
                    S.[SeviyeID] ,
                    ff.Fotograf,
                    concat(KISI.[Adi], ' ', KISI.[Soyadi]) as Aciklama
                    /* --	GOS.[DavranisNotu1], 
                    --	GOS.[DavranisNotu2], 
                    --	GOS.[DavranisPuani],                     
                    --	GOS.[OzursuzDevamsiz1], 
                    --	GOS.[OzursuzDevamsiz2], 
                    --	GOS.[OzurluDevamsiz1], 
                    --	GOS.[OzurluDevamsiz2], 
                    --	GOS.[YapilanSosyalEtkinlikSaati], 
                    --	GOS.[SosyalEtkinlikTamamlandi], 
                    --	GOS.[KayitYenileme], 
                    --	GOS.[KayitYenilemeAciklamasi], 
                    --	GOS.[YetistirmeKursu], 
                    --	GOS.[YetistirmeKursuAciklamasi], 
                    --	GOS.[Yatili], 
                    --	GOS.[Gunduzlu], 
                    --	GOS.[Parali], 
                    --	GOS.[Yemekli], 
                    --	GOS.[Burslu], 
                    --	GOS.[BursOrani], 
                    --	GOS.[KimlikParasi], 
                    --	GOS.[SeviyedeOkulaKayitli], 
                    -- GOS.[OgrenciArsivTurID], 
                    --	OOB.[YabanciDilID], 
                    --	OOB.[KayitTarihi], 
                    --	OOB.[IkinciYabanciDilID], 
                    */
                    FROM GNL_OgrenciSeviyeleri GOS 
                    INNER JOIN GNL_Siniflar S ON S.SinifID = GOS.SinifID  
                    INNER JOIN GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID) 
                    INNER JOIN GNL_DersYillari DY ON (DY.DersYiliID = S.DersYiliID)  
                    INNER JOIN GNL_OgrenciOkulBilgileri OOB ON (OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID)  
                    INNER JOIN GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID) 
                    LEFT JOIN GNL_Fotograflar ff on ff.KisiID =GOS.OgrenciID
                    WHERE  
                            GOS.SinifID = Cast('".$SinifID."' AS nvarchar(39)) AND
                            GOS.OgrenciArsivTurID =  cast(1 AS nvarchar(2))
                            ; 

            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
        // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    
     /** 
     * @author Okan CIRAN
     * @ login olan kurum yöneticisinin sectiği subedeki ögrencilistesi  !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kySubeOgrenciDersListesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            
           $OgrenciSeviyeID =  'CCCCCCC1-CCC2-CCC3-CCC4-CCCCCCCCCCC5';
            if ((isset($params['OgrenciSeviyeID']) && $params['OgrenciSeviyeID'] != "")) {
                $OgrenciSeviyeID = $params['OgrenciSeviyeID'];
            }
            $sql = "  
            SET NOCOUNT ON; 
            SELECT  OgrenciID ,
                OgrenciSeviyeID ,
                DersHavuzuID ,
                Numarasi ,
                Adi ,
                Soyadi ,
                ( Adi + ' ' + Soyadi ) AS AdiSoyadi ,
                DersKodu ,
                DersAdi ,
                DonemID ,
                Donem1_DonemNotu ,
                Donem2_DonemNotu ,
                PuanOrtalamasi ,
                Donem1_PuanOrtalamasi ,
                Donem2_PuanOrtalamasi ,
                Donem1_DonemNotu AS AktifDonemNotu ,
                YetistirmeKursuNotu ,
                YilSonuNotu ,
                YilSonuPuani , 
                YilsonuToplamAgirligi , 
                OdevAldi ,
                ProjeAldi ,
                OgrenciDersID ,
                OgrenciDonemNotID ,  
                PuanOrtalamasi ,
                Hesaplandi ,
                KanaatNotu ,
                Sira ,
                EgitimYilID ,
                HaftalikDersSaati ,
                Perf1OdevAldi ,
                Perf2OdevAldi ,
                Perf3OdevAldi ,
                Perf4OdevAldi ,
                Perf5OdevAldi ,
                AltDers ,
                YillikProjeAldi ,
                YetistirmeKursunaGirecek ,
                concat(DersOgretmenAdi ,' ', DersOgretmenSoyadi) as  OgretmenAdiSoyadi,
                isPuanNotGirilsin ,
                isPuanNotHesapDahil ,
                AgirlikliYilSonuNotu ,
                AgirlikliYilsonuPuani ,
                PBYCOrtalama, 
                DersSabitID 
                
        FROM    ( SELECT    
                    YetistirmeKursuNotu ,
                    YilSonuNotu ,
                    YilSonuPuani ,
                    YilsonuToplamAgirligi ,
                    PuanOrtalamasi ,
                    PuanOrtalamasi AS Donem1_PuanOrtalamasi ,
                    Donem2_PuanOrtalamasi ,
                    Hesaplandi ,
                    ProjeAldi ,
                    SinifID ,
                    ODNB.DersHavuzuID ,
                    ODNB.OgrenciSeviyeID ,
                    ODNB.OgrenciDersID ,
                    OgrenciDonemNotID ,
                    Puan ,
                    SinavTanimID ,
                    Donem1_DonemNotu ,
                    OdevAldi ,
                    KanaatNotu ,
                    Donem2_DonemNotu ,
                    Numarasi ,
                    OgrenciID ,
                    Adi ,
                    Soyadi ,
                    DersKodu ,
                    DersAdi ,
                    DonemID ,
                    Sira ,
                    EgitimYilID ,
                    HaftalikDersSaati ,
                    Perf1OdevAldi ,
                    Perf2OdevAldi ,
                    Perf3OdevAldi ,
                    Perf4OdevAldi ,
                    Perf5OdevAldi ,
                    AltDers ,
                    ODNB.YillikProjeAldi ,
                    YetistirmeKursunaGirecek ,
                    DersSirasi = ISNULL(( SELECT    Sira
                                          FROM      GNL_SinifDersleri SD
                                          WHERE     SD.SinifID = ODNB.SinifID
                                                    AND SD.DersHavuzuID = ODNB.DersHavuzuID
                                        ), 999) ,
                    DersOgretmenAdi ,
                    DersOgretmenSoyadi ,
                    isPuanNotGirilsin ,
                    isPuanNotHesapDahil ,
                    AgirlikliYilSonuNotu ,
                    AgirlikliYilsonuPuani ,
                    PBYCOrtalama, 
                    DersSabitID 		 
                FROM OgrenciDersNotBilgileri_Donem1 ODNB
                LEFT JOIN dbo.GNL_OgrenciDersGruplari ODG ON ODG.OgrenciDersID = ODNB.OgrenciDersID
                LEFT JOIN dbo.GNL_OgrenciDersGrupTanimlari ODGT ON 
                            ODGT.OgrenciDersGrupTanimID=ODG.OgrenciDersGrupTanimID AND 
                            ODG.OgrenciDersID = ODNB.OgrenciDersID  			  
                WHERE isPuanNotGirilsin = 1 
				                  ) p PIVOT
	( MAX(Puan) FOR SinavTanimID IN ( [1], [2], [3], [4], [5], [6], [7], [8],
                                      [9], [10], [11], [12], [13], [14], [15],
                                      [19], [20], [21], [35], [36], [37], [38],
                                      [39], [41], [42], [43], [44], [45] ) ) 
	AS pvt
        WHERE OgrenciSeviyeID = '".$OgrenciSeviyeID."' AND 
                AltDers = 0   
            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
        // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /** 
     * @author Okan CIRAN
     * @ login olan ögretmenin sectiği subedeki ögrencilistesi  !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmensinavlistesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
           
            $OgretmenID =  'CCC13986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCC23986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCC33986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            $OkulOgretmenID = 'CCC33986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }   
             
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogrsinavlari') IS NOT NULL DROP TABLE #okiogrsinavlari; 

            CREATE TABLE #okiogrsinavlari
                            (
                            /* OgretmenID [uniqueidentifier], */ 
                            SinavID [uniqueidentifier], 
                            OkulID [uniqueidentifier], 
                            OkulOgretmenID [uniqueidentifier],
                            SinavTurID int,	
                            SeviyeID int,
                            SinavUygulamaSekliID int,
                            KitapcikTurID int,
                            SinavKodu varchar(100),
                            SinavAciklamasi varchar(100),
                            SinavTarihi datetime,
                            SinavBitisTarihi datetime, 
                            SinavSuresi int, 
                            KitapcikSayisi int, 
                            DogruSilenYanlisSayisi int, 
                            PuanlarYuvarlansinMi int, 
                            OrtalamaVeSapmaHesaplansinMi int, 
                            SiralamadaYasKontroluYapilsinMi int, 	
                            isDegerlendirildi int,
                            isAlistirma int,
                            OptikFormGirisiYapilabilirMi int,
                            isOtherTeachers int,
                            isUserExam int,
                            isOgrenciVeliSinavVisible int,
                            isAltKurumHidden int,
                            sonBasilabilirOnayTarihi datetime,
                            SinavTurAdi varchar(100) ,
                            SeviyeKodu varchar(10) ,
                            NotDonemID int,
                            SinavTanimID int, 
                            isNotAktarildi bit 
                                                ) ;

                    INSERT #okiogrsinavlari EXEC [dbo].[PRC_SNV_Sinavlar_FindForOgretmen]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        gd.[Donem] , 
                        SinavTarihi ,
                        SinavBitisTarihi , 
                        SinavTurAdi  ,
                        SinavKodu ,
                        SinavAciklamasi  
                    /*
                        SinavTurID ,	
                        SeviyeID ,
                        SinavUygulamaSekliID ,
                        KitapcikTurID ,
                        SinavSuresi , 
                        KitapcikSayisi , 
                        DogruSilenYanlisSayisi , 
                        PuanlarYuvarlansinMi , 
                        OrtalamaVeSapmaHesaplansinMi , 
                        SiralamadaYasKontroluYapilsinMi , 	
                        isDegerlendirildi ,
                        isAlistirma ,
                        OptikFormGirisiYapilabilirMi ,
                        isOtherTeachers ,
                        isUserExam ,
                        isOgrenciVeliSinavVisible ,
                        isAltKurumHidden ,
                        sonBasilabilirOnayTarihi ,
                        SeviyeKodu  ,
                        SinavTanimID , 
                        isNotAktarildi  ,
                        OgretmenID  ,
                        SinavID ,  
                        OkulID , 
                        OkulOgretmenID 
                    */
                    from #okiogrsinavlari a 
                    inner join [dbo].[GNL_Donemler] gd on gd.DonemID = a.NotDonemID 
 
            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
         // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /** 
     * @author Okan CIRAN
     * @ login olan ögretmenin sectiği subedeki ögrencilistesi  !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function yakinisinavlistesi($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
           
            $OgretmenID =  'CCC13986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCC23986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCC33986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            
            $OkulOgretmenID = 'CCC33986-CCCC-CCCC-CCCC-CCC8E61A6F39';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }   
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiyakinsinavlari') IS NOT NULL DROP TABLE #okiyakinsinavlari; 

            CREATE TABLE #okiyakinsinavlari
                            ( 
                            SinavID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OkulOgretmenID [uniqueidentifier],
                            SinavTurID int,	
                            SeviyeID int,
                            SinavUygulamaSekliID int,
                            KitapcikTurID int,
                            SinavKodu varchar(100),
                            SinavAciklamasi varchar(100),
                            SinavTarihi datetime,
                            SinavBitisTarihi datetime,    
                            SinavSuresi int, 
                            KitapcikSayisi int, 
                            DogruSilenYanlisSayisi int, 
                            PuanlarYuvarlansinMi int, 
                            OrtalamaVeSapmaHesaplansinMi int, 
                            SiralamadaYasKontroluYapilsinMi int, 
                            isDegerlendirildi int,
                            isAlistirma int,
                            OptikFormGirisiYapilabilirMi int,
                            isOtherTeachers int,
                            isUserExam int,
                            isOgrenciVeliSinavVisible int,
                            isAltKurumHidden int,
                            sonBasilabilirOnayTarihi datetime, 
                            SinavTurAdi varchar(100) ,
                            SeviyeKodu varchar(10) ,
                             NotDonemID int,
                            SinavTanimID int,      
                            isNotAktarildi bit,
                            SinavOgrenciID [uniqueidentifier]
                                                ) ;

                    INSERT #okiyakinsinavlari EXEC [dbo].[PRC_SNV_Sinavlar_FindForOgrenci]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        gd.[Donem] , 
                        SinavTarihi ,
                        SinavBitisTarihi , 
                        SinavTurAdi  ,
                        SinavKodu ,
                        SinavAciklamasi  
                    /*
                        SinavTurID ,	
                        SeviyeID ,
                        SinavUygulamaSekliID ,
                        KitapcikTurID ,
                        SinavSuresi , 
                        KitapcikSayisi , 
                        DogruSilenYanlisSayisi , 
                        PuanlarYuvarlansinMi , 
                        OrtalamaVeSapmaHesaplansinMi , 
                        SiralamadaYasKontroluYapilsinMi , 	
                        isDegerlendirildi ,
                        isAlistirma ,
                        OptikFormGirisiYapilabilirMi ,
                        isOtherTeachers ,
                        isUserExam ,
                        isOgrenciVeliSinavVisible ,
                        isAltKurumHidden ,
                        sonBasilabilirOnayTarihi ,
                        SeviyeKodu  ,
                        SinavTanimID , 
                        isNotAktarildi  ,
                        OgretmenID  ,
                        SinavID ,  
                        OkulID , 
                        OkulOgretmenID ,
                        SinavOgrenciID
                    */
                    from #okiyakinsinavlari a 
                    inner join [dbo].[GNL_Donemler] gd on gd.DonemID = a.NotDonemID ;
 
            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
       // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /** 
     * @author Okan CIRAN
     * @ login olan ogretmenin velilerle olan randevu listesi.  !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function findByOkulOgretmenID($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
             $OkulID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
             $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
           
            $sql = "  
            SET NOCOUNT ON;    	 
            IF OBJECT_ID('tempdb..#okiOkulOgretmenID') IS NOT NULL DROP TABLE #okiOkulOgretmenID; 

            CREATE TABLE #okiOkulOgretmenID
                            (
                            OkulOgretmenID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OgretmenID [uniqueidentifier]   ) ;

            INSERT #okiOkulOgretmenID EXEC PRC_OGT_OkulOgretmen_FindByOkulOgretmenID 
                @OkulID= '".$OkulID."',
                @OgretmenID=  '".$OgretmenID."' ; 

            SELECT *,   
            (CASE WHEN (1 = 1) THEN 1 ELSE 0 END)  as control
            from #okiOkulOgretmenID ;  
            SET NOCOUNT OFF;  
                 "; 
            $statement = $pdo->prepare($sql);   
            // echo debugPDO($sql, $params);
            $statement->execute();
           
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
  
  
}
