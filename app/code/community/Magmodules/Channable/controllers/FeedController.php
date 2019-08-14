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

class Magmodules_Channable_FeedController extends Mage_Core_Controller_Front_Action
{

    /**
     *
     */
    public function getAction()
    {
        $storeId = $this->getRequest()->getParam('store');
        if (Mage::getStoreConfig('channable/connect/enabled', $storeId)) {
            $code = $this->getRequest()->getParam('code');
            $page = $this->getRequest()->getParam('page');
            if ($storeId && $code) {
                if ($code == Mage::getStoreConfig('channable/connect/token')) {
                    $timeStart = microtime(true);
                    $limit = Mage::getStoreConfig('channable/connect/max_products', $storeId);
                    $appEmulation = Mage::getSingleton('core/app_emulation');
                    $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
                    Mage::app()->loadAreaPart(
                        Mage_Core_Model_App_Area::AREA_GLOBAL,
                        Mage_Core_Model_App_Area::PART_EVENTS
                    )->loadArea(Mage_Core_Model_App_Area::AREA_FRONTEND);
                    if ($feed = Mage::getModel('channable/channable')->generateFeed(
                        $storeId, $limit, $page,
                        $timeStart
                    )
                    ) {
                        if ($this->getRequest()->getParam('array')) {
                            $this->getResponse()->setBody(Zend_Debug::dump($feed, null, false));
                        } else {
                            $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
                            $this->getResponse()->setBody(json_encode($feed));
                        }
                    }

                    $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
                }
            }
        }
    }

}
