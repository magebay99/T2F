<?php
class MST_Titan_Model_Config_Mcpgroup {
	public function toOptionArray()
    {
		$mcpGroups = array();
		if (Mage::helper('core')->isModuleEnabled('MST_Menupro')) {
			$groups = Mage::getModel("menupro/groupmenu")->getGroupArray();
			foreach ($groups as $group) {
				$mcpGroups[] = array('value'=> $group['value'], 'label'=>Mage::helper('titan')->__($group['label']));
			}
		}
		return $mcpGroups;
    }
}