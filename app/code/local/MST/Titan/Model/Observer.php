<?php
class MST_Titan_Model_Observer extends Varien_Object 
{
	protected $_titanBlock;
	public function __construct() {
		$this->_titanBlock = new MST_Titan_Block_Main();
	}
	public function checkBlock($observer) {
		$params = $observer->getEvent()->getData();
		$isValidBlock = Mage::helper('titan')->checkValidBlock($params['block_name'], $params['path']);
		Zend_Debug::dump($isValidBlock);
		die;
	}
	public function customLayoutUpdate($observer) {
		$action = $observer->getAction();
		if ($action->getLayout()->getArea() == "frontend") {
			$this->updateHeaderAndFooterCustomLayout("header");
			$this->updateHeaderAndFooterCustomLayout("footer");
			$this->updateOutsideContentCustomLayout("before_main_content");
			$this->updateOutsideContentCustomLayout("after_main_content");
			$this->updateHeaderAndFooterCustomLayout("maincontent");
			$this->updateHiddenBlockCustomLayout("top-bar");
			$this->updateHiddenBlockCustomLayout("bottom-bar");
		}
		//die("I'm in Observer");
	}
	public function updateHeaderAndFooterCustomLayout($dataBlock) {
		$blockConfig = $this->_titanBlock->getHeaderOrFooterBlock($dataBlock);
		if(is_array($blockConfig)) {
			foreach($blockConfig as $rootBlocks) {
				if (is_array($rootBlocks)) {
					foreach($rootBlocks as $gridColumn) {
						if (isset($gridColumn['child_blocks'])) {
							foreach($gridColumn['child_blocks'] as $childBlock) {
								$blockDetails = json_decode($childBlock, true);
								if($blockDetails['type'] == "static_block") continue;
								$newBlockInfo = Mage::getModel("titan/titanblock")->load($blockDetails["id"])->getData();
								$this->updateLayout($newBlockInfo);
							}
						}
					}
				}
			}
		}
	}
	public function updateOutsideContentCustomLayout($dataBlock) {
		$config = $this->_titanBlock->getOutsideMainContentBlock($dataBlock);
		if (is_array($config)) {
			foreach ($config['block_config'] as $gridColumns) {
				if(is_array($gridColumns)) {
					foreach($gridColumns as $childBlock) {
						if(isset($childBlock['child_blocks'])) {
							foreach($childBlock['child_blocks'] as $childBlock) {
								$blockDetails = json_decode($childBlock, true);
								if($blockDetails['type'] == "static_block") continue;
								$newBlockInfo = Mage::getModel("titan/titanblock")->load($blockDetails["id"])->getData();
								$this->updateLayout($newBlockInfo);
							}
						}
					}
				}
			}
		}
	}
	public function updateHiddenBlockCustomLayout($dataBlock) {
		$config = $this->_titanBlock->getHiddenBlock($dataBlock);
		if(!empty($config)) {
			foreach($config as $gridColumns) {
				if(is_array($gridColumns)) {
					foreach($gridColumns as $childBlock) {
						if(isset($childBlock['child_blocks'])) {
							foreach($childBlock['child_blocks'] as $childBlock) {
								$blockDetails = json_decode($childBlock, true);
								if($blockDetails['type'] == "static_block") continue;
								$newBlockInfo = Mage::getModel("titan/titanblock")->load($blockDetails["id"])->getData();
								$this->updateLayout($newBlockInfo);
							}
						}
					}
				}
			}
		}
	}
	public function updateLayout($data) {
		if (isset($data['custom_layout_update']) && $data['custom_layout_update'] != "") {
			$layout = Mage::getSingleton('core/layout');
			$layout->getUpdate()->addUpdate($data['custom_layout_update']);
		}
	}
}