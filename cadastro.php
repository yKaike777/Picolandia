<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Picolés - Picolândia</title>

    
</head>
<body>
    <?php 
        $server = "localhost";
        $usuario = "root";
        $senha = "";
        $banco_dados = "picole";

        $conn = new mysqli($server, $usuario, $senha, $banco_dados);

        if ($conn -> connect_error){
            dia("Erro na Conexão: " . $conn -> connect_error);
        }
    ?>
    <form action="cadastro.php" method="post">
        <label for="sabor">Sabor:</label>
        <select name="sabor" id="sabores">
            <?php
                $sql = "SELECT id, nome FROM sabores";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
            ?>
        </select>

        <label for="embalagem">Embalagem:</label>
        <select name="embalagem" id="tipo_embalagens">
            <?php 
                $sql = "SELECT id, nome FROM tipo_embalagens";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()){
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
            ?>
        </select>

        <label for="tipo_picole">Tipo: </label>
        <select name="tipo_picole" id="">
            <?php 
                $sql = "SELECT id, nome FROM tipo_picoles";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()){
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
            ?>
        </select>

        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" step=".01" required>

        <label for="nome">Nome do Picolé:</label>
        <input type="text" name="nome" id="nome" required>

        <button type="submit" name="acao" value="cadastrar">Cadastrar Picolé</button>
        <button type="submit" name="acao" value="exibir" formnovalidate>Ver todos Picolés</button>
    </form>
    <a href="index.html">Voltar</a>

    <table>
        <thead>

        </thead>
    </table>

    <?php 
        $server = "localhost";
        $usuario = "root";
        $senha = "";
        $banco_dados = "picole";

        $conn = new mysqli($server, $usuario, $senha, $banco_dados);

        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if ($_POST["acao"] === "cadastrar"){
                $sabor = $_POST["sabor"];
                $embalagem = $_POST["embalagem"];
                $preco = $_POST["preco"];
                $nome = $_POST["nome"];
                $tipo = $_POST["tipo_picole"];

                $stmt = $conn->prepare("INSERT INTO picoles (id_sabor, id_tipo_embalagem, preco, nome, id_tipo_picole) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iidsi", $sabor, $embalagem, $preco, $nome, $tipo);

                if ($stmt->execute()) {
                    echo "Picolé cadastrado com sucesso!";
                } else {
                    echo "Erro ao cadastrar: " . $stmt->error;
                }

            $stmt->close();
            $conn->close();
            }

            if ($_POST["acao"] === "exibir") {

                $sql = "
                    SELECT p.id, p.nome AS picole, p.preco,
                        s.nome AS sabor, 
                        e.nome AS embalagem,
                        t.nome AS tipo
                    FROM picoles p
                    INNER JOIN sabores s ON p.id_sabor = s.id
                    INNER JOIN tipo_embalagens e ON p.id_tipo_embalagem = e.id
                    INNER JOIN tipo_picoles t ON p.id_tipo_picole = t.id
                    ORDER BY p.id
                ";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    echo "
                    <table border='1' cellpadding='10'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome do Picolé</th>
                                <th>Preço</th>
                                <th>Sabor</th>
                                <th>Embalagem</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                    ";


                    // Exibir todas as linhas
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['id']}</td>
                            <td>{$row['picole']}</td>
                            <td>R$ {$row['preco']}</td>
                            <td>{$row['sabor']}</td>
                            <td>{$row['embalagem']}</td>
                            <td>{$row['tipo']}</td>
                        </tr>
                        ";
                    }

                    echo "</tbody></table>";

                } else {
                    echo "<p>Nenhum picolé cadastrado</p>";
                }
            }

        }

    ?>
</body>
</html>