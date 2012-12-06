<?php

/* @var $installer Mage_Customer_Model_Entity_Setup  */

$installer = $this;
$installer->startSetup();

$installer->addAttribute('order_payment', 'evolucard_transaction_id', array());

$installer->endSetup();
