<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Cadastrar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsAddPages
{
    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    private array $listRegistryAdd;

    private array|null $dataExitVal;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Recebe os valores do formulário.
     * Instancia o helper "AdmsValEmptyField" para verificar se todos os campos estão preenchidos 
     * Verifica se todos os campos estão preenchidos e instancia o método "valInput" para validar os dados dos campos
     * Retorna FALSE quando algum campo está vazio
     * 
     * @param array $data Recebe as informações do formulário
     * 
     * @return void
     */
    public function create(array $data = null)
    {
        $this->data = $data;
        var_dump($this->data);

        $this->dataExitVal['icon'] = $this->data['icon'];
        $this->dataExitVal['obs'] = $this->data['obs'];
        unset($this->data['icon'], $this->data['obs']);
        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->data['icon'] = $this->dataExitVal['icon'];
            $this->data['obs'] = $this->dataExitVal['obs'];
            $this->add();
        } else {
            $this->result = false;
        }
    }

    /** 
     * Instanciar o helper "AdmsValEmail" para verificar se o e-mail válido
     * Instanciar o helper "AdmsValEmailSingle" para verificar se o e-mail não está cadastrado no banco de dados, não permitido cadastro com e-mail duplicado
     * Instanciar o helper "validatePassword" para validar a senha
     * Instanciar o helper "validateUserSingleLogin" para verificar se o usuário não está cadastrado no banco de dados, não permitido cadastro com usuário duplicado
     * Instanciar o método "add" quando não houver nenhum erro de preenchimento 
     * Retorna FALSE quando houve algum erro
     * 
     * @return void
     */
    /* private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email']);

        $valPassword = new \App\adms\Models\helper\AdmsValPassword();
        $valPassword->validatePassword($this->data['password']);

        $valUserSingleLogin = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingleLogin->validateUserSingle($this->data['user']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valPassword->getResult()) and ($valUserSingleLogin->getResult())) {
            $this->add();
        } else {
            $this->result = false;
        }
    } */

    /** 
     * Cadastrar usuário no banco de dados
     * Retorna TRUE quando cadastrar o usuário com sucesso
     * Retorna FALSE quando não cadastrar o usuário
     * 
     * @return void
     */
    private function add(): void
    {
        $this->data['created'] = date("Y-m-d H:i:s");

        $createUser = new \App\adms\Models\helper\AdmsCreate();
        var_dump( $this->data);
        $createUser->exeCreate("adms_pages", $this->data);

        if ($createUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Página cadastrada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não cadastrada com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_pgs ORDER BY name ASC");
        $registry['sit_page'] = $list->getResult();

        $list->fullRead("SELECT id id_group, name name_group FROM adms_groups_pgs ORDER BY name ASC");
        $registry['group_page'] = $list->getResult();

        $list->fullRead("SELECT id id_type, type, name name_type FROM adms_types_pgs ORDER BY name ASC");
        $registry['type_page'] = $list->getResult();

        $this->listRegistryAdd = ['sit_page' => $registry['sit_page'], 'group_page' => $registry['group_page'], 'type_page' => $registry['type_page']];

        return $this->listRegistryAdd;
    }
}
