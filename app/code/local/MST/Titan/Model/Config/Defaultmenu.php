<?php
class MST_Titan_Model_Config_Defaultmenu {
	public function toOptionArray()
    {
		$navs = array();
		$navs[] = array('value'=> 'default', 'label'=>Mage::helper('titan')->__('Default Template Menu'));
		if (Mage::helper('core')->isModuleEnabled('MST_Menupro')) {
			$navs[] = array('value'=> 'mcp', 'label'=>Mage::helper('titan')->__('Use Menu Creator Pro'));
		}
		return $navs;
    }
}