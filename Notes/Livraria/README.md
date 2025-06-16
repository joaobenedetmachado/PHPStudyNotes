# Livraria Online

Um sistema de livraria online desenvolvido com PHP, MySQL e Tailwind CSS.

## Requisitos

- PHP 5.3 ou superior
- MySQL 5.6 ou superior
- Servidor web (Apache, Nginx, etc.)

## Instalação

1. **Clone o repositório ou faça o download dos arquivos**

2. **Configuração do Banco de Dados**
   - Importe o arquivo `database.sql` para o seu servidor MySQL. Isso criará o banco de dados e as tabelas necessárias.
   - Edite o arquivo `database.php` com as credenciais do seu banco de dados:

   ```php
   $host = "localhost"; // Seu host
   $user = "root";      // Seu usuário
   $password = "";      // Sua senha
   $database = "livraria"; // Nome do banco de dados
   ```

3. **Configuração do Servidor**
   - Certifique-se de que a pasta `assets/images` tenha permissões de escrita para permitir o upload de imagens.

4. **Acesso ao Sistema**
   - Acesse o sistema pelo navegador usando o endereço: `http://localhost/caminho-para-o-projeto`
   - Use as seguintes credenciais para acessar a área administrativa:
     - Email: admin@livraria.com
     - Senha: admin123

## Notas sobre Segurança

Este sistema utiliza MD5 para hash de senhas por questões de compatibilidade com PHP 5.3. Em ambientes de produção com PHP 5.5+, é recomendável atualizar o código para usar funções mais seguras como `password_hash()` e `password_verify()`.

## Estrutura do Projeto

- `database.php` - Configuração de conexão com o banco de dados
- `functions.php` - Funções comuns utilizadas pelo sistema
- `header.php` e `footer.php` - Cabeçalho e rodapé das páginas
- `index.php` - Página inicial
- `login.php` e `registro.php` - Páginas de autenticação
- `livro.php` - Página de detalhes do livro
- `carrinho.php` - Carrinho de compras
- `admin/` - Área administrativa
- `assets/` - Arquivos estáticos (imagens, CSS, etc.)

## Recursos

- Listagem de livros com filtros por título, autor, editora e categoria
- Cadastro e autenticação de usuários
- Carrinho de compras
- Área administrativa para gerenciar livros, autores, editoras e categorias
- Design responsivo com Tailwind CSS 