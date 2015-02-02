<?php

class MST_Titan_Block_Adminhtml_Titanblock_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('titan_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('titan')->__('Block Information'));
    }
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('titan')->__('Block Information'),
            'title' => Mage::helper('titan')->__('Block Information'),
            'content' => $this->getLayout()->createBlock('titan/adminhtml_titanblock_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}