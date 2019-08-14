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

class Magmodules_Channable_Adminhtml_ChannableController extends Mage_Adminhtml_Controller_Action
{

    /**
     * addToFlat contoller action
     */
    public function addToFlatAction()
    {
        $nonFlatAttributes = Mage::helper('channable')->checkFlatCatalog(Mage::getModel("channable/channable")->getFeedAttributes());

        foreach ($nonFlatAttributes as $key => $value) {
            Mage::getModel('catalog/resource_eav_attribute')->load($key)->setUsedInProductListing(1)->save();
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('channable')->__('Attributes added to Flat Catalog, please reindex Product Flat Data.'));

        $this->_redirect('adminhtml/system_config/edit/section/channable');
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/channable/channable');
    }

}