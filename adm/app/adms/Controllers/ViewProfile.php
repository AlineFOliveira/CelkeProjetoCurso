<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página visualizar perfil
 * @author Cesar <cesar@celke.com.br>
 */
class ViewProfile
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;



    /**
     * Instantiar a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {

        $viewProfile = new \App\adms\Models\AdmsViewProfile;
        $viewProfile->viewProfile();
        if ($viewProfile->getResult()) {
            $this->data['viewProfile'] = $viewProfile->getResultBd();
            $this->loadViewProfile();
        } else {
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
        }
    }

    private function loadViewProfile(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $loadView = new \Core\ConfigView("adms/Views/users/viewProfile", $this->data);
        $loadView->loadView();
    }
}
