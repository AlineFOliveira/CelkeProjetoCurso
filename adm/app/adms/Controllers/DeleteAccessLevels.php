<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class DeleteAccessLevels
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
            $deleteUser = new \App\adms\Models\AdmsDeleteAccessLevels();
            $deleteUser->deleteAccessLvls($this->id);
            
        } else { //quando for enviado o formulário
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
    
        }

        $urlRedirect = URL . "list-access-levels/index";
        header("Location: $urlRedirect");
    }

}
