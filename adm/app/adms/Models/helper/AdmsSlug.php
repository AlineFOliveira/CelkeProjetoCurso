<?php

namespace App\adms\Models\helper;

if (!defined('C8L6K7E')) {
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

// 

class AdmsSlug
{
    private string $text;
    private array $format;


    public function slug(string $text): string|null
    {

        $this->text = $text;
        $this->format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:,\\\'<>°ºª ';
        $this->format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr------------------------------------------------------------------------------------------------';

        $this->text = strtr(mb_convert_encoding($this->text, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($this->format['a'], 'ISO-8859-1', 'UTF-8'), $this->format['b']);
        $this->text = str_replace(" ", "-", $this->text);
        $this->text = str_replace(array('-----', '----', '---', '--'), "-", $this->text);
        $this->text = strtolower($this->text);

        //var_dump($this-> text);
        return $this->text;
    }
}
