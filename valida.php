<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "basefort_ADM";
$password = "Jjcr*)e}%~E2";
$dbname = "basefort_BD";

$link = mysqli_connect("localhost", "basefort_ADM", "Jjcr*)e}%~E2", "basefort_BD");

if (!$link) {
    echo "Error: Falha ao conectar-se com o banco de dados MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

// Verificar a conexão
if ($link->connect_error) {
    die("Conexão falhou: " . $link->connect_error);
}

// Função para limpar e validar dados recebidos do formulário
function validar_dados($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Receber dados do formulário
$empresa = isset($_POST['empresa']) ? validar_dados($_POST['empresa']) : '';
$cnpj = isset($_POST['cnpj']) ? validar_dados($_POST['cnpj']) : '';
$endereco = isset($_POST['endereco']) ? validar_dados($_POST['endereco']) : '';
$complemento = isset($_POST['complemento']) ? validar_dados($_POST['complemento']) : '';
$cidade = isset($_POST['cidade']) ? validar_dados($_POST['cidade']) : '';
$estado = isset($_POST['estado']) ? validar_dados($_POST['estado']) : '';
$cep = isset($_POST['cep']) ? validar_dados($_POST['cep']) : '';
$email = isset($_POST['email']) ? validar_dados($_POST['email']) : '';
$telefone = isset($_POST['telefone']) ? validar_dados($_POST['telefone']): '';

// Inserir dados no banco de dados usando prepared statement
$stmt = $link->prepare("INSERT INTO clientes (empresa, cnpj, endereco, complemento, cidade, estado, cep, email, telefone)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if($stmt) {

$stmt->bind_param("sssssssss", $empresa, $cnpj, $endereco, $complemento, $cidade, $estado, $cep, $email, $telefone);

// Executar a declaração preparada
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";

    // Enviar e-mail para a empresa
    $destinatario = "vendas@baseforte.net.com";
    $assunto = "Novo Cliente Cadastrado";
    
    $$mensagem = "Novo cliente cadastrado:\n\n";
    $mensagem .= "Empresa: $empresa\n";
    $mensagem .= "CNPJ: $cnpj\n";
    $mensagem .= "Endereço: $endereco\n";
    $mensagem .= "Complemento: $complemento\n";
    $mensagem .= "Cidade: $cidade\n";
    $mensagem .= "Estado: $estado\n";
    $mensagem .= "CEP: $cep\n";
    $mensagem .= "E-mail: $email\n";
    $mensagem .= "Telefone: $telefone\n";
    
    mail($destinatario, $assunto, $mensagem);

     // Redirecionar para uma página de sucesso
    header("Location: sucesso.html");
    exit();
    
} else {
    echo "Erro na execução: " . $stmt->error;
}


} else {
    echo "Erro na preparação: " . $link->error;
}

// Fechar a conexão
$stmt->close();
$link->close();
?>
