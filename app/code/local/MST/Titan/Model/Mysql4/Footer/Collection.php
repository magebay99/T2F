<?php
class MST_Titan_Model_Mysql4_Footer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('titan/footer');
    }
}