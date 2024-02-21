<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}



class AdmsListConfEmails
{

    private $resultBd;
    private $result = false;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;
    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmail;
    /** @var string|null $searchNameValue Recebe o nome do usuario */
    private string|null $searchNameValue;
    /** @var string|null $searchEmailValue Recebe o e-mail do usuario */
    private string|null $searchEmailValue;

    function getResult(): bool
    {
        return $this->result;
    }

    function getResultBd(): array|null
    {
        return $this->resultBd;
    }
    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    //vai receber o numero da página enviada pela controller
    public function listConfEmails(int $page = null): void
    {


        $this->page = (int) $page ? $page : 1; //força a ser 1 e inteiro se for vazia
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-conf-emails/index'); //envia a url para a constructor da help
        $pagination->condition($this->page, $this->limitResult); //envia o numero da página e o limite a ser apresentado
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_confs_emails");
        $this->resultPg = $pagination->getResult();

        $listConfEmails = new \App\adms\Models\helper\AdmsRead();
        $listConfEmails->fullRead("SELECT id, title, name, email
        FROM adms_confs_emails
        ORDER BY id DESC LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");



        $this->resultBd = $listConfEmails->getResult();

        if ($this->resultBd) {
            $this->result = true;
            //var_dump($this->resultBd);
        } else {
            $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Não encontrado!</p>";
            $this->result = false;
        }
    }

    public function listSearchConfEmails(int $page = null, string|null $search_name, string|null $search_email): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchName = trim($search_name);
        $this->searchEmail = trim($search_email);

        $this->searchNameValue = "%" . $this->searchName . "%";
        $this->searchEmailValue = "%" . $this->searchEmail . "%";

        if ((!empty($this->searchName)) and (!empty($this->searchEmail))) {
            $this->searchConfNameEmail();
        } elseif ((!empty($this->searchName)) and (empty($this->searchEmail))) {
            $this->searchConfName();
        } elseif ((empty($this->searchName)) and (!empty($this->searchEmail))) {
            $this->searchConfEmail();
        } else {
            $this->searchConfNameEmail();
        }
    }

    /**
     * Metodo pesquisar pelo nome
     * @return void
     */
    public function searchConfName(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-conf-emails/index', "?search_name={$this->searchName}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_confs_emails
                            WHERE name LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT id, title, name, email
                FROM adms_confs_emails
                WHERE name LIKE :search_name
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum email encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo e-mail
     * @return void
     */
    public function searchConfEmail(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-users/index', "?search_name={$this->searchName}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                            FROM adms_confs_emails
                            WHERE email LIKE :search_email", "search_email={$this->searchEmailValue}");
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT id, title, name, email
        FROM adms_confs_emails
        WHERE email LIKE :search_email
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset", "search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum email encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo nome e e-mail
     * @return void
     */
    public function searchConfNameEmail(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-users/index', "?search_name={$this->searchName}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(usr.id) AS num_result 
                        FROM adms_users usr
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                        WHERE (usr.name LIKE :search_name OR usr.email LIKE :search_email) AND lev.order_levels > :order_levels", "search_name={$this->searchNameValue}&search_email={$this->searchEmailValue}&order_levels=" . $_SESSION['order_levels']);
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, usr.adms_sits_user_id,
                sit.name AS name_sit,
                col.color
                FROM adms_users AS usr
                INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                WHERE (usr.name LIKE :search_name OR usr.email LIKE :search_email) AND lev.order_levels > :order_levels
                ORDER BY usr.id DESC
                LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&search_email={$this->searchEmailValue}&order_levels=" . $_SESSION['order_levels'] . "&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum email encontrado!</p>";
            $this->result = false;
        }
    }

    /* "SELECT sit.id, usr.name AS name_usr, usr.nickname, usr.email, usr.user, usr.image, usr.adms_sits_user_id, usr.created, usr.modified,
            sit.name AS name_sit,
            col.color
            FROM adms_sits_users AS sit 
            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
            WHERE sit.id=:id
            LIMIT :limit", "id={$this->id}&limit=1" */
}
