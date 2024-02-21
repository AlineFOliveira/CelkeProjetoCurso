<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeleteColors
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
            $deleteUser = new \App\adms\Models\AdmsDeleteColors();
            $deleteUser->deleteCol($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cor não encontrada!</p>";
    
        }

        $urlRedirect = URL . "list-colors/index";
        header("Location: $urlRedirect");
    }

}
