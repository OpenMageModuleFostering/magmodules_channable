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

class Magmodules_Channable_Block_Adminhtml_Config_Form_Field_Extra extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract {

	protected $_renders = array();
   	
    public function __construct() 
    {                
        $layout = Mage::app()->getFrontController()->getAction()->getLayout();       
        $renderer_attributes = $layout->createBlock('channable/adminhtml_config_form_renderer_select', '', array('is_render_to_js_template' => true));                							                
        $renderer_attributes->setOptions(Mage::getModel('channable/adminhtml_system_config_source_attribute')->toOptionArray());

        $this->addColumn('attribute', array(
            'label' 	=> Mage::helper('channable')->__('Magento Attribute'),
            'style' 	=> 'width:120px',
        	'renderer' 	=> $renderer_attributes
        ));
        
        $this->addColumn('label', array(
            'label' 	=> Mage::helper('channable')->__('Title'),
            'style' 	=> 'width:120px',
        ));

        $this->_renders['attribute'] = $renderer_attributes; 
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('channable')->__('Add Attribute');
        parent::__construct();
    }
    
    protected function _prepareArrayRow(Varien_Object $row) 
    {    	
    	foreach ($this->_renders as $key => $render) {
	        $row->setData('option_extra_attr_' . $render->calcOptionHash($row->getData($key)), 'selected="selected"');
    	}
    } 

}