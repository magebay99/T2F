<?php
class MST_Titan_Model_Beforemain extends Mage_Core_Model_Abstract 
{
	public function _construct() {
		parent::_construct ();
		$this->_init ( 'titan/beforemain' );
	}
}