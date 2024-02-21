<?php

namespace App\adms\Models\helper;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Classe gernérica para selecionar registro no banco de dados
 *
 * @author Celke
 */
class AdmsButton
{
    
    /** @var array $result Recebe os registros do banco de dados e retorna para a Models */
    private array|null $result;

    /** @var object $query Recebe a QUERY preparada */
    private array|null $data;





    /** 
     * Recebe os valores para montar a QUERY.
     * Converte a parseString de string para array.
     * @param string $table Recebe o nome da tabela do banco de dados
     * @param string $terms Recebe os links da QUERY, ex: sts_situation_id =:sts_situation_id
     * @param string $parseString Recebe o valores que devem ser subtituidos no link, ex: sts_situation_id=1
     * 
     * @return void
     */
    public function buttonPermission(array|null $data): array|null
    {
        $this->data = $data;


        foreach($this->data as $key => $button){
            
            extract($button);

            $viewButton = new \App\adms\Models\helper\AdmsRead();
            $viewButton->fullRead("SELECT pag.id
            FROM adms_pages pag
            INNER JOIN adms_levels_pages AS lev_pag ON lev_pag.adms_page_id=pag.id
            WHERE pag.menu_controller =:menu_controller
            AND pag.menu_metodo =:menu_metodo
            AND lev_pag.permission = 1
            AND lev_pag.adms_access_level_id =:adms_access_level_id
            LIMIT :limit",
            "menu_controller=$menu_controller&menu_metodo=$menu_metodo&adms_access_level_id=".$_SESSION['adms_access_level_id']."&limit=1");

            if($viewButton->getResult()){
                $this->result[$key] = true;
            }else{
                $this->result[$key] = false;
            }
        }
        return $this->result;
    }

}
