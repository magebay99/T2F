<?php
class MST_Titan_Block_Adminhtml_Titanblock_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'titan';
        $this->_controller = 'adminhtml_titanblock';

        $this->_updateButton('save', 'label', Mage::helper('titan')->__('SAVE'));
        $this->_updateButton('delete', 'label', Mage::helper('titan')->__('Delete Snippet'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
            ), -100);
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('news_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'news_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'news_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
	
	protected function _prepareLayout()
    {
        // Load Wysiwyg on demand and Prepare layout
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled() && ($block = $this->getLayout()->getBlock('head'))) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		}
        return parent::_prepareLayout();
    }
    public function getHeaderText()
    {
        if (Mage::registry('titan_block_data') && Mage::registry('titan_block_data')->getId()) {
            return Mage::helper('titan')->__("Edit Snippet '%s'", $this->htmlEscape(Mage::registry('titan_block_data')->getTitle()));
        } else {
            return Mage::helper('titan')->__('New Snippet');
        }
    }
}