<?php
if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Configurações do Formulário Página de Login</span>
            <div class="top-list-right">
                <?php
                /* if ($this->data['button']['levels_']) {
                    echo "<a href='" . URL . "list-colors/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewLevelsForm'])) {
                    if ($this->data['button']['list_colors']) {
                        echo "<a href='" . URL . "edit-colors/index/" . $this->data['viewColors'][0]['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['list_colors']) {
                        echo "<a href='" . URL . "delete-colors/index/" . $this->data['viewColors'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
                    }
                } */
                if (!empty($this->data['viewLevelsForm'])) {
                    echo "<a href='" . URL . "edit-levels-forms/index/" . $this->data['viewLevelsForm'][0]['id'] . "' class='btn-warning'>Editar</a> ";
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
            <?php
            if (!empty($this->data['viewLevelsForm'])) {
                extract($this->data['viewLevelsForm'][0]);
            ?>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nível de Acesso: </span>
                    <span class="view-adm-info"><?php echo $lev_name; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação: </span>
                    <span class="view-adm-info"><?php echo "<span style='color: #$color;'>$sit_user</span>"; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created)); ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Editado: </span>
                    <span class="view-adm-info">
                        <?php
                        if (!empty($modified)) {
                            echo date('d/m/Y H:i:s', strtotime($modified));
                        } ?>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->