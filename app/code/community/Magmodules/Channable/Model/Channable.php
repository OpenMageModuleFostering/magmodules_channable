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
 
class Magmodules_Channable_Model_Channable extends Magmodules_Channable_Model_Common {
	
	public function generateFeed($storeId, $limit = '', $page = '', $time_start) 
	{
        $limit = $this->setMemoryLimit($storeId);
        $config = $this->getFeedConfig($storeId);
        $clean = $this->cleanItemUpdates($storeId, $page);
		$products = $this->getProducts($config, $config['limit'], $page);	
		$prices = Mage::helper('channable')->getTypePrices($config, $products);
		if($feed = $this->getFeedData($products, $config, $time_start, $prices)) {
			return $feed;
		}	
	}

	public function getFeedData($products, $config, $time_start, $prices) 
	{		
		$count = $this->getProducts($config, '', '', 'count');
		foreach($products as $product) {
			if($parent_id = Mage::helper('channable')->getParentData($product, $config)) {
				$parent = $this->loadParentProduct($parent_id, $config['store_id'], $config['parent_att']); 
			} else {
				$parent = '';
			}
			if($product_data = Mage::helper('channable')->getProductDataRow($product, $config, $parent)) {
				foreach($product_data as $key => $value) {
					if(!is_array($value)) { $product_row[$key] = $value; }	
				}
				if($extra_data = $this->getExtraDataFields($product_data, $config, $product, $prices)) {
					$product_row = array_merge($product_row, $extra_data);
				}
				$feed['products'][] = $product_row;				
				if($config['item_updates']) {
					$this->processItemUpdates($product_row, $config['store_id']);
				}	
				unset($product_row);
			}
		}			
		if(!empty($feed)) {
			$return_feed = array();
			$return_feed['config'] = $this->getFeedHeader($config, $count, $time_start, count($feed['products']));
			$return_feed['products'] = $feed['products'];			
			return $return_feed;
		} else {
			$return_feed = array();
			$return_feed['config'] = $this->getFeedHeader($config, $count, $time_start);
			return $return_feed;	
		}
	}
	
	public function getFeedConfig($storeId) 
	{
		
		$config							= array();
		$feed 							= Mage::helper('channable'); 
		$websiteId 						= Mage::app()->getStore($storeId)->getWebsiteId();

		// DEFAULTS
		$config['store_id'] 			= $storeId;
		$config['website_name']			= $feed->cleanData(Mage::getModel('core/website')->load($websiteId)->getName(), 'striptags');
		$config['website_url']			= Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		$config['media_url']			= Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$config['media_image_url']		= $config['media_url'] . 'catalog' . DS . 'product';
		$config['media_attributes']		= $feed->getMediaAttributes();
		$config['limit']	 			= Mage::getStoreConfig('channable/connect/max_products', $storeId);
		$config['version']				= (string)Mage::getConfig()->getNode()->modules->Magmodules_Channable->version;
		$config['media_gallery_id'] 	= Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'media_gallery');
		$config['item_updates']			= Mage::getStoreConfig('channable_api/item/enabled', $storeId);
		$config['filters']				= @unserialize(Mage::getStoreConfig('channable/filter/advanced', $storeId));	
		$config['product_url_suffix']   = $feed->getProductUrlSuffix($storeId);

		// PRODUCT & CATEGORY 
		$config['filter_enabled']		= Mage::getStoreConfig('channable/filter/category_enabled', $storeId);
		$config['filter_cat']			= Mage::getStoreConfig('channable/filter/categories', $storeId);		
		$config['filter_type']			= Mage::getStoreConfig('channable/filter/category_type', $storeId);
		$config['filter_status']		= Mage::getStoreConfig('channable/filter/visibility_inc', $storeId);
		$config['hide_no_stock']		= Mage::getStoreConfig('channable/filter/stock', $storeId);
		$config['conf_enabled']			= Mage::getStoreConfig('channable/data/conf_enabled', $storeId);	
		$config['conf_fields']			= Mage::getStoreConfig('channable/data/conf_fields', $storeId);	
		$config['parent_att']			= $this->getParentAttributeSelection($config['conf_fields']);	

