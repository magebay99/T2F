<?php
class MST_Titan_Block_Adminhtml_Titanblock_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('titanblock_form', array('legend' => Mage::helper('titan')->__('Block Information')));
        
        
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('titan')->__('Snippet Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
		$fieldset->addField('block_name', 'text', array(
            'label' => Mage::helper('titan')->__('Block Name'),
            'name' => 'block_name',
			'note' => 'Leave blank to use default block: Mage_Core_Block_Template'
        ));
		$fieldset->addField('path', 'text', array(
            'label' => Mage::helper('titan')->__('Template Path'),
            'name' => 'path',
			'required' => true,
			'note' => 'design/frontend/default/t2-frame/template/: +  Template Path'
        ));
		$fieldset->addField('position', 'text', array(
            'label' => Mage::helper('titan')->__('Position'),
            'name' => 'position',
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('titan')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('titan')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('titan')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::getSingleton('adminhtml/session')->getTitanBlockData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getTitanBlockData());
            Mage::getSingleton('adminhtml/session')->setTitanBlockData(null);
        } elseif (Mage::registry('titan_block_data')) {
            $form->setValues(Mage::registry('titan_block_data')->getData());
        }
        return parent::_prepareForm();
    }
}