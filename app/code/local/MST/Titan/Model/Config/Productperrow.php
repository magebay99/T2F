<?php
class MST_Titan_Model_Config_Productperrow {
	public function toOptionArray()
    {
        return array(
            array('value'=> 2, 'label'=>Mage::helper('titan')->__('2')),
			array('value'=> 3, 'label'=>Mage::helper('titan')->__('3')),
			array('value'=> 4, 'label'=>Mage::helper('titan')->__('4')),
        );
    }
}