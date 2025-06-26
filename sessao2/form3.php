<?php
$errors = [];

// Função para sanitizar entrada
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Processar formulário quando submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validar nome do equipamento
    $nome_equipamento = sanitize_input($_POST['nome_equipamento'] ?? '');
    if (empty($nome_equipamento)) {
        $errors[] = "Nome do equipamento é obrigatório.";
    } elseif (strlen($nome_equipamento) < 2) {
        $errors[] = "Nome do equipamento deve ter pelo menos 2 caracteres.";
    } elseif (strlen($nome_equipamento) > 100) {
        $errors[] = "Nome do equipamento não pode exceder 100 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s\-\_\.]+$/u', $nome_equipamento)) {
        $errors[] = "Nome do equipamento contém caracteres inválidos.";
    }

    // Validar escola digital (sim/não)
    $escola_digital = $_POST['escola_digital'] ?? '';
    if (empty($escola_digital) || !in_array($escola_digital, ['sim', 'nao'])) {
        $errors[] = "Deve selecionar se é da escola digital (Sim ou Não).";
    }

    // Validar ano
    $ano = filter_var($_POST['ano'] ?? '', FILTER_VALIDATE_INT);
    $ano_atual = (int)date('Y');
    if ($ano === false || empty($_POST['ano'])) {
        $errors[] = "Ano é obrigatório e deve ser um número válido.";
    } elseif ($ano < 1990 || $ano > ($ano_atual + 5)) {
        $errors[] = "Ano deve estar entre 2000 e " . ($ano_atual + 5) . ".";
    }

    // Se não há erros, redirecionar para página de resultados
    if (empty($errors)) {
        // Criar query string com os dados
        $query_data = http_build_query([
            'nome_equipamento' => $nome_equipamento,
            'escola_digital' => $escola_digital,
            'ano' => $ano
        ]);
        
        // Redirecionar para página de resultados
        header("Location: resultados.php?" . $query_data);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo de Equipamentos</title>
</head>
<body>
    <h1>Registo de Equipamentos</h1>
    
    <?php if (!empty($errors)): ?>
        <div>
            <h3>Erros encontrados:</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <hr>
    <?php endif; ?>

    <h2>Formulário de Registo</h2>
    <form method="POST" action="" id="cadastroForm">
        
        <p>
            <label for="nome_equipamento">Nome do Equipamento: *</label><br>
            <input 
                type="text" 
                id="nome_equipamento" 
                name="nome_equipamento" 
                value="<?php echo isset($_POST['nome_equipamento']) ? sanitize_input($_POST['nome_equipamento']) : ''; ?>"
                size="50"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label>Escola Digital: *</label><br>
            <input 
                type="radio" 
                id="escola_sim" 
                name="escola_digital" 
                value="sim"
                <?php echo (isset($_POST['escola_digital']) && $_POST['escola_digital'] === 'sim') ? 'checked' : ''; ?>
                required
            >
            <label for="escola_sim">Sim</label>
            <br>
            <input 
                type="radio" 
                id="escola_nao" 
                name="escola_digital" 
                value="nao"
                <?php echo (isset($_POST['escola_digital']) && $_POST['escola_digital'] === 'nao') ? 'checked' : ''; ?>
                required
            >
            <label for="escola_nao">Não</label>
        </p>

        <p>
            <label for="ano">Ano: *</label><br>
            <input 
                type="number" 
                id="ano" 
                name="ano" 
                value="<?php echo isset($_POST['ano']) ? (int)$_POST['ano'] : ''; ?>"
                min="2000" 
                max="<?php echo (int)date('Y') + 5; ?>"
                required
            >
        </p>

        <p>
            <input type="submit" value="Registar Equipamento">
            <input type="button" value="Limpar Formulário" onclick="limparFormulario()">
        </p>
        
    </form>

    <hr>
    <small>* Campos obrigatórios</small>

    <script>
 
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                let hasErrors = false;
                let errorMessages = [];
                
                // Validar nome do equipamento
                const nomeEquipamento = document.getElementById('nome_equipamento').value.trim();
                if (nomeEquipamento.length < 2) {
                    errorMessages.push('Nome do equipamento deve ter pelo menos 2 caracteres');
                    hasErrors = true;
                } else if (nomeEquipamento.length > 100) {
                    errorMessages.push('Nome do equipamento não pode exceder 100 caracteres');
                    hasErrors = true;
                }
                
                // Validar escola digital
                const escolaDigital = document.querySelector('input[name="escola_digital"]:checked');
                if (!escolaDigital) {
                    errorMessages.push('Deve selecionar se é da escola digital');
                    hasErrors = true;
                }
                
                // Validar ano
                const ano = parseInt(document.getElementById('ano').value);
                const anoAtual = new Date().getFullYear();
                if (isNaN(ano) || ano < 1990 || ano > (anoAtual + 5)) {
                    errorMessages.push('Ano deve estar entre 2000 e ' + (anoAtual + 5));
                    hasErrors = true;
                }
                
                if (hasErrors) {
                    alert('Erros encontrados:\n\n' + errorMessages.join('\n'));
                    e.preventDefault();
                }
            });
        });
        
        // Função para limpar o formulário
        function limparFormulario() {
            if (confirm('Tem certeza que deseja limpar todos os campos?')) {
               
                document.getElementById('nome_equipamento').value = '';
                
    
                const radioButtons = document.querySelectorAll('input[name="escola_digital"]');
                radioButtons.forEach(function(radio) {
                    radio.checked = false;
                });
                
            
                document.getElementById('ano').value = '';
                
         
                document.getElementById('nome_equipamento').focus();
            }
        }
    </script>
</body>
</html>