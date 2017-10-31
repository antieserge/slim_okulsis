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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            }  
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig($params);
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $tc = '011111111110';
            if ((isset($params['tc']) && $params['tc'] != "")) {
                $tc = $params['tc'];
            } 
            
          /*  $sql = "          
                exec PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo=  '".$tc."'
                 ";
            */ 
            
            $sql = "  
            SET NOCOUNT ON;     
            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okiMEBKodu".$tc."') IS NOT NULL DROP TABLE ##okiMEBKodu".$tc."; 
            DECLARE @name nvarchar(200);
            declare @database_id int;
            declare @tc nvarchar(11);
            DECLARE @sqlx nvarchar(2000); 
            DECLARE @sqlxx nvarchar(2000);
            declare @MEBKodu int;   
            set @tc =  ".$tc."; 
            CREATE TABLE #okidbname".$tc." (database_id int , name  nvarchar(200) , sqlx nvarchar(2000),MEBKodu int ); 
            CREATE TABLE ##okiMEBKodu".$tc." ( MEBKodu int ); 

            
            
            DECLARE db_cursor CURSOR FOR  
            SELECT database_id, name FROM Sys.databases 
            WHERE state = 0
                AND name NOT IN ('master','model','msdb','tempdb', 'TEYDEB_GELISTIRME_V5','BILSANET_MOBILE','BILSANET_PRODUCTION','BILSANET_DEVELOPMENT')  
                AND name NOT like ( '%ReportServer%' )  
                AND Left(name, 3) != 'RS_'
                AND (lower(name) like '%Bilsa%' or lower(name) like '%BILSA%');

            OPEN db_cursor   
            FETCH NEXT FROM db_cursor INTO  @database_id , @name 
            WHILE @@FETCH_STATUS = 0   
            BEGIN   

            INSERT INTO #okidbname".$tc." ( database_id , name , sqlx ) VALUES
                            (@database_id, CAST(@name AS nvarchar(200)) ,   'select '+ cast(@database_id as varchar(10))+'; exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+@tc  );


            SET @sqlxx = ' INSERT ##okiMEBKodu".$tc."  exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+@tc ; 
            EXEC sp_executesql @sqlxx; 

            update  #okidbname".$tc." 
                set MEBKodu = (select * from ##okiMEBKodu".$tc.")
            where database_id =  @database_id;
            
            delete from  ##okiMEBKodu".$tc." ; 
        
            FETCH NEXT FROM db_cursor INTO @database_id,  @name;
            END   

            CLOSE db_cursor;
            DEALLOCATE db_cursor ;

            select top 1 database_id , name , MEBKodu  from #okidbname".$tc." 
            where MEBKodu is not null  ;  

            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc."; 
            IF OBJECT_ID('tempdb..##okiMEBKodu".$tc."') IS NOT NULL DROP TABLE ##okiMEBKodu".$tc."; 
            SET NOCOUNT OFF;
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
     * @ login için user id döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKullaniciFindForLoginByTcKimlikNo($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $mebKoduValue = NULL;
            $dbnameValue = NULL;
            $mebKodu = $this->gnlKullaniciMebKoduFindByTcKimlikNo(array('tc' => $params['tc']));
            if ((isset($mebKodu['resultSet'][0]['MEBKodu']) && $mebKodu['resultSet'][0]['MEBKodu'] != "")) {                                    
                        $mebKoduValue = $mebKodu['resultSet'][0]['MEBKodu'];
                        $dbnameValue = $mebKodu['resultSet'][0]['name'].'.';
                    }  
            if ((isset($params['sifre']) && $params['sifre'] != "")) {                                    
                        $sifre = $params['sifre'];
                        if ($params['sifre'] =='12345')
                                {$sifre ='1YTr63O9Mdeg54DZefZg16g==';}
                        
                    }  
            $tc = '011111111110';
            if ((isset($params['tc']) && $params['tc'] != "")) {
                $tc = $params['tc'];
            } 
                 
            $sql = "    
            DECLARE  @KisiID uniqueidentifier ; 

            EXEC ".$dbnameValue."[dbo].[PRC_GNL_Kullanici_Find_For_Login_ByTcKimlikNo]
		@KisiID = @KisiID OUTPUT,
		@MEBKodu = ".intval($mebKoduValue).",
		@TcKimlikNo = '".$tc."',
		@Sifre = N'".$sifre."' ;  
            
            SELECT 
                @KisiID as KisiID,   
                concat(kk.[Adi],' ' ,kk.[Soyadi] ) as adsoyad,   
                kk.[TCKimlikNo] ,
                ff.Fotograf,
                kk.CinsiyetID
            FROM  ".$dbnameValue."[dbo].[GNL_Kisiler] kk 
            LEFT JOIN ".$dbnameValue."dbo.GNL_Fotograflar ff on ff.KisiID =kk.[KisiID] 
            where  kk.[KisiID] = @KisiID    ; 
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ login olan userin rol bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKisiTumRollerFindByID($params = array()) {
        try {
            
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }    
            
            $sql = "   
                DECLARE @return_value int;

                EXEC @return_value = [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]
                    @KisiID =  '".$kisiId."' ;

                SELECT  
                    null as KisiID , 
                    'LUTFEN SEÇİNİZ...' AS adsoyad,
                    '' AS [TCKimlikNo]   
                UNION 
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
    public function mobilfirstdata_eskisi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
          
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }    
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
                   
                    INSERT #okimobilfirstdata  EXEC  [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]  @KisiID= '".$kisiId."' ;
                      
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
                            upper(concat(oo.[OkulAdi], ' / (',rr.[RolAdi],')' )) as OkulAdi,
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

                    IF OBJECT_ID('tempdb..#okimobilfirstdata') IS NOT NULL DROP TABLE #okimobilfirstdata; 
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
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilfirstdata($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $tc = '01111111110';
            if ((isset($params['tcno']) && $params['tcno'] != "")) {
                $tc = $params['tcno'];
            }    
            $sql = "  
            SET NOCOUNT ON;     
            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 
            IF OBJECT_ID('tempdb..##okimobilfirstdata".$tc."') IS NOT NULL DROP TABLE ##okimobilfirstdata".$tc."; 
            IF OBJECT_ID('tempdb..##okimobilseconddata".$tc."') IS NOT NULL DROP TABLE ##okimobilseconddata".$tc."; 

            DECLARE @name nvarchar(200);
            declare @database_id int;
            declare @tc nvarchar(11);
            DECLARE @sqlx nvarchar(2000); 
            DECLARE @sqlxx nvarchar(2000);
            declare @MEBKodu int;   
		 
            CREATE TABLE #okidbname".$tc."(database_id int , name  nvarchar(200) , sqlx nvarchar(2000),MEBKodu int );  
            CREATE TABLE ##okidetaydata".$tc."  (dbnamex  nvarchar(200) , KisiID uniqueidentifier, KurumID uniqueidentifier, MEBKodu integer );

            set @tc =    ".$tc.";  
            
            DECLARE db_cursor CURSOR FOR  
            SELECT database_id, name FROM Sys.databases 
            WHERE state = 0
                AND name NOT IN ('master','model','msdb','tempdb', 'TEYDEB_GELISTIRME_V5','BILSANET_MOBILE','BILSANET_PRODUCTION','BILSANET_DEVELOPMENT')  
                AND name NOT like ( '%ReportServer%' )  
                AND Left(name, 3) != 'RS_'
                AND (lower(name) like '%Bilsa%' or lower(name) like '%BILSA%');

            OPEN db_cursor   
            FETCH NEXT FROM db_cursor INTO  @database_id , @name 
            WHILE @@FETCH_STATUS = 0   
            BEGIN   

            INSERT INTO #okidbname".$tc." ( database_id , name , sqlx ) VALUES
                            (@database_id, CAST(@name AS nvarchar(200)) ,   'select '+ cast(@database_id as varchar(10))+'; exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+@tc  );
 
            SET @sqlxx =   ' 
                INSERT into  ##okidetaydata".$tc."  (dbnamex,KisiID , KurumID, MEBKodu)
                    SELECT 
                            '''+@name+''', k.KisiID , k.KurumID, kr.MEBKodu   
                    FROM ['+@name+'].dbo.GNL_Kullanicilar K
                    INNER JOIN ['+@name+'].dbo.GNL_Kurumlar KR ON K.KurumID = KR.KurumID
                    INNER JOIN ['+@name+'].dbo.GNL_Kisiler KS ON K.KisiID = KS.KisiID
                    WHERE KS.TCKimlikNo =   ' +@tc ; 
			/* print(@sqlxx); */ 
            EXEC sp_executesql @sqlxx; 
			/* -- select * from #okidbname ; */ 
            update  #okidbname".$tc." 
                set MEBKodu = (select MEBKodu from ##okidetaydata".$tc." as xxx where xxx.dbnamex = #okidbname".$tc.".name  )
            where database_id =  @database_id;
            
          
            FETCH NEXT FROM db_cursor INTO @database_id,  @name;
            END   

            CLOSE db_cursor;
            DEALLOCATE db_cursor ;

            /* 
            --   select  database_id , name , MEBKodu  from #okidbname  where MEBKodu is not null  ;  
            --  select  * from  ##okidetaydata ; 
            */
		  
    
                CREATE TABLE ##okimobilfirstdata".$tc."
                    (
                        [OkulKullaniciID]  [uniqueidentifier],
                        [OkulID] [uniqueidentifier], 
                        [KisiID] [uniqueidentifier],
                        [RolID]  int,
                        [RolAdi] varchar(100)  
                    ) ;

                CREATE TABLE ##okimobilseconddata".$tc."
                    (
                        [OkulKullaniciID]  [uniqueidentifier],
                        [OkulID] [uniqueidentifier], 
                        [KisiID] [uniqueidentifier],
                        [RolID]  int,
                        [RolAdi] varchar(100), 
                        OkulAdi varchar(200),
                        MEBKodu bigint,
                        ePosta varchar(100),
                        DersYiliID [uniqueidentifier],
                        EgitimYilID int, 
                        EgitimYili varchar(100),
                        DonemID int,
                        dbnamex  nvarchar(200)
                    ) ;
                declare @dbnamex  nvarchar(200)  ;
                declare @KisiID  uniqueidentifier;
                declare @KurumID  uniqueidentifier; 

                DECLARE db_cursor CURSOR FOR  
                SELECT distinct dbnamex  ,  KisiID  , KurumID  , MEBKodu  FROM ##okidetaydata".$tc." 
                WHERE MEBKodu is not null ;

                OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO  @dbnamex  ,  @KisiID  , @KurumID  , @MEBKodu 
                WHILE @@FETCH_STATUS = 0   
                BEGIN   

                SET @sqlxx =  ' 
                          INSERT ##okimobilfirstdata".$tc."  EXEC  ['+@dbnamex+'].[dbo].[PRC_GNL_Kisi_TumRoller_FindByID]  @KisiID=  ''' +  cast(@KisiID as varchar(50))+'''   '; 

   
                /* print(@sqlxx); */
                EXEC sp_executesql @sqlxx; 
                /* select * from #okidbname ;*/
                update  #okidbname".$tc." 
                    set MEBKodu = (select MEBKodu from ##okidetaydata".$tc." as xxx where xxx.dbnamex = #okidbname".$tc.".name  )
                where database_id =  @database_id;

                delete from #okidbname".$tc." where MEBKodu is null ; 

                SET @sqlx = '
                insert into ##okimobilseconddata".$tc."  ( [OkulKullaniciID] ,
                            [OkulID],  [KisiID],  [RolID],[RolAdi],OkulAdi,
                            [MEBKodu],  [ePosta],  DersYiliID,  EgitimYilID,   EgitimYili,   DonemID , dbnamex )
                SELECT  
                    sss.[OkulKullaniciID] ,
                    sss.[OkulID],
                    sss.[KisiID],
                    sss.[RolID], 
                    rr.[RolAdi],
                    upper(concat(oo.[OkulAdi], '' / ('',rr.[RolAdi],'')'' )) as OkulAdi,
                    oo.[MEBKodu],
                    oo.[ePosta],
                    DY.DersYiliID,
                    DY.EgitimYilID, 
                    EY.EgitimYili,
                    DY.DonemID ,
                    '''+@dbnamex+''' as dbnamex
                FROM ##okimobilfirstdata".$tc." sss
                inner join ['+@dbnamex+'].[dbo].[GNL_Okullar] oo ON oo.[OkulID] = sss.[OkulID] 
                inner join ['+@dbnamex+'].[dbo].GNL_DersYillari DY ON DY.OkulID = sss.OkulID and DY.AktifMi =1 
                inner join ['+@dbnamex+'].[dbo].GNL_EgitimYillari EY ON EY.EgitimYilID = DY.EgitimYilID AND DY.AktifMi = 1
                inner join ['+@dbnamex+'].[dbo].[GNL_Roller] rr ON rr.[RolID] =  sss.[RolID];
                    ';
                /* print(@sqlx); */
                EXEC sp_executesql @sqlx;  

                FETCH NEXT FROM db_cursor INTO @dbnamex  ,  @KisiID  , @KurumID  , @MEBKodu ;
                END   

                CLOSE db_cursor;
                DEALLOCATE db_cursor ;



                SELECT  
                    null AS OkulKullaniciID ,
                    null AS OkulID,
                    null AS KisiID,
                    -1 AS RolID, 
                     null AS  RolAdi,
                    'LÜTFEN SEÇİNİZ...' AS OkulAdi,
                    '' AS MEBKodu,
                    '' AS ePosta,
                    null AS DersYiliID,
                    '' AS EgitimYilID, 
                    '' AS EgitimYili,
                    0 AS DonemID ,
                    null AS dbnamex 

                UNION  	  
                select  
                    OkulKullaniciID ,
                    OkulID,
                    KisiID,
                    RolID, 
                    RolAdi,
                    OkulAdi,
                    MEBKodu,
                    ePosta,
                    DersYiliID,
                    EgitimYilID, 
                    EgitimYili,
                    DonemID ,
                    dbnamex 
                from  ##okimobilseconddata".$tc." ; 

                IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc."; 
                IF OBJECT_ID('tempdb..##okimobilfirstdata".$tc."') IS NOT NULL DROP TABLE ##okimobilfirstdata".$tc.";  
                IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 
                IF OBJECT_ID('tempdb..##okimobilseconddata".$tc."') IS NOT NULL DROP TABLE ##okimobilseconddata".$tc."; 
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
     * @ login olan userin menusunu dondurur  !!
     * @version v 1.0  27.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilMenu($params = array()) {
        try { 
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex;
                
            } 
            $RolID = -11;
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID'];
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
                        ,[divid] ,
                        iconcolor
                    FROM ".$dbnamex."[GNL_Mobil_Menuleri]
                    WHERE active = 0 AND deleted = 0 AND 
                        [RolID] = ".intval($RolID)."   
                    ORDER BY MenuID
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $OkulID = '1CCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }  
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            CREATE TABLE #tmpzz (
 
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

            INSERT  INTO #tmpzz
            EXEC ".$dbnamex."[PRC_GNL_DersYili_Find] @OkulID = '".$OkulID."'  
 
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
			 #tmpzz.DersYiliID,
			 #tmpzz.DonemID,
			 #tmpzz.EgitimYilID
            FROM ".$dbnamex."GNL_DersProgramlari DP
            INNER JOIN ".$dbnamex."GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
							AND SO.OgretmenID = '".$kisiId."'
            INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID  AND SNF.DersYiliID = '".$dersYiliID."'    
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_Dersler DRS ON DH.DersID = DRS.DersID
            LEFT JOIN  ".$dbnamex."GNL_DersSaatleri DS ON DS.DersYiliID = SNF.DersYiliID AND DS.SubeGrupID = SNF.SubeGrupID AND DS.DersSirasi = DP.DersSirasi
            inner join #tmpzz on #tmpzz.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmpzz.DonemID 
            ) ORDER BY HaftaGunu, BaslangicSaati,DersSirasi, DersAdi ;  
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $sinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['sinifID']) && $params['sinifID'] != "")) {
                $sinifID = $params['sinifID'];
            }   
            $tarih = '1970-01-01';
            if ((isset($params['tarih']) && $params['tarih'] != "")) {
                $tarih = $params['tarih'];
            }   
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
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
							 
            INSERT #ogretmenDersSaatleri  exec  ".$dbnamex."PRC_GNL_DersProgrami_Find_forOgretmenDersSaatleri 
                    @OgretmenID='".$kisiId."',
                    @SinifID='".$sinifID."',
                    @Tarih='".$tarih."' ;  
                        
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
            IF OBJECT_ID('tempdb..#ogretmenDersSaatleri') IS NOT NULL DROP TABLE #ogretmenDersSaatleri; 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            /*
            exec dbo.PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                @SinifID='F4201B97-B073-4DD7-8891-8091C3DC82CF',
                @Tarih='2017-09-29 00:00:00',
                @DersSirasi=1,
                @DersYiliID='fc4675fc-dafb-4af6-a3c2-7acd22622039',
                @OgretmenID='17A68CAA-1A13-460A-BEAA-FB483AC82F7B' 
             
             */ 
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $sinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['sinifID']) && $params['sinifID'] != "")) {
                $sinifID = $params['sinifID'];
            }   
            $tarih = '1970-01-01';
            if ((isset($params['tarih']) && $params['tarih'] != "")) {
                $tarih = $params['tarih'];
            }   
            $dersSirasi = -1;
            if ((isset($params['dersSirasi']) && $params['dersSirasi'] != "")) {
                $dersSirasi = $params['dersSirasi'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }   
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
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
                exec  ".$dbnamex."PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                    @SinifID='".$sinifID."',
                    @Tarih='".$tarih."' ,
                    @DersSirasi='".$dersSirasi."',
                    @DersYiliID='".$dersYiliID."', 
                    @OgretmenID='".$kisiId."'  ;  
                        

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
                LEFT JOIN  ".$dbnamex."GNL_Fotograflar ff on ff.KisiID =tt.OgrenciID ; 
            IF OBJECT_ID('tempdb..#tmpe') IS NOT NULL DROP TABLE #tmpe; 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
             
            $sql = "  
            SET NOCOUNT ON;   

            EXEC ".$dbnamex."[PRC_VLG_VeliRandevu_FindByOgretmenID]
		  @OgretmenID='".$kisiId."' ; 

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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = '-2';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DersID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersID']) && $params['DersID'] != "")) {
                $DersID = $params['DersID'];
            }
            $SinifDersID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
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
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulOgretmenID']) && $params['OkulOgretmenID'] != "")) {
                $OkulOgretmenID = $params['OkulOgretmenID'];
            } 
            $Tarih = NULL;
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $Tarih = $params['Tarih'];
            } 
            $JsonData = NULL;
            if ((isset($params['JsonData']) && $params['JsonData'] != "")) {
                $JsonData = $params['JsonData'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
             
              $xml = new SimpleXMLElement('<xml/>');
            
              
              for ($i = 1; $i <= $JsonData; ++$i) {
                $track = $xml->addChild('track');
                $track->addChild('path', "song$i.mp3");
                $track->addChild('title', "Track $i - Track Title");
            }

            /*
             * // <Table><Ogrenci><OgrenciID>c6bc540a-1c6e-4ee9-a7f6-3d76eb9027eb</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>4d6ea4f9-8ad9-410e-97f9-930b6b8fe41a</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>c82cc86a-6dde-4213-82a2-812344275720</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>8eae147f-0798-4a77-af17-16972fc10382</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>cf7223bc-4b0c-49c5-bf49-922a4d7f252d</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci></Table>
             $xml = new SimpleXMLElement('<xml/>');

            for ($i = 1; $i <= 8; ++$i) {
                $track = $xml->addChild('track');
                $track->addChild('path', "song$i.mp3");
                $track->addChild('title', "Track $i - Track Title");
            }

            Header('Content-type: text/xml');
            print($xml->asXML());
             
             */
            $sql = " 
                 

                exec ".$dbnamex."PRC_GNL_OgrenciDevamsizlikSaatleri_SaveXML 
                    @DersYiliID='" . $OgretmenID . "',
                    @Tarih='" . $Tarih . "', 
                    @DersSirasi=" . intval($DersSirasi) . " ,
                    @XmlData= '" . $XmlData . "',
                    @SinifDersID='" . $SinifDersID . "' ; 

    
                exec ".$dbnamex."PRC_GNL_SaveOgretmenDevamsizlikGirisiLog 
                    @OgretmenID= '" . $OgretmenID . "',
                    @DersYiliID= '" . $DersYiliID . "',
                    @SinifID='" . $SinifID . "',
                    @DersID= '" . $DersID . "',
                    @DersSirasi=" . intval($DersSirasi) . " ; 
                 
 
                exec ".$dbnamex."PRC_GNL_OgretmenDevamKontrol_Save 
                    @OgretmenID='" . $OgretmenID . "', 
                    @Tarih='" . $Tarih . "',
                    @DersSirasi=" . intval($DersSirasi) . ",
                    @SinifDersID='" . $SinifDersID . "',
                    @DonemID=" . intval($DersSirasi) . " ; 

                exec ".$dbnamex."PRC_GNL_SinifDevamsizlikKayitlari_Save 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
      
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#ogrenciIdBul') IS NOT NULL DROP TABLE #ogrenciIdBul; 
    
            CREATE TABLE #ogrenciIdBul
                (
                        OgrenciID  [uniqueidentifier]  
                ) ;

            INSERT #ogrenciIdBul exec ".$dbnamex."PRC_GNL_OgrenciYakinToOgrenciID_Find @YakinID='".$kisiId."' ; 
            
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
                FROM   ".$dbnamex."GNL_OgrenciSeviyeleri GOS
                INNER JOIN ".$dbnamex."GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID)
                INNER JOIN ".$dbnamex."GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID)
                INNER JOIN ".$dbnamex."GNL_Siniflar SINIF ON (SINIF.SinifID = GOS.SinifID)
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = SINIF.DersYiliID
                INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID 
                LEFT JOIN GNL_Fotograflar fo on fo.KisiID = GOS.OgrenciID
                WHERE 
                        GOS.OgrenciID in (SELECT distinct OgrenciID FROM #ogrenciIdBul) 
                AND 
                        SINIF.DersYiliID ='".$dersYiliID."'
            ) as assss 
            ORDER BY Numarasi; 
            IF OBJECT_ID('tempdb..#ogrenciIdBul') IS NOT NULL DROP TABLE #ogrenciIdBul; 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
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
                 FROM ".$dbnamex."GNL_OgrenciDevamsizliklari 
                     WHERE 
                             DersYiliID = '".$dersYiliID."' AND 
                             OgrenciID ='".$kisiId."'; 
                
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
                LEFT JOIN ".$dbnamex."[GNL_DevamsizlikKodlari] dd on dd.DevamsizlikKodID = tt.DevamsizlikKodID;
 
                IF OBJECT_ID('tempdb..#ogrenciIdDevamsizlikTarih') IS NOT NULL DROP TABLE #ogrenciIdDevamsizlikTarih; 
                SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
             echo debugPDO($sql, $params);
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
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
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
                FROM ".$dbnamex."GNL_Siniflar S
                INNER JOIN ".$dbnamex."GNL_Seviyeler SEV ON S.SeviyeID = SEV.SeviyeID
                LEFT JOIN ".$dbnamex."GNL_SinifOgretmenleri SO ON (S.SinifID = SO.SinifID AND SO.OgretmenTurID=1)
                LEFT JOIN ".$dbnamex."GNL_SinifOgretmenleri MY ON (S.SinifID = MY.SinifID AND MY.OgretmenTurID=2)
                LEFT JOIN ".$dbnamex."GNL_Kisiler gks on gks.KisiID=SO.OgretmenID 
                LEFT JOIN ".$dbnamex."GNL_Kisiler gkm on gkm.KisiID=MY.OgretmenID
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
           $SinifID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
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
                    ".$dbnamex."FNC_GNL_AdayKayitUcretOdendiMi(GOS.[OgrenciID],DY.DersYiliID) AS OdendiMi,  	
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
                    FROM ".$dbnamex."GNL_OgrenciSeviyeleri GOS 
                    INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = GOS.SinifID  
                    INNER JOIN ".$dbnamex."GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID) 
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON (DY.DersYiliID = S.DersYiliID)  
                    INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON (OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID)  
                    INNER JOIN ".$dbnamex."GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID) 
                    LEFT JOIN ".$dbnamex."GNL_Fotograflar ff on ff.KisiID =GOS.OgrenciID
                    WHERE  
                            GOS.SinifID = Cast('".$SinifID."' AS nvarchar(39)) AND
                            GOS.OgrenciArsivTurID =  cast(1 AS nvarchar(2))   ; 

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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
           $OgrenciSeviyeID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciSeviyeID']) && $params['OgrenciSeviyeID'] != "")) {
                $OgrenciSeviyeID = $params['OgrenciSeviyeID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
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
                    DersSirasi = ISNULL(( SELECT Sira
                                          FROM ".$dbnamex."GNL_SinifDersleri SD
                                          WHERE SD.SinifID = ODNB.SinifID
                                                 AND SD.DersHavuzuID = ODNB.DersHavuzuID  ), 999) ,
                    DersOgretmenAdi ,
                    DersOgretmenSoyadi ,
                    isPuanNotGirilsin ,
                    isPuanNotHesapDahil ,
                    AgirlikliYilSonuNotu ,
                    AgirlikliYilsonuPuani ,
                    PBYCOrtalama, 
                    DersSabitID 		 
                FROM ".$dbnamex."OgrenciDersNotBilgileri_Donem1 ODNB
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGruplari ODG ON ODG.OgrenciDersID = ODNB.OgrenciDersID
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGrupTanimlari ODGT ON 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }   
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
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

                    INSERT #okiogrsinavlari EXEC ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgretmen]
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
                    FROM #okiogrsinavlari a 
                    inner join ".$dbnamex."[GNL_Donemler] gd on gd.DonemID = a.NotDonemID 
            IF OBJECT_ID('tempdb..#okiogrsinavlari') IS NOT NULL DROP TABLE #okiogrsinavlari; 
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
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
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

                    INSERT #okiyakinsinavlari EXEC  ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgrenci]
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
                    FROM #okiyakinsinavlari a 
                    INNER JOIN ".$dbnamex."[GNL_Donemler] gd on gd.DonemID = a.NotDonemID ;
            IF OBJECT_ID('tempdb..#okiyakinsinavlari') IS NOT NULL DROP TABLE #okiyakinsinavlari; 
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
    public function kurumYoneticisiSinavListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }  
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okikysinavlari') IS NOT NULL DROP TABLE #okikysinavlari; 

            CREATE TABLE #okikysinavlari
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
                            YaziliStsSinavDersiDersHavuzuID [uniqueidentifier]
                            ) ;

                    INSERT #okikysinavlari EXEC ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgrenci]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        gd.[Donem], 
                        SinavTarihi,
                        SinavBitisTarihi, 
                        SinavTurAdi,
                        SinavKodu,
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
                        SinavOgrenciID,
                        YaziliStsSinavDersiDersHavuzuID
                    */
                    FROM #okikysinavlari a 
                    INNER JOIN ".$dbnamex."[GNL_Donemler] gd ON gd.DonemID = a.NotDonemID ;
            IF OBJECT_ID('tempdb..#okikysinavlari') IS NOT NULL DROP TABLE #okikysinavlari; 
            SET NOCOUNT OFF;  
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
     * @ login olan ogretmenin velilerle olan randevu listesi.  !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function findByOkulOgretmenID($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             $OkulID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
           
            $sql = "  
            SET NOCOUNT ON;    	 
            IF OBJECT_ID('tempdb..#okiOkulOgretmenID') IS NOT NULL DROP TABLE #okiOkulOgretmenID; 

            CREATE TABLE #okiOkulOgretmenID
                            (
                            OkulOgretmenID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OgretmenID [uniqueidentifier]   ) ;

            INSERT #okiOkulOgretmenID EXEC ".$dbnamex."PRC_OGT_OkulOgretmen_FindByOkulOgretmenID 
                @OkulID= '".$OkulID."',
                @OgretmenID=  '".$OgretmenID."' ; 

            SELECT *,   
            (CASE WHEN (1 = 1) THEN 1 ELSE 0 END)  as control
            FROM #okiOkulOgretmenID ;
            IF OBJECT_ID('tempdb..#okiOkulOgretmenID') IS NOT NULL DROP TABLE #okiOkulOgretmenID; 
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
     * @ login olan kişinin gelen mesajları... 
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            declare @startRowIndex int; 
            declare @maximumRows int ; 
            declare @sortExpression  nvarchar(10);
            
            set  @startRowIndex = 1;
            set  @maximumRows = 1000;

            SELECT 
                   MesajID,
                   ReceiverID,
                   Okundu,
                   OkunduguTarih,
                   Silindi,
                   MesajOncelikID,
                   Konu,
                   Mesaj,
                   Tarih,
                   SenderID,
                   SenderAdi,
                   SenderSoyadi,
                   SenderAdiSoyadi,
                   AttachmentFile,
                   RowNum 
            FROM (
		SELECT 
			M.MesajID,
			M.KisiID AS ReceiverID,
			MK.Okundu,
			MK.OkunduguTarih,
			M.Silindi,
			M.MesajOncelikID,
			M.Konu,
			M.Mesaj,
			M.Tarih,
			M.KisiID AS SenderID,
			K.Adi AS SenderAdi,
			K.Soyadi AS SenderSoyadi,
			(K.Adi + ' ' + K.Soyadi) AS SenderAdiSoyadi,
			(CASE WHEN (SELECT COUNT(1) FROM ".$dbnamex."MSJ_MesajEklentileri WHERE MesajID = M.MesajID)>0 THEN 1 ELSE 0 END) AS AttachmentFile,
			ROW_NUMBER() OVER(ORDER BY Tarih DESC) as RowNum 
		FROM ".$dbnamex."MSJ_Mesajlar M 
		INNER JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID  
		INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
		WHERE MK.KisiID = '".$KisiID."'
            ) AS Parent 
            WHERE Silindi=0 and RowNum BETWEEN @startRowIndex AND @maximumRows ORDER BY Tarih DESC;

             
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
     * @ login olan kişinin sectiği mesajın detayı... 
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajDetay($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $MesajID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['MesajID']) && $params['MesajID'] != "")) {
                $MesajID = $params['MesajID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;   

            SELECT 
                   MesajID,
                   ReceiverID,
                   Okundu,
                   OkunduguTarih,
                   Silindi,
                   MesajOncelikID,
                   Konu,
                   Mesaj,
                   Tarih,
                   SenderID,
                   SenderAdi,
                   SenderSoyadi,
                   SenderAdiSoyadi,
                   AttachmentFile,
                   RowNum 
            FROM (
		SELECT 
			M.MesajID,
			M.KisiID AS ReceiverID,
			MK.Okundu,
			MK.OkunduguTarih,
			M.Silindi,
			M.MesajOncelikID,
			M.Konu,
			M.Mesaj,
			M.Tarih,
			M.KisiID AS SenderID,
			K.Adi AS SenderAdi,
			K.Soyadi AS SenderSoyadi,
			(K.Adi + ' ' + K.Soyadi) AS SenderAdiSoyadi,
			(CASE WHEN (SELECT COUNT(1) FROM ".$dbnamex."MSJ_MesajEklentileri WHERE MesajID = M.MesajID)>0 THEN 1 ELSE 0 END) AS AttachmentFile,
			ROW_NUMBER() OVER(ORDER BY Tarih DESC) AS RowNum 
		FROM ".$dbnamex."MSJ_Mesajlar M 
		LEFT JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID  
		INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
		WHERE M.MesajID = '".$MesajID."'
            ) AS Parent ; 
            SET NOCOUNT OFF;  
                 "; 
            $statement = $pdo->prepare($sql);   
       // echo debugPDO($sql, $params);
            $statement->execute(); 
            
         //   $gelenMesajOkunduParams = array('MesajID' =>  $params['MesajID'], ); 
          //  $this->gelenMesajOkundu($gelenMesajOkunduParams); 
            
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
    * @ login olan kişinin sectiği mesajın detayına tıklarsa okundu isaretliyecegiz.... 
     * @version v 1.0  21.04.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajOkundu($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();
            
            $MesajID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['MesajID']) && $params['MesajID'] != "")) {
                $MesajID = $params['MesajID'];
            }  
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
             
            $sql = "
                INSERT INTO ".$dbnamex."[MSJ_MesajKutulari]
                        ([MesajID]
                        ,[KisiID]
                        ,[MesajID] ='".$MesajID."'
                        ,[Silindi]
                        ,[OkunduguTarih])
                SELECT 
                        :MesajID, 
                        (SELECT  [KisiID]  FROM [Bilsanet1].[dbo].[MSJ_Mesajlar] where [MesajID] ='".$MesajID."') , 
                        1,
                        0,  
                        getdate() 
                FROM ".$dbnamex."MSJ_MesajKutulari 
                WHERE [MesajID] ='".$MesajID."'
                           ";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':MesajID', $params['MesajID'], \PDO::PARAM_STR); 
                  
                //    echo debugPDO($sql, $params);
                    $result = $statement->execute(); 
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]); 
                     
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, );
                 
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    
    
    /** 
     * @author Okan CIRAN
     * @ ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiOgretmen($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiozetodevtanimlari') IS NOT NULL DROP TABLE #okiozetodevtanimlari; 

            CREATE TABLE #okiozetodevtanimlari
                        (  
                            OdevTanimID [uniqueidentifier], 
                            OgretmenAdi  varchar(100),
                            SinifKodu  varchar(20),
                            SeviyeID int,
                            SeviyeAdi  varchar(100), 
                            DersBilgisi  varchar(100), 
                            Tanim  varchar(100),
                            Aciklama  varchar(100),
                            Tarih smalldatetime, 
                            TeslimTarihi smalldatetime, 
                            OdevTipID tinyint, 
                            TanimDosyaID [uniqueidentifier], 
                            TanimDosyaAdi   varchar(100), 
                            TanimYuklemeTarihi smalldatetime,  
                            TanimBoyut int ,  
                            TanimDosya image, 
                            VerildigiOgrenciSayisi int, 
                            BakanOgrenciSayisi int ,
                            YapanOgrenciSayisi int,
                            OnayOgrenciSayisi int 
                        ) ;

                    INSERT #okiozetodevtanimlari EXEC ".$dbnamex."[PRC_ODV_OdevTanimlari_FindOzet]
                                                    @OgretmenID =  '".$OgretmenID."',
                                                    @DersYiliID =  '".$DersYiliID."',
                                                    @Tumu =  1;   

                    SELECT  
                        OdevTanimID  , 
                        OgretmenAdi  ,
                        SinifKodu   ,
                        SeviyeID  ,
                        SeviyeAdi   , 
                        DersBilgisi   , 
                        Tanim   ,
                        Aciklama  ,
                        Tarih  , 
                        TeslimTarihi  , 
                        OdevTipID  , 
                        TanimDosyaID  , 
                        TanimDosyaAdi   , 
                        TanimYuklemeTarihi  ,  
                        TanimBoyut   ,  
                        TanimDosya  , 
                        VerildigiOgrenciSayisi  , 
                        BakanOgrenciSayisi   ,
                        YapanOgrenciSayisi  ,
                        OnayOgrenciSayisi   
                    from #okiozetodevtanimlari a ;
            IF OBJECT_ID('tempdb..#okiozetodevtanimlari') IS NOT NULL DROP TABLE #okiozetodevtanimlari;       
            SET NOCOUNT OFF;   
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
     * @ ögrenci ve yakını için ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiOgrenciveYakin($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $OgrenciID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $OgrenciID = $params['OgrenciID'];
            }
            $EgitimYilID = 1970;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            
            SELECT 
                OO.OgrenciOdevID,
                OO.OgrenciID,
                OO.OdevTanimID,
                OO.OgrenciCevap,
                OO.OgrenciGordu,
                OO.OgrenciOnay,
                OO.OgrenciTeslimTarihi,
                OO.OgretmenDegerlendirme,
                OO.OdevOnayID,
                K.Adi + ' ' + K.Soyadi AS OgretmenAdi,
                D.DersAdi,
                OT.Tanim,
                OT.Tarih,
                OT.TeslimTarihi 
            FROM ".$dbnamex."ODV_OgrenciOdevleri OO 
            INNER JOIN ".$dbnamex."ODV_OdevTanimlari OT ON OT.OdevTanimID = OO.OdevTanimID 
            INNER JOIN ".$dbnamex."OGT_Ogretmenler AS OGT ON OGT.OgretmenID = OT.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Kisiler AS K ON K.KisiID = OGT.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_SinifDersleri AS SD ON SD.SinifDersID = OT.SinifDersID 
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri AS SO ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID AND OT.OgretmenID = SO.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Siniflar AS S ON S.SinifID = SD.SinifID 
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari AS DH ON DH.DersHavuzuID = SD.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = DH.DersYiliID 
            INNER JOIN ".$dbnamex."GNL_Dersler AS D ON D.DersID = DH.DersID 
            WHERE OO.OgrenciID = '".$OgrenciID."' AND DY.EgitimYilID = ".intval($EgitimYilID)."
            ORDER BY Tarih DESC
 
                   
            SET NOCOUNT OFF;   
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
     * @ kurum ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiKurumYoneticisi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            }  
            $sql = "  
            SET NOCOUNT ON;  
            SELECT  
                OT.OdevTanimID ,
                K.Adi + ' ' + K.Soyadi AS OgretmenAdi ,
                S.SinifKodu ,
                SV.SeviyeAdi ,
                DH.DersKodu + ' (' + D.DersAdi + ')' AS DersBilgisi ,
                OT.Tanim ,
                OT.Tarih ,
                OT.TeslimTarihi
            FROM ".$dbnamex."ODV_OdevTanimlari AS OT
            INNER JOIN ".$dbnamex."GNL_SinifDersleri AS SD ON SD.SinifDersID = OT.SinifDersID
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri AS SO ON SO.SinifID = SD.SinifID
                                                          AND SO.DersHavuzuID = SD.DersHavuzuID
                                                          AND OT.OgretmenID = SO.OgretmenID
            INNER JOIN ".$dbnamex."GNL_Siniflar AS S ON S.SinifID = SD.SinifID
            INNER JOIN ".$dbnamex."GNL_Seviyeler SV ON S.SeviyeID = SV.SeviyeID
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari AS DH ON DH.DersHavuzuID = SD.DersHavuzuID
            INNER JOIN ".$dbnamex."GNL_Dersler AS D ON D.DersID = DH.DersID
            INNER JOIN ".$dbnamex."OGT_Ogretmenler AS OGT ON OGT.OgretmenID = OT.OgretmenID
            INNER JOIN ".$dbnamex."GNL_Kisiler AS K ON K.KisiID = OGT.OgretmenID
            WHERE
                S.DersYiliID = '".$DersYiliID."'
            ORDER BY TeslimTarihi DESC;  
            SET NOCOUNT OFF;   
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
     * @ öğretmen ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogretmendersprogramilistesi') IS NOT NULL DROP TABLE #okiogretmendersprogramilistesi; 

            CREATE TABLE #okiogretmendersprogramilistesi
                    (   
                        HaftaGunu smallint,
                        DersSirasi smallint,
                        SinifDersID [uniqueidentifier],
                        DersAdi varchar(100), 
                        DersKodu varchar(100), 
                        SinifKodu varchar(100),
                        SubeGrupID int,
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersBaslangicBitisSaati  varchar(20),
                        SinifOgretmenID [uniqueidentifier],
                        DersHavuzuID [uniqueidentifier],
                        SinifID [uniqueidentifier],
                        DersID [uniqueidentifier]  
                    ) ;

            INSERT #okiogretmendersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_FindByOgretmenIDDersYiliID]
                                            @OgretmenID = '".$OgretmenID."',
                                            @DersYiliID = '".$DersYiliID."', 
                                            @DonemID = ".intval($DonemID)." ; 

            SELECT  
                HaftaGunu ,
                DersSirasi ,
                SinifDersID ,
                DersAdi , 
                DersKodu, 
                SinifKodu ,
                SubeGrupID ,
                BaslangicSaati ,
                BitisSaati ,
                DersBaslangicBitisSaati,
                SinifOgretmenID ,
                DersHavuzuID ,
                SinifID ,
                DersID    
            from #okiogretmendersprogramilistesi a ;
                   
            IF OBJECT_ID('tempdb..#okiogretmendersprogramilistesi') IS NOT NULL DROP TABLE #okiogretmendersprogramilistesi; 
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
     * @ öğrenci, yakını ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciVeYakiniDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $KisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $KisiID = $params['OgrenciID'];
            }
            $findOgrenciseviyeIDValue= null ; 
            $findOgrenciseviyeID = $this->findOgrenciseviyeID(
                            array( 'KisiID' =>$KisiID,  ));
            if (\Utill\Dal\Helper::haveRecord($findOgrenciseviyeID)) {
                $findOgrenciseviyeIDValue = $findOgrenciseviyeID ['resultSet'][0]['OgrenciseviyeID'];
            }  
            
            $OgrenciSeviyeID = $findOgrenciseviyeIDValue;
            if ((isset($params['OgrenciSeviyeID']) && $params['OgrenciSeviyeID'] != "")) {
                $OgrenciSeviyeID = $params['OgrenciSeviyeID'];
            }
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogrencidersprogramilistesi') IS NOT NULL DROP TABLE #okiogrencidersprogramilistesi; 

            CREATE TABLE #okiogrencidersprogramilistesi
                    (   
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersSaati varchar(20),
                        DersSirasi smallint,
                        Gun1_SinifDersID  varchar(20),
                        Gun2_SinifDersID  varchar(20),
                        Gun3_SinifDersID  varchar(20),
                        Gun4_SinifDersID  varchar(20),
                        Gun5_SinifDersID  varchar(20),
                        Gun6_SinifDersID  varchar(20),
                        Gun7_SinifDersID  varchar(20) 
                    ) ;

            INSERT #okiogrencidersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_FindForOgrenci]
                                            @OgrenciSeviyeID = '".$OgrenciSeviyeID."',
                                            @SinifID =   '".$SinifID."', 
                                            @DonemID = ".intval($DonemID)." ; 

            SELECT  
                BaslangicSaati ,
                BitisSaati ,
                DersSaati ,
                DersSirasi,
                Gun1_SinifDersID,
                Gun2_SinifDersID,
                Gun3_SinifDersID,
                Gun4_SinifDersID,
                Gun5_SinifDersID,
                Gun6_SinifDersID,
                Gun7_SinifDersID 
            FROM #okiogrencidersprogramilistesi   ;
                   
            IF OBJECT_ID('tempdb..#okiogrencidersprogramilistesi') IS NOT NULL DROP TABLE #okiogrencidersprogramilistesi; 
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
     * @ sınıf combobox listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumPersoneliSinifListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
            SET NOCOUNT ON;  
            SELECT 
                SN.SinifID,
                SN.SinifKodu,
                SN.SinifAdi 
            FROM ".$dbnamex."GNL_Siniflar SN 
            WHERE SN.DersYiliID =  '".$DersYiliID."' AND 
                    Sanal = 0 
            ORDER BY SN.SeviyeID,SN.SinifKodu,SN.SinifAdi;
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
     * @ kurum Personeli ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumPersoneliDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
             
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            }  
            
            $sql = "   
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okikpdersprogramilistesi') IS NOT NULL DROP TABLE #okikpdersprogramilistesi; 

            CREATE TABLE #okikpdersprogramilistesi
                    (   
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersSaati varchar(20),
                        DersSirasi smallint,
                        Gun1_SinifDersID  varchar(20),
                        Gun2_SinifDersID  varchar(20),
                        Gun3_SinifDersID  varchar(20),
                        Gun4_SinifDersID  varchar(20),
                        Gun5_SinifDersID  varchar(20),
                        Gun6_SinifDersID  varchar(20),
                        Gun7_SinifDersID  varchar(20) 
                    ) ;

            INSERT #okikpdersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_Find] 
                                            @SinifID =  '".$SinifID."', 
                                            @DonemID =  ".intval($DonemID)." ; 

            SELECT  
                BaslangicSaati ,
                BitisSaati ,
                DersSaati ,
                DersSirasi,
                Gun1_SinifDersID,
                Gun2_SinifDersID,
                Gun3_SinifDersID,
                Gun4_SinifDersID,
                Gun5_SinifDersID,
                Gun6_SinifDersID,
                Gun7_SinifDersID 
            FROM #okikpdersprogramilistesi   ;
                   
            IF OBJECT_ID('tempdb..#okikpdersprogramilistesi') IS NOT NULL DROP TABLE #okikpdersprogramilistesi; 
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
     * @ sınıf seviyelerini listeler (combobox)... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinifSeviyeleriCombo($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
           SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 

            CREATE TABLE #okisinifseviyeleri
                    (    
					SinifID uniqueidentifier,  
					DersYiliID uniqueidentifier,  
					SeviyeID int,  
					SinifKodu varchar(20),  
					SinifAdi varchar(20),  
					SinifMevcudu int ,
					Sanal bit,  
					SubeGrupID int ,  
 					SeviyeAdi  varchar(20),  
					HaftalikDersSaati int  
					) ;

            INSERT #okisinifseviyeleri EXEC ".$dbnamex."[PRC_GNL_Sinif_Find_BySeviyeID] 
                                            @DersYiliID =   '".$DersYiliID."',  
                                            @SinifKodu=  '',
                                            @basSeviye = 0 ,
                                            @bitSeviye= 50  ; 

            SELECT  distinct  
                        SeviyeID ,  
                        SeviyeAdi   
            FROM #okisinifseviyeleri   ;
                   
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinifSeviyeleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SeviyeID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SeviyeID']) && $params['SeviyeID'] != "")) {
                $SeviyeID = $params['SeviyeID'];
            }
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
           SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 

            CREATE TABLE #okisinifseviyeleri
                    (    
					SinifID uniqueidentifier,  
					DersYiliID uniqueidentifier,  
					SeviyeID int,  
					SinifKodu varchar(20),  
					SinifAdi varchar(20),  
					SinifMevcudu int ,
					Sanal bit,  
					SubeGrupID int ,  
 					SeviyeAdi  varchar(20),  
					HaftalikDersSaati int  
					) ;

            INSERT #okisinifseviyeleri EXEC ".$dbnamex."[PRC_GNL_Sinif_Find_BySeviyeID] 
                                            @DersYiliID = '".$DersYiliID."',  
                                            @SinifKodu=  '',
                                            @basSeviye = 0 ,
                                            @bitSeviye= 50  ; 

            SELECT  
                SinifID ,  
                DersYiliID ,  
                SeviyeID ,  
                SinifKodu ,  
                SinifAdi ,  
                SinifMevcudu  ,
                Sanal ,  
                SubeGrupID  ,  
                SeviyeAdi  ,  
                HaftalikDersSaati   
            FROM #okisinifseviyeleri   
            WHERE SeviyeID = ".intval($SeviyeID)." ;
                   
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 
            SET NOCOUNT OFF; 
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
     * @ profil bilgisini döndürür... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlProfil($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
           
            $sql = "   
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okignlProfil') IS NOT NULL DROP TABLE #okignlProfil; 

            CREATE TABLE #okignlProfil
                    (   
                        KisiID uniqueidentifier,
                        CinsiyetID int,
                        Adi varchar(50),
                        Soyadi varchar(50),
                        AdiSoyadi varchar(80), 
                        TCKimlikNo bigint,
                        ePosta varchar(50),
                        Yasamiyor bit,
                        EPostaSifresi varchar(50),
                    ) ;

            INSERT #okignlProfil EXEC  ".$dbnamex."PRC_GNL_Kisi_Get @KisiID = '".$KisiID."'; 

            SELECT   
                KisiID ,
                CinsiyetID ,
                Adi,
                Soyadi ,
                AdiSoyadi , 
                TCKimlikNo ,
                ePosta ,
                Yasamiyor ,
                EPostaSifresi 
            FROM #okignlProfil ;   
                   
            IF OBJECT_ID('tempdb..#okignlProfil') IS NOT NULL DROP TABLE #okignlProfil; 
            SET NOCOUNT OFF; 
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
     * @ dashboard   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
 
    public function dashboarddata ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $OkulID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $kisiId = $params['OkulID'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }    
           $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            CREATE TABLE #tmpzz (
 
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

            INSERT  INTO #tmpzz
            EXEC  ".$dbnamex."[PRC_GNL_DersYili_Find] @OkulID = '".$OkulID."'  
 
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
            FROM  ".$dbnamex."GNL_DersProgramlari DP
            INNER JOIN  ".$dbnamex."GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
            INNER JOIN  ".$dbnamex."GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
							AND SO.OgretmenID = '".$kisiId."'
            INNER JOIN  ".$dbnamex."GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID  AND SNF.DersYiliID = '".$dersYiliID."' @DersYiliID  
            INNER JOIN  ".$dbnamex."GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
            INNER JOIN  ".$dbnamex."GNL_Dersler DRS ON DH.DersID = DRS.DersID
            LEFT JOIN   ".$dbnamex."GNL_DersSaatleri DS ON DS.DersYiliID = SNF.DersYiliID AND DS.SubeGrupID = SNF.SubeGrupID AND DS.DersSirasi = DP.DersSirasi
            inner join #tmpzz on #tmpzz.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmpzz.DonemID 
            ) ORDER BY HaftaGunu, BaslangicSaati,DersSirasi, DersAdi ;  
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhBorcluSozlesmeleri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SeviyeID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SeviyeID']) && $params['SeviyeID'] != "")) {
                $SeviyeID = $params['SeviyeID'];
            }
           $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "  
           SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 

            CREATE TABLE #okisinifseviyeleri
                    (    
                        SinifID uniqueidentifier,  
                        DersYiliID uniqueidentifier,  
                        SeviyeID int,  
                        SinifKodu varchar(20),  
                        SinifAdi varchar(20),  
                        SinifMevcudu int ,
                        Sanal bit,  
                        SubeGrupID int ,  
                        SeviyeAdi  varchar(20),  
                        HaftalikDersSaati int  
                    ) ;

            INSERT #okisinifseviyeleri EXEC  ".$dbnamex."[PRC_GNL_Sinif_Find_BySeviyeID] 
                                            @DersYiliID = '".$DersYiliID."',  
                                            @SinifKodu=  '',
                                            @basSeviye = 0 ,
                                            @bitSeviye= 50  ; 

            SELECT  
                SinifID ,  
                DersYiliID ,  
                SeviyeID ,  
                SinifKodu ,  
                SinifAdi ,  
                SinifMevcudu  ,
                Sanal ,  
                SubeGrupID  ,  
                SeviyeAdi  ,  
                HaftalikDersSaati   
            FROM #okisinifseviyeleri   
            WHERE SeviyeID = ".intval($SeviyeID)." ;
                   
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 
            SET NOCOUNT OFF; 
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumVePersonelDevamsizlik($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $tarih = '1970-01-01';
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $tarih = $params['Tarih'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "    
            SET NOCOUNT ON;  

            declare  @DersYiliID uniqueidentifier ;
            declare  @Tarih datetime ;
            declare  @pageNo int ;
            declare  @pageRecCount int;

            set @DersYiliID='".$DersYiliID."';
            set @Tarih= '".$tarih."';
            set @pageNo=1;
            set @pageRecCount=0;

            SELECT  							
		Tarih,	 
		Adi, 
		Soyadi, 
                CONCAT(adi,' ',soyadi) AS adsoyad,	
		OOB.Numarasi, 
		SinifKodu, 
		OgrenciDevamsizlikID,  
		OD.DersYiliID,  
		OD.OgrenciID,  
		OD.DevamsizlikKodID,  
		OD.DevamsizlikPeriyodID, 		
		Aciklama, 
		DevamsizlikKodu,  
		DevamsizlikAdi, 
		DevamsizlikPeriyodu,  
		ROW_NUMBER() OVER(ORDER BY Tarih, Adi, Soyadi) AS rownum   
            FROM  ".$dbnamex."GNL_OgrenciDevamsizliklari OD  
		LEFT JOIN ".$dbnamex."GNL_Kisiler K ON OD.OgrenciID=K.KisiID 
		LEFT JOIN ".$dbnamex."GNL_Ogrenciler O ON OD.OgrenciID=O.OgrenciID 
		LEFT JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON O.OgrenciID = OS.OgrenciID 
		LEFT JOIN ".$dbnamex."GNL_Siniflar S ON OS.SinifID = S.SinifID 
		LEFT JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID 
		LEFT JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OgrenciID = O.OgrenciID AND OOB.OkulID= DY.OkulID 
		LEFT JOIN ".$dbnamex."GNL_DevamsizlikKodlari DK ON OD.DevamsizlikKodID=DK.DevamsizlikKodID 
		LEFT JOIN ".$dbnamex."GNL_DevamsizlikPeriyodlari DP ON OD.DevamsizlikPeriyodID=DP.DevamsizlikPeriyodID  
            WHERE 
                    CONVERT (NVARCHAR(10),(CONVERT(DATETIME,Tarih,103)),120) =  CONVERT (NVARCHAR(10),(CONVERT(DATETIME,@Tarih,103)),120)    AND  
                    OD.DersYiliID= cast(@DersYiliID AS NVARCHAR(50))    AND 
                    S.DersYiliID= cast(@DersYiliID as nvarchar(50))   
            ORDER BY OOB.Numarasi,DevamsizlikPeriyodu DESC; 
            SET NOCOUNT OFF;  
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function findOgrenciseviyeID($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $dbnamex = 'dbo.';
            if ((isset($params['dbnamex']) && $params['dbnamex'] != "")) {
                $dbnamex = $params['dbnamex'].'.'.$dbnamex; 
            } 
            $sql = "    
            SET NOCOUNT ON;  

            SELECT top 1 
                OS.OgrenciseviyeID, 
                OS.SinifID , 
                OK.OkulID, 
                OK.KurumID,
                OOB.OgrenciOkulBilgiID,
                DY.DersYiliID ,
                (CASE WHEN (1 = 1) THEN 1 ELSE 0 END)  as control
            FROM ".$dbnamex."GNL_OgrenciSeviyeleri OS
            INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
            INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID AND OOB.OgrenciID = OS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID
            WHERE     
                Os.OgrenciID = '".$KisiID."' 
            ORDER BY dy.EgitimYilID desc;

            SET NOCOUNT OFF;  
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
   
     
    
   
}
