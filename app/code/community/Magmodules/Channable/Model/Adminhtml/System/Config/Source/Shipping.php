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
 
class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Shipping {

	public function toOptionArray() {
		$type = array();
		$type[] = array('value' => 'price', 'label'=> Mage::helper('channable')->__('Price'));
		$type[] = array('value' => 'weight', 'label'=> Mage::helper('channable')->__('Weight'));				
		return $type;		
	}
	
}