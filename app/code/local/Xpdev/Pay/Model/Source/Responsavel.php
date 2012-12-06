<?php

class Xpdev_Pay_Model_Source_Responsavel
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => "1",
                'label' => 'Estabelecimento'
            ),
            array(
                'value' => "2",
                'label' => 'Administradora'
            )
        );
    }
}