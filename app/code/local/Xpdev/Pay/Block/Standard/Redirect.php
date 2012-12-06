<?php
class Xpdev_Pay_Block_Standard_Redirect extends Mage_Core_Block_Abstract {
    
    protected function _toHtml(){
        $standard = Mage::getModel('pay/standard');
        $urldosite = Mage::getBaseUrl('web', true);
        
        $status_pgdireto = Mage::registry('StatusOrder');
        
        $html = '<div id="div-confirm-evolucard-link">'.
        '<button type="button" id="button-confirm-evolucard-link" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout" onclick="window.location=\''.$urldosite.'pay/standard/success/\';"><span><span>Confirmar Pagamento</span></span></button>'.
        '</div>';
        
        ?>
        
<h2 id="titulo-page-pay-evolucard"><?php echo $this->__('Pagamento com Evolucard'); ?></h2>
<p id="description-page-pay-evolucard"><?php echo $this->__('Pague em 30 segundos digitando apenas seu celular e o token recebido por SMS. EvoluCard apresenta a primeira tecnologia de autenticação forte para o e-commerce brasileiro.'); ?></p>
        
<div class="box-formulario-evolucard">
<!-- div form  -->
<!-- box-left -->
<div class="box-left" id="order-billing_address">

    <!-- passo 1 -->
    <div class="box-payments" id="div-evolucard-passo-um" style="display: none; ">
    
    <div class="box-head">
    <h2><?php echo $this->__('Passo 1 - Informe seu celular'); ?></h2>
    </div>
    
    <table id="table-evolucard-passo-um" class="data-table">
    <colgroup>
        <col width="1">
        <col width="1">
    </colgroup>
    <thead>
        <tr class="first last">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
        <p><?php echo $this->__('Informe seu celular no campo abaixo:'); ?></p>
        <tr class="odd">
            <td><b><?php echo $this->__('DDI'); ?></b></td>
            <td><b><?php echo $this->__('DDD'); ?></b></td>
            <td><b><?php echo $this->__('Celular'); ?></b></td>
            <td class="last"></td>
		</tr>
		<tr class="last even">
            <td><input type="text" id="ddi-celular" name="ddi-celular" size="2" maxlength="2" value="" class="input-text required-entry"></td>
            <td><input type="text" id="ddd-celular" name="ddd-celular" size="2" maxlength="2" value="" class="input-text required-entry"></td>
            <td><input type="text" id="celular" name="celular" size="9" maxlength="9" value="" class="input-text required-entry"></td>
            <td class="value last"><button type="button" title="Enviar" class="button" id="evc_cel_enviar"><span><span>Enviar</span></span></button></td>
		</tr>
    </tbody>
	</table>
    </div>
    <!-- /passo 1 -->
    
<!-- Passo 2 -->    
    <div class="box-payments" id="div-evolucard-passo-dois" style="display: none; ">

    <div class="box-head">
    <h2><?php echo $this->__('Passo 2 - Escolha a forma de pagamento'); ?></h2>
    </div>

    <table id="table-evolucard-passo-dois" class="data-table">
    <colgroup>
        <col width="1">
        <col width="1">
    </colgroup>
    <thead>
        <tr class="first last">
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr class="first odd">
            <td><b><?php echo $this->__('Nome Completo'); ?></b></td>
            <td class="last"><div id="retorno_nome_completo">&nbsp;</div></td>
        </tr>
        <tr class="even">
            <td><b><?php echo $this->__('Cartão'); ?></b></td>
            <td class="last"><div id="retorno_cartao">&nbsp;</div></td>
        </tr>
        <tr class="odd">
            <td><b><?php echo $this->__('Parcelas'); ?></b></td>
            <td class="last"><div id="retorno_parcelas">&nbsp;</div></td>
        </tr>
        <tr id="tr-dig-cpf" class="even">
            <td id="num-dig-cpf"></td>
            <td class="last"><input type="text" id="dig-cpf" name="dig-cpf" size="3" maxlength="3" value="" class="input-text required-entry"></td>
        </tr>
        <tr class="last odd">
            <td></td>
            <td class="last"><button type="button" title="Enviar" class="button" id="evc_parc_enviar"><span><span><?php echo $this->__('Enviar'); ?></span></span></button></td>
        </tr>
    </tbody>
    </table>

    </div>
<!-- /Passo 2 -->
    


<!-- Passo 3 -->
<div class="box-payments" id="div-evolucard-passo-tres" style="display: none; ">

    <div class="box-head">
    <h2><?php echo $this->__('Passo 3 - Confirmação'); ?></h2>
    </div>

    <div>
        <p><?php echo $this->__('Para finalizar sua compra informe abaixo o token recebido por SMS, substituindo os asteríscos (***) pelo código de segurança do seu cartão de crédito. Este encontra-se no verso do seu cartão sendo 3 números ou caso seu cartão seja AMEX, serão 4 dígitos encontrados na frente do cartão.'); ?></p>
    </div>

    <table id="table-evolucard-passo-tres" class="data-table">
        <colgroup>
            <col width="1">
            <col width="1">
        </colgroup>
        
        <thead>
            <tr class="first last">
                <th></th>
                <th></th>
            </tr>
        </thead>
        
        <tbody>
            <tr class="first even">
                <td><b><?php echo $this->__('Token'); ?></b></td>
                <td class="last"><input type="text" id="token-evc" name="token-evc" size="8" maxlength="8" value="" class="input-text required-entry"></td>
            </tr>
            <tr id="tr-evolucard-retorno-dia-aniversario-passo-tres" class="odd" style="display: none; ">
                <td><b><?php echo $this->__('Dia de Aniversário'); ?></b></td>
                <td class="last"><div id="retorno_dia_aniversario">&nbsp;</div></td>
            </tr>
            <tr id="tr-evolucard-retorno-mes-aniversario-passo-tres" class="odd" style="display: none; ">
                <td><b><?php echo $this->__('Mês de Aniversário'); ?></b></td>
                <td class="last"><div id="retorno_mes_aniversario">&nbsp;</div></td>
            </tr>
            <tr id="tr-evolucard-retorno-ano-aniversario-passo-tres" class="odd" style="display: none; ">
                <td><b><?php echo $this->__('Ano de Aniversário'); ?></b></td>
                <td class="last"><div id="retorno_ano_aniversario">&nbsp;</div></td>
            </tr>
            <tr id="tr-resend-token" class="even">
                <td></td>
                <td class="last">
                    <button type="button" title="<?php echo $this->__('Reenviar Token'); ?>" class="button" id="resend-token"><span><span><?php echo $this->__('Reenviar Token'); ?></span></span>
                    </button>
                </td>
            </tr>
            <tr class="last odd">
                <td></td>
                <td class="last">
                    <button type="button" title="<?php echo $this->__('Enviar'); ?>" class="button" id="evc_token_enviar"><span><span><?php echo $this->__('Enviar'); ?></span></span>
                    </button>
                    
                    <input type="hidden" id="transactionNumberEvc" name="transactionNumberEvc" value="">
                    <input type="hidden" id="additionalInfo" name="additionalInfo" value="">
                </td>
            </tr>

        </tbody>
    </table>

</div>
<!-- /Passo 3 -->    
    
    
    
</div>
<!-- /box-left -->
<!-- divisor -->
<!-- box-right -->
<div class="box-right">

<!-- Dados Pedido -->
<div class="box-payments" id="div-evolucard-dados-pedido" style="">

    <div class="box-head">
        <h2><?php echo $this->__('Dados do Pedido'); ?></h2>
    </div>
    
    <table id="table-evolucard-dados-pedido" class="data-table">
    <colgroup>
        <col width="1">
        <col width="1">
    </colgroup>
    <thead>
        <tr class="first last">
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr class="first odd">
            <td><b><?php echo $this->__('Número do pedido'); ?></b></td>
            <td class="last"><? echo Mage::helper('pay')->convertOrderId(Mage::registry('orderId')); ?></td>
        </tr>
        <tr class="last even">
            <td><b><?php echo $this->__('Valor do pedido'); ?></b></td>
            <td class="last"><div id="valor_pedido"><? echo 'R$ '.number_format(Mage::registry('orderValor'), 2, ",", ""); ?></div></td>
        </tr>
    </tbody>
    </table>

</div>
<!-- / Dados Pedido -->

<!-- Resultado -->
<div class="box-payments" id="div-evolucard-resultado-pedido" style="display: none;">

    <div class="box-head">
        <h2><?php echo $this->__('Resultado'); ?></h2>
    </div>
    
    <table id="table-evolucard-resultado-pedido" class="data-table">
    <colgroup>
        <col width="1">
    </colgroup>
    <thead>
        <tr class="first last">
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr class="first odd">
            <td id="result-box"></td>
        </tr>
        <tr class="last even">
            <td id="result-box-msg"></td>
        </tr>
    </tbody>
    </table>

</div>
<!-- / Dados Pedido -->


</div>
<!-- box-right -->
<!-- /div form -->
</div>

<script type="text/javascript">

    jQuery('#div-evolucard-passo-um').fadeIn();

    jQuery('#evc_cel_enviar').click(function(){
        evcPasso1();
    });
    
    jQuery('#evc_parc_enviar').click(function(){
        evcPasso2();
    });
    
    jQuery('#evc_token_enviar').click(function(){
        evcPasso3();
    });
    
    jQuery('#resend-token').click(function(){
        resend_token();
    });
    
    function evcPasso1(){
        
		var merchantCode = jQuery("#merchantCode").val();
		var mobileCc = jQuery("#ddi-celular").val();
		var mobileAc = jQuery("#ddd-celular").val();
		var mobileNb = jQuery("#celular").val();
		         
		var string_envio = { 'mobileCc' : mobileCc,
                             'mobileAc' : mobileAc,
                             'mobileNb' : mobileNb }
					
		if((mobileCc.length == 2 || mobileCc.length == 1) && (mobileAc.length == 2) && (mobileNb.length == 8 || mobileNb.length == 9))
		{
	        jQuery.ajax({
	            data: string_envio,
	            type: "POST",
	            url: "<?php echo $urldosite . 'pay/standard/p1/'; ?>",
	            success: function(response) {
                    response = jQuery.parseJSON(response);
                    console.log(response);
                    
	    			if (response.code == "EV000") {
                        montaPasso2(response);
	        		}
	        		else {
	        		    
	        		    switch (response.code){
		        			case "EV002":
		        				alert("Nenhum cliente válido foi encontrado com os parâmetros passados. " +
		        						"Não existe cliente com esse número ou ele não possui cartões válidos para compras.");
		        				break;
		        			case "EV003":
		        				alert("Nenhum cliente válido foi encontrado com os parâmetros passados. " +
		        						"O status do cliente está inválido para efetuar transações.");
		        				break;
		        			case "EV004":
		        				alert("Nenhum estabelecimento foi encontrado com os parâmetros passados.");
		        				break;
		        			case "EV005":
		        				alert("Nenhum estabelecimento válido foi encontrado com os parâmetros passados." +
		        						" O status do estabelecimento está inválido para efetuar transações.");
		        				break;
		        			case "EV006":
		        				alert("Um erro inesperado aconteceu em nosso servidor. " +
		        						"Entre em contato com a EvoluCard com o código do erro."); 
		        				break;
		        			case "EV007":
		        				alert("O IP utilizado para efetuar a chamada ao método é inválido." +
		        						" O estabelecimento não cadastrou o IP utilizado na Evolucard.");
		        				break;
                            case "EV027":
		        				alert("Consumidor não possui nenhum cartão aprovado.");
		        				break;
                            case "EV028":
		        				alert("Consumidor não possui nenhum cartão aprovado com bandeira aceita pelo estabelecimento.");
		        				break;
		        			case 'EV101':
		        				alert('Código do estabelecimento inválido. Verifique o formato enviado no campo "merchantCode".');
		        				break;
		        			case 'EV102':
		        				alert('DDI do celular do cliente inválido. Verifique o formato enviado no campo "mobileCc".');
		        				break;
		        			case 'EV103':
		        				alert('DDD do celular do cliente inválido. Verifique o formato enviado no campo "mobileAc".');
		        				break;
		        			case 'EV104':
		        				alert('Número do celular do cliente inválido. Verifique o formato enviado no campo "mobileNb".');
		       				break;
		        			default:
		        				alert(response.code+" - "+response.errorMsg);
		        				break;
	        			}
	        		}
	        	}
	        });
		}
	}
    
    function montaPasso2(response){
        
        jQuery('#retorno_nome_completo').html(response.consumerName);
                
        var select = "<select id='cartao-evolucard' name='cartao-evolucard'>"
        console.log(response);
        console.log(response.cardList[0].description);
        
        for(var qCard=0; qCard < response.cardList.length; qCard++) {
            var descricao = response.cardList[qCard].description;
			if(descricao.length > 16) {
				descricao = descricao.substring(0,16); 
			}
            var valorOption = response.cardList[qCard].paymentBrand + "-" + response.cardList[qCard].id;
            var labelOption = descricao + " - " + response.cardList[qCard].binNumber;
            select += "<option value='"+valorOption+"'>"+labelOption+"</option>";
        }
        
        select += "</select>"; 
        
        jQuery('#retorno_cartao').html(select);
        
        var valor = '<?php echo number_format(Mage::registry('orderValor'), 2, ",", ""); ?>'//jQuery('#valor_pedido').text();
        valor = valor.replace('.', '');
    	valor = valor.replace(',', '.');
    	
        var parcelas = '<select id="opcao-evc" name="opcao-evc">';
    	
        for (i=1;i<=<?php echo $standard->getConfigData('parcelamento'); ?>;i++)
    	{
        	parcela = valor/i;
        	parcelas += '<option value="'+i+'">'+i+' x de R$ '+parcela.formatMoney()+'</option>';
    	}
    	parcelas += '</select>';
    	
        jQuery('#retorno_parcelas').html(parcelas);
        
		valor_operacao = '<?php echo number_format(Mage::registry('orderValor'), 2, ",", ""); ?>';//jQuery('#valor-operacao').val()+"";
		valor_operacao = valor_operacao.replace('.','').replace(',','.');
		exibeOpcoesCartaoEVC(valor_operacao);
        
        if(response.additionalSecurity == 1) {
            jQuery('#num-dig-cpf').html('<b><?php echo $this->__('Três primeiros dígitos do CPF'); ?></b>');
        }
        else {
            if(response.additionalSecurity == 2) {
                jQuery('#num-dig-cpf').html('<b><?php echo $this->__('Três dígitos do meio do CPF'); ?></b>');
            }
            else {
                if(response.additionalSecurity == 3) {
                    jQuery('#num-dig-cpf').html('<b><?php echo $this->__('Três últimos dígitos do CPF'); ?></b>');
                }
                else {
                    jQuery('#tr-dig-cpf').remove();
                    jQuery('#table-evolucard-passo-dois tr.last').removeClass('odd')
                    jQuery('#table-evolucard-passo-dois tr.last').addClass('even');
                }
            }
        }
        
        
        jQuery('#div-evolucard-passo-um').fadeOut();
        jQuery('#div-evolucard-passo-dois').fadeIn();
	}
    
    function exibeOpcoesCartaoEVC(valor) {

    	jQuery("#parcelamento-evc").html("");
    	
    	var html = '<select id="opcao-evc" name="opcao-evc">';
		for (i=1;i<=<?php echo $standard->getConfigData('parcelamento'); ?>;i++) {
			parcela = valor/i;
    		html+='<option value="'+i+'">'+i+' x de R$ '+parcela.formatMoney()+'</option>';
    	}
    	html += '</select><br>';
    		
    	jQuery("#parcelamento-evc").html(html);
    	jQuery("#parcelamento-evc").show();
    	return;
    }
    
    Number.prototype.formatMoney = function(c, d, t){ //Serve para formatar um number em formato dinheiro 
	var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, 
	i = parseInt(n = (+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0; 
	return (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) 
	+ (c ? d + (n - i).toFixed(c).slice(2) : ""); 
	};
    
    function evcPasso2(){
    	
    	var cardId =  jQuery("#cartao-evolucard").val();
		var charPos = cardId.indexOf('-');
		var bandeira = cardId.substring(0,charPos);
		var cardId = cardId.substring(charPos+1);
    	var value = valor_operacao; //$('#valor-operacao').val()+""; // valor da transacao
    		value = value.replace(',','.');
    	var numberPayment = jQuery('#opcao-evc').val();
    	var installmentResponsible = '<?php echo $standard->getConfigData('responsa'); ?>' // quem parcela? estabelecimento ou administradora?
    	var additionalSecurity = jQuery('#dig-cpf').val();
        
        if(installmentResponsible == 1) {
            installmentResponsible = 'M'
        } else {
            installmentResponsible = 'A'
        }
        
    	if (cardId!='' && numberPayment!='' &&
    		(installmentResponsible=='M' || installmentResponsible=='A') && bandeira!='')
    	{
    	    setTimeout(function(){
                if(additionalSecurity != "" && jQuery('#dig-cpf').size() > 0) {
                    var string_envio = { 'cardId' : cardId,
                                    'numberPayment' : numberPayment,
        							'value' : value,
                                    'additionalSecurity' : additionalSecurity,
        							'bandeira' : bandeira }
                }
                else {
                    var string_envio = { 'cardId' : cardId,
                                    'numberPayment' : numberPayment,
        							'value' : value,
        							'bandeira' : bandeira }
                }
        	    
    		    jQuery.ajax({
                    data: string_envio,
		            type: "POST",
                    url: "<?php echo $urldosite . 'pay/standard/p2/'; ?>",
                    success: function(response) {
                        response = jQuery.parseJSON(response);
                        console.log(response);
                        if(response.code == "EV000") {
                            x8globalInfoEv0 = response;
                            montaPasso3(response);
                        }
                        else {
                            switch (response.code) {
            	    			case 'EV002': alert("Nenhum cliente válido foi encontrado com os parâmetros passados. Não existe cliente com esse número ou ele não possui cartões válidos para compras."); break;
            	    			case 'EV003': alert("Nenhum cliente válido foi encontrado com os parâmetros passados. O status do cliente está inválido para efetuar transações."); break;
            	    			case 'EV004': alert("Nenhum estabelecimento foi encontrado com os parâmetros passados."); break;
            	    			case 'EV005': alert("Nenhum estabelecimento válido foi encontrado com os parâmetros passados. O status do estabelecimento está inválido para efetuar transações."); break;
            	    			case 'EV007': alert("O IP utilizado para efetuar a chamada ao método é inválido. O estabelecimento não cadastrou o IP utilizado na EvoluCard."); break;
            	    			case 'EV008': alert("O cartão do cliente está inválido para efetuar transações."); break;
            	    			case 'EV009': alert("Não foi encontrada uma integração para efetuar a transação. Verifique o cadastro do estabelecimento, para certificar que há integrações para a bandeira do cartão escolhido."); break;
            	    			case 'EV010': alert("Transação reprovada por crédito. (APENAS AMBIENTE DE TESTES)"); break;
            	    			case 'EV011': alert("TOKEN não enviado. Entre em contato com a EvoluCard."); break;
            	    			case 'EV020': alert("Erro de comunicação com o integrador (adquirente ou gateway)."); break;
            	    			case 'EV025': alert("Erro na criação da transação. Entre em contato com a EvoluCard."); break;
                                case 'EV027': alert("Consumidor não possui nenhum cartão aprovado."); break;
                                case 'EV028': alert("Consumidor não possui nenhum cartão aprovado com bandeira aceita pelo estabelecimento."); break;
            	    			case 'EV031': alert("Plano do estabelecimento não possui o número de parcelas da transação efetuada."); break;
                                case 'EV032': alert("Este cartão possui uma compra não finalizada, espere 30 segundos para iniciar uma nova compra."); break; 
                                case 'EV061': alert("Autenticação extra incorreta. Se desejar, tente novamente."); break;
                                case 'EV062': alert("Dados não confirmados! A autenticação extra não foi informada ou esta loja não está com o sistema \"SMS Block\" atualizado. Nesse caso para prosseguir, por favor, clique aqui para acessar sua área de consumidor EvoluCard e desabilite esta funcionalidade. Em seguida retorne a loja e repita o processo de pagamento."); break;
                                case 'EV079': alert("Valor da total da transação diferente da soma dos valores de cada cartão."); break;
                                case 'EV080': alert("A transação bloqueada porque o cliente já realizou uma transação no período de 00h00 às 06h00. A EvoluCard permite apenas uma transação nesse horário por cliente."); break;
                                case 'EV082': alert("Valor da transação é maior que o permitido pelo estabelecimento. Verifique seu cadastro na EvoluCard para mais detalhes."); break;
                                case 'EV101': alert("Código do estabelecimento inválido. Verifique o formato enviado no campo \"merchantCode\"."); break;
            	    			case 'EV105': alert("ID do cartão do cliente inválido. Verifique o formato enviado no campo \"cardId\"."); break;
            	    			case 'EV106': alert("Valor da transação inválido. Verifique o formato enviado no campo \"value\"."); break;
            	    			case 'EV107': alert("Número de parcelas da transação inválido. Verifique o formato enviado no campo \"numberPayment\"."); break;
            	    			case 'EV108': alert("Número de documento do estabelecimento inválido. Verifique o formato enviado no campo \"docNumber\"."); break;
            	    			case 'EV109': alert("Responsável pelo parcelamento inválido. Verifique o formato enviado no campo \"installmentResponsible\"."); break;
                                case 'EV120': alert("IP do cliente inválido. Verifique o formato enviado no campo \"consumerIp\"."); break;
        	    			}
                        }
                	}
                });
    		},800);
    	}
    	else{
    		alert("Preencha todos os Campos");
    	}
    }
    
    function montaPasso3(json){
        
        jQuery("#transactionNumberEvc").val(json.transactionNumberEvc);
        jQuery("#additionalInfo").val(json.additionalInfo);
        
		if (json.additionalInfo == 1){
			htmlDia = "<select id='birthInfo'>" +
					"<option value=''>Dia</option>";
			for (var dia=1; dia<=31; dia++){
				if (dia<10) {
					str_dia = "0"+dia;
				}
				else {
					str_dia = dia;
				}
				htmlDia += "<option value='"+str_dia+"'>"+str_dia+"</option>";
			}
			htmlDia += "</select>";
            jQuery('#retorno_dia_aniversario').html(htmlDia);
            jQuery('#tr-evolucard-retorno-dia-aniversario-passo-tres').show();
            jQuery('#retorno_dia_aniversario').show();
		}
		else if (json.additionalInfo == 2){
			htmlMes = "<select id='birthInfo'>" +
			"<option value=''>Mês</option>";
			for (var mes=1; mes<=12; mes++){
				if (mes<10){
					str_mes = "0"+mes;
				}
				else{
					str_mes = mes;
				}
				htmlMes += "<option value='"+str_mes+"'>"+str_mes+"</option>";
			}
			htmlMes += "</select>";
            jQuery('#retorno_mes_aniversario').html(htmlMes);
            jQuery('#tr-evolucard-retorno-mes-aniversario-passo-tres').show();
            jQuery('#retorno_mes_aniversario').show();
		}
		else if (json.additionalInfo == 3){
			htmlAno = "<input type='text' id='birthInfo' size='4' maxlength='4' >";
            jQuery('#retorno_ano_aniversario').html(htmlAno);
            jQuery('#tr-evolucard-retorno-ano-aniversario-passo-tres').show();
            jQuery('#retorno_ano_aniversario').show();
		}
    	
    	if (json.resend > 0){
            jQuery('#resend-token').parent().parent().find('td').first().html(json.resend+' envio(s) restante(s)');
    	}
        else {
            if(json.additionalInfo != 1 && json.additionalInfo != 2 && json.additionalInfo != 3) {
                jQuery('#tr-resend-token').remove();
            }
            else {
                jQuery('#table-evolucard-passo-tres tr.last').removeClass('odd');
                jQuery('#table-evolucard-passo-tres tr.last').addClass('even');
        		jQuery('#tr-resend-token').remove();
            }
        }
        
        jQuery('#div-evolucard-passo-dois').fadeOut();
        jQuery('#div-evolucard-passo-tres').fadeIn();
    }
    
    function resend_token() {
    
        var json = x8globalInfoEv0;
        
        var evcNumber = json.transactionNumberEvc;
    	
    	var string_envio = { 'transactionNumberEvc' : evcNumber }
        
        if(json.resend == 1 || json.resend == 2 ) {
        	jQuery.ajax({
                data: string_envio,
                type: "POST",
    		    url: "<?php echo $urldosite . 'pay/standard/r1/'; ?>",
                success: function(response) {
                    response = jQuery.parseJSON(response);
                    console.log(response);
                    
                    if(response.resend == 1) {
                        jQuery('#resend-token span span').text("<?php echo $this->__('Requisitar Ligação'); ?>");
                    }
                    
            		if (response.code == "EV000") {
          		        if(response.resend == 1) {
      			            alert("<?php echo $this->__('Token Reenviado'); ?>");
                        }
                        else {
                            alert("<?php echo $this->__('Em alguns instantes você receberá a ligação'); ?>");
                        }
                        
                        if ( response.resend < 1 || response.resend > 2 ) {
                			if(json.additionalInfo != 1 && json.additionalInfo != 2 && json.additionalInfo != 3) {
                                jQuery('#tr-resend-token').remove();
                            }
                            else {
                                jQuery('#table-evolucard-passo-tres tr.last').removeClass('odd');
                                jQuery('#table-evolucard-passo-tres tr.last').addClass('even');
                        		jQuery('#tr-resend-token').remove();
                            }
                		}
                        else {
                            jQuery('#resend-token').parent().parent().find('td').first().html(response.resend+' <?php echo $this->__('envio(s) restante(s)'); ?>');
                        }
            		}
            		else{
                        switch (response.code) {
                	    	case 'EV004': alert("Nenhum estabelecimento foi encontrado com os parâmetros passados."); break;
                            case 'EV005': alert("Nenhum estabelecimento válido foi encontrado com os parâmetros passados. O status do estabelecimento está inválido para efetuar transações."); break;
                            case 'EV007': alert("O IP utilizado para efetuar a chamada ao método é inválido. O estabelecimento não cadastrou o IP utilizado na EvoluCard."); break;
                            case 'EV013': alert("Um erro inesperado aconteceu em nosso servidor. Entre em contato com a EvoluCard com o código do erro."); break;
                            case 'EV014': alert("Número máximo de reenvios de TOKEN excedido."); break;
                            case 'EV015': alert("Limite de tempo para reenvio de TOKEN excedido."); break;
                            case 'EV016': alert("Transação não está com status Incompleta."); break;
                            case 'EV017': alert("Não foi possível reenviar o TOKEN."); break;
                            case 'EV018': alert("Transação não encontrada."); break;
                            case 'EV026': alert("Tempo mínimo para reenvio de TOKEN é de 30 segundos. Aguarde e tente novamente."); break;
                            case 'EV111': alert("Número de transação inválido. Verifique o formato enviado no campo \"transactionNumberEvc\"."); break;
                        }
            		}
            	}
            });
        }
    }
    
    function evcPasso3() {
    	
    	var transactionNumberEvc = jQuery("#transactionNumberEvc").val();
        
        <?php if($standard->getConfigData('istest') == "1"): ?> 
        var token = x8globalInfoEv0.tokenSent;        
        <?php else: ?>
        var token = jQuery("#token-evc").val();                        
        <?php endif; ?>        
    	
    	var birthRequested = jQuery("#additionalInfo").val();
    	var birthOk = false;
    	var operacao = jQuery("#opeCode").val();
    	var cardId =  jQuery("#cartao-evolucard").val();// id do cartao escolhido
    		var charPos = cardId.indexOf('-');
    		var bandeira = cardId.substring(0,charPos);
    	
    	if (birthRequested != 0) {
            var birthInfo = '';
    	    if(birthRequested == 1) {
    	       birthInfo = jQuery("#retorno_dia_aniversario select").val();
               birthOk = true;
    	    }
            else if(birthRequested == 2) {
    	       birthInfo = jQuery("#retorno_mes_aniversario select").val();
               birthOk = true;
    	    }
            else if(birthRequested == 3) {
    	       birthInfo = jQuery("#retorno_ano_aniversario input").val();
               birthOk = true;
    	    }
    	}
    	else{
    		birthOk = true;
    	}
        
        jQuery('#result-box').html('');
        jQuery('#result-box-msg').html('');
    	
    	if (transactionNumberEvc!='' && token!='' && birthOk){
    		
    		if (birthRequested != 0) {
                var string_envio = {
    				"transactionNumberEvc" : transactionNumberEvc,
    				"token" : token,
                    "cardId" : cardId,
                    "birthInfo" : birthInfo
                }
    		}
            else {
                var string_envio = {
    				"transactionNumberEvc" : transactionNumberEvc,
                    "cardId" : cardId,
    				"token" : token
                }
            }
    		
    		jQuery.ajax({
                data: string_envio,
		        type: "POST",
                url: "<?php echo $urldosite . 'pay/standard/p3/'; ?>",
                success: function(response) {
                    
    	            response = jQuery.parseJSON(response);
                    console.log(response);
                    
                    if (response.code == "EV000") {
                        var txt = '<center><b><?php echo $this->__('Transação Aprovada'); ?></b></center>';
                        jQuery('#result-box').html(txt);
                        
                        txt = '';
        				jQuery.each(response, function(attr, val){
        					txt += attr+" : "+val+"<br>";
        					if (attr=='merchantSalesNewDTO' && response.merchantSalesNewDTO != null){
        						jQuery.each(response.merchantSalesNewDTO, function(attrDTO, valDTO){
        							txt += ""+attrDTO+" : "+valDTO+"<br>";
        						});
        					}
        				});
       					jQuery('#result-box-msg').html(txt);
                        jQuery('#div-evolucard-resultado-pedido').fadeIn();
                        jQuery('#div-confirm-evolucard-link').fadeIn();
                    }
                    else {
                        if(response.code == "EV019") {
                            var txt = '<center><b><?php echo $this->__('Transação Reprovada'); ?></b></center>';
                            jQuery('#result-box').html(txt);
                            
                            txt = 'Token incorreto na primeira tentativa. Se desejar, tente novamente.';
                            jQuery('#result-box-msg').html(txt);
                            jQuery('#div-evolucard-resultado-pedido').fadeIn();
                        }
                        else {
                            if(response.code == "EV029") {
                                var txt = '<center><b><?php echo $this->__('Transação Reprovada'); ?></b></center>';
                                jQuery('#result-box').html(txt);
                                
                                txt = '<?php echo $this->__('Token incorreto na segunda tentativa.'); ?><br><a href="<?php echo Mage::getSingleton('pay/standard')->getOrderPlaceRedirectUrl(Mage::registry('orderId')); ?>"><?php echo $this->__('Tentar Pagar Novamente.'); ?></a>';
                                jQuery('#result-box-msg').html(txt);
                                jQuery('#div-evolucard-resultado-pedido').fadeIn();
                            }
                            else {
                                switch (response.code) {
                	    	        case 'EV004': alert("Nenhum estabelecimento foi encontrado com os parâmetros passados."); break;
                                    case 'EV005': alert("Nenhum estabelecimento válido foi encontrado com osparâmetros passados. O status do estabelecimento está inválido para efetuar transações."); break;
                                    case "EV007": alert("O IP utilizado para efetuar a chamada ao método é inválido. O estabelecimento não cadastrou o IP utilizado na Evolucard.");
                                    case 'EV009': alert("Não foi encontrada uma integração para efetuar a transação. Verifique o cadastro do estabelecimento para certificar que há integrações para a bandeira do cartão escolhido."); break;
                                    case 'EV012': alert("Transação reprovada pelo adquirente. (APENAS AMBIENTE DE TESTES)"); break;
                                    case 'EV018': alert("Transação não encontrada."); break;
                                    case 'EV020': alert("Erro de comunicação com o integrador (adquirinte ou gateway)"); break;
                                    case 'EV021': alert("Informação adicional de nascimento incorreta na primeira tentativa. Se desejar, tente novamente."); break;
                                    case 'EV022': alert("Tempo limite para finalização da transação excedido."); break;
                                    case 'EV023': alert("Não foi encontrado integrador para efetuar a transação. Verifique o cadastro do estabelecimento para certificar que há integrações para a bandeira do cartão escolhido"); break;
                                    case 'EV030': alert("Informação adicional de nascimento incorreta na segunda tentativa. Transação reprovada."); break;
                                    case 'EV031': alert("Plano do Estabelecimento não possui o número de parcelas da transação efetuada."); break;
                                    case 'EV078': alert("Um dos cartões de uma das transações de compra não foi encontrado."); break;
                                    case 'EV081': alert("A transação foi bloqueada porque o cliente já realizou 3 transações no dia ou 2 transações neste estabelecimento no dia. A Evolucard permite para cada cliente apenas 3 transações por dia ou 2 transações efetuadas em um estabelecimento por dia."); break;
                                    case 'EV099': alert("Um erro inesperado aconteceu em nosso servidor. Entre em contato com a Evolucard com o código do erro."); break;
                                    case 'EV105': alert("ID do cartão do cliente inválido."); break;
                                    case 'EV110': alert("TOKEN Inválido"); break;
                                    case 'EV111': alert("Número de transação inválido. Verifique o formato enviado no \"transactionNumberEvc\"."); break;
                                    case 'EV112': alert("Informação adicional de nascimento inválida. Verifique o formato enviado no campo \"birthInfo\"."); break;
                                    case 'EV137': alert("Código de segurança do cartão inválido."); break;
                                }
                            }
                        }
                    }
            	}
            });
    	}
    }
</script>

   <?php
        ob_flush();
        
        
        return $html;
    }
    
}