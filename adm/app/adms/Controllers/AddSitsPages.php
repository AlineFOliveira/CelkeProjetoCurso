<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Controller da página cadastrar nova situação
 * @author Cesar <cesar@celke.com.br>
 */
class AddSitsPages
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Instantiar a classe responsável em carregar a View e enviar os dados para View.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página novo usuário. Acessa o IF e instância a classe "AdmsAddUsers" responsável em cadastrar o usuário no banco de dados.
     * Usuário cadastrado com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);        

        if(!empty($this->dataForm['SendAddSitsPage'])){
            //var_dump($this->dataForm);
            unset($this->dataForm['SendAddSitsPage']);
            $createPage = new \App\adms\Models\AdmsAddSitsPages();
            $createPage->create($this->dataForm);
            if($createPage->getResult()){
                $urlRedirect = URL. "list-sits-pages/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddSitUser();
            }    
        }else{
            $this->viewAddSitUser();
        }  
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddSitUser(): void
    {
        $listSelect = new \App\adms\Models\AdmsAddSitsPages();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        $this->data["sidebarActive"] = "list-sits-pages";
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/addSitPages", $this->data);
        $loadView->loadView();
    }
}
