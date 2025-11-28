    <?php
    require 'inc/config.php';

    $id_lacador = $_GET['id'] ?? 0;

    // Busca dados do corredor
    $stmt_corredor = $pdo->prepare("SELECT nome, apelido FROM lacadores WHERE id = ?");
    $stmt_corredor->execute([$id_lacador]);
    $corredor = $stmt_corredor->fetch(PDO::FETCH_ASSOC);

    // Busca todas as inscrições do corredor
    $stmt = $pdo->prepare("
        SELECT 
            i.id as inscricao_id,
            i.tipo,
            i.status,
            i.valor,
            i.created_at,
            e.nome as evento_nome,
            e.created_at as evento_created_at,
            e.local
        FROM inscricoes i
        INNER JOIN eventos e ON i.id_evento = e.id
        WHERE i.id_lacador = ?
        ORDER BY i.created_at DESC
    ");
    $stmt->execute([$id_lacador]);
    $inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Exibe nome do corredor
    if ($corredor): ?>
        <div class="alert alert-secondary mb-3">
            <strong><i class="bi bi-person-fill"></i> Corredor:</strong>
            <?= htmlspecialchars($corredor['nome']) ?>
            <?php if (!empty($corredor['apelido'])): ?>
                <span class="text-muted">(<?= htmlspecialchars($corredor['apelido']) ?>)</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (count($inscricoes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#cod</th>

                        <th>Evento</th>
                        <th>Local</th>
                        <th>Data Evento</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Data Inscrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $insc): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($insc['inscricao_id']) ?></strong></td>
                            <td><strong><?= htmlspecialchars($insc['evento_nome']) ?></strong></td>
                            <td><?= htmlspecialchars($insc['local']) ?></td>
                            <td><?= date('d/m/Y', strtotime($insc['evento_created_at'])) ?></td>
                            <td>
                                <?php
                                $tipo_class = '';
                                switch ($insc['tipo']) {
                                    case 'Cabeça':
                                        $tipo_class = 'primary';
                                        break;
                                    case 'Pé':
                                        $tipo_class = 'success';
                                        break;
                                    case 'Ambos':
                                        $tipo_class = 'info';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?= $tipo_class ?>"><?= htmlspecialchars($insc['tipo']) ?></span>
                            </td>
                            <td>
                                R$ <?= number_format($insc['valor'], 2, ',', '.') ?>
                            </td>

                            <td>
                                <?php
                                $status_class = '';
                                switch ($insc['status']) {
                                    case 'Pendente':
                                        $status_class = 'badge-pendente';
                                        break;
                                    case 'Aprovado':
                                        $status_class = 'badge-aprovado';
                                        break;
                                    case 'Reprovado':
                                        $status_class = 'badge-reprovado';
                                        break;
                                    default:
                                        $status_class = 'badge-pendente';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $status_class ?>"><?= htmlspecialchars($insc['status']) ?></span>
                            </td>
                            <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($insc['created_at'])) ?></small></td>
                            <td>
                                <?php if ($insc['status'] == 'Pendente'): ?>
                                    <button class="btn btn-success btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Aprovado')" title="Aprovar">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Reprovado')" title="Reprovar">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                <?php elseif ($insc['status'] == 'Aprovado'): ?>
                                    <button class="btn btn-warning btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Pendente')" title="Tornar Pendente">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Reprovado')" title="Reprovar">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                <?php elseif ($insc['status'] == 'Reprovado'): ?>
                                    <button class="btn btn-success btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Aprovado')" title="Aprovar">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="atualizarStatus(<?= $insc['inscricao_id'] ?>, 'Pendente')" title="Tornar Pendente">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle fs-3"></i>
            <p class="mb-0 mt-2">Este corredor ainda não possui inscrições em eventos.</p>
        </div>
    <?php endif; ?>