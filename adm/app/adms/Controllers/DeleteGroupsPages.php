<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeleteGroupsPages
{
    /**
     * Controller da página deletar nivel de acesso
     *
     */

    private int|string|null $id;



    public function index(int|string|null $id = null)
    {
        if (!empty($id)) {
            $this->id = (int) $id;
            $deleteGroupPg = new \App\adms\Models\AdmsDeleteGroupsPages();
            $deleteGroupPg->deleteGroupsPages($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
    
        }

        $urlRedirect = URL . "list-groups-pages/index";
        header("Location: $urlRedirect");
    }

}
