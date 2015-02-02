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
		$model = $this->getModelObjectByDataBlock($dataBlock);
		if ($model !== null) {
			$collection = $model->getCollection();
			if($collection->count()) {
				return $collection->getLastItem()->getData();
			} 
			return array (
				'title' => '',
				'config' => null,
				'description' => '',
				'width_config' => ''
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