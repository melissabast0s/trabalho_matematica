<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Álgebra Linear Computacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-2 text-primary text-center">Álgebra Linear Computacional</h1>
    <p class="text-muted text-center mb-4">Calculadora e Solucionador de Sistemas Lineares com Validação Automatizada</p>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Erro de Processamento:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">1. Operações Matriciais</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="operacao_matriz">
                        
                        <div class="mb-3">
                            <label class="form-label font-monospace">Matriz A (JSON 2D):</label>
                            <textarea name="matriz_a" class="form-control font-monospace" rows="3" required>[[1, 2], [3, 4]]</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-monospace">Matriz B (JSON 2D):</label>
                            <textarea name="matriz_b" class="form-control font-monospace" rows="3">[[5, 6], [7, 8]]</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Operação:</label>
                            <select name="operacao" class="form-select">
                                <option value="soma">Adição (A + B)</option>
                                <option value="subtração">Subtração (A - B)</option>
                                <option value="multiplicacao">Multiplicação (A * B)</option>
                                <option value="transposta">Transposta (Aᵀ)</option>
                                <option value="determinante">Determinante det(A)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Calcular Operação</button>
                    </form>

                    <?php if (isset($resultadoMatriz)): ?>
                        <div class="alert alert-success mt-3">
                            <h6>Resultado:</h6>
                            <pre class="mb-0"><?= json_encode($resultadoMatriz, JSON_PRETTY_PRINT) ?></pre>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">2. Sistema Linear (Eliminação de Gauss)</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="acao" value="sistema_linear">
                        
                        <div class="mb-3">
                            <label class="form-label font-monospace">Matriz A (Coeficientes 2D):</label>
                            <textarea name="matriz_a" class="form-control font-monospace" rows="4" required>[[2, 1, -1], [-3, -1, 2], [-2, 1, 2]]</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-monospace">Vetor B (Termos Independentes 1D):</label>
                            <textarea name="vetor_b" class="form-control font-monospace" rows="2" required>[8, -11, -3]</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Resolver Sistema Linear</button>
                    </form>

                    <?php if (isset($resultadoSistema)): ?>
                        <div class="alert alert-success mt-3">
                            <h6>Vetor Solução (x):</h6>
                            <ul>
                                <?php foreach ($resultadoSistema as $i => $val): ?>
                                    <li><strong>x<?= $i + 1 ?>:</strong> <?= round($val, 6) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>