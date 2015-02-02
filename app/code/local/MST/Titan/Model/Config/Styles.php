<?php
class MST_Titan_Model_Config_Styles {
	public function toOptionArray()
    {
        return array(
            array('value'=> 1, 'label'=>Mage::helper('titan')->__('Skin 1')),
			array('value'=> 2, 'label'=>Mage::helper('titan')->__('Skin 2')),
			array('value'=> 3, 'label'=>Mage::helper('titan')->__('Skin 3')),
			array('value'=> 4, 'label'=>Mage::helper('titan')->__('Skin 4')),
			array('value'=> 5, 'label'=>Mage::helper('titan')->__('Skin 5')),
            
        );
    }
}