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
 
class Magmodules_Channable_Block_Adminhtml_Widget_Info_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    public function render(Varien_Data_Form_Element_Abstract $element) 
    {
        $magento_version = Mage::getVersion();
        $module_version = Mage::getConfig()->getNode()->modules->Magmodules_Channable->version;
        
		$logo_link = '//www.magmodules.eu/logo/channable/' . $module_version . '/' . $magento_version . '/logo.png';
            
		$html = '<div style="background:url(\'' . $logo_link . '\') no-repeat scroll 15px center #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 200px;">
					<h4>About Magmodules.eu</h4>
					<p>We are a Magento only E-commerce Agency located in the Netherlands.<br>
                    <br />
                  	<table width="500px" border="0">
						<tr>
							<td width="58%">View more extensions from us:</td>
							<td width="42%"><a href="http://www.magentocommerce.com/magento-connect/developer/Magmodules" target="_blank">Magento Connect</a></td>
						</tr>
						<tr>
							<td height="30">Visit our website:</td>
							<td><a href="http://www.magmodules.eu" target="_blank">www.magmodules.eu</a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td height="30"><strong>Read everything about the extension configuration in our <a href="http://www.magmodules.eu/help/channable-connect" target="_blank">Knowledgebase</a></strong>.</td>
							<td>&nbsp;</td>
						</tr>
					</table>
                </div>';

		if(Mage::helper('channable')->checkOldVersion('Channable')) {
			$msg = '<div id="messages"><ul class="messages"><li class="error-msg"><ul><li><span>' . Mage::helper('channable')->__('Old version detected on the server, please remove the directory <u>app/code/local/Magmodules/Channable</u> and flush cache!') . '</span></li></ul></li></ul></div>';
			$html = $msg . $html;
		}

		if(empty($oldversion)) {
			if(Mage::getStoreConfig('catalog/frontend/flat_catalog_product')) {
				$store_id =  Mage::helper('channable')->getStoreIdConfig();
				$non_flat_attributes = Mage::helper('channable')->checkFlatCatalog(Mage::getModel("channable/channable")->getFeedAttributes('', $store_id)); 
				if(count($non_flat_attributes) > 0) {
					$html .= '<div id="messages"><ul class="messages"><li class="error-msg"><ul><li><span>';
					$html .= $this->__('Warning: The following used attribute(s) were not found in the flat catalog: %s. This can result in empty data or higher resource usage. Click <a href="%s">here</a> to add these to the flat catalog. ', implode($non_flat_attributes, ', '), $this->getUrl('*/channable/addToFlat'));
					$html .= '</span></ul></li></ul></div>';
				}	
			}
		}
		
        return $html;
    }

}