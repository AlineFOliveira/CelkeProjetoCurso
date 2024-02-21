<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeletePages
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
            $deletePages = new \App\adms\Models\AdmsDeletePages();
            $deletePages->deletePages($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
    
        }

        $urlRedirect = URL . "list-pages/index";
        header("Location: $urlRedirect");
    }

}
