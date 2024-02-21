<?php
if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
} ?>



<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Grupos de Páginas</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_groups_pages']) {
                    echo "<a href='" . URL . "add-groups-pages/index' class='btn-success'>Cadastrar</a>";
                }
                ?>

            </div>
        </div>
        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_group = "";
                    if (isset($valorForm['search_group_pag'])) {
                        $search_group = $valorForm['search_group_pag'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Nome: </label>
                        <input type="text" name="search_group_pag" id="search_group_pag" class="input-search" placeholder="Pesquisar pelo nome do grupo..." value="<?php echo $search_group; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchGroupsPag" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                    <th class="list-head-content">Ordem</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listGroupsPages'] as $access) {
                    extract($access);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        <td class="list-body-content"><?php echo $order_group_pg; ?></td>
                        <td class="list-body-content">
                            <!--<button type="button" class="btn-primary">Visualizar</button>
                                <button type="button" class="btn-warning">Editar</button>
                                <button type="button" class="btn-danger">Apagar</button>-->
                            <!--<button type="button" class="btn-primary"><i class="fa-solid fa-eye"></i></button>
                                <button type="button" class="btn-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="btn-danger"><i class="fa-solid fa-trash-can"></i></button>-->
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['order_groups_pages']) {
                                        echo "<a href='" . URL . "order-groups-pages/index/$id?pag=" . $this->data['pag'] . "'><i class='fa-solid fa-angles-up'></i> Ordem</a>";
                                    }
                                    if ($this->data['button']['view_groups_pages']) {
                                        echo "<a href='" . URL . "view-groups-pages/index/$id'><i class='fa-solid fa-eye'></i> Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_groups_pages']) {
                                        echo "<a href='" . URL . "edit-groups-pages/index/$id'><i class='fa-solid fa-pen-to-square'></i> Editar</a>";
                                    }
                                    if ($this->data['button']['delete_groups_pages']) {
                                        echo "<a href='" . URL . "delete-groups-pages/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'><i class='fa-solid fa-trash-can'></i> Apagar</a>";
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