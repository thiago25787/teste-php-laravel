![Logo AI Solutions](http://aisolutions.tec.br/wp-content/uploads/sites/2/2019/04/logo.png)

# AI Solutions

## Teste para novos candidatos (PHP/Laravel)

### Introdução

Este teste utiliza PHP 8.1, Laravel 10 e um banco de dados SQLite simples.

1. Faça o clone desse repositório;
1. Execute o `composer install`;
1. Crie e ajuste o `.env` conforme necessário
1. Execute as migrations e os seeders;

### Primeira Tarefa:

Crítica das Migrations e Seeders: Aponte problemas, se houver, e solucione; Implemente melhorias;

### Segunda Tarefa:

Crie a estrutura completa de uma tela que permita adicionar a importação do arquivo `storage/data/2023-03-28.json`, para a tabela `documents`. onde cada registro representado neste arquivo seja adicionado a uma fila para importação.

Feito isso crie uma tela com um botão simples que dispara o processamento desta fila.

Utilize os padrões que preferir para as tarefas.

### Terceira Tarefa:

Crie um test unitário que valide o tamanho máximo do campo conteúdo.

Crie um test unitário que valide a seguinte regra:

Se a categoria for "Remessa" o título do registro deve conter a palavra "semestre", caso contrário deve emitir um erro de registro inválido.
Se a caterogia for "Remessa Parcial", o titulo deve conter o nome de um mês(Janeiro, Fevereiro, etc), caso contrário deve emitir um erro de registro inválido.


Boa sorte!

## Para testar:
caso no `.env` `QUEUE_CONNECTION` seja `sync` não precisa rodar nada, caso seja `database`, basta rodar o comando `php artisan queue:work --queue=process_import_documents`
os testes foram divididos em 3 arquivos:
1. um para o listener ` Tests\Feature\Listeners\ImportDocumentHandlerTest`
2. um para o job `Tests\Feature\Jobs\ImportDocumentJobTest`
3. outro para o controller `Tests\Feature\API\V1\DocumentControllerTest` 
para rodar todos: `php artisan test`

foi utilizado vue para visão, então será necessário executar `npm install` e em seguida `npm run dev`
a rota de acesso é: `/document/import`
foi usada uma base de dados postgres (apenas por questão de prefêrencia mesmo)
