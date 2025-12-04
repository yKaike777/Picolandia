<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/revendedores.css">
    <title>Revendedores - Picolândia</title>
</head>
<body>

<form action="revendedores.php" method="post">
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <input type="text" name="cpf" placeholder="CNPJ" required>

    <button type="submit" name="acao" value="cadastrar">Cadastrar Revendedor</button>
    <button type="submit" name="acao" value="exibir" formnovalidate>Exibir Revendedores</button>
</form>

<a href="index.html">Voltar</a>

<?php
$server = "localhost";
$usuario = "root";
$senha = "";
$banco_dados = "picole";

$conn = new mysqli($server, $usuario, $senha, $banco_dados);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ---------- CADASTRAR ----------
    if ($_POST["acao"] === "cadastrar") {

        $nome = $_POST["nome"];
        $telefone = $_POST["telefone"];
        $cpf = $_POST["cpf"];

        $stmt = $conn->prepare("INSERT INTO revendedores (nome, telefone, cpf) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $telefone, $cpf);

        if ($stmt->execute()) {
            echo "<p>Revendedor cadastrado com sucesso!</p>";
        } else {
            echo "<p>Erro ao cadastrar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // ---------- EXIBIR ----------
    if ($_POST["acao"] === "exibir") {

        $sql = "SELECT id, nome, telefone, cpf FROM revendedores ORDER BY id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            echo "
            <table border='1' cellpadding='10'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                    </tr>
                </thead>
                <tbody>
            ";

            // Exibir todas as linhas
            while ($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nome']}</td>
                    <td>{$row['telefone']}</td>
                    <td>{$row['cpf']}</td>
                </tr>
                ";
            }

            echo "</tbody></table>";

        } else {
            echo "<p>Nenhum revendedor cadastrado</p>";
        }
    }
}

$conn->close();
?>

</body>
</html>
