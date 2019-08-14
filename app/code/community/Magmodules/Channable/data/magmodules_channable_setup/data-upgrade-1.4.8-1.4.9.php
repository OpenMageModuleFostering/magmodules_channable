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

$token = Mage::getModel('core/config_data')->getCollection()
    ->addFieldToFilter('path', 'channable/connect/token')        
    ->addFieldToFilter('scope_id', 0)
    ->addFieldToFilter('scope', 'default')
    ->getFirstItem()
    ->getValue();    

if (empty($token)) {
    $chars = str_split("abcdefghijklmnopqrstuvwxyz0123456789");
    for ($i = 0; $i < 32; $i++) {
        $token .= $chars[array_rand($chars)];
    }
}

Mage::getModel('core/config')->saveConfig('channable/connect/token', Mage::helper('core')->encrypt($token));
Mage::app()->getCacheInstance()->cleanType('config');
