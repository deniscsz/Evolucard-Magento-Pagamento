<?php
class Xpdev_Pay_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'pay';
	protected $_formBlockType = 'pay/form_pay';
	protected $_infoBlockType = 'pay/info';
    
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;
    protected $_canUseCheckout = true;
    protected $_order = null;
    
    /**
     *  Retorna pedido
     *
     *  @return	  Mage_Sales_Model_Order 
     */
    public function getOrder()
    {
        if ($this->_order == null) {
        }
        return $this->_order;
    }
    
    /**
     *  Associa pedido
     *
     *  @param Mage_Sales_Model_Order $order
     */
    public function setOrder($order)
    {
        if ($order instanceof Mage_Sales_Model_Order) {
            $this->_order = $order;
        } elseif (is_numeric($order)) {
            $this->_order = Mage::getModel('sales/order')->load($order);
        } else {
            $this->_order = null;
        }
        return $this;
    }
    
    /**
     * log
     * 
     * Registra log de eventos/erros.
     * 
     * @param string $message
     * @param integer $level
     * @param string $file
     * @param bool $forceLog
     */
    public function log($message, $level = null, $file = '', $forceLog = false) {
        Mage::log("Evolucard - " . $message, $level, $file, $forceLog);
    }
    
    /**
     * Retorna o estado da última ordem da sessão
     * 
     * @return Mage_Sales_Model_Order $order
     */
    public function getStatusOfOrder() {
        $session = Mage::getSingleton('checkout/session');
        $orderToStatus = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
        return $orderToStatus->getState();
    }
    
    /**
     * Gera a url para pagamento da ordem após a compra.
     * 
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    public function getOrderPlaceRedirectUrl($orderId = 0) {
	   $params = array();
       $params['_secure'] = true;
       
	   if ($orderId != 0 && is_numeric($orderId)) {
	       $params['order_id'] = $orderId;
	   }
       
        return Mage::getUrl($this->getCode() . '/standard/redirect', $params);
    }
    
    /**
     * Retorna o code do método de pagamento.
     * 
     * @return string
     */
    public function getCode() {
        return $this->_code;
    }
    
    /**
     * Captura IP do Cliente
     * 
     * @return string $ip
     */ 
    public function getIpClient() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    /**
     * Pega ordem ao final das 3 etapas da evolucard e processa a ordem no Magento
     * 
     * @uses processEvo
     */
    public function retornoEvo($post)
    {
        //$this->_order = Mage::getModel('sales/order')->loadByIncrementId($post['OrderId']);
        $this->_order = Mage::getModel('sales/order')->load($post['OrderId']);
        $this->processEvo($post['StateId'],$post['PayOrderId']);
    }
    
    /**
     * Pega ordem ao final das 3 etapas da evolucard e processa a ordem no Magento
     * 
     * @uses processEvo
     */
    public function retornoTeste()
    {
        $orderId = Mage::getSingleton("core/session")->getPayOrderId();
        $this->log('orderId: '.$orderId);
        //$this->_order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $this->_order = Mage::getModel('sales/order')->load($orderId);
        
        //$this->log('_order_: '. var_dump($this->_order));
        $this->processEvo(Mage::getSingleton("core/session")->getPayEvoOrderId());
    }
    
    /**
     * Processa a ordem ao final da interação com a Evolucard
     * 
     */
    public function processEvo($transacaoID, $status = NULL)
    {
        $order = $this->getOrder();
                
        if($status == NULL) {
            $status = $order->getStatus();
        }
        
        $this->log("Pedido #" . $order->getRealOrderId() . ": $status");
        $this->log("Libera #" . Mage::getSingleton("core/session")->getLiberaFatura());
        
        if ($order->getId()) {
            if ($order->getPayment()->getMethod() == $this->getCode()) {

                $order->getPayment()->setEvolucardTransactionId(utf8_encode($transacaoID));
    		    $order->getPayment()->save();
                
                $libera = Mage::getSingleton("core/session")->getLiberaFatura();
                if($libera === "true") {
                    Mage::getSingleton("core/session")->setLiberaFatura("false");
                    $changeTo = "";
                    
                    if (in_array(strtolower(trim($status)), array('completo', 'aprovado', 'complete','pending'))) {
                        if ($order->canUnhold()) {
            			    $order->unhold();
            			}
            			if ($order->canInvoice()) {
            			    $changeTo = Mage_Sales_Model_Order::STATE_PROCESSING;
                            
                            Mage::getSingleton("core/session")->setInvoiceMail(1);
                            
            			    $invoice = $order->prepareInvoice();
            			    $invoice->register()->pay();
            			    $invoice_msg = utf8_encode(sprintf('Pagamento confirmado. Transa&ccedil;&atilde;o Evolucard: %s', $transacaoID));
            			    $invoice->addComment($invoice_msg, true);
            			    $invoice->sendEmail(true, $invoice_msg);
            			    $invoice->setEmailSent(true);
                            $this->log("Email Fatura Enviado");
            
            			    Mage::getModel('core/resource_transaction')
            			       ->addObject($invoice)
            			       ->addObject($invoice->getOrder())
            			       ->save();
            			    $comment = utf8_encode(sprintf('Fatura #%s criada.', $invoice->getIncrementId()));
            			    $order->setState($changeTo, true, $comment, $notified = true);
                            $order->save();                        
            			    $this->log("Fatura criada");
                            Mage::getSingleton("core/session")->setInvoiceMail(0);
                            Mage::getSingleton("core/session")->clear();
            			}
                        else {
            			    // Lógica para quando a fatura não puder ser criada
            			    $this->log("Fatura nao pode ser criada");
            			}
                    }
                    else {
                        // Pedido cancelado
        			    if ($order->canUnhold()) {
        				    $order->unhold();
        			    }
        			    if ($order->canCancel()) {
        			        $order_msg = "Pagamento Cancelado.";
            				$changeTo = Mage_Sales_Model_Order::STATE_CANCELED;
            				$order->getPayment()->setMessage($order_msg);
            				$order->cancel();
                            $this->log("Ordem cancelada ".$order->getRealOrderId());
        			    }
                    }
                }
                else {
                    $this->log("ERRO: Não liberou a fatura. Tentativa de Fraude");
                }
            }
            else {
                $this->log("ERRO: Pedido nao efetuado com este metodo de pagamento.");
    	    }
        }
        else {
            $this->log("ERRO: Pedido nao encontrado.");
        }
    }
}
?>
