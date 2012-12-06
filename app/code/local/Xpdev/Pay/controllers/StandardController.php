<?php
class Xpdev_Pay_StandardController extends Mage_Core_Controller_Front_Action {
    
    /**
     * Retorna o Checkout
     *
     * @return Mage_Checkout_Model_Session 
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    /**
     * Retorna ID da store, através do ID do pedido
     * 
     */
    function getOrderStoreId($orderId) {
        return Mage::getModel('sales/order')->load($orderId)->getStoreId();
    }
    
    protected function _expireAjax() {
        if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1', '403 Session Expired');
            exit;
        }
    }
    
    /**
     * Retorna o módulo
     * 
     * @return Xpdev_Pay_Model_Standard
     */
    public function getEvo() {
        return Mage::getSingleton('xpdev_pay/standard');
    }
    
    /**
     * Cria o pedido, envia o email, e redireciona para página de pagamento da Evolucard
     * 
     */
    public function redirectAction()
    {   
        ob_start();
        $session = $this->getCheckout();
        //$evolucard = Mage::getModel('pay/standard');
        $evolucard = Mage::getSingleton('pay/standard');
        
        $evolucard->log("[ Redirect Controller ]");
        Mage::register('StatusOrder', $evolucard->getStatusOfOrder());
        Mage::register('EvoCode', $evolucard->getConfigData('evocode'));
        Mage::register('CieloCode', $evolucard->getConfigData('cielocode'));
        Mage::register('CieloKey', $evolucard->getConfigData('cielokey'));
        Mage::register('RedecardCode', $evolucard->getConfigData('redecardcode'));
        
        if($this->getRequest()->getParam('order_id')) {
            $orderId = $this->getRequest()->getParam('order_id');
            $evolucard->log('GET [ '.$orderId.' ]');
        }
        //$orderId = Mage::helper('pay')->revertOrderId($orderId);
        
        if (empty($orderId)) {
            $orderId = $session->getLastOrderId();
            //$session->clear(); //Limpa o carrinho
        }

        $order = Mage::getModel('sales/order')->load($orderId);
        
        if ($order->getId()) {
            Mage::register('orderId', $orderId);
            Mage::register('orderValor', (float)$order->getBase_grand_total());
            //$orderId = $order->getId();
            
            // Envia email de confirmação ao cliente
            if(!$order->getEmailSent()) {
            	$order->sendNewOrderEmail();
    			$order->setEmailSent(true);
    			$order->save();
                $evolucard->log("[ Email Order Enviado ]");
            }
            
            $order_redirect = false;
            
            if ($order->getPayment()->getMethod() == $evolucard->getCode()) {
                switch ($order->getState()) {
                    case Mage_Sales_Model_Order::STATE_NEW:
                        Mage::getSingleton("core/session")->setPayOrderId($orderId);
                        
                        /*$html = $evolucard->setOrder($order);
                        $this->getResponse()->setHeader("Content-Type", "text/html; charset=ISO-8859-1", true);
                        $this->getResponse()->setBody($html);*/
                        break;
                    case Mage_Sales_Model_Order::STATE_PENDING_PAYMENT:
                        break;
                    case Mage_Sales_Model_Order::STATE_PROCESSING:
                        $order_redirect = true;
                        break;
                    case Mage_Sales_Model_Order::STATE_COMPLETE:
                        $order_redirect = true;
                        break;
                    default:
                        // Redireciona para página do pedido
                        $order_redirect = true;
                        break;
                }
            }
        }
        else {
            $order_redirect = true;
        }
        
        if($order_redirect === true) {
            $this->_redirect('', array('_secure' => true));
        }
        else {
            $this->loadLayout();
    		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
    		$this->getLayout()->createBlock('cms/block')->setBlockId('footer_static')->toHtml();
    		$this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('pay/standard_redirect'));
            ob_flush();
            $this->renderLayout();
        }
    }
    
    /**
     * Post para Passo 1 (getCards) de pagamento da Evolucard
     * 
     */
    public function p1Action() {
        
        if($this->getRequest()->isPost()) {
            
            $evolucard = Mage::getSingleton('pay/standard');
            $merchantCode = $evolucard->getConfigData('evocode');
            
            $mobileCc = $this->getRequest()->getParam('mobileCc');
            $mobileAc = $this->getRequest()->getParam('mobileAc');
            $mobileNb = $this->getRequest()->getParam('mobileNb');
            
            if($evolucard->getConfigData('istest') == "1") {
                $url = 'https://www.evolucard.com.br/postServiceTest/getCards';
            }
            else {
                $url = 'https://www.evolucard.com.br/postService/getCards';
            }
            
            $evolucard->log(" [ getCards ] ");
            $fields = array(
                'merchantCode' => urlencode($merchantCode),
                'mobileCc' => urlencode($mobileCc),
                'mobileAc' => urlencode($mobileAc),
                'mobileNb' => urlencode($mobileNb)
            );
            
            $curlAdapter = new Varien_Http_Adapter_Curl();
            $curlAdapter->setConfig(array('timeout'   => 20));
            //$curlAdapter->connect(your_host[, opt_port, opt_secure]);
            $curlAdapter->write(Zend_Http_Client::POST, $url, '1.1', array(), $fields);
            $resposta = $curlAdapter->read();
            $retorno = substr($resposta,strpos($resposta, "\r\n\r\n"));
            print($retorno);
            $curlAdapter->close();
        }
        else {
            print("No Post Method!");
        }
    }
    
    /**
     * Post para Passo 2 (preApproveTransaction) de pagamento da Evolucard
     * 
     */
    public function p2Action() {
        
        if($this->getRequest()->isPost()) {
            
            $evolucard = Mage::getSingleton('pay/standard');
            
            if($evolucard->getConfigData('istest') == "1") {
                $url = 'https://www.evolucard.com.br/postServiceTest/preApproveTransaction';
            }
            else {
                $url = 'https://www.evolucard.com.br/postService/preApproveTransaction';
            }
            
            $merchantCode = $evolucard->getConfigData('evocode');
            $cardId = $this->getRequest()->getParam('cardId');
            $value = $this->getRequest()->getParam('value');
            $numberPaymentMAX = $evolucard->getConfigData('parcelamento');
            $numberPayment = $this->getRequest()->getParam('numberPayment');
            
            if($numberPayment > $numberPaymentMAX) {
                $numberPayment = $numberPaymentMAX;
            }
            
            $installmentResponsible = $evolucard->getConfigData('responsa');
            $bandeira = $this->getRequest()->getParam('bandeira');
            //$docNumber = Mage::helper('pay')->convertOrderId(Mage::registry('orderId'));
            $docNumber = Mage::helper('pay')->convertOrderId(Mage::getSingleton("core/session")->getPayOrderId());
            $consumerIp = $evolucard->getIpClient();
            $additionalSecurityInfo = $this->getRequest()->getParam('additionalSecurityInfo');
            if($installmentResponsible == 1 || $numberPayment == 1) {
                $installmentResponsible = 'M';
            }
            elseif($installmentResponsible == 2) {
                $installmentResponsible = 'A';
            }
            
            $fields = array(
                'merchantCode' => $merchantCode,
                'docNumber' => $docNumber,
                'cardId' => $cardId,
                'consumerIp' => $consumerIp,
                'value' => $value,
                'numberPayment' => $numberPayment,
                'bandeira' => $bandeira,
                'installmentResponsible' => $installmentResponsible
            );
            
            $evolucard->log(" [ preApproveTransaction ] ");
            $evolucard->log(" [ DocNumber: ".$docNumber." ] ");
            
            if(isset($additionalSecurityInfo)) {
                $fields['additionalSecurityInfo'] = urlencode($additionalSecurityInfo);
            }
            
            $curlAdapter = new Varien_Http_Adapter_Curl();
            $curlAdapter->setConfig(array('timeout'   => 20));
            $curlAdapter->write(Zend_Http_Client::POST, $url, '1.1', array(), $fields);
            $resposta = $curlAdapter->read();
            $retorno = substr($resposta,strpos($resposta, "\r\n\r\n"));
            print($retorno);
            $curlAdapter->close();
            
            if(function_exists('json_decode')){
                $json_php = json_decode($retorno);
                if(isset($json_php->{'transactionNumberEvc'}))
                $evolucard->setTransactionIdEvo($json_php->{'transactionNumberEvc'});
            }
        }
        else {
            print("No Post Method!");
        }
    }
    
    /**
     * Post para Passo 3 (confirmTransaction) de pagamento da Evolucard
     * 
     */
    public function p3Action() {
        
        if($this->getRequest()->isPost()) {
            
            $evolucard = Mage::getSingleton('pay/standard');
            $evolucard->log(" [ confirmTransaction ] ");
            $recuperaTransactionIdEvo = $evolucard->getTransactionIdEvo();
            
            if(isset($recuperaTransactionIdEvo)) {
                $transactionNumberEvc = $recuperaTransactionIdEvo;
            }
            else {
                $transactionNumberEvc = $this->getRequest()->getParam('transactionNumberEvc');
            }
            Mage::getSingleton("core/session")->setPayEvoOrderId($transactionNumberEvc);
            
            $token = $this->getRequest()->getParam('token');
            $birthInfo = $this->getRequest()->getParam('birthInfo');
            $cardId = $this->getRequest()->getParam('cardId');
            $ChaveCielo = $evolucard->getConfigData('cielokey');//$this->getRequest()->getParam('ChaveCielo');
            $NumAfiliado = $evolucard->getConfigData('cielocode');//$this->getRequest()->getParam('NumAfiliado');
            $NumFiliado = $evolucard->getConfigData('redecardcode');
            
            $evolucard->setEvolucardTransactionId(utf8_encode($transactionNumberEvc));
            
            if($evolucard->getConfigData('istest') == "1") {
                $url = 'https://www.evolucard.com.br/postServiceTest/confirmTransaction';
            }
            else {
                $url = 'https://www.evolucard.com.br/postService/confirmTransaction';
            }
            
            $fields = array(
                'transactionNumberEvc' => $transactionNumberEvc,
                'token' => $token,
                'cardId' => $cardId
            );
            
            if(isset($birthInfo)) {
                $fields['birthInfo'] = $birthInfo;
            }            
            if(isset($ChaveCielo)) {
                $fields['Chave de Acesso'] = $ChaveCielo;
            }
            if(isset($NumAfiliado)) {
                $fields['Número da Afiliação'] = $NumAfiliado;
            }
            if(isset($NumFiliado)) {
                $fields['Número de Filiação'] = $NumFiliado;
            }
            
            $curlAdapter = new Varien_Http_Adapter_Curl();
            $curlAdapter->setConfig(array('timeout'   => 20));
            //$curlAdapter->connect(your_host[, opt_port, opt_secure]);
            $curlAdapter->write(Zend_Http_Client::POST, $url, '1.1', array(), $fields);
            $resposta = $curlAdapter->read();
            $retorno = substr($resposta,strpos($resposta, "\r\n\r\n"));
            print($retorno);
            $curlAdapter->close();
            
            if(function_exists('json_decode')) {
                $json_php = json_decode($retorno);
                $evolucard->log("Code da transacao: ".$json_php->{'code'});
                if(isset($json_php->{'code'})) {
                    if($json_php->{'code'} == "EV000") {
                        Mage::getSingleton("core/session")->setLiberaFatura("true");
                    }
                    else {
                        Mage::getSingleton("core/session")->setLiberaFatura("false");
                    }
                }
                else {
                    if(strpos($retorno, 'EV000')) {
                        Mage::getSingleton("core/session")->setLiberaFatura("true");
                    }
                    else {
                        Mage::getSingleton("core/session")->setLiberaFatura("false");
                    }
                }
            }
            else {
                if(strpos($retorno, 'EV000')) {
                    $evolucard->setLiberaFatura("true");
                }
                else {
                    $evolucard->setLiberaFatura("false");
                }
            }
        }
        else {
            print("No Post Method!");
        }
    }
        
    /**
     * Passo Extra de reenvio de token (resendToken)
     * 
     */
    public function r1Action() {
        if($this->getRequest()->isPost()) {
            //resendToken
            $evolucard = Mage::getSingleton('pay/standard');
            
            $transactionNumberEvc = $this->getRequest()->getParam('transactionNumberEvc');
            
            if($evolucard->getConfigData('istest') == "1") {
                $url = 'https://www.evolucard.com.br/postServiceTest/resendToken';
            }
            else {
                $url = 'https://www.evolucard.com.br/postService/resendToken';
            }
            
            $fields = array(
                'transactionNumberEvc' => urlencode($transactionNumberEvc)
            );
            
            $curlAdapter = new Varien_Http_Adapter_Curl();
            $curlAdapter->setConfig(array('timeout'   => 20));
            $curlAdapter->write(Zend_Http_Client::POST, $url, '1.1', array(), $fields);
            $resposta = $curlAdapter->read();
            $retorno = substr($resposta,strpos($resposta, "\r\n\r\n"));
            print($retorno);
            $curlAdapter->close();
        }
        else {
            print("No Post Method!");
        }
    }
    
    /**
     * Gera fatura e exibe tela de sucesso
     * 
     */
    public function successAction() {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!empty($orderId)) {
            Mage::getSingleton("core/session")->setPayOrderId($orderId);
        }
        else {
            $orderId = Mage::getSingleton("core/session")->getPayOrderId();
        }
        $evolucard = Mage::getModel('pay/standard');
                                
        if ($this->getRequest()->isPost()) {
            // É um $_POST, processa o retorno automático
            $evolucard->log("[ Inicio do processamento da fatura ]");
            $evolucard->retornoTeste($this->getRequest()->getPost());
            //$evolucard->retornoEvo($this->getRequest()->getPost());
            $evolucard->log("[ Fim do processamento da fatura ]");
        }
        else {
            $evolucard->retornoTeste();
        }
        
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure' => true));
        
        /*if ($orderId) {
            $evolucard->log("[ if - success ]");
            $storeId = $this->getOrderStoreId($orderId);
            
            $session = Mage::getSingleton('checkout/session');
            Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
            $this->_redirect('checkout/onepage/success', array('_secure' => true));
        }
        else {
            $evolucard->log("[ else - success ]");
            $session = Mage::getSingleton('checkout/session');
            Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
            $this->_redirect('checkout/onepage/success', array('_secure' => true));
        }*/
    }
}