<?php

namespace Tests;

use Controller\Controller; 
use InvalidArgumentException;
use DomainException;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    private Controller $controller;

    protected function setUp(): void
    {
        $this->controller = new Controller();
    }

    public function testSomaDeMatrizesValidas(): void
    {
        $matrizA = [[1, 2], [3, 4]];
        $matrizB = [[5, 6], [7, 8]];
        $esperado = [[6, 8], [10, 12]];

        $resultado = $this->controller->somarMatrizes($matrizA, $matrizB);
        $this->assertEquals($esperado, $resultado);
    }

    public function testMultiplicacaoDeMatrizesValida(): void
    {
        $matrizA = [[1, 2, 3], [4, 5, 6]];
        $matrizB = [[7, 8], [9, 1], [2, 3]];
        $esperado = [[31, 19], [85, 55]];

        $resultado = $this->controller->multiplicarMatrizes($matrizA, $matrizB);
        $this->assertEquals($esperado, $resultado);
    }

    public function testDeterminanteMatriz3x3(): void
    {
        $matriz = [
            [6, 1, 1],
            [4, -2, 5],
            [2, 8, 7]
        ];

        $resultado = $this->controller->calcularDeterminante($matriz);
        $this->assertEqualsWithDelta(-306.0, $resultado, 0.0001);
    }

    public function testSistemaLinearPossivelDeterminado(): void
    {
        $matrizA = [
            [2, 1, -1],
            [-3, -1, 2],
            [-2, 1, 2]
        ];
        $vetorB = [8, -11, -3];

        $solucao = $this->controller->resolverSistemaLinear($matrizA, $vetorB);

        $this->assertEqualsWithDelta(2.0, $solucao[0], 0.0001);
        $this->assertEqualsWithDelta(3.0, $solucao[1], 0.0001);
        $this->assertEqualsWithDelta(-1.0, $solucao[2], 0.0001);
    }

    public function testSomaComMatrizNula(): void
    {
        $matrizA = [[3, -2], [5, 1]];
        $matrizNula = [[0, 0], [0, 0]];

        $resultado = $this->controller->somarMatrizes($matrizA, $matrizNula);
        $this->assertEquals($matrizA, $resultado);
    }

    public function testSomaDeMatrizes1x1(): void
    {
        $matrizA = [[15.5]];
        $matrizB = [[4.5]];

        $resultado = $this->controller->somarMatrizes($matrizA, $matrizB);
        $this->assertEqualsWithDelta([[20.0]], $resultado, 0.0001);
    }

    public function testMultiplicacaoPorMatrizIdentidade(): void
    {
        $matrizA = [[4, 7], [2, 9]];
        $matrizIdentidade = [[1, 0], [0, 1]];

        $resultado = $this->controller->multiplicarMatrizes($matrizA, $matrizIdentidade);
        $this->assertEquals($matrizA, $resultado);
    }

    public function testDeterminanteMatriz1x1(): void
    {
        $matriz = [[-7.5]];
        $resultado = $this->controller->calcularDeterminante($matriz);
        $this->assertEqualsWithDelta(-7.5, $resultado, 0.0001);
    }

    public function testSistemaComPivoInicialNuloComTrocaDeLinhas(): void
    {
        $matrizA = [
            [0, 2, 1],
            [1, -1, 1],
            [2, 1, -1]
        ];
        $vetorB = [5, 0, 3];

        $solucao = $this->controller->resolverSistemaLinear($matrizA, $vetorB);

        $this->assertEqualsWithDelta(1.0, $solucao[0], 0.0001);
        $this->assertEqualsWithDelta(2.0, $solucao[1], 0.0001);
        $this->assertEqualsWithDelta(1.0, $solucao[2], 0.0001);
    }

    public function testSomaComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matrizA = [[1, 2, 3], [4, 5, 6]];
        $matrizB = [[1, 2], [3, 4], [5, 6]];

        $this->controller->somarMatrizes($matrizA, $matrizB);
    }

    public function testMultiplicacaoComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matrizA = [[1, 2], [3, 4]];
        $matrizB = [[1, 2], [3, 4], [5, 6]];

        $this->controller->multiplicarMatrizes($matrizA, $matrizB);
    }

    public function testDeterminanteMatrizNaoQuadradaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matriz = [[1, 2, 3], [4, 5, 6]];

        $this->controller->calcularDeterminante($matriz);
    }

    public function testDeterminanteMatrizSingularRetornaZero(): void
    {
        $matriz = [
            [1, 2, 3],
            [2, 4, 6],
            [1, 1, 1]
        ];
        $this->assertEqualsWithDelta(0.0, $this->controller->calcularDeterminante($matriz), 0.0001);
    }

    public function testSistemaImpossivelOuIndeterminadoLancaExcecao(): void
    {
        $this->expectException(DomainException::class);
        $matrizA = [
            [1, 1, 1],
            [2, 2, 2],
            [3, 3, 3]
        ];
        $vetorB = [1, 2, 3];

        $this->controller->resolverSistemaLinear($matrizA, $vetorB);
    }

    public function testMatrizComVetorBIncompativelLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matrizA = [[2, 1], [1, 3]];
        $vetorB = [8, 14, 20];

        $this->controller->resolverSistemaLinear($matrizA, $vetorB);
    }

    public function testEntradaComDadosNaoNumericosLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $matrizA = [["texto", 2], [3, 4]];
        $matrizB = [[1, 2], [3, 4]];

        $this->controller->somarMatrizes($matrizA, $matrizB);
    }

    public function testMultiplicacaoComElementosDecimaisEPrecisao(): void
    {
        $matrizA = [[1.5, 2.2], [0.5, 4.0]];
        $matrizB = [[2.0, 1.1], [3.0, 0.5]];

        $resultado = $this->controller->multiplicarMatrizes($matrizA, $matrizB);

        $this->assertEqualsWithDelta(9.6, $resultado[0][0], 0.0001);
        $this->assertEqualsWithDelta(2.75, $resultado[0][1], 0.0001);
    }
}