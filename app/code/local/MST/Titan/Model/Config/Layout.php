<?php
class MST_Titan_Model_Config_Layout {
	public function toOptionArray()
    {
        return array(
            array('value'=> 'fullwidth', 'label'=>Mage::helper('titan')->__('Full width')),
            array('value'=> 't2-boxed', 'label'=>Mage::helper('titan')->__('Boxed width')),
            array('value'=> 'custom', 'label'=>Mage::helper('titan')->__('Custom')),                                   
        );
    }
}