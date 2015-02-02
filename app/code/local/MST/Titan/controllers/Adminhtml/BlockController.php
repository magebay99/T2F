<?php 
class MST_Titan_Adminhtml_BlockController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('titan/titan')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Titan Theme Manage'), Mage::helper('adminhtml')->__('Titan Theme Manage'));
        return $this;
    }
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock("titan/adminhtml_titanblock"));
		$this->renderLayout();		
	}
	public function editAction() {
		$id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('titan/titanblock')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
				
            }
            Mage::register('titan_block_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('titan/titan');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Menu Group'), Mage::helper('adminhtml')->__('Manage Menu Group'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Menu Group'), Mage::helper('adminhtml')->__('Manage Menu Group'));
            //$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('titan/adminhtml_titanblock_edit'))
				 ->_addLeft($this->getLayout()->createBlock('titan/adminhtml_titanblock_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('menupro')->__('Menu group does not exist'));
            $this->_redirect('*/*/');
        }
	}
	public function newAction() {
		$this->_forward('edit');
	}
	public function saveAction() {
		
	}
	public function massDeleteAction() {
        $itemIds = $this->getRequest()->getParam('titan');
        if (!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $model = Mage::getModel('titan/titanblock')->load($itemId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($itemIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    public function massStatusAction()
    {
        $menuIds = $this->getRequest()->getParam('titan');
        if (!is_array($menuIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select menu(s)'));
        } else {
            try {
                foreach ($menuIds as $menuId) {
                    $seatcover = Mage::getSingleton('titan/titanblock')
                            ->load($menuId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($menuIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}