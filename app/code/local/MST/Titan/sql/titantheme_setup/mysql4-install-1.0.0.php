<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('mst_titan_blocks')} (
	id int(11) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(500) NOT NULL DEFAULT '',
	block_name TEXT NOT NULL,
	block_type TEXT NOT NULL,
	path TEXT NOT NULL,
	layout_name varchar(500) NOT NULL DEFAULT '',
	alias_name varchar(500) NOT NULL DEFAULT '',
	group_id varchar(500) NOT NULL,
	status smallint(2) NOT NULL DEFAULT '1',
	position smallint(6) NOT NULL DEFAULT '0',
	description TEXT NOT NULL,
	custom_layout_update TEXT,
	store_view varchar(500) NOT NULL DEFAULT '',
	PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");
$installer->endSetup(); 
