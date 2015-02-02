<?php
class MST_Titan_BlockController extends Mage_Core_Controller_Front_Action
{
	public function addBlockAction() 
	{
		$block = $this->getLayout()->createBlock("titan/main")->setTemplate("titan/blocks/add_block.phtml");
		$blockHtml = $block->toHtml();
		if($blockHtml == "") {
			$note = "<div class='note'>You're not change design package to our theme yet. Please go to System->Configuration, choose Design tab and set Current Package Name = 't2'.</div>";
			echo $note;
		}
		echo $blockHtml;
	}
	public function saveAction() {
		$params = $this->getRequest()->getParams();
		$helper = Mage::helper("titan");
		$isValidBlock = $helper->checkValidBlock($params['block_type'], $params['path']);
		$addBlock = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "titan/block/addBlock";
		if(isset($params['area'])) {
			$addBlock .= "/area/transforming";
		}
		if ($params['block_type'] == "") {
			$params['block_type'] = "core/template";
		}
		if (isset($params["group_id"])) {
			$groupTags = join(",", $params['group_id']);
			$params['group_id'] = $groupTags;
		}
		if (isset($params["store_view"])) {
			$storeView = join(",", $params['store_view']);
			$params['store_view'] = $storeView;
		}
		//Check json, Description will store params if exists
		if(isset($params['description']) && $params['description'] != "" ) {
			try {
				$jsonDecode = json_decode($params['description'], true);
				if(!is_array($jsonDecode)) {
					$params['description'] = "";
					Mage::getSingleton('core/session')->addError("JSON is not valid. Please check params textarea in advanced section.");
					Mage::getSingleton('core/session')->setFormData($params);
					Mage::app()->getResponse()->setRedirect($addBlock)->sendResponse();
					return;
				}
			} catch(Exception $e) {
				
			}
		}
		if ($isValidBlock['status'] == "error") {
			Mage::getSingleton('core/session')->addError($helper->__($isValidBlock['message']));
			Mage::getSingleton('core/session')->setFormData($params);
			Mage::app()->getResponse()->setRedirect($addBlock)->sendResponse();
			return;
		} else {
			$titanBlockModel = Mage::getModel('titan/titanblock');
			$response = $titanBlockModel->saveBlock($params);
			if ($response['status'] == "success") {
				Mage::getSingleton('core/session')->addSuccess($helper->__($isValidBlock['message']));
				Mage::getSingleton('core/session')->setFormData(false);
				if(isset($params['area'])) {
					$addBlock .= "/block-id/" . $response['block_id'];
				}
				Mage::app()->getResponse()->setRedirect($addBlock)->sendResponse();
				//$this->getResponse()->setBody("<script>window.top.Windows.closeAll();</script>");
				return;
			} else {
				Mage::getSingleton('core/session')->addError($helper->__($response['message']));
				Mage::getSingleton('core/session')->setFormData($params);
				Mage::app()->getResponse()->setRedirect($addBlock)->sendResponse();
				return;
			}
		}
	}
}