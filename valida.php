<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "basefort_ADM";
$password = "Jjcr*)e}%~E2";
$dbname = "basefort_BD";

try{
    $conn = new mysqli($servername, $username, $password, $dbname);
 }
 catch{
    MessageBox.Show("Não estabeleceu conexão com o banco de dados");
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
$empresa = validar_dados($_POST['empresa']);
$cnpj = validar_dados($_POST['cnpj']);
$endereco = validar_dados($_POST['endereco']);
$complemento = validar_dados($_POST['complemento']);
$cidade = validar_dados($_POST['cidade']);
$estado = validar_dados($_POST['estado']);
$cep = validar_dados($_POST['cep']);
$email = validar_dados($_POST['email']);
$email = validar_dados($_POST['telefone']);

// Inserir dados no banco de dados usando prepared statement
$stmt = $conn->prepare("INSERT INTO clientes (empresa, cnpj, endereco, complemento, cidade, estado, cep, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $empresa, $cnpj, $endereco, $complemento, $cidade, $estado, $cep, $email);

// Executar a declaração preparada
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro na preparação: " . $link->error;
}

// Fechar a conexão
$stmt->close();
$link->close();
?>


</body>
</html>