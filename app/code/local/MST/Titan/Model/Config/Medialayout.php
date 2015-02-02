<?php
class MST_Titan_Model_Config_Medialayout {
	public function toOptionArray()
    {
        return array(
            array('value'=> 1, 'label'=>Mage::helper('titan')->__('Main image on top - Thumbnail on bottom')),
			array('value'=> 2, 'label'=>Mage::helper('titan')->__('Main image on the left - Thumbnail on the right')),
        );
    }
}