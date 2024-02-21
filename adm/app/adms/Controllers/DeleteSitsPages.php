<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeleteSitsPages
{
    /**
     * Controller da página deletar usuário
     *
     */

    private int|string|null $id;



    public function index(int|string|null $id = null)
    {
        if (!empty($id)) {
            $this->id = (int) $id;
            $deleteSitPg = new \App\adms\Models\AdmsDeleteSitsPages();
            $deleteSitPg->deleteSit($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação de página não encontrada!</p>";
    
        }

        $urlRedirect = URL . "list-sits-pages/index";
        header("Location: $urlRedirect");
    }

}
