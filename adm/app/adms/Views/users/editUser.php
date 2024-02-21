<?php
if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

if (isset($this->data['form'][0])) {
    $valorForm = $this->data['form'][0];
}

?>

<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Usuário</span>
            <div class="top-list-right">
                <?php
                echo "<a href='" . URL . "list-users/index/' class='btn-info'>Listar</a>";
                if (isset($valorForm['id'])) {
                    echo "<a href='" . URL . "view-users/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a>";
                }
                ?>
            </div>
        </div>

        <div class="content-adm-alert ">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <span id="msg"></span>
        </div>

        <div class="content-adm">
            <form class="form-adm" method="POST" action="" id="form-edit-user">
                <div class="row-input">
                    <?php
                    $id = "";
                    if (isset($valorForm['id'])) {
                        $id = $valorForm['id'];
                    }
                    ?>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Nome:<span style="color:#f00;">*</span> </label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome completo" value="<?php echo $name; ?>" required>
                    </div>
                    <div class="column">
                        <?php
                        $nickname = "";
                        if (isset($valorForm['nickname'])) {
                            $nickname = $valorForm['nickname'];
                        }
                        ?>

                        <label class="title-input">Apelido: </label>
                        <input type="text" name="nickname" id="nickname" class="input-adm" placeholder="Digite o apelido" value="<?php echo $nickname; ?>">
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php
                        $email = "";
                        if (isset($valorForm['email'])) {
                            $email = $valorForm['email'];
                        }
                        ?>
                        <label class="title-input">E-mail:<span class="text-danger">*</span> </label>
                        <input type="email" name="email" id="email" class="input-adm" placeholder="Digite o seu melhor e-mail" value="<?php echo $email; ?>" required>

                    </div>
                    <div class="column">
                        <?php
                        $user = "";
                        if (isset($valorForm['user'])) {
                            $user = $valorForm['user'];
                        }
                        ?>
                        <label class="title-input">Usuário:<span class="text-danger">*</span> </label>
                        <input type="text" name="user" id="user" class="input-adm" placeholder="Digite o usuário para acessar o administrativo" value="<?php echo $user; ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Situação<span class="text-danger">*</span></label>
                        <select name="adms_sits_user_id" id="adms_sits_user_id" class="input-adm">
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['sit'] as $sit) {
                                extract($sit);
                                if ((isset($valorForm['adms_sits_user_id'])) and ($valorForm['adms_sits_user_id'] == $id_sit)) {
                                    echo "<option value='$id_sit' selected>$name_sit</option>";
                                } else {
                                    echo "<option value='$id_sit'>$name_sit</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Nível de Acesso<span class="text-danger">*</span></label>
                        <select name="adms_access_level_id" id="adms_access_level_id" class="input-adm">
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['lev'] as $lev) {
                                extract($lev);
                                if ((isset($valorForm['adms_access_level_id'])) and ($valorForm['adms_access_level_id'] == $id_sit)) {
                                    echo "<option value='$id_lev' selected>$name_lev</option>";
                                } else {
                                    echo "<option value='$id_lev'>$name_lev</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <span style="color:#f00;">* Campo Obrigatório</span><br><br>

                <button type="submit" class="btn-success" name="SendEditUser" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>