<?php

namespace Core;
if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}
class ConfigController extends Config//faz a configController herdar da Config
{
    private $url;
    private $urlArray;
    private $urlController;
    private $urlMetodo;
    private $urlParameter;
    private $classLoad;
    private $format;
    private $urlSlugController;
    private $urlSlugMetodo;

    public function __construct()//construtor, executado no momento que usa a classe, prepara o terreno para as próximas funções
    {
        $this->configAdm();//função herdada da Config, traz as constantes criadas no config
        if(!empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT))){//se existe algo passada na variavel url
            $this->url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT); //Pega a url e salva

            //($this->url);
            $this->clearUrl();
            $this->urlArray = explode("/", $this->url); //Transforma em array quebrando onde tem barra, e salva no urlArray

            //var_dump($this->urlArray);

            if(isset($this->urlArray[0])){ // se existir algo passado na url, que referencie a controller que quer acessar
                $this->urlController = $this->slugController($this->urlArray[0]);//a função slug controller vai agir aqui
            }else{
                $this->urlController = $this->slugController(CONTROLLER);
            }

            if(isset($this->urlArray[1])){ // se existir algo passado na url, que referencie ao metodo que quer acessar
                $this->urlMetodo = $this->slugMetodo($this->urlArray[1]);
            }else{
                $this->urlMetodo = $this->slugMetodo(METODO);
            }

            if(isset($this->urlArray[2])){ // se existir algo passado na url, que referencie ao parametro (tipo, produtos da sessão 3...)
                $this->urlParameter = $this->urlArray[2];
            }else{
                $this->urlParameter = "";//essa pode ficar vazia
            }

        }else{//se a variável url não tivernada, esse é o default
            $this->urlController = $this->slugController(CONTROLLER);
            $this->urlMetodo = METODO;
            $this->urlParameter = "";

        }

        /* echo "Controller: {$this->urlController}<br>";
        echo "Metodo: {$this->urlMetodo}<br>";
        echo "Parametro: {$this->urlParameter}<br>"; */
    }

    private function clearUrl()
    {
        //Elimina a tentativa de escrever tags
        $this->url = strip_tags($this->url);
        //tira espaços em branco
        $this->url = trim($this->url);
        //Elimina barra no final
        $this->url = rtrim($this->url, '/');
        $this->format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:.,\\\'<>°ºª ';
        $this->format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr-------------------------------------------------------------------------------------------------';
        $this->url = strtr(mb_convert_encoding($this->url, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($this->format['a'], 'ISO-8859-1', 'UTF-8'), $this->format['b']);
        
    }

    /**
     * Ele irá converter o valor da url exemplo: "view-users" e converterá no formato da classe "ViewUsers"
     *
     * @param [type] $slugController
     * @return string
     */
    private function slugController($slugController): string
    {
        $this->urlSlugController = $slugController;
        // Converter para minusculo
        $this->urlSlugController = strtolower($this->urlSlugController);
        // Converter o traco para espaco em braco
        $this->urlSlugController = str_replace("-", " ", $this->urlSlugController);
        // Converter a primeira letra de cada palavra para maiusculo
        $this->urlSlugController = ucwords($this->urlSlugController);
        // Retirar espaco em branco        
        $this->urlSlugController = str_replace(" ", "", $this->urlSlugController);
        //var_dump($this->urlSlugController);
        return $this->urlSlugController;
    }

    /**
     * Ele vai tratar o método que veio da url
     * Reutiliza o método que trata a controller, com a diferença de que converte primeira letra em minúsculo
     *
     * @param [type] $urlSlugMetodo
     * @return void
     */
    private function slugMetodo($urlSlugMetodo): string
    {
        $this->urlSlugMetodo = $this->slugController($urlSlugMetodo);
        //Converter para minusculo a primeira letra
        $this->urlSlugMetodo = lcfirst($this->urlSlugMetodo);
        //var_dump($this->urlSlugMetodo);
        return $this->urlSlugMetodo;
    }

    /**
     * Carrega as controllers
     * Instancia as classes e carrega o método
     *
     * @return void
     */
    public function loadPage()//vai carregar a página correspontente
    {
        /* $this->urlController = ucwords($this->urlController);//transforma a primeira letra em maiúsculo
        
        $this->classLoad = "\\App\\adms\\Controllers\\" . $this->urlController;
        $classePage = new $this->classLoad();
        $classePage->{$this->urlMetodo}();//Aqui chama o método que está na controller */

        $loadPgAdm = new \Core\CarregarPgAdmLevel();
        $loadPgAdm->loadPage($this->urlController, $this->urlMetodo, $this->urlParameter);
        

    }

}

?>