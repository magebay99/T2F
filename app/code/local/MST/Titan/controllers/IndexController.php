<?php
class MST_Titan_IndexController extends Mage_Core_Controller_Front_Action 
{
	public function indexAction () {
		$this->loadLayout();
		$this->renderLayout();
	}
	public function saveBlockAction() {
		$params = $this->getRequest()->getParams();
		$response = array();
		if (isset($params['root_block']) && $params['root_block'] != "") {
			if (isset($params['config']) && $params['config'] != "") {
				$response = Mage::getModel('titan/main')->saveBlockConfig($params, $params['root_block']);
			}
		}
		$this->getResponse()->setBody(json_encode($response));
/* 		$jsonData = $this->getRequest()->getParam('json_data');
		$jsonDataArray = json_decode($jsonData, true);
		$finalBlockConfig = array();
		foreach ($jsonDataArray['finalBlockConfig'] as $blockKey => $gridColumns) {
			foreach ($gridColumns as $childBlocks) {
				$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['child_blocks'][] = $childBlocks['blockDetails'];
				$finalBlockConfig[$blockKey][$childBlocks['parentIndex']]['custom_class'] = $childBlocks['parentClasses'];
			}
		}
		Zend_Debug::dump($finalBlockConfig);
		die;
		$helper = Mage::helper("titan");
		$basePath = $helper->getHtmlFolderPath();
		$response = array();
		foreach ($finalBlockConfig as $_blockKey => $_childColumns) {
			$phtmlContent = array();
			$phtmlContent[] = '<div class="' . $jsonDataArray['widthStyle'] . '">';
			foreach ($_childColumns as $_childColumn) {
				$phtmlContent[] = "\t" . '<div class="'. $_childColumn['custom_class'] .'">';
				foreach ($_childColumn['child_blocks'] as $_childBlock) {
					$blockDetails = json_decode($_childBlock, true);
					if ($blockDetails['type'] == "static_block") {
						$phtmlContent[] = "\t\t" . '<?php echo Mage::getSingleton("core/layout")->createBlock("cms/block")->setBlockId("' . $blockDetails['identifier'] . '")->toHtml(); ?>';
					} elseif($blockDetails['type'] == "custom") {
						$phtmlContent[] = "\t\t" . '<?php echo $this->getLayout()->createBlock("'. $blockDetails['block_name'] .'")->setTemplate("'.$blockDetails['path'].'")->toHtml(); ?>';
					}
				}
				$phtmlContent[] = "\t" . '</div>';
			}
			$phtmlContent[] = '</div>';
			$response[] = $helper->exportHTML($basePath . DS, $_blockKey . ".phtml", join("\n", $phtmlContent));
		}
		Zend_Debug::dump($response);
		//$this->getResponse()->setBody($response); */
	}
	public function testAction() {
		echo "Hi There<br/><pre>";
	}
	public function saveGeneralAction() {
		$params = $this->getRequest()->getParams();
		$helper = Mage::helper('titan');
		$logoImage = $helper->saveImage("logo_image", "titan/images/logo/");
		$params['logo_image'] = $logoImage;
		if (!empty($params)) {
			$generalModel = Mage::getModel("titan/general");
			if ($params['logo_image'] == "") {
				$lastGeneralInfo = $generalModel->getGeneralInfo();
				//Zend_Debug::dump($lastGeneralInfo);
				$params['logo_image'] = $lastGeneralInfo['logo_image'];
			}
			$generalModel->saveGeneralInfo($params);
		}
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "titan/index/index?tab=General";
		$this->getResponse()->setRedirect($url)->sendResponse();
	}
	public function savePageOptionAction() {
		$params = $this->getRequest()->getParams();
		if (!empty($params)) {
			$data = array();
			foreach ($params as $key => $value) {
				$data[$key] = json_encode($value);
			}
			Mage::getModel("titan/pageoption")->savePageOption($data);
		}
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "titan/index/index?tab=pageOptions";
		$this->getResponse()->setRedirect($url)->sendResponse();
	}
	public function saveNavigationAction() {
		$params = $this->getRequest()->getParams();
		Mage::getModel("titan/navigation")->saveNavigationConfig($params);
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "titan/index/index?tab=Navigation";
		$this->getResponse()->setRedirect($url)->sendResponse();
	}
	public function saveCustomizationAction() {
		$params = $this->getRequest()->getParams();
		Mage::getModel("titan/customization")->saveCustomizationConfig($params['customization']);
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "titan/index/index?tab=Customization";
		$this->getResponse()->setRedirect($url)->sendResponse();
	}
}