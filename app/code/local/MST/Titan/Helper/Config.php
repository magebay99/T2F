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
	public function exportData() {
		$sampleExportDir = Mage::getBaseDir("media") . DS . "titan" . DS . "export" . DS;
		if(!file_exists($sampleExportDir)) {
			try {
				if(!file_exists(Mage::getBaseDir("media") . DS . "titan" . DS)) {
					mkdir(Mage::getBaseDir("media") . DS . "titan" . DS, 777);
				}
				mkdir($sampleExportDir, 777);
			} catch(Exception $d) {
				die("Can not create folder to save sample data");
			}
		}
		if(file_exists($sampleExportDir)) {
			$exportFilename = "sample-data-" . date("Y-m-d", time()) . "-" . time() . ".json";
			$layoutBuilderConfig = $this->getLayoutBuilder();
			$allBlock = $this->getAllBlocks();
			$sampleData = array();
			$sampleData['blocks'] = $allBlock;
			$sampleData['layout_builder'] = $layoutBuilderConfig;
			$staticBlockContent = array();
			$staticBlockList = $this->getAllStaticBlockUsed($layoutBuilderConfig);
			$staticBlockModel = Mage::getModel("cms/block");
			foreach ($staticBlockList as $identifier) {
				$staticBlockInfo = $staticBlockModel->load($identifier);
				$staticBlockContent[] = array(
					'title' => $staticBlockInfo->getTitle(),
					'identifier' => $staticBlockInfo->getIdentifier(),
					'content' => $staticBlockInfo->getContent(),
					'store_id' => $staticBlockInfo->getStoreId()
				);
			}
			$sampleData['static_blocks'] = $staticBlockContent;
			$dataInJSON = json_encode($sampleData);
			$result = file_put_contents($sampleExportDir . $exportFilename, $dataInJSON);
			if($result) {
				$samplePath = Mage::getBaseUrl("media") . "titan/export/" . $exportFilename;
				$response['path'] = $samplePath;
			}
			header('Content-type: application/json');
			header("Content-disposition: attachment; filename=$exportFilename");
			echo $dataInJSON;
		}
	}
	public function getLayoutBuilder() {
		$mainModel = new MST_Titan_Model_Main();
		$blockData = array("after_main_content", "before_main_content", "bottom-bar", "footer", "header", "maincontent", "top-bar");
		$allBlockConfig = array();
		foreach($blockData as $_blockKey) {
			$blockConfig = $mainModel->getBlockConfig($_blockKey);
			unset($blockConfig['id']);
			$allBlockConfig[$_blockKey] = $blockConfig;
		}
		return $allBlockConfig;
	}
	public function getAllBlocks() {
		$titanBlocks = Mage::getModel("titan/titanblock")->getCollection();
		$allBlock = array();
		foreach($titanBlocks as $block) {
			$blockData = $block->getData();
			//unset($blockData['id']);
			$allBlock[] = $blockData;
		}
		return $allBlock;
	}
	public function getAllStaticBlockUsed($layoutBuilderConfig) {
		$allStaticBlock = array();
		if(is_array($layoutBuilderConfig)) {
			foreach ($layoutBuilderConfig as $_blockData) {
				if($_blockData['config'] != "") {
					$_blockDataDecode = json_decode($_blockData['config'], true);
					foreach ($_blockDataDecode as $_configInfo) {
						foreach ($_configInfo as $_snippets) {
							foreach($_snippets as $snippetInfo) {
								$snippetDecode = json_decode($snippetInfo['blockDetails'], true);
								if($snippetDecode['type'] == "static_block" && !in_array($snippetDecode['identifier'], $allStaticBlock)) {
									$allStaticBlock[] = $snippetDecode['identifier'];
								}
							}
						}
					}
				}
			}
		}
		return $allStaticBlock;
	}
	public function importData($sampleJson) {
		if ($sampleJson != "") {
			$sampleDataDecoded = json_decode($sampleJson, true);
			//Import Blocks/Snippet
			$result = $this->importBlocksViaSql($sampleDataDecoded);
			//Import layout builder
			$layout_result = $this->importLayoutBuilder($sampleDataDecoded);
			//Import static block
			$this->importStaticBlock($sampleDataDecoded);
			return array(
				'blocks' => $result,
				'layout_builder' => $layout_result
			);
		}
	}
	protected function importBlocks($sampleDataDecoded) {
		$response = array();
		if(isset($sampleDataDecoded['blocks'])) {
			$response['status'] = "error";
			$response['message'] = "There is no block imported! Something went wrong";
			$blocks = $sampleDataDecoded['blocks'];
			$mainModel = Mage::getModel("titan/titanblock");
			try {
				foreach($blocks as $_blockData) {
					//unset($_blockData['id']);
					$result = $mainModel->saveBlock($_blockData);
				}
				$response['status'] = "success";
				$response['message'] = "All snippet imported successfully!";
			} catch (Exception $e) {
				
			}
		}
		return $response;
	}
	protected function importBlocksViaSql($sampleDataDecoded) {
		$response = array();
		if(isset($sampleDataDecoded['blocks'])) {
			$response['status'] = "error";
			$response['message'] = "There is no block imported! Something went wrong";
			$blocks = $sampleDataDecoded['blocks'];
			$mainModel = Mage::getModel("titan/titanblock");
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
			$blockTable = $resource->getTableName('mst_titan_blocks');
			//Reset table
			$resetSql = "TRUNCATE $blockTable;";
			//$writeConnection->query($resetSql);
			try {
				$allSql = array();
				foreach($blocks as $_blockData) {
					$allSql[] = $this->prepareAddBlockSql($_blockData, $blockTable);
				}
				if(count($allSql)) {
					$insertBlockSql = join("", $allSql);
					$sql = $resetSql . $insertBlockSql;
				}
				$writeConnection->query($sql);
				$response['status'] = "success";
				$response['message'] = "All snippet imported successfully!";
			} catch (Exception $e) {
				
			}
		}
		return $response;
	}
	public function prepareAddBlockSql($data, $tableName) {
		$sql = "INSERT INTO $tableName (`id`, `title`, `block_name`, `block_type`, `path`, `layout_name`, `alias_name`, `group_id`, `status`, `position`, `description`, `custom_layout_update`, `store_view`)";
		$sql .= "VALUES('".$data['id']."','".addslashes($data['title'])."', '".$data['block_name']."','".$data['block_type']."','".$data['path']."','".$data['layout_name']."','".$data['alias_name']."','".$data['group_id']."','".$data['status']."','".$data['position']."','".$data['description']."','".$data['custom_layout_update']."','".$data['store_view']."');";
		return $sql;
	}
	protected function importLayoutBuilder($sampleDataDecoded) {
		$response = array();
		if(isset($sampleDataDecoded['layout_builder'])) {
			$layoutBuilderConfigs = $sampleDataDecoded['layout_builder'];
			$mainModel = new MST_Titan_Model_Main();
			$response['status'] = "error";
			$response['message'] = "Can not import layout! Something went wrong";
			try {
				foreach($layoutBuilderConfigs as $_blockKey => $_blockConfig) {
					$mainModel->saveBlockConfig($_blockConfig, $_blockKey);
				}
				$response['status'] = "success";
				$response['message'] = "Layout imported successfully!";
			} catch (Exception $e) {
				
			}
		}
		return $response;
	}
	protected function importStaticBlock($sampleDataDecoded) {
		$response = array();
		if(isset($sampleDataDecoded['static_blocks'])) {
			$response['status'] = "error";
			$response['message'] = "Can not create static block! Something went wrong";
			$staticBlocks = $sampleDataDecoded['static_blocks'];
			try {
				foreach($staticBlocks as $_staticBlockData) {
					//Check if exists
					$staticBlockModel = Mage::getModel("cms/block");
					if($staticBlockModel->load($_staticBlockData['identifier'])->getId() != null) {
						continue;
					}
					$staticBlockModel->setData($_staticBlockData)->save();
				}
				$response['status'] = "success";
				$response['message'] = "Layout imported successfully!";
			} catch (Exception $e) {
				
			}
		}
		return $response;
	}
	public function autoImportFirstTime() {
		//Find json file
		$appDir = Mage::getBaseDir("app") . DS . "code" . DS . "local" . DS . "MST". DS . "Titan" . DS . "sql" . DS . "titantheme_setup" . DS;
		if (is_dir($appDir)){
			if ($dh = opendir($appDir)){
				while (($file = readdir($dh)) !== false){
					$tempArr = explode(".", $file);
					if(end($tempArr) == "json") {
						$jsonContent = file_get_contents($appDir . $file);
						$jsonContentDecoded = json_decode($jsonContent, true);
						if (isset($jsonContentDecoded['blocks']) || isset($jsonContentDecoded['layout_builder']) || isset($jsonContentDecoded['static_blocks'])) {
							//Run import then delete this file
							$this->importData($jsonContent);
							unlink($appDir . $file);
							break;
						}
					}
				}
				closedir($dh);
			}
		}
	}
} 