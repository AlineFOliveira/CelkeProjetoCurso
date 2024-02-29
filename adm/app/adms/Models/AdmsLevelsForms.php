<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Visualizar o usuário no banco de dados
 *
 * @author Celke
 */
class AdmsLevelsForms
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;



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

    public function viewLevelsForm(): void
    {

        $viewLevelPage = new \App\adms\Models\helper\AdmsRead();
        $viewLevelPage->fullRead(
            "SELECT lev_form.id, lev_form.created, lev_form.modified, lev.name AS lev_name, sit_user.name AS sit_user, col.color
    FROM adms_levels_forms AS lev_form
    INNER JOIN adms_access_levels AS lev ON lev_form.adms_access_level_id = lev.id
    INNER JOIN adms_sits_users AS sit_user ON lev_form.adms_sits_user_id = sit_user.id
    INNER JOIN adms_colors AS col ON sit_user.adms_color_id = col.id
    LIMIT :limit",
            "limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewLevelPage->getResult();


        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Página não encontrada!</p>";
            $this->result = false;
        }
    }
}
