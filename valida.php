<?php
// Conectar ao banco de dados (substitua os valores pelos seus)
$servername = "23.111.140.162";
$username = "basefort";
$password = "x8[rCX!Bi53kY0";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para limpar e validar dados recebidos do formulário
function validar_dados($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}

// Receber dados do formulário
$empresa = validar_dados($_POST['empresa']);
$cnpj = validar_dados($_POST['cnpj']);
$endereco = validar_dados($_POST['endereco']);
$complemento = validar_dados($_POST['complemento']);
$cidade = validar_dados($_POST['cidade']);
$estado = validar_dados($_POST['estado']);
$cep = validar_dados($_POST['cep']);
$email = validar_dados($_POST['email']);

// Inserir dados no banco de dados usando prepared statement
$stmt = $conn->prepare("INSERT INTO clientes (empresa, cnpj, endereco, complemento, cidade, estado, cep, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $empresa, $cnpj, $endereco, $complemento, $cidade, $estado, $cep, $email);

// Executar a declaração preparada
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
