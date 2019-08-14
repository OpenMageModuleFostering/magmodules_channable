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
 
$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$installer->startSetup();

$installer->addAttribute(
    'catalog_category', 'channable_exclude', array(
    'group' => 'Feeds',
    'input' => 'select',
    'type' => 'int',
    'source' => 'eav/entity_attribute_source_boolean',
    'label' => 'Exclude Category for Channable',
    'required' => false,
    'user_defined' => true,
    'visible' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'position' => 99,
    )
);

$installer->endSetup();