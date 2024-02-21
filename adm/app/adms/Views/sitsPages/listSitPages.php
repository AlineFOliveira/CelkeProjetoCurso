<?php
if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
    if (isset($this->data['form'])) {
        $valorForm = $this->data['form'];
    }
} ?>



<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Situações de Páginas</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_sits_pages']) {
                    echo "<a href='" . URL . "add-sits-pages/index' class='btn-success'>Cadastrar</a>";
                }
                ?>

            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_sit = "";
                    if (isset($valorForm['search_sit_pag'])) {
                        $search_sit = $valorForm['search_sit_pag'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Nome: </label>
                        <input type="text" name="search_sit_pag" id="search_sit_pag" class="input-search" placeholder="Pesquisar pelo nome da situação..." value="<?php echo $search_sit; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchSitsPag" class="btn-info" value="Pesquisar">Pesquisar</button>
                    </div>
                </div>
            </form>
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
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listSitsPages'] as $sitpg) {
                    extract($sitpg);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo "<span style='color:#$color;'>$name</span"; ?></td>
                        <td class="list-body-content">

                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_sits_pages']) {
                                        echo "<a href='" . URL . "view-sits-pages/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_sits_pages']) {
                                        echo "<a href='" . URL . "edit-sits-pages/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_sits_pages']) {
                                        echo "<a href='" . URL . "delete-sits-pages/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
                                    }

                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <?php echo $this->data['pagination']; ?>

    </div>
</div>