<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produção - Picolãndia</title>
</head>
<body>
    <?php
        $server = "localhost";
        $usuario = "root";
        $senha = "";
        $banco_dados = "picole";

        $conn = new mysqli($server, $usuario, $senha, $banco_dados);

        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
    ?>

    <form action="producao.php" method="post">
        <label for="id_picole">Nome do Picolé: </label>
        <select name="id_picole" id="">
            <?php 
                $sql = "SELECT id, nome FROM picoles";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()){
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
            ?>
        </select>

        <label for="quantidade">Quantidade (Em caixas): </label>
        <input type="number" name="quantidade" min="1" required>

        <button type="submit" name="acao" value="fazer_pedido">Fazer Pedido</button>
    </form>

    <a href="index.html">Voltar</a>

    <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if ($_POST["acao"] === "fazer_pedido"){
                $picole = $_POST["id_picole"];
                $quantidade = $_POST["quantidade"] ?? 0;
                
                if ($quantidade > 0){
                    $stmt = $conn->prepare("INSERT INTO lotes (quantidade, id_picole) VALUES (?, ?)");
                    $stmt->bind_param("ii", $quantidade, $picole);

                    if ($stmt->execute()) {
                        echo "<p>Lote Pedido com Sucesso!</p>";
                    } else {
                        echo "<p>Erro ao realizar o pedido: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                } else{

                }
            }
        }
    ?>

</body>
</html>