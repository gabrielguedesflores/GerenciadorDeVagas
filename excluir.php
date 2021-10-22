<?php

require __DIR__ .'/vendor/autoload.php';

use \App\Entity\Vaga;

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    header("Location: index.php?status=error");
    exit;
}

//Consulta Vaga
$obVaga = Vaga::getVaga($_GET['id']);



//valida se a $obVaga é uma instancia da clase Vaga (valida se o id existe) 
if (!$obVaga instanceof Vaga) {
    header("Location: index.php?status=error");
    exit;
}

//Validação POST
if(isset($_POST['excluir'])){
    $obVaga->excluir(); 
    header("Location: index.php?status=success");
    exit;
}

include __DIR__ .'/includes/header.php';
include __DIR__ .'/includes/confirmar-exclusao.php';
include __DIR__ .'/includes/footer.php';



// echo "<pre>";  print_r($obVaga); echo "</pre>";  exit;

