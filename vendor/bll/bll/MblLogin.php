<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL;

/**
 * Business Layer class for report Configuration entity
 */
class MblLogin extends \BLL\BLLSlim{
    
    /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->getAll($params);
    }
    
    
    /**
     * get private key  from public key
     * @param array$params
     * @return array
     */
    public function pkControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkControl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * check if user belongs to specific company
     * @param array$params
     * @return array
     * @author Okan CIRAN
     * @since 10/06/2016
     */
    public function isUserBelongToCompany($requestHeaderParams = array(), $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->isUserBelongToCompany($requestHeaderParams, $params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * get private key temp from public temp key
     * @param array$params
     * @return array
     * @author Okan CIRAN
     * @since 0.3 27/01/2016
     */
    public function pkTempControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkTempControl($params);  
        return $resultSet['resultSet'];
    }

    
    public function pkLoginControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkLoginControl($params);  
        return $resultSet['resultSet'];
    }

    public function gnlKullaniciMebKoduFindByTcKimlikNo($params = array()) {
 
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->gnlKullaniciMebKoduFindByTcKimlikNo($params);  
        return $resultSet['resultSet'];
    }
 
    
    public function gnlKullaniciFindForLoginByTcKimlikNo($params = array()) {

    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gnlKullaniciFindForLoginByTcKimlikNo($params);  
    return $resultSet['resultSet'];
    }
 
     
 
   /* public function mobilfirstdata($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    return $DAL->mobilfirstdata($params);
   
    }
    */ 
    
    public function mobilfirstdata($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->mobilfirstdata($params);  
    return $resultSet['resultSet'];
    }
   
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function mobilMenu($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->mobilMenu($params);  
        return $resultSet['resultSet'];
    }
    
    
       /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function gnlKisiOkulListesi($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->gnlKisiOkulListesi($params);  
        return $resultSet['resultSet'];
    }
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgrami($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgrami($params);  
    return $resultSet['resultSet'];
    }
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgramiDersSaatleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgramiDersSaatleri($params);  
    return $resultSet['resultSet'];
    }
     
     
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersPrgDersSaatleriOgrencileri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersPrgDersSaatleriOgrencileri($params);  
    return $resultSet['resultSet'];
    } 
    
       /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenVeliRandevulari($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenVeliRandevulari($params);  
    return $resultSet['resultSet'];
    } 
    
    
     /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insertDevamsizlik($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->insertDevamsizlik($params);
    }
    
    
      /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function veliOgrencileri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->veliOgrencileri($params);  
    return $resultSet['resultSet'];
    } 
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciDevamsizlikListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciDevamsizlikListesi($params);  
    return $resultSet['resultSet'];
    }  
    
     
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumyoneticisisubelistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumyoneticisisubelistesi($params);  
    return $resultSet['resultSet'];
    }
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kysubeogrencilistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kysubeogrencilistesi($params);  
    return $resultSet['resultSet'];
    } 
           
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kySubeOgrenciDersListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kySubeOgrenciDersListesi($params);  
    return $resultSet['resultSet'];
    } 
     
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmensinavlistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmensinavlistesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function yakinisinavlistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->yakinisinavlistesi($params);  
    return $resultSet['resultSet'];
    } 
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumYoneticisiSinavListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumYoneticisiSinavListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gelenMesajListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gelenMesajListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiOgretmen($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiOgretmen($params);  
    return $resultSet['resultSet'];
    } 
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiKurumYoneticisi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiKurumYoneticisi($params);  
    return $resultSet['resultSet'];
    } 
    
      /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciVeYakiniDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciVeYakiniDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function kurumPersoneliSinifListesi($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->kurumPersoneliSinifListesi($params);  
        return $resultSet['resultSet'];
    }
      
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumPersoneliDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumPersoneliDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function sinifSeviyeleriCombo($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->sinifSeviyeleriCombo($params);  
    return $resultSet['resultSet'];
    } 
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function sinifSeviyeleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->sinifSeviyeleri($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gnlProfil($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gnlProfil($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumVePersonelDevamsizlik($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumVePersonelDevamsizlik($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gelenMesajDetay($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gelenMesajDetay($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiOgrenciveYakin($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiOgrenciveYakin($params);  
    return $resultSet['resultSet'];
    } 
    
    
    
}

