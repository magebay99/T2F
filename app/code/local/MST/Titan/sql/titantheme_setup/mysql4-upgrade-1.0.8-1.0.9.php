<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$this->getTable('mst_titan_block_after_main_content')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_before_main_content')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_bottombar')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_footer')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_header')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_main_content')} ADD COLUMN store smallint(6) DEFAULT 0;
ALTER TABLE {$this->getTable('mst_titan_block_topbar')} ADD COLUMN store smallint(6) DEFAULT 0;
");
$installer->endSetup(); 
