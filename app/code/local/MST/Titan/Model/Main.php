<?php
class MST_Titan_Model_Main extends Mage_Core_Model_Abstract 
{
	
	public function saveBlockConfig($data, $dataBlock) {
		$response = array();
		if (!isset($data['id']) || $data['id'] == "") {
			$data["id"] = NULL;
		}
		$model = $this->getModelObjectByDataBlock($dataBlock);
		if ($model == null) {
			return;
		}
		
		$model->setData($data)->setId($data['id'])->save();
		$response['status'] = "success";
		$response['message'] = Mage::helper('titan')->__($dataBlock . " block config has successfully saved!");
		return $response;
	}
	public function getBlockConfig($dataBlock) {
		
		$currentStoreId = (int) Mage::helper("titan")->getCurrentTitanStore();
		$model = $this->getModelObjectByDataBlock($dataBlock);
		if ($model !== null) {
			$collection = $model->getCollection();
			$collection->addFieldToFilter("store", 0);
			if($currentStoreId == 0) {
				if($collection->count()) {
					return $collection->getLastItem()->getData();
				}
			} else {
				//Check custom store view has setting or not
				$_customStoreCollection = $model->getCollection();
				$_customStoreCollection->addFieldToFilter("store", $currentStoreId);
				//echo $dataBlock . "---<br/>";
				if($_customStoreCollection->count()) {
					//Combine here
					$_combineConfig = array();
					$_customStoreConfig = $_customStoreCollection->getLastItem()->getData();
					$_allStoreConfig = array();
					if($collection->count()) {
						$_allStoreConfig = $collection->getLastItem()->getData();
						//Combine $_customStoreConfig and $_allStoreConfig
						$_allConfigDecoded = json_decode($_allStoreConfig['config'], true);
						$_customConfigDecoded = json_decode($_customStoreConfig['config'], true);
						$existsBlock = array(
							'static_block' => array(),
							'custom' => array()
						);
						foreach($_customConfigDecoded['finalBlockConfig'] as $blockKey => $childBlocks) {
							foreach($childBlocks as $_child) {
								$blockDetailsDecoded = json_decode($_child['blockDetails'], true);
								if($blockDetailsDecoded['type'] == "static_block") {
									$existsBlock['static_block'][] = $blockDetailsDecoded['identifier'];
								} else {
									$existsBlock['custom'][] = $blockDetailsDecoded['id'];
								}
							}
						}
						//Add block from all store view setting
						foreach($_allConfigDecoded['finalBlockConfig'] as $blockKey => $childBlocks) {
							foreach($childBlocks as $_childKey => $_child) {
								$blockDetailsDecoded = json_decode($_child['blockDetails'], true);
								if($blockDetailsDecoded['type'] == "static_block") {
									if(!in_array($blockDetailsDecoded['identifier'], $existsBlock['static_block'])) {
										$_customConfigDecoded['finalBlockConfig'][$blockKey][] = $_child;
									}
								} else {
									if(!in_array($blockDetailsDecoded['id'], $existsBlock['custom'])) {
										$_customConfigDecoded['finalBlockConfig'][$blockKey][] = $_child;
									}
								}
							}
						}
						$_customStoreConfig['config'] = json_encode($_customConfigDecoded);
						return $_customStoreConfig;
					} else {
						return $_customStoreConfig;
					}
					
				} else {
					if($collection->count()) {
						return $collection->getLastItem()->getData();
					}
				}
			}
			 
			return array (
				'title' => '',
				'config' => null,
				'description' => '',
				'width_config' => '',
				'store' => 0
			);
		}
	}
	public function getModelObjectByDataBlock($dataBlock) {
		$model = null;
		switch($dataBlock) {
			case "top-bar" :
				$model = Mage::getSingleton("titan/topbar");
				break;
			case "hidden-top" :
				$model = Mage::getSingleton("titan/topbar");
				break;	
			case "header" :
				$model = Mage::getSingleton("titan/header");
				break;
			case "before_main_content" :
				$model = Mage::getSingleton("titan/beforemain");
				break;
			case "maincontent" :
				$model = Mage::getSingleton("titan/maincontent");
				break;
			case "after_main_content" :
				$model = Mage::getSingleton("titan/aftermain");
				break;
			case "footer" :
				$model = Mage::getSingleton("titan/footer");
				break;
			case "bottom-bar" :
				$model = Mage::getSingleton("titan/bottombar");
				break;
			case "hidden-bottom" :
				$model = Mage::getSingleton("titan/bottombar");
				break;				
		}
		return $model;
	}
}