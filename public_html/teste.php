<?php
require_once "../vendor/autoload.php";

use App\Connection;
$conn = Connection::getDb();

$id = $_GET['id'];

$query = "SELECT `codigo`, `senha`, `nome`, `premio`, `valor`, `tipo`, `tipoQuantidade`, `quantidade`, `listaDados` FROM `rifas` WHERE `codigo` = :id;";
$stmt = $conn->prepare($query); 
$stmt->bindValue('id', $id);
$stmt->execute(); 
$rifa = $stmt->fetch(\PDO::FETCH_ASSOC);

$codigo = $rifa['codigo'];
$nome = $rifa['nome'];
$premio = $rifa['premio'];
$valor = $rifa['valor'];
$tipo = $rifa['tipo'];
$tipoQuantidade = $rifa['tipoQuantidade'];
$quantidade = $rifa['quantidade'];
$listaDados = $rifa['listaDados'];

$arraylista = explode(',', $listaDados, -1);



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cartela Rifa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap');

        
        *{
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }
        .caixinhaMoldura{
            width: 720px; 
            padding-top: 15px;
        }
        .caixinha{
            width: 50px;
            height: 50px;
            border: 1px solid black;
            display: inline-block;
            margin-right:-5px ;
            margin-top:-1px ;
            
        }

        .caixinhaNomes{
            width: 80px;
            height: 40px;
            border: 1px solid black;
            display: inline-block;
            margin-right:-5px ;
            margin-top:-1px ;
            padding: 2px;
            
        }

        .centralizar{
            width: 720px;
            margin-left: 2px; 
        }

        .centralizarNomes{
            width: 720px;
            margin-left: 15px; 
        }
        
        .fim{
            clear: both;
        }

        table{
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid;
        }
        
    </style>


</head>
<body>

    <div  class="caixinhaMoldura">
        
    <table width="100%">
        <tr>
            <td style="width: 100px;" >
                Cartela com <?= $quantidade?> <?= $tipo?>
            </td>
            <td align="center" rowspan="2">
              <h1><?= $nome ?></h1>
            </td>
            <td style="width: 100px; border-bottom:1px solid white; font-weight: 900" align="center">Valor : </td>
        </tr>
        
        <tr>
            <td>Numero : <?= $codigo ?></td> 
            <td align="center" style=" font-weight: 900; color : red; border: 1px solid black;font-size:1.3em;">R$<?= $valor ?></td> 
        </tr>
        <tr>
            <td colspan="3" align="left">
             Prêmio: <?= $premio ?>
            </td>
        </tr>
    </table>
    </div>

    <div id="caxixaMoldura" class="caixinhaMoldura">
        <div class="centralizar">
            <?php 
                if ($tipo == 'numeros') {
                    foreach ($arraylista as $arraylista)  { ?>
                        <span class="caixinha"><?=$arraylista?></span>
            <?php }  } ?>
        </div>  
        <div class="centralizarNomes">              
            <?php 
                if ($tipo == 'nomes') {
                    foreach ($arraylista as $arraylista)  { ?>
                        <span class="caixinhaNomes"><?=$arraylista?></span>
            <?php }  } ?>          
           
        </div>
    
    <div class="fim"></div>
    </div>
    
    
    
</body>
</html>

