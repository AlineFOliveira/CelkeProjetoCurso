<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeleteTypesPages
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
            $deleteTypePg = new \App\adms\Models\AdmsDeleteTypesPages();
            $deleteTypePg->deleteTypesPages($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de página não encontrado!</p>";
    
        }

        $urlRedirect = URL . "list-types-pages/index";
        header("Location: $urlRedirect");
    }

}
