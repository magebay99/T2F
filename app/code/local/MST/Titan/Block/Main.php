<?php
class MST_Titan_Block_Main extends Mage_Core_Block_Template {
	protected $_blockModel = null;
	public function __construct() {
		$this->_blockModel = Mage::getModel("titan/titanblock");
	}
	public function getBaseUrl() {
		$baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		$validUrl = $this->getValidUrl($baseUrl, Mage::app()->getStore()->isCurrentlySecure());
		$baseUrl = $validUrl;
		return $baseUrl;
	}
	public function getValidUrl ($url, $isSecure) {
		//$isSecure = return Mage::app()->getStore()->isCurrentlySecure();
		if ($isSecure) {
			//If current page in secure mode, but menu url not in secure, => change menu to secure
			//secure mode your current URL is HTTPS
			if (!strpos($url, 'https://')) {
				$validUrl = str_replace('http://', 'https://', $url);
				$url = $validUrl;
			}
		} else {
			//page is in HTTP mode
			if (!strpos($url, 'http://')) {
				$validUrl = str_replace('https://', 'http://', $url);
				$url = $validUrl;
			}
		}
		return $url;
	}
	public function getControlPanelBlocks () {
		$allBlocks = array();
		$titanBlockModel = Mage::getModel("titan/titanblock");
		foreach ($titanBlockModel->getBlocksByGroup() as $block) {
			//$blockData = $block->getData();
			//Zend_Debug::dump($block->getData());
			$blockData = array(
				'type' => "custom",
				'id' => $block->getId(),
				'title' => $block->getTitle(),
				'group_id' => $block->getGroupId(),
				'store_view' => $block->getStoreView()
			);
			//$blockData['type'] = "custom";
			$allBlocks[] = $blockData;
		}
		return $allBlocks;
	}
	public function getAllStaticBlocks() {
		$allStaticBlocks = array();
		$staticBlocks = $this->helper('titan')->getStaticBlocks();
		foreach ($staticBlocks as $identifier => $title) {
			$allStaticBlocks[] = array(
				"type" => "static_block",
				"title" => $title,
				"identifier" => $identifier
			);
		}
		return $allStaticBlocks;
	}
	public function checkBlockActive($childBlock) {
		$childBlockInfo = json_decode($childBlock['blockDetails'], true);
		if ($childBlockInfo['type'] == "custom") {
			$status = $this->_blockModel->load($childBlockInfo['id'])->getStatus();
			if ($status == NULL || $status == 2) {
				return false;
			} 
		} else {
			if (!Mage::getModel('cms/block')->load($childBlockInfo['identifier'])->getIsActive()) {
				return false;
			}
		}
		return true;
	}
	public function getCustomClass($allClasses) {
		$temp = str_replace("border-dash", "", $allClasses);
		$customClasses = str_replace("ui-sortable", "", $temp);
		return trim($customClasses);
	}
	public function getHeaderOrFooterBlock($dataBlock) {
		$config = Mage::getModel('titan/main')->getBlockConfig($dataBlock);
		if (isset($config['config'])) {
			$jsonDataArray = json_decode($config['config'], true);
			$finalBlockConfig = array();
			$finalBlockConfig ['width_config'] = $config['width_config'];
			foreach ($jsonDataArray['finalBlockConfig'] as $blockKey => $gridColumns) {
				foreach ($gridColumns as $childBlocks) {
					if (!$this->checkBlockActive($childBlocks)) continue;
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['child_blocks'][] = $childBlocks['blockDetails'];
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['custom_class'] = $childBlocks['parentClasses'];
				}
			}
			return $finalBlockConfig;
		}
	}
	public function getOutsideMainContentBlock($dataBlock) {
		$config = Mage::getModel('titan/main')->getBlockConfig($dataBlock);
		if (isset($config['config'])) {
			$jsonDataArray = json_decode($config['config'], true);
			$finalBlockConfig = array();
			$widthConfig = array();
			foreach ($jsonDataArray['finalBlockConfig'] as $blockKey => $gridColumns) {
				foreach ($gridColumns as $childBlocks) {
					if (!$this->checkBlockActive($childBlocks)) continue;
					if (isset($childBlocks['block_width']) && $childBlocks['block_width'] != "") {
						$widthConfig[$blockKey] = $childBlocks['block_width'];
					}
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['child_blocks'][] = $childBlocks['blockDetails'];
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['custom_class'] = $childBlocks['parentClasses'];
				}
			}
			return array(
				'block_config' => $finalBlockConfig,
				'block_width' => $widthConfig
			);
		}
	}
	public function getHiddenBlock($dataBlock) {
		$config = Mage::getModel('titan/main')->getBlockConfig($dataBlock);
		if (isset($config['config'])) {
			$jsonDataArray = json_decode($config['config'], true);
			$finalBlockConfig = array();
			foreach ($jsonDataArray['finalBlockConfig'] as $blockKey => $gridColumns) {
				foreach ($gridColumns as $childBlocks) {
					if (!$this->checkBlockActive($childBlocks)) continue;
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['child_blocks'][] = $childBlocks['blockDetails'];
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['custom_class'] = $childBlocks['parentClasses'];
				}
			}
			return $finalBlockConfig;
		}
	}
	public function getMainContentBlock($dataBlock) {
		$config = Mage::getModel('titan/main')->getBlockConfig($dataBlock);
		if (isset($config['config'])) {
			$jsonDataArray = json_decode($config['config'], true);
			$finalBlockConfig = array();
			foreach ($jsonDataArray['finalBlockConfig'] as $blockKey => $gridColumns) {
				foreach ($gridColumns as $childBlocks) {
					if (!$this->checkBlockActive($childBlocks)) continue;
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['child_blocks'][] = $childBlocks['blockDetails'];
					$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['custom_class'] = $childBlocks['parentClasses'];
				}
			}
			return $finalBlockConfig;
		}
	}
	public function getGeneralInfo () {
		$general = Mage::getModel("titan/general")->getGeneralInfo();
		if (empty($general) || !is_array($general)) {
			$general = array(
				"theme_layout" => "",
				"theme_style" => "",
				"logo_type" => "",
				"logo_image" => "",
				"logo_text" => "",
				"slogan" => "",
				"enable_top_bar" => "",
				"enable_setting_block" => ""
			);
		}
		return $general;
	}
	public function getPageOption() {
		$options = Mage::getModel("titan/pageoption")->getPageOption();
		$arrayOptions = array();
		foreach ($options as $key => $value) {
			if ($key == "id") continue;
			if ($value != "") {
				$arrayOptions[$key] = json_decode($value, true);
			}
		}
		return $arrayOptions;
	}
	public function isMCPEnabled () {
		return Mage::helper('core')->isModuleEnabled('MST_Menupro');
	}
	public function getMCPGroups() {
		if ($this->isMCPEnabled()) {
			return Mage::getModel("menupro/groupmenu")->getGroupArray();
		}
		return null;
	}
	public function _getBlockHtml($blockInfo) {
		if (!$blockInfo) return;
		$html = "";
		$id = $blockInfo["id"];
		if ($id != "") {
			$newestBlockInfo = $this->_blockModel->load($id)->getData();
			$blockInfo = $newestBlockInfo;
			//Filter by Store: Zend_Debug::dump($blockInfo);
			if($blockInfo["store_view"] != "") {
				$storeViews = explode(",", $blockInfo['store_view']);
				$currentStoreId = Mage::app()->getStore()->getStoreId();
				if (!in_array($currentStoreId, $storeViews) && !in_array(0, $storeViews)) {
					return false;
				}
			}
			//Check if block exists in layout
			$layout = $this->getLayout();
			//$layout->generateXml()->generateBlocks();
			if ($layout->getBlock($blockInfo['block_name'])) {
				return $layout->getBlock($blockInfo['block_name'])->toHtml();
			}
			$block = Mage::app()->getLayout()->createBlock($blockInfo['block_type']);
			if($blockInfo['description'] != "") {
				try {
					$blockParams = json_decode($blockInfo['description'], true);
					if (is_array($blockParams)) {
						$block->setData($blockParams);
					}
				} catch(Exception $e) {
					
				}
			}
			if ($block) {
				if ($blockInfo['path'] != "") {
					$block->setTemplate($blockInfo['path']);
					if ($blockInfo['block_name'] != "") {
						$block->setNameInLayout($blockInfo['block_name']);
					}
					$html = $block->toHtml();
				} else {
					$html = $block->toHtml();
				}
			}
		}
		return $html;
	}
}