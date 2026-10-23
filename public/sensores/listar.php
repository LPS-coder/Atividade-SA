<?php
require_once __DIR__ . "/../../infra/conexao.php";

$sql = "SELECT s.id_sensor, s.nome_sensores, s.localizacao,
               s.tipo_dado, s.id_trem, t.nome_trens, t.codigo
        FROM sensores s
        INNER JOIN trens t ON s.id_trem = t.id_trem
        ORDER BY s.id_sensor DESC";

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0):
    while ($sensor = $resultado->fetch_assoc()):
?>
    <tr>
        <td><?= (int) $sensor["id_sensor"] ?></td>
        <td><?= htmlspecialchars($sensor["nome_sensores"]) ?></td>
        <td><?= htmlspecialchars($sensor["localizacao"]) ?></td>
        <td><?= htmlspecialchars($sensor["tipo_dado"]) ?></td>
        <td>
            <?= htmlspecialchars($sensor["nome_trens"]) ?>
            (<?= htmlspecialchars($sensor["codigo"]) ?>)
        </td>
        <td>
            <div class="d-flex gap-2">
                <a
                    href="sensores/visualizar.php?id=<?= (int) $sensor["id_sensor"] ?>"
                    class="btn btn-sm btn-outline-primary"
                    title="Visualizar"
                >
                    <i class="bi bi-eye"></i>
                </a>

                <a
                    href="sensores/editar.php?id=<?= (int) $sensor["id_sensor"] ?>"
                    class="btn btn-sm btn-outline-warning"
                    title="Editar"
                >
                    <i class="bi bi-pencil"></i>
                </a>

                <form
                    method="POST"
                    action="sensores/excluir.php"
                    onsubmit="return confirm('Deseja realmente excluir este sensor?');"
                >
                    <input
                        type="hidden"
                        name="id_sensor"
                        value="<?= (int) $sensor["id_sensor"] ?>"
                    >

                    <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger"
                        title="Excluir"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
<?php
    endwhile;
else:
?>
    <tr>
        <td colspan="6" class="text-center text-secondary py-4">
            Nenhum sensor cadastrado.
        </td>
    </tr>
<?php endif; ?>
