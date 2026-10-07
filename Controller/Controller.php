<?php

namespace App;

use InvalidArgumentException;
use DomainException;

class Controller
{
    public function validarMatriz(array $A): void
    {
        if (empty($A) || !is_array($A[0])) {
            throw new InvalidArgumentException("A matriz deve ser um array bidimensional nao vazio.");
        }
        $cols = count($A[0]);
        foreach ($A as $row) {
            if (!is_array($row) || count($row) !== $cols) {
                throw new InvalidArgumentException("A matriz possui linhas com tamanhos inconsistentes.");
            }
            foreach ($row as $val) {
                if (!is_numeric($val)) {
                    throw new InvalidArgumentException("Todos os elementos da matriz devem ser numericos.");
                }
            }
        }
    }

    public function somarMatrizes(array $A, array $B): array
    {
        $this->validarMatriz($A);
        $this->validarMatriz($B);

        $m = count($A);
        $n = count($A[0]);

        if (count($B) !== $m || count($B[0]) !== $n) {
            throw new InvalidArgumentException("Dimensoes incompativeis para adicao de matrizes.");
        }

        $C = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $C[$i][$j] = $A[$i][$j] + $B[$i][$j];
            }
        }
        return $C;
    }

    public function subtrairMatrizes(array $A, array $B): array
    {
        $this->validarMatriz($A);
        $this->validarMatriz($B);

        $m = count($A);
        $n = count($A[0]);

        if (count($B) !== $m || count($B[0]) !== $n) {
            throw new InvalidArgumentException("Dimensoes incompativeis para subtracao de matrizes.");
        }

        $C = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $C[$i][$j] = $A[$i][$j] - $B[$i][$j];
            }
        }
        return $C;
    }

    public function multiplicarMatrizes(array $A, array $B): array
    {
        $this->validarMatriz($A);
        $this->validarMatriz($B);

        $m = count($A);
        $n = count($A[0]);
        $p = count($B);
        $q = count($B[0]);

        if ($n !== $p) {
            throw new InvalidArgumentException("Dimensoes incompativeis para multiplicacao: colunas de A devem ser iguais as linhas de B.");
        }

        $C = array_fill(0, $m, array_fill(0, $q, 0.0));

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $q; $j++) {
                $soma = 0.0;
                for ($k = 0; $k < $n; $k++) {
                    $soma += $A[$i][$k] * $B[$k][$j];
                }
                $C[$i][$j] = $soma;
            }
        }
        return $C;
    }

   
    public function transporMatriz(array $A): array
    {
        $this->validarMatriz($A);
        $m = count($A);
        $n = count($A[0]);

        $T = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $T[$j][$i] = $A[$i][$j];
            }
        }
        return $T;
    }
    public function calcularDeterminante(array $A): float
    {
        $this->validarMatriz($A);
        $n = count($A);
        if ($n !== count($A[0])) {
            throw new InvalidArgumentException("O determinante so pode ser calculado para matrizes quadradas.");
        }

        if ($n === 1) {
            return (float)$A[0][0];
        }

        if ($n === 2) {
            return (float)($A[0][0] * $A[1][1] - $A[0][1] * $A[1][0]);
        }

        $det = 0.0;
        for ($j = 0; $j < $n; $j++) {
            $submatriz = $this->obterSubmatriz($A, 0, $j);
            $sinal = ($j % 2 === 0) ? 1.0 : -1.0;
            $det += $sinal * $A[0][$j] * $this->calcularDeterminante($submatriz);
        }

        return $det;
    }

  
    public function resolverSistemaLinear(array $A, array $b): array
    {
        $this->validarMatriz($A);
        $n = count($A);

        if ($n !== count($A[0])) {
            throw new InvalidArgumentException("A matriz de coeficientes deve ser quadrada.");
        }

        if (count($b) !== $n) {
            throw new InvalidArgumentException("O numero de elementos do vetor b deve ser igual ao numero de linhas da matriz A.");
        }

        $M = [];
        for ($i = 0; $i < $n; $i++) {
            $M[$i] = $A[$i];
            $M[$i][$n] = (float)$b[$i];
        }

        for ($i = 0; $i < $n; $i++) {
            $maxRow = $i;
            for ($k = $i + 1; $k < $n; $k++) {
                if (abs($M[$k][$i]) > abs($M[$maxRow][$i])) {
                    $maxRow = $k;
                }
            }

            if ($maxRow !== $i) {
                $temp = $M[$i];
                $M[$i] = $M[$maxRow];
                $M[$maxRow] = $temp;
            }
            if (abs($M[$i][$i]) < 1e-11) {
                throw new DomainException("O sistema e impossivel ou indeterminado (matriz singular ou pivô nulo).");
            }

            for ($k = $i + 1; $k < $n; $k++) {
                $fator = $M[$k][$i] / $M[$i][$i];
                for ($j = $i; $j <= $n; $j++) {
                    $M[$k][$j] -= $fator * $M[$i][$j];
                }
            }
        }

        $x = array_fill(0, $n, 0.0);
        for ($i = $n - 1; $i >= 0; $i--) {
            $soma = 0.0;
            for ($j = $i + 1; $j < $n; $j++) {
                $soma += $M[$i][$j] * $x[$j];
            }
            $x[$i] = ($M[$i][$n] - $soma) / $M[$i][$i];
        }

        return $x;
    }

    private function obterSubmatriz(array $A, int $linhaExcluir, int $colunaExcluir): array
    {
        $sub = [];
        foreach ($A as $i => $row) {
            if ($i === $linhaExcluir) continue;
            $novaLinha = [];
            foreach ($row as $j => $val) {
                if ($j === $colunaExcluir) continue;
                $novaLinha[] = $val;
            }
            $sub[] = $novaLinha;
        }
        return $sub;
    }
}