<?php
session_start();
include '../infra/conexao.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Backend: só o administrador logado cadastra rotas
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
if (!isset($_SESSION['cargo']) || strtolower($_SESSION['cargo']) != 'administrador') {
    header('Location: listar.php?msg=acesso_negado');
    exit;
}

$erros = [];
$nome = '';
$descricao = '';
$distancia = '';
$tempo = '';
$id_trem = '';

// trens cadastrados no sistema (o trem vinculado é escolhido entre eles)
$trens = [];
try {
    $trens = $conn->query("SELECT id_trem, nome_trens FROM trens ORDER BY nome_trens")->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    $erros[] = 'Não foi possível carregar os trens cadastrados.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_rota'] ?? '');
    $descricao = trim($_POST['descricao_rota'] ?? '');
    $distancia = str_replace(',', '.', trim($_POST['distancia_km'] ?? ''));
    $tempo = trim($_POST['tempo_previsto_min'] ?? '');
    $id_trem = trim($_POST['id_trem'] ?? '');

    // validação dos dados
    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 100) {
        $erros[] = 'O nome da rota deve ter entre 3 e 100 caracteres.';
    }
    if ($descricao == '' || mb_strlen($descricao) > 500) {
        $erros[] = 'Informe a descrição da rota (até 500 caracteres).';
    }
    if (!is_numeric($distancia) || $distancia <= 0 || $distancia > 99999) {
        $erros[] = 'A distância deve ser um número maior que 0 e até 99999 km.';
    }
    if (!ctype_digit($tempo) || $tempo < 1 || $tempo > 99999) {
        $erros[] = 'O tempo previsto deve ser um número inteiro de minutos, de 1 a 99999.';
    }
    if (!ctype_digit($id_trem) || !in_array($id_trem, array_column($trens, 'id_trem'))) {
        $erros[] = 'Selecione um trem cadastrado para vincular à rota.';
    }

    if (count($erros) == 0) {
        try {
            // não repetir o nome da rota
            $stmt = $conn->prepare("SELECT id_rota FROM rotas WHERE nome_rota = ?");
            $stmt->bind_param("s", $nome);
            $stmt->execute();
            $existe = $stmt->get_result()->num_rows;
            $stmt->close();

            if ($existe > 0) {
                $erros[] = 'Já existe uma rota com esse nome.';
            } else {
                // a data de criação é registrada na hora, com NOW()
                $stmt = $conn->prepare("INSERT INTO rotas (nome_rota, descricao_rota, distancia_km, tempo_previsto_min, id_trem, data_criacao) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssdii", $nome, $descricao, $distancia, $tempo, $id_trem);
                $stmt->execute();
                $stmt->close();

                header('Location: listar.php?msg=cadastrada');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            $erros[] = 'Não foi possível salvar a rota. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova rota - OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>
<body>
    <div id="conteudo-principal" class="p-4">
        <h1 class="h3 fw-bold mb-4">Nova rota</h1>

        <?php if (count($erros) > 0) { ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($erros as $erro) { ?>
                        <li><?= $erro ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (count($trens) == 0) { ?>
            <div class="alert alert-warning">Nenhum trem cadastrado. Cadastre um trem antes de criar uma rota.</div>
        <?php } ?>

        <form method="post" action="CADASTRO.php" class="row g-3">
            <div class="col-md-8">
                <label for="nome_rota" class="form-label fw-bold">Nome da rota</label>
                <input type="text" class="form-control" id="nome_rota" name="nome_rota" maxlength="100" value="<?= htmlspecialchars($nome) ?>" required>
            </div>

            <div class="col-md-4">
                <label for="id_trem" class="form-label fw-bold">Trem vinculado</label>
                <select class="form-select" id="id_trem" name="id_trem" required>
                    <option value="">Selecione o trem</option>
                    <?php foreach ($trens as $t) { ?>
                        <option value="<?= $t['id_trem'] ?>" <?= $t['id_trem'] == $id_trem ? 'selected' : '' ?>><?= htmlspecialchars($t['nome_trens']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12">
                <label for="descricao_rota" class="form-label fw-bold">Descrição</label>
                <textarea class="form-control" id="descricao_rota" name="descricao_rota" rows="3" maxlength="500" required><?= htmlspecialchars($descricao) ?></textarea>
            </div>

            <div class="col-md-6">
                <label for="distancia_km" class="form-label fw-bold">Distância (km)</label>
                <input type="number" class="form-control" id="distancia_km" name="distancia_km" min="0.01" max="99999" step="0.01" value="<?= htmlspecialchars($distancia) ?>" required>
            </div>

            <div class="col-md-6">
                <label for="tempo_previsto_min" class="form-label fw-bold">Tempo previsto (minutos)</label>
                <input type="number" class="form-control" id="tempo_previsto_min" name="tempo_previsto_min" min="1" max="99999" step="1" value="<?= htmlspecialchars($tempo) ?>" required>
            </div>

            <div class="col-12">
                <p class="text-muted small mb-0">A data de criação é registrada automaticamente ao salvar.</p>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-danger fw-bold" <?= count($trens) == 0 ? 'disabled' : '' ?>>Salvar rota</button>
                <a href="listar.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>