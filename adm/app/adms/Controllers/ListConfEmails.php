<?php

namespace App\adms\Controllers;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

class ListConfEmails
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;
    private array|null $dataForm;
    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;
    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmail;

    public function index(string|int|null $page = null): void //recebe o numero que tá vindo da url
    {
        $this->page = (int) $page ? $page : 1;
        // se não há numero, automaticamente sera considerado 1
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);
        $this->searchEmail = filter_input(INPUT_GET, 'search_email', FILTER_DEFAULT);

        $listConfEmails = new \App\adms\Models\AdmsListConfEmails();
        if (!empty($this->dataForm['SendSearchUser'])) {
            $this->page = 1;
            $listConfEmails->listSearchConfEmails($this->page, $this->dataForm['search_name'], $this->dataForm['search_email']);

            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName)) or (!empty($this->searchEmail))) {
            $listConfEmails->listSearchConfEmails($this->page, $this->searchName, $this->searchEmail);

            $this->data['form']['search_name'] = $this->searchName;
            $this->data['form']['search_email'] = $this->searchEmail;
        } else {

            $listConfEmails->listConfEmails($this->page);
        }
        if ($listConfEmails->getResult()) {
            $this->data['listConfEmails'] = $listConfEmails->getResultBd();
            $this->data['pagination'] = $listConfEmails->getResultPg();
        } else {
            $this->data['listConfEmails'] = [];
            $this->data['pagination'] = "";
        }

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $button = [
            'add_conf_emails' => ['menu_controller' => 'add-conf-emails', 'menu_metodo' => 'index'],
            'view_conf_emails' => ['menu_controller' => 'view-conf-emails', 'menu_metodo' => 'index'],
            'edit_conf_emails' => ['menu_controller' => 'edit-conf-emails', 'menu_metodo' => 'index'],
            'delete_conf_emails' => ['menu_controller' => 'delete-conf-emails', 'menu_metodo' => 'index']
        ];
        $listButton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listButton->buttonPermission($button);

        /**
         * Chama a funcão que está no configView e passa como parametro o caminho para a View certa
         */
        $this->data["sidebarActive"] = "list-conf-emails";
        $loadView = new \Core\ConfigView("adms/Views/confEmails/listConfEmails", $this->data);
        $loadView->loadView();
    }
}
