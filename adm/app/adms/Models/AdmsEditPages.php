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
class AdmsEditPages
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

    public function viewPages(int $id): void
    {
        $this->id = $id;


        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT pg.id, pg.controller, pg.metodo, pg.menu_controller, pg.menu_metodo, pg.name_page, pg.publish, pg.icon, pg.obs, pg.created, pg.modified,
            tpg.type type_tpg, tpg.name name_tpg, grpg.name name_grpg,
            sit.name name_sit,
            col.color, pg.adms_sits_pgs_id, adms_types_pgs_id, adms_groups_pgs_id 
            FROM adms_pages AS pg
            LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
            LEFT JOIN adms_groups_pgs AS grpg ON grpg.id=pg.adms_groups_pgs_id
            LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
            LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id
            WHERE pg.id=:id
            LIMIT :limit", "id={$this->id}&limit=1"
        );

        //pçvar_dump($viewUser->getResult());
        $this->resultBd = $viewUser->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado a!</p>";
            $this->result = false;
        }
    }

    public function update(array $data = null): void
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
            $this->edit();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }


    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_pages", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Página editada com sucesso!</p><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_pgs ORDER BY name ASC");
        $registry['sit'] = $list->getResult();

        $list->fullRead("SELECT id id_group, name name_group FROM adms_groups_pgs ORDER BY name ASC");
        $registry['group_page'] = $list->getResult();

        $list->fullRead("SELECT id id_type, type, name name_type FROM adms_types_pgs ORDER BY name ASC");
        $registry['type_page'] = $list->getResult();

        $this->listRegistryAdd = ['sit' => $registry['sit'], 'group_page' => $registry['group_page'], 'type_page' => $registry['type_page']];


        return $this->listRegistryAdd;
    }
}
