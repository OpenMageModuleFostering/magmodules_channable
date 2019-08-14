<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 *
 * @category    Magmodules
 * @package     Magmodules_Channable
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2016 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/
 * =============================================================
 */

class Magmodules_Channable_Block_Adminhtml_System_Config_Form_Field_Feeds
    extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('channable');
        $storeIds = $helper->getStoreIds('channable/connect/enabled');
        $token = Mage::getStoreConfig('channable/connect/token');
        $htmlFeedlinks = '';

        foreach ($storeIds as $storeId) {
            $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
            $channableFeed = $baseUrl . 'channable/feed/get/code/' . $token . '/store/' . $storeId . '/array/1';
            $storeTitle = Mage::app()->getStore($storeId)->getName();
            $htmlFeedlinks .= '<tr><td>' . $storeTitle . '</td><td><a href="' . $channableFeed . '">Preview</a></td><td><a href="https://app.channable.com/connect/magento.html?store_id=' . $storeId . '&url=' . $baseUrl . '&token=' . $token . '" target="_blank">Click to auto connect with Channable</a></td></tr>';
        }

        if (!$htmlFeedlinks) {
            $htmlFeedlinks = $helper->__('No enabled feed(s) found');
        } else {
            $htmlHeader = '<div class="grid"><table cellpadding="0" cellspacing="0" class="border" style="width:425px;"><tbody><tr class="headings"><th>Store</th><th>Preview</th><th>Connect</th></tr>';
            $htmlFooter = '</tbody></table></div>';
            $htmlFeedlinks = $htmlHeader . $htmlFeedlinks . $htmlFooter;
        }


        return sprintf(
            '<tr id="row_%s"><td colspan="6" class="label" style="margin-bottom: 10px;">%s</td></tr>',
            $element->getHtmlId(), $htmlFeedlinks
        );
    }

}
