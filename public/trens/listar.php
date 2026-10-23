<?php

require_once __DIR__ . "/../../infra/seguranca.php";
exigirLogin();

require_once __DIR__ . "/../../infra/conexao.php";

$sql = "SELECT id_trem, nome_trens, codigo, modelo
        FROM trens
        ORDER BY id_trem DESC";

$resultado = $conn->query($sql);

if (!$resultado) {
    echo '<tr><td colspan="5">Erro ao carregar os trens.</td></tr>';
    return;
}

if ($resultado->num_rows === 0) {
    echo '<tr><td colspan="5" class="text-center text-muted">Nenhum trem cadastrado.</td></tr>';
}

while ($trem = $resultado->fetch_assoc()):
?>
    <tr>
        <td><?= (int) $trem["id_trem"] ?></td>
        <td><?= htmlspecialchars($trem["nome_trens"]) ?></td>
        <td><?= htmlspecialchars($trem["codigo"]) ?></td>
        <td><?= htmlspecialchars($trem["modelo"]) ?></td>
        <td>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-outline-secondary"
                   href="trens/visualizar.php?id=<?= (int) $trem["id_trem"] ?>">
                    <i class="bi bi-eye"></i>
                </a>

                <a class="btn btn-sm btn-outline-primary"
                   href="trens/editar.php?id=<?= (int) $trem["id_trem"] ?>">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <form method="POST" action="trens/excluir.php"
                      onsubmit="return confirm('Deseja excluir este trem?')">
                    <input type="hidden" name="id_trem" value="<?= (int) $trem["id_trem"] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
<?php endwhile; ?>
