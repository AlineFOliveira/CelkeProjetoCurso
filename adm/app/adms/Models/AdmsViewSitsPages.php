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
class AdmsViewSitsPages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

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

    public function viewSitPages(int $id): void
    {
        $this->id = (int)$id;

        $viewSitUser = new \App\adms\Models\helper\AdmsRead();
        $viewSitUser->fullRead(
            "SELECT sitpg.id, sitpg.name, sitpg.created, sitpg.modified, col.color
            FROM adms_sits_pgs AS sitpg 
            INNER JOIN adms_colors AS col ON sitpg.adms_color_id=col.id
            WHERE sitpg.id=:id LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewSitUser->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-success'>Erro: Situação não encontrada!</p>";
            $this->result = false;
        }
    }
}
