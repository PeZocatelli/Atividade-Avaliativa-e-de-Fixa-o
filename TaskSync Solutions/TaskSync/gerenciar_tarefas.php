<?php
include 'conexao.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conexao->query("DELETE FROM tarefas WHERE id = $id");
    header("Location: gerenciar_tarefas.php?excluido=1");
    exit;
}

if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $novo_status = $_GET['status'];
    $stmt = $conexao->prepare("UPDATE tarefas SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_status, $id);
    $stmt->execute();
    header("Location: gerenciar_tarefas.php?status_atualizado=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gerenciar Tarefas</title>
    <link rel="stylesheet" href="gerenciar.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="header">
        <img src="TaskSync.png" alt="TaskSync Logo" />
        <h1>Gerenciamento de Tarefas</h1>
    </div>

    <?php
    $status_array = ['a fazer', 'fazendo', 'concluido'];
    foreach ($status_array as $status) {
        echo "<div class='coluna'><h2>" . ucfirst($status) . "</h2>";

        $stmt = $conexao->prepare("SELECT t.*, u.nome AS nome_usuario FROM tarefas t JOIN usuarios u ON t.usuario_id = u.id WHERE status = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($tarefa = $result->fetch_assoc()) {
            echo "<div class='tarefa'>";
            echo "<strong>{$tarefa['descricao']}</strong><br>";
            echo "Usuário: {$tarefa['nome_usuario']}<br>";
            echo "Setor: {$tarefa['setor']}<br>";
            echo "Prioridade: {$tarefa['prioridade']}<br>";
            echo "Data: {$tarefa['data_cadastro']}<br>";
            echo "<button class='botao botao-fazendo' data-id='{$tarefa['id']}'>Fazendo</button> ";
            echo "<button class='botao botao-concluido' data-id='{$tarefa['id']}'>Concluído</button> ";
            echo "<button class='botao botao-excluir' data-id='{$tarefa['id']}'>Excluir</button>";
            echo "</div>";
        }

        echo "</div>";
    }
    ?>

    <div class="clear"></div>

    <script>
        document.querySelectorAll('.botao-fazendo').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                Swal.fire({
                    icon: 'success',
                    title: 'Tarefa atualizada',
                    text: 'Tarefa transferida para Fazendo.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = `?status=fazendo&id=${id}`;
                });
            });
        });

        document.querySelectorAll('.botao-concluido').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                Swal.fire({
                    icon: 'success',
                    title: 'Tarefa atualizada',
                    text: 'Tarefa transferida para Concluído.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = `?status=concluido&id=${id}`;
                });
            });
        });

        document.querySelectorAll('.botao-excluir').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                Swal.fire({
                    title: 'Tem certeza que deseja excluir?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Não'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `?delete=${id}`;
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Exclusão cancelada',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });

        // Mensagens de sucesso após redirecionamentos
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('excluido') === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Excluído',
                text: 'Tarefa excluída com sucesso!'
            }).then(() => {
                urlParams.delete('excluido');
                const url = new URL(window.location);
                url.search = urlParams.toString();
                window.history.replaceState({}, document.title, url);
            });
        }

        if (urlParams.get('status_atualizado') === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Status Atualizado',
                text: 'Status da tarefa atualizado com sucesso!'
            }).then(() => {
                urlParams.delete('status_atualizado');
                const url = new URL(window.location);
                url.search = urlParams.toString();
                window.history.replaceState({}, document.title, url);
            });
        }
    </script>
</body>
</html>
