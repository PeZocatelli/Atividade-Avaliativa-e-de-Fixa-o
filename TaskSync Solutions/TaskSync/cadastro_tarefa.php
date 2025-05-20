<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['usuario_id'];
    $descricao = $_POST['descricao'];
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];

    $stmt = $conexao->prepare("INSERT INTO tarefas (usuario_id, descricao, setor, prioridade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $usuario_id, $descricao, $setor, $prioridade);
    $stmt->execute();
    header("Location: cadastro_tarefa.php?sucesso=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="tarefa.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Cadastro de Tarefa</title>
</head>
<body>

   
    <video autoplay muted loop id="videoFundo">
        <source src="fundo.mp4" type="video/mp4">
        Seu navegador não suporta vídeos em HTML5.
    </video>

    <div class="container">
        <div class="header">
            <img src="./TaskSync.png" alt="TaskSync Logo" />
            <h1>Cadastro de Tarefa</h1>
        </div>

        <form method="post">
            <select name="usuario_id" required>
                <option value="">Selecione o Usuário</option>
                <?php
                $resultado = $conexao->query("SELECT * FROM usuarios");
                while ($usuario = $resultado->fetch_assoc()) {
                    echo "<option value='{$usuario['id']}'>{$usuario['nome']}</option>";
                }
                ?>
            </select>
            <textarea name="descricao" placeholder="Descrição" required></textarea>
            <input type="text" name="setor" placeholder="Setor" required />
            <select name="prioridade" required>
                <option value="">Prioridade</option>
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
            </select>
            <input type="submit" value="Cadastrar Tarefa" />
        </form>
    </div>

    <script>
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        if (getQueryParam('sucesso') === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Tarefa cadastrada com sucesso.',
                confirmButtonText: 'OK'
            }).then(() => {
                const url = new URL(window.location);
                url.searchParams.delete('sucesso');
                window.history.replaceState({}, document.title, url.toString());
                window.location.href = 'gerenciar_tarefas.php';
            });
        }
    </script>
</body>
</html>
