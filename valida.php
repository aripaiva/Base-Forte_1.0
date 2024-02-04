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

$link = mysqli_connect($servername, $username, $password, $dbname);

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

// Validar campos obrigatórios
if (empty($empresa) || empty($cnpj) || empty($endereco) || empty($cidade) || empty($estado) || empty($cep) || empty($email) || empty($telefone)) {
    echo "Erro: Todos os campos devem ser preenchidos.";
    exit();
}

// Inserir dados no banco de dados usando prepared statement
$stmt = $link->prepare("INSERT INTO clientes (empresa, cnpj, endereco, complemento, cidade, estado, cep, email, telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("sssssssss", $empresa, $cnpj, $endereco, $complemento, $cidade, $estado, $cep, $email, $telefone);

    // Executar a declaração preparada
    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";

        // Enviar e-mail para a empresa usando PHPMailer (exemplo)
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = '23.111.140.162'; // Insira o endereço do servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'vendas@baseforte.net.br'; // Insira seu endereço de e-mail
        $mail->Password = 'Baseforte.2024'; // Insira sua senha
        $mail->SMTPSecure = 'ssl'; // Use 'tls' ou 'ssl' dependendo das configurações do seu servidor
        $mail->Port = 465; // Porta do servidor SMTP, geralmente 587 para TLS ou 465 para SSL

// Restante das configurações do PHPMailer...

        // Configurar o restante das opções do PHPMailer conforme necessário

        $mail->setFrom('vendas@baseforte.net.br', 'Gessica Mara');
        $mail->addAddress('vendas@baseforte.net.br', 'Baseforte');

        $mail->Subject = 'Novo Cliente Cadastrado';
        $mail->Body    = "Novo cliente cadastrado:\n\n" . 
                         "Empresa: $empresa\n" . 
                         "CNPJ: $cnpj\n" .
                         "Endereço: $endereco\n" .
                         "Complemento: $complemento\n" .
                         "Cidade: $cidade\n" .
                         "Estado: $estado\n" .
                         "CEP: $cep\n" .
                         "E-mail: $email\n" .
                         "Telefone: $telefone"; 
                         // Restante do corpo do e-mail

        if ($mail->send()) {
            echo "E-mail enviado com sucesso!";
        } else {
            echo "Erro ao enviar o e-mail: " . $mail->ErrorInfo;
        }

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



</body>
</html>