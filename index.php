<?php
$conf = parse_ini_file(".conf");
$user = $_POST['user'] ?? null;
$password = $_POST['password'] ?? null;
$newpassword = $_POST['newpassword'] ?? null;
$result = null;
$message = '';



if (isset($user) && strlen($newpassword) < 6) {
    $newpassword = null;
    $message = "A nova senha deve possuir ao menos 6 caracteres";
    $result = 0;
}

if (!empty($user) && !empty($password) && !empty($newpassword)) {
    exec("(echo '$password'; echo '$password') | smbclient -L //localhost -U $user", $out, $result);


    if (count($out) == 1) {
        $message = "Erro na comunicação com o servidor";
    } elseif ($result === 0) {
        //Aceitou a autenticação
        
        exec("(echo -e \"".$newpassword."\n".$newpassword."\" | sudo passwd $user", $out2, $result);
        exec("(echo '$newpassword'; echo '$newpassword') | sudo smbpasswd -a $user", $out2, $result);
        if ($result === 0) {
            $message = "Senha alterada com sucesso!";
        } else {
            $message = "Erro na alteração da senha";
        }
    } else {

        if (strpos($out[1], 'LOGON_FAILURE')) {
            $message = "Credenciais de acesso inválidas!";
        } else {
            $message = "Erro na alteração da senha";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de senha | <?= $conf['serverName'] ?></title>
    <link rel="stylesheet" href="libs/css/bootstrap.min.css">
    <style>
        body,
        html {
            height: 100%;
        }
    </style>
</head>

<body class='bg-dark'>
    <div class="container h-100">
        <div class="row h-100 d-flex justify-content-center align-items-center">
            <div class="col-xs-4 col-md-6">

                <form class='bg-white pb-3 pt-5 px-5 rounded-3' action="?" method="post">
                    <h1 class='fs-2 text-center'><?= $conf['serverName'] ?></h1>
                    <h2 class='fs-4 text-center mb-5'>Alteração de senha</h2>
                    <div class="form-group mb-4">
                        <label for="">Usuário</label>
                        <input required type="text" class="form-control" name="user" id="user" aria-describedby="helpId" placeholder="Seu nome de usuário" value='<?= !empty($user) ? $user : '' ?>'>
                    </div>
                    <div class="form-group mb-4">
                        <label for="">Senha atual</label>
                        <input required type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Informe sua senha atual">
                    </div>
                    <div class="form-group mb-4">
                        <label for="">Nova senha</label>
                        <input required type="password" minlength="6" class="form-control" name="newpassword" id="newpassword" aria-describedby="helpId" placeholder="Crie uma senha forte">
                    </div>
                    <div class="form-group mb-4">
                        <button type="submit" class="btn btn-primary">Alterar senha</button>
                    </div>

                    <?php

                    if ($result === 0) {
                        echo "<div class='alert alert-success' role='alert'>
                $message
              </div>";
                    }
                    if ($result === 1) {
                        echo "<div class='alert alert-danger' role='alert'>
                $message
              </div>";
                    }
                    ?>

                    <p class=' small text-center' style='color:#999'>&copy;<?= date('Y') ?> - <a href="<?= $conf['siteURL'] ?>"><?= $conf['siteName'] ?></a></p>


                </form>
            </div>
        </div>
        <script src="libs/jquery.js"></script>
        <script src="libs/js/bootstrap.bundle.min.js"></script>

</body>

</html>