<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    /* die('Erro: página não encontrada'); */
}

/**
 * Editar a imagem do usuário no banco de dados
 *
 * @author Celke
 */
class AdmsEditUsersImage
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    private array|null $data;
    private array|null $dataImage;
    /** @var int|string|null  Recebe o endereço de upload da imagem */
    private string $directory;
    /** @var int|string|null  Recebe o endereço da imagem que deve ser excluida*/
    private string $delImg;
     /** @var int|string|null  Recebe o nome da imagem slug*/
     private string $nameImg;

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

    public function viewUser(int $id): bool
    {
        $this->id = $id;


        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT usr.id, usr.image
            FROM adms_users AS usr
            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
            WHERE usr.id=:id AND lev.order_levels >:order_levels
            LIMIT :limit",
            "id={$this->id}&order_levels=".$_SESSION['order_levels']."&limit=1"
        );

        //var_dump($viewUser->getResult());
        $this->resultBd = $viewUser->getResult();

        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado b!</p>";
            $this->result = false;
            return false;
        }
    }

    public function update(array $data = null): void
    {
        $this->data = $data;
        $this->dataImage = $this->data['new_image'];
        unset($this->data['new_image']);


        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            if (!empty($this->dataImage['name'])) {
                //$this->result = false;
                $this->valInput();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar uma imagem</p>";
                $this->result = false;
            }
            //$this->result = false;

        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valExtImg = new \App\adms\Models\helper\AdmsValExtImg();
        $valExtImg->validateExtImg($this->dataImage['type']);
        if (($this->viewUser($this->data['id'])) and ($valExtImg->getResult())) {
            $this->upload();
        } else {
            $this->result = false;
        }
    }

    private function upload(): void
    {
        $slugImg = new \App\adms\Models\helper\AdmsSlug();
        $this->nameImg =  $slugImg->slug($this->dataImage['name']);
        

        $this->result = false;

        $this->directory = "app/adms/assets/image/users/" . $this->data['id'] . "/";

        /* $uploadImg = new \App\adms\Models\helper\AdmsUpload();
        $uploadImg->upload($this->directory, $this->dataImage['tmp_name'], $this->nameImg); */

        $uploadImgRes = new \App\adms\Models\helper\AdmsUploadImgRes();
        $uploadImgRes->upload($this->dataImage, $this->directory, $this->nameImg, 300, 300);

        if($uploadImgRes->getResult()){
            $this->edit();
        }else{
            //$_SESSION['msg'] = "<p style='color:#f00;'>Erro: Upload da imagem não realizado com sucesso!</p>";
            $this->result = false;
        }
        /* if ((!file_exists($this->directory)) and (!is_dir($this->directory))) {
            mkdir($this->directory, 0755); //cria um diretório com o id do usuário
        } */

        // Envia e verifica se o arquivo foi movido com sucesso para o novo destino
        /* if (move_uploaded_file($this->dataImage['tmp_name'], $this->directory . $this->nameImg)) {
            $this->edit();
        } else {
            $_SESSION['msg'] = "<p style='color:#f00;'>Erro: Upload da imagem não realizado com sucesso!</p>";
            $this->result = false;
        } */
    }

    private function edit(): void
    {
        $this->data['image'] = $this->nameImg;
        $this->data['modified'] = date("Y-m-d H:i:s");


        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $this->deletImage();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    private function deletImage(): void
    {

        //apagar para substituir uma imagem já existente
        if (((!empty($this->resultBd[0]['image'])) or ($this->resultBd[0]['image'] != null)) and ($this->resultBd[0]['image'] != $this->nameImg)) {
            $this->delImg = "app/adms/assets/image/users/" . $this->data['id'] . "/" . $this->resultBd[0]['image'];
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Imagem editada com sucesso!</p>";
        $this->result = true;
    }
}
