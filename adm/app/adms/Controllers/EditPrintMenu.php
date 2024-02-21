<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class EditPrintMenu
{

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    /** @var int|string|null $level Recebe o nível de acesso */
    private int|string|null $level;
    /** @var int|string|null $pag Recebe o número da página */
    private int|string|null $pag;



    public function index(int|string|null $id = null):void
    {
        $this->id = $id;
        $this->level = filter_input(INPUT_GET, "level", FILTER_SANITIZE_NUMBER_INT);
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);

        if ((!empty($this->id)) and (!empty($this->level)) and (!empty($this->pag))) {
            $editPrintMenu = new \App\adms\Models\AdmsEditPrintMenu();
            $editPrintMenu->editPrintMenu($this->id);

            $urlRedirect = URL . "list-permission/index/{$this->pag}?level={$this->level}";
            header("Location: $urlRedirect");
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: necessário selecionar o item de menu para liberar a permissão!</p>";
            $urlRedirect = URL . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }
}
