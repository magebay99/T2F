<?php
class MST_Titan_Model_Config_Logotype {
	public function toOptionArray()
    {
        return array(
            array('value'=> 'default', 'label'=>Mage::helper('titan')->__('Default (General > Design > Header)')),
			array('value'=> 'image', 'label'=>Mage::helper('titan')->__('Logo Image')),
			array('value'=> 'text', 'label'=>Mage::helper('titan')->__('Use Text')),
        );
    }
}