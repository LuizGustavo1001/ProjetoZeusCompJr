<?php 
    ob_start();
    include '../../dbConnection.php';
    
    function modifyCookieValue($cookiePos, $newValue){
        setcookie($cookiePos, $newValue, time() + 3600, "/");
    }

    function changePicture($type, $field){
        global $mysqli;
        $uploadDirect = "../users-page/user-images";

        if(! is_dir($uploadDirect)){ // verifica se o diretório existe
            mkdir($uploadDirect, 0755, true); // permissão de modificar e adicionar
        }

        $fileTemp = $_FILES["picture"] ["tmp_name"]; // salva o caminho até a imagem no servidor temporariamente
        $fileName = basename($_FILES["picture"]["name"]); // pegar o nome do arquivo enviado pelo usuário
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // tipo do arquivo(png, por exemplo)
        $allowedExtensions = ["jpg", "png", "jpeg", "webp"]; // tipos válidos de fotos de perfil

        if(in_array($fileExt, $allowedExtensions)){ // imagem está em um formato permitido
             // Buscar caminho da imagem antiga ANTES de atualizar
            $oldPicPath = null;
            $stmt = $mysqli->prepare("SELECT $field FROM employee WHERE emailEmpl = ? LIMIT 1");
            $stmt->bind_param("s", $_COOKIE["email"]);
            $stmt->execute();
            $stmt->bind_result($oldPicPath);
            $stmt->fetch();
            $stmt->close();

            // adicionar a nova imagem de perfil
            $newFileName = uniqid("img_") . "." . $fileExt; // nome único para a imagem
            $filePath = "{$uploadDirect}/{$newFileName}"; // caminho completo da imagem

            $stmt = $mysqli->prepare("UPDATE employee SET $field = ? WHERE emailEmpl = ? LIMIT 1");
            $stmt->bind_param("ss", $filePath, $_COOKIE["email"]);
            $stmt->execute();
            $stmt->close();

            move_uploaded_file($fileTemp, $filePath); // mover a imagem do diretório temporário para o diretório de upload
            modifyCookieValue($type, $filePath); // modificar o cookie com o novo caminho da imagem

            // remove a imagem antiga
            if (!empty($oldPicPath) && file_exists($oldPicPath)) { 
                unlink($oldPicPath); 
            }
            header("Location: {$_SERVER["PHP_SELF"]}?status=changeComplete&local=$type" );
            
        }else{
            return "picFormatError"; // imagem não está em um formato permitido
        }
    }

    function changeItem($type, $field){
        global $mysqli;

        $allowedTypes = ["username", "picture", "number", "bdate", "entryDate", "position", "area"];
        $allowedFields = ["nameEmpl", "profilePicPath", "numberEmpl", "bDayEmpl", "entryDate", "emplPos", "areaEmpl"];

        if((in_array($type, $allowedTypes)) && (in_array($field, $allowedFields))){
            $newValue = $_POST[$type];
            $userEmail = $_COOKIE["email"];
            
            if($newValue == $_COOKIE[$type]){
                return "sameValue";
            }

            $currentDate = date("Y-m-d");
            $minBDate = date("Y-m-d", strtotime("-16 years"));

            if($type === "bdate"){
                if($newValue >= $minBDate){ // data de nascimento invalida (precisa ser maior de 16 anos)
                    return "invalidBDay";
                }
            }

            if($type === "entryDate"){
                if($newValue > $currentDate){ //  data de entrada na empresa inválida
                    return "invalidJoinDate";
                }
            }
            
            $stmt = $mysqli->prepare("UPDATE employee SET $field = ? WHERE emailEmpl = ? LIMIT 1");
            $stmt->bind_param("ss", $newValue, $userEmail);
            $stmt->execute();

            modifyCookieValue($type, $newValue);

            header("Location: {$_SERVER["PHP_SELF"]}?status=changeComplete&local=$type");
            exit;
        }
    }

    // output da opção que corresponde ao funcionário selecionado
    function optionSelected($type, $option){
        if(isset($_COOKIE["$type"]) && $_COOKIE["$type"] == "$option"){
            echo "selected";
        }

    }

    // Chamar changeItem antes de qualquer output
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["number"])) {
        changeItem("number", "numberEmpl");
    }
  
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/other-pages.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/dark-mode.css">


    <script src="../scripts/dark-mode.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <style>
        .content{
            background: #f8f3ed;

        }

        .dark-mode .content{
            background: var(--dm-bg);
        }

        .content-bottom-forms{
            background: white;
            padding: 1em;
            border-radius: var(--border-radius);

        }

        .dark-mode .content-bottom-forms{
            background: var(--dm-bg-2);
            
        }

        .forms-item-img{
            display: flex;
            gap: 1em;
            align-items: center;

        }

        .forms-item-img img{
            width: 100px;
            height: 100px;
            border-radius: 100px;

        }

        .forms-item button{
            margin-top: 0.5em;

        }

        @media(min-width: 1024px){
            .content{
                width: 100%;

            }

            .forms-item-img img{
                width: 200px;
                height: 200px;

            }

            .content-bottom-forms{
                display: flex;
                flex-direction: column;
                justify-content: space-around;
                align-items: center;

            }

            .right-content{
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 3em;

            }

            .forms-item input, .forms-item select, .forms-item .button-submit button{
                width: 30vw;

            }
        }

    </style>

    <title>Projeto Zeus - Login</title>

