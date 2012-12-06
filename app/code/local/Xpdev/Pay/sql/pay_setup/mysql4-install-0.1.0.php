<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */
//$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$installer->addAttribute('order_payment', 'evolucard_transaction_id', array());
$installer->addAttribute('order_payment', 'evolucard_order_id', array());

$installer->endSetup();
