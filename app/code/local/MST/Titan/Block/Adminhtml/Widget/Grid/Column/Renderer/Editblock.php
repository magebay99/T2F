<?php
class MST_Titan_Block_Adminhtml_Widget_Grid_Column_Renderer_Editblock
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
    public function render(Varien_Object $row)
    {
        $html = "";
        $html .= '<a id="block_'. $row->getId() .'" href="#" onclick="MBTitan.editBlock(this); return false;">' . Mage::helper('titan')->__('Edit') . '</a>';
        return $html;
    }
}