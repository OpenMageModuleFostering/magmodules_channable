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

class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Mainimage
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')->addFieldToFilter(
            'frontend_input',
            'media_image'
        );
        $type = array();
        $type[] = array('value' => '', 'label' => Mage::helper('channable')->__('Use default'));
        foreach ($attributes as $attribute) {
            $type[] = array(
                'value' => $attribute->getData('attribute_code'),
                'label' => str_replace("'", "", $attribute->getData('frontend_label'))
            );
        }

        $type[] = array('value' => 'first', 'label' => Mage::helper('channable')->__('First Image'));
        $type[] = array('value' => 'last', 'label' => Mage::helper('channable')->__('Last Image'));

        return $type;
    }

}