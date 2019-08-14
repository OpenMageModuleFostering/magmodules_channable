<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Channable
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2016 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Channable_Block_Adminhtml_System_Config_Form_Field_Token extends Mage_Adminhtml_Block_System_Config_Form_Field {

    public function _getElementHtml(Varien_Data_Form_Element_Abstract $element) 
    {		
		if($token = Mage::getStoreConfig('channable/connect/token')) {
			return $token;
		} else {			
			if($license = Mage::getStoreConfig('channable/general/license_key')) {		
				$token = substr(Mage::getStoreConfig('channable/general/license_key'), 10, -15);
				$config = new Mage_Core_Model_Config();
				$config->saveConfig('channable/connect/token', $token, 'default', 0);
				Mage::app()->getCacheInstance()->cleanType('config');
				return $token;								
			} else {
				$token = '';
				$chars = str_split("abcdefghijklmnopqrstuvwxyz0123456789");
			    for($i = 0; $i < 16; $i++) {
	    			$token .= $chars[array_rand($chars)];
    			}
				$config = new Mage_Core_Model_Config();
				$config->saveConfig('channable/connect/token', $token, 'default', 0);
				Mage::app()->getCacheInstance()->cleanType('config');
				return $token;								
			}
		}
    }
    
}