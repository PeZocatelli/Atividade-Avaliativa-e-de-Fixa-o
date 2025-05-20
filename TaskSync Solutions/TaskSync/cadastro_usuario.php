<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $setor = $_POST['setor'];

    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, setor) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $setor);
    $stmt->execute();

    $sucesso = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="usuario.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>


    <video autoplay muted loop id="videoFundo">
        <source src="fundo.mp4" type="video/mp4">
        Seu navegador não suporta vídeos em HTML5.
    </video>

    <div class="container">
        <div class="header">
            <img src="TaskSync.png" alt="TaskSync Logo" />
            <h1>Cadastro de Usuário</h1>
        </div>

        <form method="post">
            <input type="text" name="nome" placeholder="Nome" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="text" name="setor" placeholder="Setor" required />
            <input type="submit" value="Cadastrar" />
        </form>
    </div>

    <?php if (!empty($sucesso)): ?>
    <script>
        Swal.fire({
            title: 'Sucesso!',
            text: 'Usuário cadastrado com sucesso.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'cadastro_tarefa.php';
        });
    </script>
    <?php endif; ?>

</body>
</html>
