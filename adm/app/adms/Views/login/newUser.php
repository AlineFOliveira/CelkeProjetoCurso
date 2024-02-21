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
            <span>Novo Usuário</span>
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

        <form method="POST" action="" id="form-new-user" class="form-login">
            <?php
            $name = "";
            if (isset($valorForm['name'])) {
                $name = $valorForm['name'];
            }
            ?>

            <div class="row">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Digite o nome completo" value="<?php echo $name; ?>" required>
            </div>


            <?php
            $email = "";
            if (isset($valorForm['email'])) {
                $email = $valorForm['email'];
            }
            ?>

            <div class="row">
                <i class="fa-solid fa-user"></i>
                <input type="email" name="email" id="email" placeholder="Digite o seu melhor e-mail" value="<?php echo $email; ?>" required>
            </div>


            <?php
            $password = "";
            if (isset($valorForm['password'])) {
                $password = $valorForm['password'];
            }
            ?>

            <div class="row">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Digite a senha" onkeyup="passwordStrength()" autocomplete="on" value="<?php echo $password; ?>" required>
            </div>

            <span id="msgViewStrength"></span>

            <div class="row button">
                <button type="submit" name="SendNewUser" value="Cadastrar">Cadastrar</button>
            </div>

            <div class="signup-link">
                <p><a href="<?php echo URL; ?>">Clique aqui</a> para acessar</p>
            </div>

        </form>


    </div>
</div>