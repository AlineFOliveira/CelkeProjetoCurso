<?php

namespace App\adms\Models;

if (!defined('C8L6K7E')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar os usuários do banco de dados
 *
 * @author Celke
 */
class AdmsListPages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int $page Recebe o número página */
    private int $page;

    /** @var int $page Recebe a quantidade de registros que deve retornar do banco de dados */
    private int $limitResult = 40;

    /** @var string|null $page Recebe a páginação */
    private string|null $resultPg;

    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;


    /** @var string|null $searchNameValue Recebe o nome do usuario */
    private string|null $searchNameValue;





    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os registros do BD
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * @return bool Retorna a paginação
     */
    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    /**
     * Metodo faz a pesquisa dos usuários na tabela adms_users e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listPages(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-pages/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_pages");
        $this->resultPg = $pagination->getResult();

        $listPages = new \App\adms\Models\helper\AdmsRead();
        $listPages->fullRead("SELECT pg.id, pg.name_page,
                        tpg.type type_tpg, tpg.name name_tpg,
                        sit.name name_sit,
                        col.color 
                        FROM adms_pages AS pg
                        LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                        LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                        LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        ORDER BY id DESC
                        LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listPages->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos usuários na tabela adms_users e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * Recebe o paramentro "search_name" para pesquisar o usuario atraves do nome
     * Recebe o paramentro "search_email" para pesquisar o usuario atraves do e-mail
     * @param integer|null $page
     * @param string|null $search_name
     * @param string|null $search_email
     * @return void
     */
    public function listSearchPages(int $page = null, string|null $search_pag): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchName= trim($search_pag);

        $this->searchNameValue = "%" . $this->searchName . "%";

        if (!empty($this->searchName)) {
            $this->searchPageName();
        }
    }

    /**
     * Metodo pesquisar pelo nome
     * @return void
     */
    public function searchPageName(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URL . 'list-pages/index', "?search_name={$this->searchName}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_pages
                            WHERE name_page LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT pg.id, pg.name_page,
                tpg.type type_tpg, tpg.name name_tpg,
                sit.name name_sit,
                col.color 
                FROM adms_pages AS pg
                LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id
                WHERE pg.name_page LIKE :search_name
                ORDER BY pg.id DESC
                LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum usuário encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo e-mail
     * @return void
     */
   
}
