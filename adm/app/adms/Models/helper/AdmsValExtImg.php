<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

// 

class AdmsValExtImg
{
    private string $mimeType;
    private bool $result;

    function getResult(): bool
    {
        return $this->result;
    }

    public function validateExtImg(string $mimeType): void
    {
        $this->mimeType = $mimeType;
        

        switch ($this->mimeType) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->result = true;
                break;
            case 'image/png':
            case 'image/x-png':
                $this->result = true;
                break;
            default:
                $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Necessário selecionar uma imagem JPEG ou PNG</p>";
                $this->result = false;
        }
    }
}
