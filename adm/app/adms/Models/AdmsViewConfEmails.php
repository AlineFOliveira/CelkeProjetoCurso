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
class AdmsViewConfEmails
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

    public function viewConfEmails(int $id): void
    {
        $this->id = (int)$id;

        $viewConfEmails = new \App\adms\Models\helper\AdmsRead();
        $viewConfEmails->fullRead(
            "SELECT id, title, name, email, host, username, smtpsecure, port, created, modified
        FROM adms_confs_emails
        WHERE id=:id LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        // var_dump($viewUser->getResult());
        $this->resultBd = $viewConfEmails->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Configuração de e-mail não encontrada!</p>";
            $this->result = false;
        }
    }
}
