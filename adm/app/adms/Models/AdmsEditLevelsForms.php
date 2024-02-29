<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Editar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsEditLevelsForms
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    private array|null $data;
    private array|null $dataExitVal;
    private array $listRegistryAdd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function viewLevelsForms(int $id): void
    {
        $this->id = $id;


        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT lev_form.id, lev.name AS lev_name, sit_user.name AS sit_user
            FROM adms_levels_forms AS lev_form
            INNER JOIN adms_access_levels AS lev ON lev_form.adms_access_level_id = lev.id
            INNER JOIN adms_sits_users AS sit_user ON lev_form.adms_sits_user_id = sit_user.id
            LIMIT :limit",
            "limit=1"
        );

        $this->resultBd = $viewUser->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Configuração não encontrado!</p>";
            $this->result = false;
        }
    }

    public function update(array $data = null): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->edit();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nivel de acesso não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }


    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_levels_forms", $this->data);

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Nivel de acesso editado com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nivel de acesso não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_users ORDER BY name ASC");
        $registry['sit'] = $list->getResult();
        $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
        $registry['lev'] = $list->getResult();

        $this->listRegistryAdd = ['sit' => $registry['sit'], 'lev' => $registry['lev']];

        return $this->listRegistryAdd;
    }
}
