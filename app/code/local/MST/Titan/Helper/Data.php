<?php
class MST_Titan_Helper_Data extends Mage_Core_Helper_Abstract 
{
	protected $_basePath = null;
	public function __construct() {
		$this->_basePath = Mage::getBaseDir('design') . DS . "frontend" . DS . "t2-frame" . DS . "default" . DS . "template" . DS . "titan" . DS . "html";
	}
	public function makeFolderWriteable($path) {
		return chmod($path, 0777);
	}
	public function exportHTML($path, $filename, $content) {
		try {
			ob_start();
			echo $content;
			$content = ob_get_contents();
			//ob_end_flush();
			ob_end_clean();// Will not display the content
			//Make folder writeable
			$this->makeFolderWriteable($path);
			file_put_contents($path . $filename, $content);
		} catch (Exception $e) {
			Mage::log($e, null, 'titan.log');
		}
		$response = array();
		if (file_exists($path . $filename)) {
			$response['success'] = true;
			$response['filename'] = $filename;
		} else {
			$response['error'] = true;
			$response['message'] = "Can not export menu file! Something went wrong ...";
		}
		return $response;
	}
	public function checkHtmlFolder() {
		if (!file_exists($this->_basePath)) {
			return mkdir($this->_basePath, 0777);
		}
		return true;
	}
	public function getHtmlFolderPath() {
		if ($this->checkHtmlFolder()) {
			return $this->_basePath;
		}
	}
	public function getStaticBlocks () {
		$collection = Mage::getModel("cms/block")->getCollection();
		$staticBlocks = array();
		foreach($collection as $value)
		{
			if($value->getIsActive() == true){
				$staticBlocks[$value->getIdentifier()] = $value->getTitle();
			}
		}
		return $staticBlocks;
	}
	public function checkValidBlock($blockName, $path) {
		$response = array();
		try {
			$isBlockObj = Mage::app()->getLayout()->createBlock($blockName);
			if ($isBlockObj) {
				if ($path == "") {
					$template = Mage::app()->getLayout()->setArea("frontend")->createBlock($blockName);
				} else {
					$template = Mage::app()->getLayout()->setArea("frontend")->createBlock($blockName)->setTemplate($path);
					$templateFile = Mage::getBaseDir("design") . DS . $template->getTemplateFile();
					if (!file_exists($templateFile)) {
						$response['status'] = 'error';
						$response['message'] = $this->__("Template file not exists! Check this path: " . $templateFile);
						return $response;
					}
				}
				$response['status'] = 'success';
				if ($template) {
					$response['message'] = $this->__("This block has been added successfully!");
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = $this->__("Template type (" . $blockName . ") is invalid.");
			}
		} catch(Exception $e) {
			$response['status'] = 'error';
			$response['message'] = $this->__("Sorry. This block name is invalid, please check again!");
		}
		return $response;
	}
	public function saveImage($inputName, $mediaPath) {
		$imageName = $_FILES[$inputName]['name'];
		if ($imageName != "") {
			try {
				$ext = substr($imageName, strrpos($imageName, '.') + 1);
				$filename = $inputName . time() . '.' . $ext;
				$uploader = new Varien_File_Uploader($inputName);
				$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg+xml')); // or pdf or anything
				$uploader->setAllowRenameFiles(false);
				$uploader->setFilesDispersion(false);
				$path = Mage::getBaseDir('media') . DS . $mediaPath;
				if (!file_exists($path)) {
					return mkdir($path, 0777);
				}
                $validFilename1 = str_replace('_', '', $filename);
                $validFilename2 = str_replace('-', '', $validFilename1);
                $filename = $validFilename2;
				$uploader->save($path, $filename);
				return $filename;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Zend_Debug::dump($e->getMessage());
				echo "Error while upload inlay image";
				return;
			}
		}
		return "";
	}
}