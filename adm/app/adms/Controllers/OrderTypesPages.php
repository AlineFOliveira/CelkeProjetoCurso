<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar situação usuario
 */
class OrderTypesPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    private int|null $pag;

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
        if ((!empty($id)) and(!empty($this->pag))) {
            $this->id = (int) $id;

            $viewTypesPages = new \App\adms\Models\AdmsOrderTypesPages();
            $viewTypesPages->orderTypesPages($this->id);
            if ($viewTypesPages->getResult()) {

                $urlRedirect = URL . "list-types-pages/index/{$this->pag}";
                header("Location: $urlRedirect");
            } else {
                $urlRedirect = URL . "list-types-pages/index/{$this->pag}";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado aaa!</p>";
            $urlRedirect = URL . "list-types-pages/index";
            header("Location: $urlRedirect");
        }
    }

    
}
