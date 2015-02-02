<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('mst_titan_block_header')} (
	id int(11) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(500) NOT NULL DEFAULT '',
	config TEXT NOT NULL,
	description TEXT NOT NULL,
	width_config varchar(500) NOT NULL DEFAULT '',
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");
$installer->endSetup(); 
