<?php

class Xpdev_Pay_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Escapa entidades HTML.
     * Função criada para compatibilidade com versões mais antigas do Magento.
     *
     * @param   mixed $data 
     * @param   array $allowedTags
     * @return  string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        $core_helper = Mage::helper('core');
        if (method_exists($core_helper, "escapeHtml")) {
            return $core_helper->escapeHtml($data, $allowedTags);
        } elseif (method_exists($core_helper, "htmlEscape")) {
            return $core_helper->htmlEscape($data, $allowedTags);
        } else {
            return $data;
        }
    }
    
    public function convertOrderId($OrderId) {
        $orderStr = (string)$OrderId;
        $tam = strlen($orderStr);
        
        for($i=0;$i<8-$tam;$i++) {
            $orderStr = '0'.$orderStr;
        }
        $orderStr = '1'.$orderStr;
        
        return $orderStr;
    }
    
    public function revertOrderId($OrderId) {
        $orderStr = (string)$OrderId;
        
        $orderStr = explode('0',$orderStr);
        $pos = count($orderStr);
                 
        return $orderStr[$pos-1];
    }
}