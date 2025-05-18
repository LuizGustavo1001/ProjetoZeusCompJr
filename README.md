# ProjetoZeusCompJr
 <h2>Projeto de um site conectado a um Banco de Dados em MySQL utilizando PHP</h2>
 <h3>⬇️ Preview do Site</h3>
 <a href="preview-images/preview.jpg"><img src="preview2.png" alt=""></img></a>
 
 <p>- Teste de Sistema backend de Banco de Dados para <strong>Gerenciar Funcionários e orçamentos</strong> de uma <strong>Empresa</strong></p>
 <p>- <strong>FrontEnd</strong>: <strong>HTML5</strong> com as <strong>CSS</strong> + <strong>JavaScript</strong></p>
 
 <hr>
 
 <h3>📟 API </h3>
 <p>
    - Inicialmente, projetada utilizando a linguagem <strong>PHP</strong> para conectar um <strong>Banco de Dados</strong>(BackEnd) com uma <strong>Página Web</strong>(FrontEnd)
 </p>
 <p>- Gerenciar Funcionários, Orçamentos e Estoques, adicionando e pesquisando</p>
 <p>🔐 Senhas protegidas com <strong>Sistema Hash</strong> imbutido no banco de dados: senhas são armazenadas em chaves</p>
 <p>- Validação de filtros escolhidos por meio de vetores indicando quais os possíveis valores válidos (evitar SQL injection)</p>
 <p>- Uso de<strong>$stmt</strong> e seus derivados para evitar SQL injection</p>
 <p>- Possui soluções para usuários que tentarem acessar a páginas bloqueadas</p>
 <img src="preview-images/preview3.png" alt=""></img>
 <ol>
    <li>👤 <strong>Funcionários</strong></li>
    <p>- Mostrar na tela 
        <ul>
            <li><strong>Total de Funcionários</strong></li>
            <li><strong>Todos os Funcionários Cadastrados</strong></li>
            <li><strong>Realizar Buscas</strong> por nome</li>
            <li><strong>Filtra</strong> por Nome, por Área de Atuação, por Cargo e por Idade (Ordem Crescente)</li>
        </ul>
    </p>
     <ul>
         <li>Nome</li>
         <li>Data de Nascimento</li>
         <li>Email</li>
         <li>Número de Telefone</li>
         <li>Gênero</li>
         <li>Data de Ingresso</li>
         <li>Cargo</li>
         <li>Área</li>
     </ul>
     <li>💰 <strong>Orçamentos</strong></li>
     <p>- Mostra na tela
        <ul>
            <li><strong>Total de Orçamentos</strong></li>
            <li><strong>Todos os Orçamentos Cadastrados</strong></li>
            <li><strong>Realizar Buscas</strong> por Número do Orçamento</li>
            <li>
                <strong>Filtra</strong> por Númerom, Valor Estimado(crescente), por Custo Previsto(crescente) e por Nome do Cliente (Ordem Crescente)
            </li>
        </ul>
     </p>
     <ul>
         <li>Número do Orçamento</li>
         <li>Descrição</li>
         <li>Valor Estimado</li>
         <li>Custos Previstos</li>
         <li>Cliente Relacionado</li>
     </ul>
     <li>📝 <strong>Estoques</strong>(Em Desenvolvimento) </li>
 </ol>

 <hr>
 <p>🌑 Possui suporte para <strong>Modo Escuro</strong> </p>
 <p>🗺️ Navegação entre as sessões (user-page) utilizando <strong>JavaScript</strong> </p>
 <p>📱  Possui suporte à responsividade em dispositivos portáteis</p>
 <hr>

 <h3>📂 Esquema de Pastas</h3>
    <pre>
        |
        |-- public                              (Interface Visual)
        |   |-- general-images                  (imagens utilizadas em todas as páginas)
        |   |-- icon                            (ícone/favicon das páginas)
        |   |-- login-page                      (página de login)
        |   |-- scripts                         (JavaScript utilizado nas páginas)
        |   |-- sign-up-page                    (página de cadastro)
        |   |-- styles                          (Folhas de Estilo utilizadas nas páginas)
        |   |-- users-page                      (página do usuário já logado)
        |
        |-- dbConnection.php                    (Conectar o Banco de Dados com o FrontEnd)
        |
        |-- DumpEmpresaPZ.sql                   (Clone do Banco de Dados)
        |
        |-- usuario.txt                         (Email e Senha de usuários já cadastrados)   
    </pre>
<hr>

<h3>🖥️ Rodar o projeto</h3>
<ol>
    <li>Baixe o <a href="https://www.youtube.com/watch?v=0Y9OZ0vc1SU&t=213s">XAMPP</a></li>
    <li>Ative os módulos <strong>Apache</strong> e <strong>MySQL dentro do XAMPP</strong></li>
    <li>Baixe o <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQLWorkBench</a></li>
    <li>Abra o arquivo "DumpEmpresaPZ.sql" e Clone o Banco de Dados (Dump)</li>
    <li>
        Adicione o Banco de Dados ao seu Servidor Local clicando no símbolo demonstrado abaixo <br> <img src="preview-images/dump.png" alt=""></img>
    </li>
    <li>
        Para verificar se o Banco de Dados foi realmente adicionado digite no navegador "localhost/phpmyadmin", se a relação "empresapz" existir
        na aba esquerda da tela o Banco de Dados foi adicionado com sucesso <img src="preview-images/phpmyadmin.png" alt=""></img>
    </li>
    <li>Adicione a Pasta do projeto a pasta "htdocs" dentro de xampp (C:\xampp\htdocs)</li>
    <li>
        Digite no Navegador "localhost/ProjetoZeusCompJr/public/users-page/user.php"<img src="preview-images/local.png" alt=""></img>
        <br> 
        <strong>Ou</strong>
        <br> 
        Digite no Navegador "localhost/ProjetoZeusCompJr/public/" e navegue pela pasta que quiser
    </li>
</ol>

<hr>

<h3>📋 Para fazer: </h3>
<ul>
    <li>Página de Estoques</li>
    <li>Página de Notificações</li>
    <li>✅ Permitir acesso completo ao site apenas para usuários logados </li>
    <li>✅ Linkar página de login com página do usuário já logado</li>
    <li>✅ Linkar página de cadastrar novo usuário com o Banco de Dados</li>
    <li>✅ Melhorar CSS</li>
    <li>Mandar código de recuperação de senha pelo email</li>
    <li>✅ Segurança de Senhas</li>
    <li>Botão de Lembrar Usuário</li>
</ul>
