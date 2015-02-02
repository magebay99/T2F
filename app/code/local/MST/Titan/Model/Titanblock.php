<?php
class MST_Titan_Model_Titanblock extends Mage_Core_Model_Abstract 
{
	public function _construct() {
		parent::_construct ();
		$this->_init ( 'titan/titanblock' );
	}
	public function saveBlock($data) {
		$response = array();
		if (!isset($data['id']) || $data['id'] == "") {
			$data['id'] = NULL;
			$collection = $this->getCollection();
			$collection->addFieldToFilter("path", $data['path']);
			$collection->addFieldToFilter("title", $data['title']);
			$collection->addFieldToFilter("block_name", $data['block_name']);
			if ($collection->count()) {
				$response['status'] = "error";
				$response['message'] = "This template file has been used. Please check item with ID = " . $collection->getFirstItem()->getId();
				return $response;
			}
		}
		$result = $this->setData($data)->setId($data['id'])->save();
		$response['status'] = "success";
		$response['block_id'] = $result->getId();
		return $response;
	}
	public function getBlockGroup() {
		return array(
			1 => "Header",
			2 => "Before Main Content",
			3 => "After Main Content",
			9 => "Home Block",
			4 => "Left Sidebar",
			5 => "Right Sidebar",
			6 => "Footer",
			7 => "Hidden Block",
			8 => "Any Position"
		);
	}
	public function getSortedCollection() {
		$collection = $this->getCollection();
		$collection->setOrder("position", "ASC");
		$collection->setOrder("title", "ASC");
		$collection->addFieldToFilter("status", 1);
		return $collection;
	}
	public function getBlocksByGroup() {
		$collection = $this->getSortedCollection();
		//$store_fillter_a = array('like'=>'%'. $groupId .'%');
        //$store_fillter_b = array('like'=>'%0%');
		//$collection->addFieldToFilter('group_id',array($store_fillter_a));
		return $collection;
	}
}