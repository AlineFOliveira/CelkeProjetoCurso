<?php

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>

<div class="container-login">
    <div class="wrapper-login">

        <div class="title">
            <span>Recuperar Senha</span>
        </div>
        <div class="msg-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                //echo $_SESSION['msg'];
                echo "<span id='msg'>".$_SESSION['msg']."</span>";
                unset($_SESSION['msg']);
            }else{
                echo "<span id='msg'></span>";
            }

            ?>
        </div>

        <form method="POST" action="" id="form-recover-pass" class="form-login">

            <?php
            $email = "";
            if (isset($valorForm['email'])) {
                $email = $valorForm['email'];
            }
            ?>
            <div class="row">
                <i class="fa-solid fa-user"></i>
                <input type="email" name="email" id="email" placeholder="Digite o seu e-mail" value="<?php echo $email; ?>" required>
            </div>
            <div class="row button">
                <button type="submit" name="SendRecoverPass" value="Recuperar">Recuperar</button>
            </div>
            <div class="signup-link">
                <p><a href="<?php echo URL; ?>">Clique aqui</a> para acessar</p>
            </div>

        </form>


    </div>
</div>