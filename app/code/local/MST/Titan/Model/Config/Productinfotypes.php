<?php
class MST_Titan_Model_Config_Productinfotypes {
	public function toOptionArray()
    {
        return array(
            array('value'=> "tabs", 'label'=>Mage::helper('titan')->__('Tabs')),
			array('value'=> "listview", 'label'=>Mage::helper('titan')->__('List View')),
			array('value'=> "accordion", 'label'=>Mage::helper('titan')->__('Accordion')),
        );
    }
}