<?php

session_start();
ob_start();

define('C8L6K7E', true);

        require "./vendor/autoload.php";
        $home = new Core\ConfigController(); //Cria tipo uma cópia da classe e guarda na variável
        $home->loadPage();//agora a variável pode usar as funções da configController

    ?>
