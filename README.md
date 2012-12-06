Módulo de Pagamento Evolucard para Magento

Instruções
Recomendamos que primeiramente realize backup completo do seu site, banco e dados e arquivos. Eu ou mesmo a Evolucard não nos responsabilizamos por quaisquer danos ou prejuízos financeiros decorrentes da má utilização ou instalação desse módulo.

Requerimentos

* Magento 1.4.2.0 ou superior
* PHP 5.2.0 ou superior

Instalações

1.	Faça o download do arquivo zipado (.zip) do módulo (provavelmente já o fez).
2.	Descompactar os arquivos para uma pasta qualquer em seu computador, por exemplo, nova_pasta.
3.	Caso sua loja já possua jQuery, delete* o arquivo jquery.js da pasta: nova_pasta/skin/frontend/default/default/js .
4.	Envie via FTP todos os arquivos e pastas que foram descompactados em sua pasta (ex: nova pasta).

Configuração

1.	Limpar o cache do Magento através do menu SISTEMA >  GERENCIAMENTO DE CACHE
2.	Clique em SISTEMA > CONFIGURAÇÃO
3.	Clique na seção VENDAS no subitem MÉTODOS DE PAGAMENTOS e abra a seção EVOLUCARD.
4.	Coloque nos devidos campos as informações referentes a códigos de integração, números de parcelas e responsável pelo parcelamento que serão fornecidos pela Evolucard.
5.	Para colocar o módulo em Produção, desative o campo AMBIENTE DE TESTE selecionando a opção NÃO.
6.	O campo MENSAGEM, quando utilizado, substitui o texto padrão da Evolucard na página de checkout.

Customização

Além de utilizar o campo mensagem para customizar o texto que será exibido no momento da escolha da forma de pagamento, também é possível pode efetuar alterações visuais via CSS (skin/frontend/default/default/css/evolucard/evolucard.css) e modificando o HTML no arquivo de template pay.xml, localizado em app/design/frontend/default/default/template/pay/form/pay.html

Você deve apagar o jQuery para que não ocorra o carregamento do arquivo mais de uma vez, o que vai ocasionar o não funcionamento.
