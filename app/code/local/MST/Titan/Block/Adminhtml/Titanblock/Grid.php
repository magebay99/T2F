<?php
class MST_Titan_Block_Adminhtml_Titanblock_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'titanBlockGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel("titan/titanblock")->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'id', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'ID' ),
			'align' => 'left',
			'width' => '50px',
			'index' => 'id' 
		) );
		
		$this->addColumn ( 'title', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Snippet Title' ),
			'align' => 'left',
			'index' => 'title' 
		) );
		
		$this->addColumn ( 'block_type', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Block Type' ),
			'align' => 'left',
			'index' => 'block_type' 
		) );
		$this->addColumn ( 'block_name', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Block Name' ),
			'align' => 'left',
			'index' => 'block_name' 
		) );
		/* $groups = Mage::getModel("titan/titanblock")->getBlockGroup();
		$this->addColumn ( 'group_id', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Group' ),
			'align' => 'left',
			'index' => 'group_id',
			'type' => 'options',
			'options' => $groups
		) ); */
		$this->addColumn ( 'path', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Template File Path' ),
			'align' => 'left',
			'index' => 'path' 
		) );
		$this->addColumn ( 'status', array (
			'header' => Mage::helper ( 'titan' )->__ ( 'Status' ),
			'align' => 'left',
			'width' => '80px',
			'index' => 'status',
			'type' => 'options',
			'options' => array (
					1 => 'Enabled',
					2 => 'Disabled' 
			) 
		) );
		
		$this->addColumn('edit_block', array(
			'header'    => Mage::helper('titan')->__('Action'),
			'renderer'	=> 'titan/adminhtml_widget_grid_column_renderer_editblock',
			'index'     => 'edit_block',
			'align' 	=> 'left',
			'width'     => '80px',
			'filter' => false,
			'sortable' => false,
		));

		$this->addExportType ( '*/*/exportCsv', Mage::helper ( 'titan' )->__ ( 'CSV' ) );
		$this->addExportType ( '*/*/exportXml', Mage::helper ( 'titan' )->__ ( 'XML' ) );
		
		return parent::_prepareColumns ();
	}
	protected function _prepareMassaction() {
		$this->setMassactionIdField ( 'id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'titan' );
		
		$this->getMassactionBlock ()->addItem ( 'delete', array (
				'label' => Mage::helper ( 'titan' )->__ ( 'Delete' ),
				'url' => $this->getUrl ( '*/*/massDelete' ),
				'confirm' => Mage::helper ( 'titan' )->__ ( 'Are you sure?' ) 
		) );
		$statuses = array (
			1 => Mage::helper ( 'titan' )->__ ( 'Enabled' ),
			2 => Mage::helper ( 'titan' )->__ ( 'Disabled' )
		);
		array_unshift ( $statuses, array (
				'label' => '',
				'value' => '' 
		) );
		$this->getMassactionBlock ()->addItem ( 'status', array (
			'label' => Mage::helper ( 'titan' )->__ ( 'Change status' ),
			'url' => $this->getUrl ( '*/*/massStatus', array (
					'_current' => true 
			) ),
			'additional' => array (
				'visibility' => array (
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper ( 'titan' )->__ ( 'Status' ),
						'values' => $statuses 
				) 
			) 
		) );
		return $this;
	}
	public function getRowUrl($row) {
		
		//return $this->getUrl ( '*/*/edit', array (
		//		'id' => $row->getId () 
		//) );
		return "#";
	}
}