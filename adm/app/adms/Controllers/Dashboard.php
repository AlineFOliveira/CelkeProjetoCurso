<?php

namespace App\adms\Controllers;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class Dashboard
{
    private array|string|null $data;
    public function index()//metodo?
    {
        $countUsers = new \App\adms\Models\AdmsDashboard();
        $countUsers->countUsers();
        if($countUsers->getResult()){
            $this->data['countUsers'] = $countUsers->getResultBd();
    
        }else{
            $this->data['countUsers'] = false;
        }
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data["sidebarActive"] = "dashboard";
        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $loadView = new \Core\ConfigView("adms/Views/dashboard/dashboard", $this->data);
        $loadView->loadView();

    }
}


?>