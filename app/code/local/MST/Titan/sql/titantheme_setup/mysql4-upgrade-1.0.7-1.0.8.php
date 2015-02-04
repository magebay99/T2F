<?php
$installer = $this;
$installer->startSetup();
Mage::helper("titan/config")->autoImportFirstTime();
$installer->endSetup(); 