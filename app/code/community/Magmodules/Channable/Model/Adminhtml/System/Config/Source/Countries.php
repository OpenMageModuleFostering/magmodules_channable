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

class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Countries
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $countries = array();
        $countries[] = array('value' => '', 'label' => Mage::helper('channable')->__('-- All Countries'));
        $source = Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
        unset($source[0]);

        return array_merge($countries, $source);
    }

}