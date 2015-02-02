<?php
class MST_Titan_Helper_Config extends Mage_Core_Helper_Abstract {
	protected $_generalInfo = array ();
	protected $_pageOptions = array ();
	protected function getStoreConfig($path) {
		return Mage::getStoreConfig($path);
	}
	/**
	 * Get theme layout
	 *
	 * return fullwidth|t2-boxed|t2-boxed-medium
	 */
	public function getThemeLayout() {
		return $this->getStoreConfig("titan/general/theme_layout");
	}
	public function getThemeStyle() {
		return $this->getStoreConfig("titan/general/theme_style");
	}
	public function isEnableTopBar() {
		if ($this->getStoreConfig("titan/general/enable_top_bar") == 1) {
			return true;
		}
		return false;
	}
	public function isEnableSettingBox() {
		if ($this->getStoreConfig("titan/general/enable_setting_box") == 1) {
			return true;
		}
		return false;
	}
	/**
	 * Get logo type
	 *
	 * return default|text|image 
	 */
	public function getLogoType() {
		return $this->getStoreConfig("titan/general/logo_type");
	}
	public function getLogo() {
		$logo = $this->getStoreConfig("titan/general/logo_image");
		if ($logo != "") {
			$logo = Mage::getBaseUrl ( Mage_Core_Model_Store::URL_TYPE_MEDIA ) . "titan/logo/" . $logo;
		}
		return $logo;
	}
	public function getLogoText() {
		return $this->getStoreConfig("titan/general/logo_text");
	}
	public function getSlogan() {
		return $this->getStoreConfig("titan/general/store_slogan");
	}
	/**
	 * Get number of products per row on Gridview mode.
	 *
	 * return int
	 */
	public function getProductItemsPerRow() {
		$item = $this->getStoreConfig("titan/category/product_per_row");
		if ($item == 2) {
			return 'col-sm-6 col-xs-12';
		}
		if ($item == 3) {
			return 'col-sm-4 col-xs-6';
		}
		if ($item == 4) {
			return 'col-sm-3 col-xs-6';
		}
	}
	/**
	 * Get thumbnail image width in category page
	 *
	 * return string
	 */
	public function getThumbnailWidth() {
		return $this->getStoreConfig("titan/category/image_width");
	}
	/**
	 * Get thumbnail image height in category page
	 *
	 * return string
	 */
	public function getThumbnailHeight() {
		return $this->getStoreConfig("titan/category/image_height");
	}
	/**
	 * Show sub categories thumbnails(image, title) below category description
	 *
	 * return boolean
	 */
	public function isShowSubCategories() {
		if ($this->getStoreConfig("titan/category/show_sub") == 1) {
			return true;
		}
		return false;
	}
	/**
	 * * Show Media Layout * * return [1,2]
	 */
	public function getMediaStyle() {
		return $this->getStoreConfig("titan/product/media_layout");
	}
	/**
	 * * Get Product Large Image Width * * return number
	 */
	public function getLargeImageWidth() {
		return $this->getStoreConfig("titan/product/large_image_width");
	}
	/**
	 * * Get Large Image Height * * return number
	 */
	public function getLargeImageHeight() {
		return $this->getStoreConfig("titan/product/large_image_height");
	}
	/**
	 * * Get Product Main Image Width * * return number
	 */
	public function getMainWidth() {
		return $this->getStoreConfig("titan/product/main_image_width");
	}
	/**
	 * * Get Product Main Image Height * * return number
	 */
	public function getMainHeight() {
		return $this->getStoreConfig("titan/product/main_image_height");
	}
	/**
	 * * Get Product Information Tab Style
	 * * Return string
	 */
	public function getProducInformationStyle() {
		return $this->getStoreConfig("titan/product/product_info");
	}
	/**
	 * Get navigation config
	 *
	 * return array
	 */
	public function getNavigationConfig() {
		$navConfig = array(
			'menu_type' => $this->getStoreConfig("titan/navigation/main_menu"),
			'menu_group' => $this->getStoreConfig("titan/navigation/mcp_group"),
			'custom_class' => $this->getStoreConfig("titan/navigation/custom_class")
		);
		//return Mage::getModel("titan/navigation")->getNavigationConfig();
		return $navConfig;
	}
	/**
	 * Get customization tab data
	 *
	 * return array
	 */
	public function getCustomizationConfig() {
		$customData = array(
			'after_head_open' => $this->getStoreConfig("titan/customization/after_head_open"),
			'before_head_close' => $this->getStoreConfig("titan/customization/before_head_close"),
			'after_body_open' => $this->getStoreConfig("titan/customization/after_body_open"),
			'before_body_close' => $this->getStoreConfig("titan/customization/before_body_close"),
		);
		return $customData;
	}
} 