		$config['conf_switch_urls']		= Mage::getStoreConfig('channable/data/conf_switch_urls', $storeId);	
		$config['stock_manage']			= Mage::getStoreConfig('cataloginventory/item_options/manage_stock');
		$config['use_qty_increments']	= Mage::getStoreConfig('cataloginventory/item_options/enable_qty_increments');
		$config['qty_increments']		= Mage::getStoreConfig('cataloginventory/item_options/qty_increments');
		$config['delivery'] 			= Mage::getStoreConfig('channable/data/delivery', $storeId);
		$config['delivery_att'] 		= Mage::getStoreConfig('channable/data/delivery_att', $storeId);
		$config['delivery_in'] 			= Mage::getStoreConfig('channable/data/delivery_in', $storeId);
		$config['delivery_out'] 		= Mage::getStoreConfig('channable/data/delivery_out', $storeId);			
		$config['delivery_be'] 			= Mage::getStoreConfig('channable/data/delivery', $storeId);
		$config['delivery_be'] 			= Mage::getStoreConfig('channable/data/delivery_be', $storeId);
		$config['delivery_att_be'] 		= Mage::getStoreConfig('channable/data/delivery_att_be', $storeId);
		$config['delivery_in_be'] 		= Mage::getStoreConfig('channable/data/delivery_in_be', $storeId);
		$config['delivery_out_be'] 		= Mage::getStoreConfig('channable/data/delivery_out_be', $storeId);	
		$config['delivery_out_be'] 		= Mage::getStoreConfig('channable/data/delivery_out_be', $storeId);	
		$config['images'] 				= Mage::getStoreConfig('channable/data/images', $storeId);
		$config['default_image'] 		= Mage::getStoreConfig('channable/data/default_image', $storeId);
		$config['skip_validation'] 		= false;

		// WEIGHT
		$config['weight']				= Mage::getStoreConfig('channable/data/weight', $storeId);
		$config['weight_units']			= Mage::getStoreConfig('channable/data/weight_units', $storeId);

		// PRICE
		$config['price_scope']			= Mage::getStoreConfig('catalog/price/scope');
		$config['price_add_tax']	 	= Mage::getStoreConfig('channable/data/add_tax', $storeId);
		$config['price_add_tax_perc']	= Mage::getStoreConfig('channable/data/tax_percentage', $storeId);
		$config['force_tax']	 		= Mage::getStoreConfig('channable/data/force_tax', $storeId);
		$config['currency'] 			= Mage::app()->getStore($storeId)->getCurrentCurrencyCode(); 
		$config['base_currency_code'] 	= Mage::app()->getStore($storeId)->getBaseCurrencyCode();
		$config['markup']				= Mage::helper('channable')->getPriceMarkup($config);
		$config['use_tax']				= Mage::helper('channable')->getTaxUsage($config);
		
		// SHIPPING
		$config['shipping_prices']		= @unserialize(Mage::getStoreConfig('channable/advanced/shipping_price', $storeId));
		$config['shipping_method']		= Mage::getStoreConfig('channable/advanced/shipping_method', $storeId);
		
		// FIELD & CATEGORY DATA
		$config['field']				= $this->getFeedAttributes($config, $storeId);
		$config['category_data']		= $feed->getCategoryData($config, $storeId);
			
