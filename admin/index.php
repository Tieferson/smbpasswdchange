<?php
session_start();
$_SESSION['admin'] = $_SESSION['admin'] ?? false;

$conf = parse_ini_file("../.conf");

$admin = $_POST['admin'] ?? null;
$user = $_POST['user'] ?? null;
$password = $_POST['password'] ?? null;
$newpassword = $_POST['newpassword'] ?? null;
$result = null;
$message = '';

if (isset($_GET['logout'])) {
    $_SESSION['admin'] = false;
} elseif ($_SESSION['admin'] && isset($_GET['restartsamba'])) {
    exec("sudo /etc/init.d/smbd restart", $out, $result);
    $message = $result === 1 ? "Erro ao reiciar o serviço" : "Serviço reiniciado com sucesso";
} elseif ($_SESSION['admin'] && isset($_GET['reboot'])) {
    header("Location: ?home");
    exec("sudo /usr/sbin/reboot -f", $out, $result);
    $message = $result === 1 ? "Erro ao reiciar o servidor" : "Aguarde enquanto o servidor é reiniciado";
} elseif ($_SESSION['admin'] && isset($_GET['shutdown'])) {
    header("Location: ?home");
    exec("sudo /usr/sbin/halt -p -f", $out, $result);
    $message = $result === 1 ? "Erro ao desligar o servidor" : "Aguarde enquanto o servidor é desligado";
}



if (!empty($admin) && !empty($password)) {
    exec("(echo '$password'; echo '$password') | smbclient -L //localhost -U $admin", $out, $result);
    $_SESSION['admin'] = false;
    if (count($out) == 1) {
        $message = "Erro na comunicação com o servidor";
    } elseif ($result === 1) {
        $message = "Credenciais de acesso inválidas!";
    } else {
        exec("groups $admin", $out3);
        if (strpos($out3[0], "sudo")) {
            $_SESSION['admin'] = true;
            $result = null;
        } else {
            $message = "O usuário admin deve pertencer ao grupo sudo!";
            $result = 1;
        }
    }
}

if ($_SESSION['admin'] && !empty($user)) {
    $newpassword = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789"), 0, 8);
   
    if($conf['syncUnixPwd']=='yes'){
        exec("(echo -e \"".$newpassword."\n".$newpassword."\" | sudo passwd $user", $out2, $result);
    }
    exec("(echo '$newpassword'; echo '$newpassword') | sudo smbpasswd -a $user", $out2, $result);
    if ($result === 0) {
        $message = "A nova senha do usuário <b>$user</b> é <b>$newpassword</b>";
    } else {
        $message = "Erro na alteração da senha";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de senha de usuários | <?= $conf['serverName'] ?></title>
    <link rel="stylesheet" href="../libs/css/bootstrap.min.css">
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

                <form class='bg-white py-5 px-5 rounded-3' action="?" method="post">
                    <h1 class='fs-2 text-center'><?= $conf['serverName'] ?></h1>
                    <?php
                    if (!$_SESSION['admin']) {
                    ?>
                        <div class="form-group mb-4">
                            <label for="">Admin</label>
                            <input required type="text" class="form-control" name="admin" id="admin" aria-describedby="helpId" placeholder="Seu nome de usuário" value='<?= !empty($admin) ? $admin : '' ?>'>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Senha</label>
                            <input required type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Informe sua senha atual">
                        </div>

                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <h2 class='fs-4 text-center mb-5'>Admin do Servidor</h2>
                        <div class="form-group mb-4 text-center">

                            <a href="?restartsamba" class="btn btn-success">Reiniciar Samba</a>
                            <a href="javascript: void(0)" onclick="if(confirm('Deseja mesmo reiniciar o servidor?')){window.location.href='?reboot'}" class="btn btn-primary">Reiniciar Servidor</a>
                            <a href="javascript: void(0)" onclick="if(confirm('Deseja mesmo desligar o servidor?')){window.location.href='?shutdown'}" class="btn btn-danger">Desligar Servidor</a>
                        </div>
                        <hr />
                        <h2 class='fs-4 text-center mb-5'>Admin de usuários</h2>
                        <div class="form-group mb-4">
                            <label for="">Usuário</label>
                            <select required class="form-select" name="user" id="user" aria-describedby="helpId"'>
                            <?php
                            exec("sudo pdbedit -L | cut -d':' -f1 | sort", $users);
                            foreach ($users as $user) {
                                echo "<option>$user</option>";
                            }
                            ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-primary">Alterar senha</button>
                            <a href="?logout" class="btn btn-danger">Sair</a>
                        </div>
                       
                    <?php
                    }
                    ?>  
                    
                    <div class='message'>

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
                        </div>
                        <p class=' small text-center' style='color:#999'>&copy;<?= date('Y') ?> - <a href="<?= $conf['siteURL'] ?>"><?= $conf['siteName'] ?></a></p>


                </form>
            </div>
        </div>
        <script src="../libs/jquery.js"></script>
        <script src="../libs/js/bootstrap.bundle.min.js"></script>

</body>

</html>