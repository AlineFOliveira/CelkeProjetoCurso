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
class AdmsViewAccessLevels
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

    public function accessLevels(int $id): void
    {
        $this->id = (int)$id;

        $viewAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $viewAccessLevels->fullRead(
            "SELECT id, name, order_levels, created, modified
        FROM adms_access_levels 
        WHERE id=:id AND order_levels >:order_levels
        LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewAccessLevels->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }
}
