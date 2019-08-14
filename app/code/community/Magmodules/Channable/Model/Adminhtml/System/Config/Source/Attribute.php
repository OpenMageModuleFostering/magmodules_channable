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

class Magmodules_Channable_Model_Adminhtml_System_Config_Source_Attribute
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = array();
        $optionArray[] = array(
            'value' => '',
            'label' => Mage::helper('channable')->__('-- none')
        );
        $optionArray[] = array(
            'label' => Mage::helper('channable')->__('- Product ID'),
            'value' => 'entity_id'
        );
        $optionArray[] = array(
            'label' => Mage::helper('channable')->__('- Final Price'),
            'value' => 'final_price'
        );
        $optionArray[] = array(
            'label' => Mage::helper('channable')->__('- Product Type'),
            'value' => 'type_id'
        );
        $optionArray[] = array(
            'label' => Mage::helper('channable')->__('- Attribute Set'),
            'value' => 'attribute_set_id'
        );

        $backendTypes = array('text', 'select', 'textarea', 'date', 'int', 'boolean', 'static', 'varchar', 'decimal');
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setOrder('frontend_label', 'ASC')
            ->addFieldToFilter('backend_type', $backendTypes);
        foreach ($attributes as $attribute) {
            if ($attribute->getData('attribute_code') != 'price') {
                if ($attribute->getData('frontend_label')) {
                    $label = str_replace("'", "", $attribute->getData('frontend_label'));
                } else {
                    $label = str_replace("'", "", $attribute->getData('attribute_code'));
                }

                $optionArray[] = array(
                    'value' => $attribute->getData('attribute_code'),
                    'label' => $label,
                );
            }
        }

        return $optionArray;
    }

}