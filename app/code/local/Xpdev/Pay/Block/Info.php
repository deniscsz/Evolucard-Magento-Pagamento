<?php
class Xpdev_Pay_Block_Info extends Mage_Payment_Block_Info
{
	protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('pay/info.phtml');
    }
    
    public function getPagEvolucard()
    {
        return Mage::getSingleton('pay/standard');
    }
    
    public function getOrder()
    {
        $order = Mage::registry('current_order');

		$info = $this->getInfo();

		if (!$order) {
			if ($this->getInfo() instanceof Mage_Sales_Model_Order_Payment) {
				$order = $this->getInfo()->getOrder();
			}
		}

		return($order);
    }
    
    public function getPaymentUrl()
    {
        return $this->getPagEvolucard()->getOrderPlaceRedirectUrl($this->getOrder()->getId());
    }
    
    public function isShowPaylink()
    {
        $order = $this->getOrder();
        if(isset($order)) {
            if($order->getState() != Mage_Sales_Model_Order::STATE_NEW) {
                return 1;
            }
            else {
                return (bool) ($order->getState() == Mage_Sales_Model_Order::STATE_NEW || $order->getState() == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
            }
        }
        else {
            return false;
        }
    }
    
    public function returnTransaction() {
        $order = $this->getOrder();
        if(isset($order)) {
            return $order->getPayment()->getEvolucardTransactionId();//$order->getTransactionEvc();
        }
        else {
            return NULL;
        }
    }
}