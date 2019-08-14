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

class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Visibility
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $type = array();
        $type[] = array('value' => '1', 'label' => Mage::helper('adminhtml')->__('Not Visible Individually'));
        $type[] = array('value' => '2', 'label' => Mage::helper('adminhtml')->__('Catalog'));
        $type[] = array('value' => '3', 'label' => Mage::helper('adminhtml')->__('Search'));
        $type[] = array('value' => '4', 'label' => Mage::helper('adminhtml')->__('Catalog, Search'));

        return $type;
    }

}