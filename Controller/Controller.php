<?php

namespace Controller;

use InvalidArgumentException;
use DomainException;

class Controller
{
    public function validarMatriz(array $matriz): void
    {
        if (empty($matriz) || !isset($matriz[0]) || !is_array($matriz[0])) {
            throw new InvalidArgumentException("A matriz deve ser um array bidimensional nao vazio.");
        }

        $colunas = count($matriz[0]);

        foreach ($matriz as $linha) {
            if (!is_array($linha) || count($linha) !== $colunas) {
                throw new InvalidArgumentException("A matriz possui linhas com tamanhos inconsistentes.");
            }

            foreach ($linha as $valor) {
                if (!is_numeric($valor)) {
                    throw new InvalidArgumentException("Todos os elementos da matriz devem ser numericos.");
                }
            }
        }
    }

    public function somarMatrizes(array $matrizA, array $matrizB): array
    {
        $this->validarMatriz($matrizA);
        $this->validarMatriz($matrizB);

        $linhasA = count($matrizA);
        $colunasA = count($matrizA[0]);

        if (count($matrizB) !== $linhasA || count($matrizB[0]) !== $colunasA) {
            throw new InvalidArgumentException("Dimensoes incompativeis para adicao de matrizes.");
        }

        $matrizResultado = [];
        for ($i = 0; $i < $linhasA; $i++) {
            for ($j = 0; $j < $colunasA; $j++) {
                $matrizResultado[$i][$j] = $matrizA[$i][$j] + $matrizB[$i][$j];
            }
        }
        return $matrizResultado;
    }

    public function subtrairMatrizes(array $matrizA, array $matrizB): array
    {
        $this->validarMatriz($matrizA);
        $this->validarMatriz($matrizB);

        $linhasA = count($matrizA);
        $colunasA = count($matrizA[0]);

        if (count($matrizB) !== $linhasA || count($matrizB[0]) !== $colunasA) {
            throw new InvalidArgumentException("Dimensoes incompativeis para subtracao de matrizes.");
        }

        $matrizResultado = [];
        for ($i = 0; $i < $linhasA; $i++) {
            for ($j = 0; $j < $colunasA; $j++) {
                $matrizResultado[$i][$j] = $matrizA[$i][$j] - $matrizB[$i][$j];
            }
        }
        return $matrizResultado;
    }

    public function multiplicarMatrizes(array $matrizA, array $matrizB): array
    {
        $this->validarMatriz($matrizA);
        $this->validarMatriz($matrizB);

        $linhasA = count($matrizA);
        $colunasA = count($matrizA[0]);
        $linhasB = count($matrizB);
        $colunasB = count($matrizB[0]);

        if ($colunasA !== $linhasB) {
            throw new InvalidArgumentException("Dimensoes incompativeis para multiplicacao: colunas de A devem ser iguais as linhas de B.");
        }

        $matrizResultado = array_fill(0, $linhasA, array_fill(0, $colunasB, 0.0));

        for ($i = 0; $i < $linhasA; $i++) {
            for ($j = 0; $j < $colunasB; $j++) {
                $soma = 0.0;
                for ($k = 0; $k < $colunasA; $k++) {
                    $soma += $matrizA[$i][$k] * $matrizB[$k][$j];
                }
                $matrizResultado[$i][$j] = $soma;
            }
        }
        return $matrizResultado;
    }

    public function transporMatriz(array $matriz): array
    {
        $this->validarMatriz($matriz);
        $linhas = count($matriz);
        $colunas = count($matriz[0]);

        $matrizTransposta = [];
        for ($i = 0; $i < $linhas; $i++) {
            for ($j = 0; $j < $colunas; $j++) {
                $matrizTransposta[$j][$i] = $matriz[$i][$j];
            }
        }
        return $matrizTransposta;
    }

    public function calcularDeterminante(array $matriz): float
    {
        $this->validarMatriz($matriz);
        $tamanho = count($matriz);

        if ($tamanho !== count($matriz[0])) {
            throw new InvalidArgumentException("O determinante so pode ser calculado para matrizes quadradas.");
        }

        if ($tamanho === 1) {
            return (float)$matriz[0][0];
        }

        if ($tamanho === 2) {
            return (float)($matriz[0][0] * $matriz[1][1] - $matriz[0][1] * $matriz[1][0]);
        }

        $determinante = 0.0;
        for ($j = 0; $j < $tamanho; $j++) {
            $submatriz = $this->obterSubmatriz($matriz, 0, $j);
            $sinal = ($j % 2 === 0) ? 1.0 : -1.0;
            $determinante += $sinal * $matriz[0][$j] * $this->calcularDeterminante($submatriz);
        }

        return $determinante;
    }

    public function resolverSistemaLinear(array $matrizA, array $vetorB): array
    {
        $this->validarMatriz($matrizA);
        $tamanho = count($matrizA);

        if ($tamanho !== count($matrizA[0])) {
            throw new InvalidArgumentException("A matriz de coeficientes deve ser quadrada.");
        }

        if (count($vetorB) !== $tamanho) {
            throw new InvalidArgumentException("O numero de elementos do vetor b deve ser igual ao numero de linhas da matriz A.");
        }

        $matrizAumentada = [];
        for ($i = 0; $i < $tamanho; $i++) {
            $matrizAumentada[$i] = $matrizA[$i];
            $matrizAumentada[$i][$tamanho] = (float)$vetorB[$i];
        }

        for ($i = 0; $i < $tamanho; $i++) {
            $linhaPivo = $i;
            for ($k = $i + 1; $k < $tamanho; $k++) {
                if (abs($matrizAumentada[$k][$i]) > abs($matrizAumentada[$linhaPivo][$i])) {
                    $linhaPivo = $k;
                }
            }

            if ($linhaPivo !== $i) {
                $temp = $matrizAumentada[$i];
                $matrizAumentada[$i] = $matrizAumentada[$linhaPivo];
                $matrizAumentada[$linhaPivo] = $temp;
            }

            if (abs($matrizAumentada[$i][$i]) < 1e-11) {
                throw new DomainException("O sistema e impossivel ou indeterminado (matriz singular ou pivô nulo).");
            }

            for ($k = $i + 1; $k < $tamanho; $k++) {
                $fator = $matrizAumentada[$k][$i] / $matrizAumentada[$i][$i];
                for ($j = $i; $j <= $tamanho; $j++) {
                    $matrizAumentada[$k][$j] -= $fator * $matrizAumentada[$i][$j];
                }
            }
        }

        $solucao = array_fill(0, $tamanho, 0.0);
        for ($i = $tamanho - 1; $i >= 0; $i--) {
            $soma = 0.0;
            for ($j = $i + 1; $j < $tamanho; $j++) {
                $soma += $matrizAumentada[$i][$j] * $solucao[$j];
            }
            $solucao[$i] = ($matrizAumentada[$i][$tamanho] - $soma) / $matrizAumentada[$i][$i];
        }

        return $solucao;
    }

    private function obterSubmatriz(array $matriz, int $linhaExcluir, int $colunaExcluir): array
    {
        $submatriz = [];
        foreach ($matriz as $i => $linha) {
            if ($i === $linhaExcluir) continue;
            $novaLinha = [];
            foreach ($linha as $j => $valor) {
                if ($j === $colunaExcluir) continue;
                $novaLinha[] = $valor;
            }
            $submatriz[] = $novaLinha;
        }
        return $submatriz;
    }
}