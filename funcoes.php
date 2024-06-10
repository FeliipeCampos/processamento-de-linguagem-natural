<?php
function removerCaracteresEspeciais($str) {
    $str = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '', $str);
    $str = mb_strtolower($str);
    return $str;
}

function processarTexto($palavras) {
    $contagemPalavras = count($palavras);
    $ranking = array();

    for ($i = 0; $i < $contagemPalavras - 2; $i++) {
        $palavraAtual = $palavras[$i];
        $proximaPalavra1 = $palavras[$i + 1];
        $proximaPalavra2 = $palavras[$i + 2];
        $chave = $palavraAtual . '|' . $proximaPalavra1;

        if (!isset($ranking[$chave])) {
            $ranking[$chave] = array();
        }

        if (!isset($ranking[$chave][$proximaPalavra2])) {
            $ranking[$chave][$proximaPalavra2] = 1;
        } else {
            $ranking[$chave][$proximaPalavra2]++;
        }
    }

    foreach ($ranking as &$palavra) {
        $totalOcorrencias = array_sum($palavra);
        foreach ($palavra as &$contagem) {
            $contagem = $contagem / $totalOcorrencias;
        }
    }

    return $ranking;
}

function processarTextoBigram($palavras) {
    $contagemPalavras = count($palavras);
    $ranking = array();

    for ($i = 0; $i < $contagemPalavras - 1; $i++) {
        $palavraAtual = $palavras[$i];
        $proximaPalavra = $palavras[$i + 1];

        if (!isset($ranking[$palavraAtual])) {
            $ranking[$palavraAtual] = array();
        }

        if (!isset($ranking[$palavraAtual][$proximaPalavra])) {
            $ranking[$palavraAtual][$proximaPalavra] = 1;
        } else {
            $ranking[$palavraAtual][$proximaPalavra]++;
        }
    }

    foreach ($ranking as &$palavra) {
        $totalOcorrencias = array_sum($palavra);
        foreach ($palavra as &$contagem) {
            $contagem = $contagem / $totalOcorrencias;
        }
    }

    return $ranking;
}

function gerarFrasesAleatorias($ranking, $quantidade = 10, $tamanho = 10) {
    $frases = array();

    for ($j = 0; $j < $quantidade; $j++) {
        $frase = '';
        $chaveAtual = array_rand($ranking);
        list($palavraAtual, $proximaPalavra1) = explode('|', $chaveAtual);

        $frase .= $palavraAtual . ' ' . $proximaPalavra1 . ' ';

        for ($i = 2; $i < $tamanho; $i++) {
            $chave = $palavraAtual . '|' . $proximaPalavra1;

            if (isset($ranking[$chave])) {
                $proximaPalavra2 = selecionarAleatoriamente($ranking[$chave]);
                $frase .= $proximaPalavra2 . ' ';

                $palavraAtual = $proximaPalavra1;
                $proximaPalavra1 = $proximaPalavra2;
            } else {
                break;
            }
        }

        $frases[] = trim($frase);
    }

    return $frases;
}

function gerarFrasesAleatoriasBigram($ranking, $quantidade = 10, $tamanho = 10, $limiteProbabilidade = 0.6) {
    $frases = array();

    for ($j = 0; $j < $quantidade; $j++) {
        $frase = '';
        $palavraAtual = array_rand($ranking);
        $frase .= $palavraAtual . ' ';

        for ($i = 1; $i < $tamanho; $i++) {
            if (isset($ranking[$palavraAtual])) {
                $proximaPalavra = selecionarAleatoriamenteComLimite($ranking[$palavraAtual], $limiteProbabilidade);
                $frase .= $proximaPalavra . ' ';
                $palavraAtual = $proximaPalavra;
            } else {
                break;
            }
        }

        $frases[] = trim($frase);
    }

    return $frases;
}

function selecionarAleatoriamente($pesos) {
    $aleatorio = mt_rand() / mt_getrandmax();
    $soma = 0;

    foreach ($pesos as $item => $peso) {
        $soma += $peso;
        if ($aleatorio <= $soma) {
            return $item;
        }
    }

    return end(array_keys($pesos));
}

function selecionarAleatoriamenteComLimite($pesos, $limite) {
    $aleatorio = mt_rand() / mt_getrandmax();

    if ($aleatorio < $limite) {
        return selecionarAleatoriamente($pesos);
    } else {
        return array_rand($pesos);
    }
}
?>
