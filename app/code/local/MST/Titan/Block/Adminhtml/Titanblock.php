<?php
class MST_Titan_Block_Adminhtml_Titanblock extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_titanblock';
        $this->_blockGroup = 'titan';
        $this->_headerText = Mage::helper('titan')->__('Manage Snippets');
        $this->_addButtonLabel = Mage::helper('titan')->__('New Snippet');
		$this->_addButton('add_titan_block', array(
            'label' => Mage::helper('adminhtml')->__('New Snippet'),
            'onclick' => 'MBTitan.addBlock()',
            'class' => 'add',
            ), -100);
        parent::__construct();
		$this->_removeButton("add");
    }
}