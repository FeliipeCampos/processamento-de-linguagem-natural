<?php
include 'funcoes.php'; 

$arquivoTextos = 'textos.txt';
$texto = file_get_contents($arquivoTextos);

$ranking = null;
$frasesGeradas = null;
$rankingBigram = null;
$frasesGeradasBigram = null;

if ($texto !== false) {
    $palavras = explode(" ", removerCaracteresEspeciais($texto));
    $ranking = processarTexto($palavras);
    $frasesGeradas = gerarFrasesAleatorias($ranking, 10);
    $rankingBigram = processarTextoBigram($palavras);
    $frasesGeradasBigram = gerarFrasesAleatoriasBigram($rankingBigram, 10, 5, 0);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Geração de Frases Aleatórias</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .full-text-container {
            max-height: 200px;
            overflow-y: auto;
        }
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Geração de Frases Aleatórias</h2>
        <div class="row">
            <div class="col-md-12">
                <?php if ($texto !== false): ?>
                    <h3>Texto completo:</h3>
                    <div class="full-text-container">
                        <p><?= htmlspecialchars($texto) ?></p>
                    </div>
                <?php else: ?>
                    <p>Nenhum texto disponível</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($ranking) && isset($frasesGeradas)): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Tabela de Probabilidades para Trigramas:</h3>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Palavra 1</th>
                                    <th>Palavra 2</th>
                                    <th>Próxima Palavra</th>
                                    <th>Probabilidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ranking as $chave => $proximasPalavras): ?>
                                    <?php list($palavraAtual, $proximaPalavra1) = explode('|', $chave); ?>
                                    <?php foreach ($proximasPalavras as $proximaPalavra => $probabilidade): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($palavraAtual) ?></td>
                                            <td><?= htmlspecialchars($proximaPalavra1) ?></td>
                                            <td><?= htmlspecialchars($proximaPalavra) ?></td>
                                            <td><?= number_format($probabilidade, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Frases geradas para Trigramas:</h3>
                    <div class="generated-sentences-container">
                        <ul>
                            <?php foreach ($frasesGeradas as $frase): ?>
                                <li><?= htmlspecialchars($frase) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($rankingBigram) && isset($frasesGeradasBigram)): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Tabela de Probabilidades para Bigramas:</h3>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Palavra 1</th>
                                    <th>Próxima Palavra</th>
                                    <th>Probabilidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankingBigram as $palavraAtual => $proximasPalavras): ?>
                                    <?php foreach ($proximasPalavras as $proximaPalavra => $probabilidade): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($palavraAtual) ?></td>
                                            <td><?= htmlspecialchars($proximaPalavra) ?></td>
                                            <td><?= number_format($probabilidade, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h3>Frases geradas para Bigramas:</h3>
                    <div class="generated-sentences-container">
                        <ul>
                            <?php foreach ($frasesGeradasBigram as $frase): ?>
                                <li><?= htmlspecialchars($frase) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
