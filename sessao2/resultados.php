<?php

if (empty($_GET['nome_equipamento']) || empty($_GET['escola_digital']) || empty($_GET['ano'])) {
    header("Location: form3.php");
    exit();
}

$nome_equipamento = $_GET['nome_equipamento'];
$escola_digital = ($_GET['escola_digital'] === 'sim') ? 'Sim' : 'Não';
$ano = (int)$_GET['ano'];

?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipamento Registado</title>
</head>
<body>
    <h1>Equipamento Registado com Sucesso!</h1>
    
    <h2>Dados do Equipamento:</h2>
    
    <table  cellpadding="10" cellspacing="0">
        <tr>
            <td><strong>Nome do Equipamento:</strong></td>
            <td><?php echo $nome_equipamento; ?></td>
        </tr>
        <tr>
            <td><strong>Escola Digital:</strong></td>
            <td><?php echo $escola_digital; ?></td>
        </tr>
        <tr>
            <td><strong>Ano:</strong></td>
            <td><?php echo $ano; ?></td>
        </tr>
     
    </table>
    
    <br>
    
    <p>
        <a href="form3.php">
            <button type="button">Registar Novo Equipamento</button>
        </a>
    </p>
    
    <hr>
    
</body>
</html>