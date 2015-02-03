<?php 
class MST_Titan_Adminhtml_LayoutController extends Mage_Adminhtml_Controller_Action {	
	protected function _initAction()    {       
		$this->loadLayout()->_setActiveMenu('titan/titan')->_addBreadcrumb(Mage::helper('adminhtml')->__('Titan Theme Manage'), Mage::helper('adminhtml')->__('Titan Theme Manage'));        
		return $this;
	}	
	public function indexAction() {		
		$this->_initAction();		
		$this->loadLayout();
		$this->initLayoutMessages('adminhtml/session');
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
				$importStatus = Mage::helper("titan/config")->importData($sampleJson);
				//Zend_Debug::dump($importStatus);
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('titan')->__('Import successfully!'));
			} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('titan')->__('Please upload json file'));
			}
		}
		$this->_redirect('*/*/index');
	}
}