<?php 
class MST_Titan_Adminhtml_LayoutController extends Mage_Adminhtml_Controller_Action {	
	protected function _initAction()    {       
		$this->loadLayout()->_setActiveMenu('titan/titan')->_addBreadcrumb(Mage::helper('adminhtml')->__('Titan Theme Manage'), Mage::helper('adminhtml')->__('Titan Theme Manage'));        
		return $this;
	}	
	public function indexAction() {	
		$this->_initAction();		
		$this->loadLayout();
		$this->renderLayout();	
	}
	public function exportAction() {
		Mage::helper("titan/config")->exportData();
	}
	public function importAction() {
		if(isset($_FILES['sample_data_file'])) {
			$filename = $_FILES['sample_data_file']['name'];
			$tempArr = explode(".", $filename);
			if (end($tempArr) == "json" && $_FILES['sample_data_file']['type'] == "application/octet-stream") {
				$sampleJson = file_get_contents($_FILES['sample_data_file']['tmp_name']);
				//Check is a valid json to import
				$jsonContentDecoded = json_decode($sampleJson, true);
				if (isset($jsonContentDecoded['blocks']) || isset($jsonContentDecoded['layout_builder']) || isset($jsonContentDecoded['static_blocks'])) {
					$importStatus = Mage::helper("titan/config")->importData($sampleJson);
					Mage::getSingleton('core/session')->setTitanSuccessMessage(Mage::helper('titan')->__('<strong>Import successfully!</strong>'));
				} else {
					Mage::getSingleton('core/session')->setTitanErrorMessage(Mage::helper('titan')->__('<strong>Import fail !</strong> Please upload a valid file in <strong>.json</strong> format!'));
				}
			} else {
				Mage::getSingleton('core/session')->setTitanErrorMessage(Mage::helper('titan')->__('<strong>Import fail !</strong> Please upload a valid file in <strong>.json</strong> format!'));
			}
		}
		$this->_redirect('*/*/index');
	}
	public function switchStoreAction() {
		$params = $this->getRequest()->getParams();
		Mage::getSingleton('core/session')->setCurrentTitanStore($params['store']);
		$this->_redirect('*/*/index');
	}
}