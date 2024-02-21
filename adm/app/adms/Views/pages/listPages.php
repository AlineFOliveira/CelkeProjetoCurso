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
            <span class="title-content">Listar Páginas</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_pages']) {
                    echo "<a href='" . URL . "add-pages/index' class='btn-success'>Cadastrar</a> ";
                }
                if ($this->data['button']['sync_pages_levels']) {
                    echo "<a href='" . URL . "sync-pages-levels/index' class='btn-warning'>Sincronizar</a>";
                }
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_name = "";
                    if (isset($valorForm['search_name'])) {
                        $search_name = $valorForm['search_name'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Nome: </label>
                        <input type="text" name="search_name" id="search_name" class="input-search" placeholder="Pesquisar pelo nome..." value="<?php echo $search_name; ?>">
                    </div>


                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchPag" class="btn-info" value="Pesquisar">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content table-sm-none">Tipo de Página</th>
                    <th class="list-head-content table-sm-none">Situação</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listPages'] as $pages) {
                    extract($pages);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name_page; ?></td>
                        <td class="list-body-content table-sm-none">
                            <?php echo $type_tpg . " - " . $name_tpg; ?>
                        </td>
                        <td class="list-body-content table-sm-none">
                            <?php echo "<span style='color: #$color'>$name_sit</span>"; ?>
                        </td>
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_pages']) {
                                        echo "<a href='" . URL . "view-pages/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_pages']) {
                                        echo "<a href='" . URL . "edit-pages/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_pages']) {
                                        echo "<a href='" . URL . "delete-pages/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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
<!-- Fim do conteudo do administrativo -->