</head>
<body>

    <main>
        <div class="content">
           <!--Logo and Dark/light mode Switch-->
            <div class="content-header">
                <a href="../users-page/user.php">
                    <img src="../general-images/church-symbol.png" alt="church-symbol" id="church-symbol">
                </a>

                <button class="theme-switch-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="dark-mode-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="hidden light-mode-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </button>

            </div>
        <!--Logo and Dark/light Mode Switch-->
            <div class="content-bottom">
                <div class="right-section-back-button back-employee">
                    <a href="../users-page/user.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                        <p>Voltar</p>
                    </a>
                </div>
                <div class="content-bottom-text">
                    <h1>Alterar suas <span class="highlight-word">Credenciais</span></h1>
                    <p>Após alterar um campo <span class="highlight-word">clique no botão de enviar</span> para atualizar</p>
                    <p>É possível modificar apenas <span class="highlight-word">um campo por vez</span> </p>
                </div>
                <div class="content-bottom-forms">
                    <div class="left-content">
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
                            <div class="forms-item">
                                <label for="ipicture">
                                    Foto de Perfil:
                                    <small style="font-weight: lighter;">Tamanho máximo: 2MB</small>
                                </label>

                                <div class="forms-item-img">
                                    <img src="<?php echo $_COOKIE["picture"]?>" alt="profile Picture">
                                    <div>
                                        <input type="file" name="picture" id="ipicture" accept="image/*" max-file-size="2097152">
                                        <div class="button-submit">
                                            <button type="submit">Alterar Foto de Perfil</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $result = null;
                                
                                if(isset($_FILES["picture"])){
                                    $result = changePicture("picture", "profilePicPath");
                                }

                                $status = $_GET["status"] ?? null;
                                $local  = $_GET["local"] ?? null;

                                if($status === "changeComplete" && $local === "picture"){
                                    echo "
                                        <span class=\"sucess-text\">
                                            <p>Foto de Perfil <strong>Alterado com sucesso</strong></p>
                                        </span>
                                        ";
                                }else if($result === "picFormatError"){
                                    echo "
                                        <span class=\"error-text\">
                                            <p>Erro: Foto Inserida <strong>Está em um formato Inválido ou nada foi enviado</strong></p>
                                            <p>Formatos Permitidos: <strong>jpg, png, jpeg, webp</strong></p>
                                        </span>
                                    ";
                                }
                            ?>
                        </form>
                    </div>

                    <div class="right-content">
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="iusername">Nome:</label>
                                <div class="forms-item-input">
                                    <input type="text" name="username" id="iusername" class="input-control" required maxlength="30" placeholder="<?php echo $_COOKIE["username"]?>">
                                    <div class="button-submit">
                                        <button type="submit">Alterar Nome</button>
                                        <?php 
                                            $result = null;

                                            if(isset($_POST["username"])){
                                                $result = changeItem("username", "nameEmpl");
                                            }

                                            echo match($result){
                                                "sameValue" => "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Nome Inserido <strong> é o mesmo 
                                                            <br>  
                                                            cadastrado anteriormente</strong>
                                                        </p>
                                                    </span>
                                                ",
                                                default => ""
                                                
                                            };

                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "username"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Nome de Usuário <strong>Alterado com sucesso</strong></p>
                                                    </span>
                                                    ";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="inumber">Número de Telefone:</label>
                                <div class="forms-item-input">
                                    <input type="text" name="number" id="inumber" class="input-control" required placeholder="<?php echo $_COOKIE["number"]?>" required>
                                    <div class="button-submit">
                                        <button type="submit">Alterar Telefone</button>
                                        <?php 
                                            $result = null;

                                            if(isset($_POST["number"])){
                                                $result = changeItem("number", "numberEmpl");
                                            }

                                            echo match($result){
                                                "sameValue" => 
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Telefone Inserido <strong> é o mesmo 
                                                            <br> 
                                                            cadastrado anteriormente</strong>
                                                        </p>
                                                    </span>
                                                    ",
                                                default => ""
                                                
                                            };

                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "number"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Telefone <strong>Alterado com sucesso</strong></p>
                                                    </span>
                                                ";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="ibdate">Data de Nascimento:</label>
                                <div class="forms-item-input">
                                    <input type="date" name="bdate" id="ibdate" class="input-control" value="<?php echo $_COOKIE["bday"]?>" required>
                                    <div class="button-submit">
                                        <button type="submit">Alterar Data Nascimento</button>
                                        <?php
                                            $result = null;

                                            if(isset($_POST["bdate"])){
                                                $result = changeItem("bdate", "bDayEmpl");
                                            }

                                            echo match($result){
                                                "sameValue" => 
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Data de Nascimento Inserida <strong> é a mesma
                                                            <br>
                                                            cadastrada anteriormente</strong>
                                                        </p>
                                                    </span>
                                                    ",
                                                "invalidBDay" =>
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>Data de Nascimento Inserida <strong>Invalida</strong></p>
                                                        <p>Usuário precisa ser maior de 16 anos</p>
                                                    </span>
                                                    ",
                                                default => ""
                                                
                                            };

                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "bdate"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Data de Nascimento <strong>Alterado com sucesso</strong></p>
                                                    </span>
                                                ";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="ientrydate">Data de Ingresso:</label>
                                <div class="forms-item-input">
                                    <input type="date" name="entryDate" id="ientrydate" class="input-control" value="<?php echo $_COOKIE["entrydate"]?>">
                                    <div class="button-submit">
                                        <button type="submit">Alterar Data Ingresso</button>
                                        <?php
                                            $result = null;
                                            if(isset($_POST["entryDate"])){
                                                $result = changeItem("entryDate", "entryDate");
                                            }

                                            echo match($result){
                                                "sameValue" => 
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Data de Ingresso Inserida <strong> é a mesma  <br>
                                                            cadastrada anteriormente</strong>
                                                        </p>
                                                    </span>
                                                    ",
                                                "invalidBDay" =>
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>Data de Ingresso Inserida <strong>Invalida</strong></p>
                                                        <p>Usuário precisa ser maior de 16 anos</p>
                                                    </span>
                                                    ",
                                                default => ""
                                                
                                            };

                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "entryDate"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Data de Ingresso <strong>Alterada com sucesso</strong></p>
                                                    </span>
                                                ";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="iPos">Cargo: </label>
                                <div class="forms-item-input">
                                    <select name="position" id="iPos" class="input-control">
                                        <option value="RH" <?php optionSelected("position", "RH")?>>
                                            Recursos Humanos
                                        </option>

                                        <option value="Operações" <?php optionSelected("position", "Operações")?>>
                                            Operações
                                        </option>

                                        <option value="Gerente Projetos" <?php optionSelected("position", "Gerente Projetos")?>>
                                            Gerente de Projetos
                                        </option>

                                        <option value="SAC" <?php optionSelected("position", "SAC")?>>
                                            SAC
                                        </option>

                                        <option value="Infraestrutura" <?php optionSelected("position", "Infraestrutura")?>>
                                            Infraestrutura 
                                        </option>

                                        <option value="Segurança" <?php optionSelected("position", "Segurança")?>>
                                            Segurança 
                                        </option>
                                    </select>

                                    <div class="button-submit">
                                        <button type="submit">Alterar Cargo</button>

                                        <?php
                                            $result = null;
                                            if(isset($_POST["position"])){
                                                $result = changeItem("position", "emplPos");
                                            }

                                            echo match($result){
                                                "sameValue" => 
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Cargo Selecionado <strong>é o mesmo 
                                                            <br>
                                                            cadastrado anteriormente</strong>
                                                        </p>
                                                    </span>
                                                    ",
                                                default => ""
                                            };
                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "position"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Cargo <strong>Alterado com sucesso</strong></p>
                                                    </span>
                                                ";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form action ="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data" autocomplete="on">
                            <div class="forms-item">
                                <label for="iarea">Área: </label>
                                <div class="forms-item-input">
                                    <select name="area" id="iarea" class="input-control">

                                        <option value="Gerencia" <?php optionSelected("area", "Gerencia")?>>
                                            Gerencia
                                        </option>

                                        <option value="Projetos" <?php optionSelected("area", "Projetos")?>>
                                            Projetos
                                        </option>

                                        <option value="RH" <?php optionSelected("area", "RH")?>>
                                            RH
                                        </option>

                                        <option value="Comercial" <?php optionSelected("area", "Comercial")?>>
                                            Comercial
                                        </option>

                                    </select>

                                    <div class="button-submit">
                                        <button type="submit">Alterar Área</button>
                                        <?php 
                                            $result = null;
                                            if(isset($_POST["area"])){
                                                $result = changeItem("area", "areaEmpl");
                                            }

                                            echo match($result){
                                                "sameValue" => 
                                                    "
                                                    <span class=\"error-text\">
                                                        <p>
                                                            Erro: Área Selecionada <strong>é a mesma 
                                                            <br>
                                                            cadastrada anteriormente</strong>
                                                        </p>
                                                    </span>
                                                    ",
                                                default => ""
                                            };

                                            $status = $_GET["status"] ?? null;
                                            $local  = $_GET["local"] ?? null;

                                            if($status === "changeComplete" && $local === "area"){
                                                echo "
                                                    <span class=\"sucess-text\">
                                                        <p>Área <strong>Alterado com sucesso</strong></p>
                                                    </span>
                                                ";
                                            }
                                            
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php 
    ob_end_flush(); // completar o redirecionamento da página
?>