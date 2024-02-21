<?php
if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
?>

<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Visualizar Páginas</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_sits_pages']) {
                    echo "<a href='" . URL . "list-sits-pages/index' class='btn-info'>Listar</a> ";
                }


                if (!empty($this->data['viewSitPages'])) {
                    if ($this->data['button']['edit_sits_pages']) {
                        echo "<a href='" . URL . "edit-sits-pages/index/" . $this->data['viewSitPages'][0]['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['delete_sits_pages']) {
                        echo "<a href='" . URL . "delete-sits-pages/index/" . $this->data['viewSitPages'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
                    }
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
            if (!empty($this->data['viewSitPages'])) {
                extract($this->data['viewSitPages'][0]);
            ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação: </span>
                    <span class="view-adm-info"><?php echo "<span style='color: #$color;' >$name</span>"; ?></span>
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
                        }
                        ?>
                    </span>
                </div>

            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->




<?php

/* echo "<h2>Detalhes da situação</h2>";

echo "<a href='" . URL . "list-sits-users/index'>Listar</a><br>";


if (!empty($this->data['viewSitPages'])) {
    echo "<a href='" . URL . "edit-sits-users/index/" . $this->data['viewSitPages'][0]['id'] . "'>Editar</a><br>";
    echo "<a href='" . URL . "delete-sits-users/index/" . $this->data['viewSitPages'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a><br>";
    
}

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if (!empty($this->data['viewSitPages'])) {

    extract($this->data['viewSitPages'][0]);
    echo "ID: $id <br>";
    echo "Situação do Usuário: <span style='color: $color;' >$name</span> <br>";
    echo "Cadastrado: " . date('d/m/Y H:i:s', strtotime($created)) . " <br>";
    echo "Editado: ";
    if (!empty($modified)) {
        echo date('d/m/Y H:i:s', strtotime($modified));
    }
    echo "<br>";
} */
?>