		return $config;	
	}

	public function getFeedAttributes($config = '', $storeId = 0) 
	{
		$attributes = array();
		$attributes['id']			= array('label' => 'id', 'source' => 'entity_id');
		$attributes['name']			= array('label' => 'name', 'source' => Mage::getStoreConfig('channable/data/name', $storeId));
		$attributes['description']	= array('label' => 'description', 'source' => Mage::getStoreConfig('channable/data/description', $storeId));
		$attributes['product_url']	= array('label' => 'url', 'source' => '');
		$attributes['image_link']	= array('label' => 'image', 'source' => Mage::getStoreConfig('channable/data/default_image', $storeId));
		$attributes['price']		= array('label' => 'price', 'source' => '');		
		$attributes['sku']			= array('label' => 'sku', 'source' => Mage::getStoreConfig('channable/data/sku', $storeId));
		$attributes['brand']		= array('label' => 'brand', 'source' => Mage::getStoreConfig('channable/data/brand', $storeId));
		$attributes['size']			= array('label' => 'size', 'source' => Mage::getStoreConfig('channable/data/size', $storeId));
		$attributes['color']		= array('label' => 'color', 'source' => Mage::getStoreConfig('channable/data/color', $storeId));
		$attributes['material']		= array('label' => 'material', 'source' => Mage::getStoreConfig('channable/data/material', $storeId));
		$attributes['gender']		= array('label' => 'gender', 'source' => Mage::getStoreConfig('channable/data/gender', $storeId));
		$attributes['ean']			= array('label' => 'ean', 'source' => Mage::getStoreConfig('channable/data/ean', $storeId));
		$attributes['categories']	= array('label' => 'categories', 'source' => '', 'parent' => 1);				
		$attributes['type']			= array('label' => 'type', 'source' => 'type_id');
		$attributes['status']		= array('label' => 'status', 'source' => 'status', 'parent' => 1);
		$attributes['visibility']	= array('label' => 'visibility', 'source' => 'visibility');
		$attributes['parent_id']	= array('label' => 'item_group_id', 'source' => 'entity_id', 'parent' => 1);				
		$attributes['weight']		= array('label' => 'weight', 'source' => '');		
		
		if(Mage::getStoreConfig('channable/data/stock_status', $storeId)) {
			$attributes['stock_status']	= array('label' => 'stock_status', 'source' => 'stock_status');		
		}
		if(Mage::getStoreConfig('channable/data/stock', $storeId)) {
			$attributes['stock'] = array('label' => 'qty', 'source' => 'qty', 'action' => 'round');		
		}

		if(Mage::getStoreConfig('channable/data/delivery', $storeId) == 'attribute') {
			$attributes['delivery']	= array('label' => 'delivery_period', 'source' => Mage::getStoreConfig('channable/data/delivery_att', $storeId));
		}	
		if(Mage::getStoreConfig('channable/data/delivery_be', $storeId) == 'attribute') {
			$attributes['delivery_be'] = array('label' => 'delivery_period_be', 'source' => Mage::getStoreConfig('channable/data/delivery_att_be', $storeId));
		}	
					
		if($extra_fields = @unserialize(Mage::getStoreConfig('channable/advanced/extra', $storeId))) {
			foreach($extra_fields as $extra_field) {
				$attributes[$extra_field['attribute']] = array('label' => $extra_field['label'], 'source' => $extra_field['attribute'], 'action' => '');		
			}
		}
		return Mage::helper('channable')->addAttributeData($attributes, $config);	
	}
	
	protected function getPrices($data, $conf_prices, $product, $currency) 
	{			
		$prices = array();
		$id = $product->getEntityId();
		if(!empty($conf_prices[$id])) {
			$conf_price = Mage::helper('tax')->getPrice($product, $conf_prices[$id], true);
			$conf_price_reg = Mage::helper('tax')->getPrice($product, $conf_prices[$id . '_reg'], true);
			if($conf_price_reg > $conf_price) {
				$prices['special_price'] = number_format($conf_price, 2, '.', '') . ' ' . $currency;
				$prices['price'] = number_format($conf_price_reg, 2, '.', '') . ' ' . $currency;
			} else {
				$prices['price'] = number_format($conf_price, 2, '.', '') . ' ' . $currency;			
			}
		} else {
			$prices['price'] = $data['price'];
			$prices['special_price'] = '';
			$prices['special_price_from'] = '';
			$prices['special_price_to'] = '';
			if(isset($data['sales_price'])) {
				$prices['price'] = $data['regular_price'];
				$prices['special_price'] = $data['sales_price'];
				if(isset($data['sales_date_start'])) {
					$prices['special_price_from'] = $data['sales_date_start'];		
				}
				if(isset($data['sales_date_end'])) {
					$prices['special_price_to'] = $data['sales_date_end'];		
				}
			}
		}
		return $prices;
	}
	
	public function getShipping($data, $config, $weight, $product) 
	{
		$shipping_array = array();
					
		if($config['delivery'] == 'fixed') {
			if(!empty($data['availability'])) {			
				if(!empty($config['delivery_in'])) {
					$shipping_array['delivery'] = $config['delivery_in'];
				}	
			} else {
				if(!empty($config['delivery_out'])) {
					$shipping_array['delivery'] = $config['delivery_out'];
				}	
			}			
		}
		
		if($config['delivery_be'] == 'fixed') {
			if(!empty($data['availability'])) {			
				if(!empty($config['delivery_in_be'])) {
					$shipping_array['delivery_be'] = $config['delivery_in_be'];
				}	
			} else {
				if(!empty($config['delivery_out'])) {
					$shipping_array['delivery_be'] = $config['delivery_out_be'];
				}	
			}			
		}

		$shipping_cost = '0.00';
		$cal_value =  '';
		if($config['shipping_method'] == 'weight') {
			$cal_value = $weight;
		} else {
			if(!empty($data['price']['final_price_clean'])) {
				$cal_value = $data['price']['final_price_clean'];
			}		
		}
		
		if(!empty($config['shipping_prices'])) {
			foreach($config['shipping_prices'] as $shipping_price) {
				if(($cal_value >= $shipping_price['price_from']) && ($cal_value <= $shipping_price['price_to'])) {
					$shipping_cost = $shipping_price['cost'];
					$shipping_cost = number_format($shipping_cost, 2, '.', '');					
					if(empty($shipping_price['country'])) {
						$shipping_array['shipping'] = $shipping_cost;
					} else {
						$label = 'shipping_' . strtolower($shipping_price['country']);
						$shipping_array[$label] = $shipping_cost;												
						if(strtolower($shipping_price['country']) == 'nl') {
							$shipping_array['shipping'] = $shipping_cost;						
						}
					}
				}
			}
		}
		return $shipping_array;
	}

	protected function getCategoryData($product_data, $config) 
	{			
		$category = array(); $i = 0;
		if(!empty($product_data['categories'])) {
			foreach($product_data['categories'] as $cat) {
				if(!empty($cat['path'])) {
					if($i == 0) {
						$category['category'] = implode(' > ', $cat['path']);						
					}	
					$category['categories'][] = implode(' > ', $cat['path']);	
					$i++;				
				}		
			}
		}		
		return $category;		
	}

	protected function getStockData($product_data, $config, $product) 
	{			
		$stock_data = array();
		if(!empty($product_data['qty'])) {
			$stock_data['qty'] = $product_data['qty'];
		} else {
			$stock_data['qty'] = (string)'0';		
		}	
		if($product->getUseConfigManageStock()) {
			$stock_data['manage_stock'] = (string)$config['stock_manage'];
		} else {
			$stock_data['manage_stock'] = (string)$product->getManageStock();			
		}	
		if(!empty($product['min_sale_qty'])) {
			$stock_data['min_sale_qty'] = (string)round($product['min_sale_qty']);
		} else {
			$stock_data['min_sale_qty'] = '1';	
		}
		if($product->getUseEnableQtyIncrements()) {
			if(!empty($config['use_qty_increments'])) {
				$stock_data['qty_increments'] = (string)$config['qty_increments'];
			}	
		} else {
			if($product->getUseConfigQtyIncrements()) {
				$stock_data['qty_increments'] = (string)$config['qty_increments'];			
			} else {
				$stock_data['qty_increments'] = round($product['qty_increments']);				
			}			
		}	
		if(empty($stock_data['qty_increments'])) {
			$stock_data['qty_increments'] = '1';
		}	
		return $stock_data;		
	}
	
	public function getImages($product_data, $config) 
	{			
		$_images = array(); 
		if(!empty($config['default_image'])) {
			if(!empty($product_data['image'][$config['default_image']])) {
				$_images['image_link'] = $product_data['image'][$config['default_image']];
			}			
		} else {
			if(!empty($product_data['image']['base'])) {
				$_images['image_link'] = $product_data['image']['base'];
			}						
		}
		if(empty($_images['image_link'])) {
			if(!empty($product_data['image_link'])) {
				$_images['image_link'] = $product_data['image_link'];
			}	
		}	
		if(!empty($product_data['image']['all'])) {
			$_additional = array();
			foreach($product_data['image']['all'] as $image) {
				if(empty($_images['image_link'])) {
					$_images['image_link'] = $image;
				}	
				if($image != $_images['image_link']) {				
					$_additional[] = $image;
				}	
			}		
			if(count($_additional) > 0) {
				$_images['additional_imagelinks'] = $_additional;
			}
		}
		return $_images;
	}
		
	protected function getExtraDataFields($product_data, $config, $product, $prices) 
	{
		$_extra = array();
		if(!empty($product_data['price'])) {
			if($_prices = $this->getPrices($product_data['price'], $prices, $product, $config['currency'])) {
				$_extra = array_merge($_extra, $_prices);
			}	
		}
		if($_shipping = $this->getShipping($product_data, $config, $product->getWeight(), $product)) {
			$_extra = array_merge($_extra, $_shipping);
		}
		if($_category_data = $this->getCategoryData($product_data, $config)) {
			$_extra = array_merge($_extra, $_category_data);
		}
		if($_stock_data = $this->getStockData($product_data, $config, $product)) {
			$_extra = array_merge($_extra, $_stock_data);
		}
		if($config['images'] == 'all') {
			if($_images = $this->getImages($product_data, $config)) {
				$_extra = array_merge($_extra, $_images);
			}		
		}
		return $_extra;
	}	
	
	protected function getFeedHeader($config, $count, $time_start, $product_count = 0) 
	{
		$header = array();
		$header['system'] = 'Magento';
		$header['extension'] = 'Magmodules_Channable';
		$header['extension_version'] = $config['version'];
		$header['store'] = $config['website_name'];
		$header['url'] = $config['website_url'];
		$header['products_total'] = $count;
		$header['products_limit'] = $config['limit'];
		$header['products_pages'] = (($config['limit']) && ($count > $config['limit'])) ? ceil($count / $config['limit']) : 1;
		$header['processing_time'] = number_format((microtime(true) - $time_start), 4);
		if(($count > 0) && ($config['limit'] == $product_count)) {
			$header['next_page'] = 'true';
		} else {
			$header['next_page'] = 'false';
		}
		return $header;
	}

	protected function cleanItemUpdates($storeId, $page) 
	{
		if(empty($page)) {
			if(Mage::helper('core')->isModuleEnabled('Magmodules_Channableapi')) {
				Mage::getModel('channableapi/items')->cleanItemStore($storeId);				
			}			
		}	
	}

	protected function processItemUpdates($product_row, $store_id) 
	{
		if(Mage::helper('core')->isModuleEnabled('Magmodules_Channableapi')) {
			Mage::getModel('channableapi/items')->saveItemFeed($product_row, $store_id);				
		}			
	}
	
	protected function setMemoryLimit($storeId)
	{
		if(Mage::getStoreConfig('channable/server/overwrite', $storeId)) {
			if($memory_limit = Mage::getStoreConfig('channable/server/memory_limit', $storeId)) {
				ini_set('memory_limit', $memory_limit);
			}		
			if($max_execution_time = Mage::getStoreConfig('channable/server/max_execution_time', $storeId)) {
				ini_set('max_execution_time', $max_execution_time);
			}		
		}	
	}	
}
