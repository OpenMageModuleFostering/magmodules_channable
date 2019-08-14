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

class Magmodules_Channable_Block_Adminhtml_Config_Form_Field_Shipping
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    protected $_renders = array();

    /**
     * Magmodules_Channable_Block_Adminhtml_Config_Form_Field_Shipping constructor.
     */
    public function __construct()
    {        
        $layout = Mage::app()->getFrontController()->getAction()->getLayout();
        $rendererCoutries = $layout->createBlock(
            'channable/adminhtml_config_form_renderer_select',
            '',
            array('is_render_to_js_template' => true)
        );

        $rendererCoutries->setOptions(
            Mage::getModel('channable/adminhtml_system_config_source_countries')->toOptionArray()
        );

        $this->addColumn(
            'country', array(
            'label' => Mage::helper('channable')->__('Country'),
            'style' => 'width:120px',
            'renderer' => $rendererCoutries
            )
        );    

        $this->addColumn(
            'price_from', array(
            'label'     => Mage::helper('channable')->__('Price From'),
            'style'     => 'width:40px',
            )
        );
        $this->addColumn(
            'price_to', array(
            'label'     => Mage::helper('channable')->__('Price To'),
            'style'     => 'width:40px',
            )
        );
        $this->addColumn(
            'cost', array(
            'label'     => Mage::helper('channable')->__('Cost'),
            'style'     => 'width:40px',
            )
        );        

        $this->_renders['country'] = $rendererCoutries;
        
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('channable')->__('Add Option');
        parent::__construct();
    }

    /**
     * @param Varien_Object $row
     */
    protected function _prepareArrayRow(Varien_Object $row)
    {        
        foreach ($this->_renders as $key => $render) {
            $row->setData('option_extra_attr_' . $render->calcOptionHash($row->getData($key)), 'selected="selected"');
        }
    } 

}