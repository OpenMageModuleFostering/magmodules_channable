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
 
class Magmodules_Channable_Model_Common extends Mage_Core_Helper_Abstract {
		
    public function getProducts($config, $limit = '', $page = 1, $type = '') 
    {
		$store_id = $config['store_id'];
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setStore($store_id);
        $collection->addStoreFilter($store_id);
        $collection->addFinalPrice();
		$collection->addUrlRewrite();

		if(!empty($config['filter_enabled'])) {			
			$f_type = $config['filter_type'];
			$categories = $config['filter_cat'];
			if($f_type && $categories) {
				$table = Mage::getSingleton('core/resource')->getTableName('catalog_category_product');
				if($f_type == 'include') {
					$collection->getSelect()->join(array('cats' => $table), 'cats.product_id = e.entity_id');
					$collection->getSelect()->where('cats.category_id in (' . $categories . ')');			
				} else {
					$collection->getSelect()->join(array('cats' => $table), 'cats.product_id = e.entity_id');
					$collection->getSelect()->where('cats.category_id not in (' . $categories . ')');
				}
			}
		}	

		$collection->addAttributeToFilter('status', 1);        		
				
		if(($limit) && ($type != 'count')) {
			$collection->setPage($page, $limit)->getCurPage();
		}

		if(empty($config['conf_enabled'])) {
			$collection->addAttributeToFilter('visibility', array('in' => array(2,3,4)));        					
		}
		
		if($type != 'count') {

			// All attributes
			$attributes = array(); 
	        $attributes[] = 'url_key';        
			$attributes[] = 'sku';
			$attributes[] = 'price';
			$attributes[] = 'final_price';
			$attributes[] = 'price_model';
			$attributes[] = 'price_type';
			$attributes[] = 'special_price';
			$attributes[] = 'special_from_date';
			$attributes[] = 'special_to_date';        
			$attributes[] = 'type_id';                
			$attributes[] = 'tax_class_id';
			$attributes[] = 'tax_percent';
			$attributes[] = 'weight';
			$attributes[] = 'visibility';
			$attributes[] = 'type_id';
			$attributes[] = 'image';
			$attributes[] = 'small_image';
			$attributes[] = 'thumbnail';          

			if(!empty($config['filter_exclude'])) {
				$attributes[] = $config['filter_exclude'];
			}

			foreach($config['field'] as $field) {
				if(!empty($field['source'])) {
					$attributes[] = $field['source'];
				}	
			}		

			if(!empty($config['delivery_att'])) {
				$attributes[] = $config['delivery_att'];
			}

			if(!empty($config['delivery_att_be'])) {
				$attributes[] = $config['delivery_att_be'];
			}

			if(!empty($config['media_attributes'])) {
				foreach($config['media_attributes'] as $media_att) {
					$attributes[] = $media_att;			
				}			
			}
				
			$custom_values = '';
			if(isset($config['custom_name'])) {
				$custom_values .= $config['custom_name'] . ' ';
			}
			if(isset($config['custom_description'])) {
				$custom_values .= $config['custom_description'] . ' ';
			}
			if(isset($config['category_default'])) {
				$custom_values .= $config['category_default'] . ' ';
			}

			$att = preg_match_all("/{{([^}]*)}}/", $custom_values, $found_atts);			
			if(!empty($found_atts)) {
				foreach($found_atts[1] as $att) {
					$attributes[] = $att;
				}	
			}

			$collection->addAttributeToSelect(array_unique($attributes));   	

			if(!empty($config['filters'])) {
				foreach($config['filters'] as $filter) {
					$attribute = $filter['attribute'];
					if($filter['type'] == 'select') {
						$attribute = $filter['attribute'] . '_value';
					}
					$condition = $filter['condition'];
					$value = $filter['value'];
					switch ($condition) {
						case 'nin':								
							if(strpos($value, ',') !== FALSE) { $value = explode(',', $value); }
							$collection->addAttributeToFilter(array(array('attribute' => $attribute, $condition => $value), array('attribute' => $attribute, 'null' => true)));
							break;
						case 'in';
							if(strpos($value, ',') !== FALSE) { $value = explode(',', $value); }
							$collection->addAttributeToFilter($attribute, array($condition => $value));        					
							break;					
						case 'neq':								
							$collection->addAttributeToFilter(array(array('attribute' => $attribute, $condition => $value), array('attribute' => $attribute, 'null' => true)));
							break;
						case 'empty':								
							$collection->addAttributeToFilter($attribute, array('null' => true));     					
							break;
						case 'not-empty':								
							$collection->addAttributeToFilter($attribute, array('notnull' => true));				
							break;
						default:
							$collection->addAttributeToFilter($attribute, array($condition => $value));        					
							break;
					}				
				}
			} 
			$collection->joinTable('cataloginventory/stock_item', 'product_id=entity_id', array("qty" => "qty", "stock_status" => "is_in_stock", "manage_stock" => "manage_stock", "use_config_manage_stock" => "use_config_manage_stock", "min_sale_qty" => "min_sale_qty", "qty_increments" => "qty_increments", "enable_qty_increments" => "enable_qty_increments" ,"use_config_qty_increments" => "use_config_qty_increments"))->addAttributeToSelect(array('qty', 'stock_status', 'manage_stock', 'use_config_manage_stock', 'min_sale_qty', 'qty_increments', 'enable_qty_increments', 'use_config_qty_increments'));		
			$collection->getSelect()->group('e.entity_id');               
			if(!empty($config['hide_no_stock'])) {
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
			}
			$products = $collection->load();			

		} else {
			
			if(!empty($config['filters'])) {
				foreach($config['filters'] as $filter) {
					$attribute = $filter['attribute'];
					if($filter['type'] == 'select') {
						$attribute = $filter['attribute'] . '_value';
					}
					$condition = $filter['condition'];
					$value = $filter['value'];
					switch ($condition) {
						case 'nin':								
							if(strpos($value, ',') !== FALSE) { $value = explode(',', $value); }
							$collection->addAttributeToFilter(array(array('attribute' => $attribute, $condition => $value), array('attribute' => $attribute, 'null' => true)));
							break;
						case 'in';
							if(strpos($value, ',') !== FALSE) { $value = explode(',', $value); }
							$collection->addAttributeToFilter($attribute, array($condition => $value));        					
							break;					
						case 'neq':								
							$collection->addAttributeToFilter(array(array('attribute' => $attribute, $condition => $value), array('attribute' => $attribute, 'null' => true)));
							break;
						case 'empty':								
							$collection->addAttributeToFilter($attribute, array('null' => true));     					
							break;
						case 'not-empty':								
							$collection->addAttributeToFilter($attribute, array('notnull' => true));				
							break;
						default:
							$collection->addAttributeToFilter($attribute, array($condition => $value));        					
							break;
					}				
				}
			} 
			if(!empty($config['hide_no_stock'])) {
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);    					
			}

			$products = $collection->getSize();
		}	
        return $products;
    }	
    
}