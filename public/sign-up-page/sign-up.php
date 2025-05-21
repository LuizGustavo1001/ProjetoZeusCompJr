
<?php 
    include '../../dbConnection.php';

    if(isset($_POST["eName"])){ // verificar se algo está escrito no input de nome
        $email = $mysqli->real_escape_string($_POST["eEmail"]);

        $stmt = $mysqli->prepare('SELECT * FROM employee WHERE emailEmpl = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result and $result->num_rows > 0){ // existe um usuário com o email digitado anteriormente
            $emailExistente = true;
        }else{ // cadastrar novo usuário
            $username     = $mysqli->real_escape_string($_POST["eName"]);
            $bday         = $mysqli->real_escape_string($_POST["eBDay"]);
            $gender       = $mysqli->real_escape_string($_POST["eGender"]);
            $number       = $mysqli->real_escape_string($_POST["eNum"]);
            $position     = $mysqli->real_escape_string($_POST["position"]);
            $entryDate    = $mysqli->real_escape_string($_POST["joinDate"]);
            $area         = $mysqli->real_escape_string($_POST["eArea"]);
            // criar um hash para a senha ser armazenada no BD
            $passwordHash =  password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $imagePath    =  null; // valor padrão caso não envie imagem

            if(isset($_FILES["picture"])){
                $uploadDirect = "../users-page/user-images";

                if(! is_dir($uploadDirect)){ // verifica se o diretório existe
                    mkdir($uploadDirect, 0755, true); // permissão de modificar e adicionar
                }

                $fileTemp = $_FILES["picture"] ["tmp_name"]; // salva o caminho até a imagem no servidor temporariamente
                $fileName = basename($_FILES["picture"]["name"]); // pegar o nome do arquivo enviado pelo usuário
                $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // tipo do arquivo(png, por exemplo)
                $allowedTypes = ["jpg", "png", "jpeg", "webp"]; // tipos válidos de fotos de perfil

                if(in_array($fileExt, $allowedTypes)){ // imagem está em um formato permitido
                    if($_FILES["picture"]["size"] <= 2* 1024 * 1024){ // imagem menor que 2MB -> adiciona a pasta local
                        $uniqueName = uniqid("img_") . "." . $fileExt;
                        $imagePath = $uploadDirect . '/' . $uniqueName;
                        move_uploaded_file($fileTemp, $imagePath);
                    }
                }
            }

            $currentDate = date("Y-m-d");
            $minBDate = date("Y-m-d", strtotime("-16 years"));

            if($bday >= $minBDate){ // data de nascimento invalida (precisa ser maior de 16 anos)
                $invalidBDay = true;
            }else if($entryDate > $currentDate){ //  data de entrada na empresa invalida
                $invalidJoinD = true;
            }else{ // nada de anormal com as datas
                 $stmt2 = $mysqli->prepare('
                    INSERT INTO employee 
                    (nameEmpl, emailEmpl, bDayEmpl, genderEmpl, numberEmpl, emplPos, entryDate, areaEmpl, emplPassword, profilePicPath) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );

                $stmt2->bind_param('ssssssssss', $username, $email, $bday, $gender, $number, $position, $entryDate, $area, $passwordHash, $imagePath);

                if($stmt2->execute()){
                    session_start();
                    $_SESSION['registrado'] = true;
                    header("location:../login-page/login.php");
                    exit();
                }
                $stmt2->close();  // Fecha a declaração
                }
        }      
        $stmt->close();  // Fecha a declaração
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../styles/other-pages.css">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/dark-mode.css">

    <script src="../scripts/dark-mode.js" defer></script>

    <link rel="shortcut icon" href="../icon/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <style>
        
        .content{
            padding-bottom: 0;
            padding-top: 3vw;

        }

        @media(min-width: 1024px){
            .right-content-img{
                display: block;
                background: url(images/signUpBg.png) no-repeat center center;
                background-size: cover;
                height: 127vh;

            }

            form {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.8em;
                align-items: end;
            }
        }
    </style>

    <title>Projeto Zeus - Registrar</title>

</head>

<body>
    <main>
    <!--Logo and Dark/light mode Switch-->
        <div class="content">
            <div class="content-header">
                <a href="../login-page/login.php">
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
    <!--Logo and Dark/light mode Switch-->

    <!--Forms-->
            <div class="content-bottom">
                <div class="content-bottom-text">
                    <p>É sua primeira vez aqui?</p>
                    <h1><span class="highlight-word">Registre-se</span> para continuar</h1>
                </div>
                
                <div class="content-bottom-forms">
                    <form action="" method="post" enctype="multipart/form-data" autocomplete="on">
                        <div class="forms-item">
                            <label for="iEName">Nome:</label>
                            <input type="text" name="eName" id="iEName" class="input-control" required placeholder="Primeiro e Último Nome" maxlength="30">
                        </div>

                        <div class="forms-item">
                            <label for="iEBday">Data de Nascimento:</label>
                            <input type="date" name="eBDay" id="iEBday" class="input-control" required>
                        </div>

                        <div class="forms-item">
                            <label for="ieEmail">Email:</label>
                            <input type="email" name="eEmail" id="ieEmail" class="input-control" required placeholder="exemplo@gmail.com">
                        </div>

                        <div class="forms-item">
                            <label for="inum">Número de Telefone:</label>
                            <input type="text" name="eNum" id="iENum" class="input-control" required placeholder="(XX) X XXXX-XXXX">
                        </div>

                        <div class="forms-item">
                            <label for="iEGender">Gênero:</label>
                            <select name="eGender" id="iEGender" class="input-control">
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="O">Outros</option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="iJoinDate">Data de Ingresso:</label>
                            <input type="date" name="joinDate" id="iJoinDate" class="input-control">
                        </div>

                        <div class="forms-item">
                            <label for="iPos">Cargo:</label>
                            <select name="position" id="iPos" class="input-control">
                                <option value="RH">Recursos Humanos</option>
                                <option value="Operações">Operações</option>
                                <option value="Gerente Projetos">Gerente de Projetos</option>
                                <option value="SAC">SAC</option>
                                <option value="Infraestrutura">Infraestrutura </option>
                                <option value="Segurança">Segurança </option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="iarea">Área:</label>
                            <select name="eArea" id="iarea" class="input-control">
                                <option value="Gerencia">Gerencia</option>
                                <option value="Projetos">Projetos </option>
                                <option value="RH">RH</option>
                                <option value="Comercial">Comercial</option>
                            </select>
                        </div>

                        <div class="forms-item">
                            <label for="isenha">
                                Senha:
                            </label>
                            <input type="password" name="senha" id="isenha" class="input-control"  placeholder="• • • • • • •" maxlength="30" required >
                        </div>

                        <div class="forms-item">
                            <label for="ipicture">Foto de Perfil: <small style="font-weight: lighter;">Tamanho máximo: 2MB</small></label>
                            <input type="file" name="picture" id="ipicture" accept="image/*" max-file-size="2097152">
                        </div>

                        <div class="button-submit">
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                                </svg>
                                Enviar
                            </button>
                        </div>
                        <?php 
                            if(isset($emailExistente)){
                                echo "
                                    <span class=\"error-text\">
                                        <p>Erro: Email Inserido <strong>já está cadastrado</strong></p>
                                    </span>
                                ";
                                $emailExistente = false; // resetar a variável para não mostrar a mensagem de erro novamente
                            }else if(isset($invalidBDay)){
                                echo "
                                    <span class=\"error-text\">
                                        <p>Erro: Data de Nascimento Inserida <strong>Invalida</strong></p>
                                        <p>Usuário precisa ser maior de 16 anos</>
                                    </span>
                                ";
                                $nascimentoInvalido = false; // resetar a variável para não mostrar a mensagem de erro novamente
                            }else if(isset($invalidJoinD)){
                                echo "
                                    <span class=\"error-text\">
                                        <p>Erro: Data de Entrada na Empresa Inserida <strong>Invalida</strong></p>
                                        <p>Usuário precisa ter entrado em um dia anterior a hoje</>
                                    </span>
                                ";
                                $entradaInvalida = false; // resetar a variável para não mostrar a mensagem de erro novamente
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    <!--Forms-->
        <div class="right-content-img"></div>
    </main>
</body>
</html>