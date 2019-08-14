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
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Conditions {

	public function toOptionArray() {
		$type = array();
		$type[] = array('value'=> '', 'label'=> Mage::helper('channable')->__(''));
		$type[] = array('value'=> 'eq', 'label'=> Mage::helper('channable')->__('Equal'));
		$type[] = array('value'=> 'neq', 'label'=> Mage::helper('channable')->__('Not equal'));
		$type[] = array('value'=> 'gt', 'label'=> Mage::helper('channable')->__('Greater than'));
		$type[] = array('value'=> 'gteq', 'label'=> Mage::helper('channable')->__('Greater than or equal to'));
		$type[] = array('value'=> 'lt', 'label'=> Mage::helper('channable')->__('Less than'));
		$type[] = array('value'=> 'lteg', 'label'=> Mage::helper('channable')->__('Less than or equal to'));
		$type[] = array('value'=> 'in', 'label'=> Mage::helper('channable')->__('In'));
		$type[] = array('value'=> 'nin', 'label'=> Mage::helper('channable')->__('Not in'));
		$type[] = array('value'=> 'like', 'label'=> Mage::helper('channable')->__('Like'));
		$type[] = array('value'=> 'empty', 'label'=> Mage::helper('channable')->__('Empty'));
		$type[] = array('value'=> 'not-empty', 'label'=> Mage::helper('channable')->__('Not Empty'));
		return $type;		
	}
	
}