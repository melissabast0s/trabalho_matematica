<?php

namespace Tests;

use App\Controller;
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
        $A = [[1, 2], [3, 4]];
        $B = [[5, 6], [7, 8]];
        $esperado = [[6, 8], [10, 12]];

        $resultado = $this->controller->somarMatrizes($A, $B);
        $this->assertEquals($esperado, $resultado);
    }

    public function testMultiplicacaoDeMatrizesValida(): void
    {
        $A = [[1, 2, 3], [4, 5, 6]];
        $B = [[7, 8], [9, 1], [2, 3]];
        $esperado = [[31, 19], [85, 55]];

        $resultado = $this->controller->multiplicarMatrizes($A, $B);
        $this->assertEquals($esperado, $resultado);
    }

    public function testDeterminanteMatriz3x3(): void
    {
        $A = [
            [6, 1, 1],
            [4, -2, 5],
            [2, 8, 7]
        ];

        $resultado = $this->controller->calcularDeterminante($A);
        $this->assertEqualsWithDelta(-306.0, $resultado, 0.0001);
    }

    public function testSistemaLinearPossivelDeterminado(): void
    {
        $A = [
            [2, 1, -1],
            [-3, -1, 2],
            [-2, 1, 2]
        ];
        $b = [8, -11, -3];

        $x = $this->controller->resolverSistemaLinear($A, $b);

        $this->assertEqualsWithDelta(2.0, $x[0], 0.0001);
        $this->assertEqualsWithDelta(3.0, $x[1], 0.0001);
        $this->assertEqualsWithDelta(-1.0, $x[2], 0.0001);
    }
    public function testSomaComMatrizNula(): void
    {
        $A = [[3, -2], [5, 1]];
        $Nula = [[0, 0], [0, 0]];

        $resultado = $this->controller->somarMatrizes($A, $Nula);
        $this->assertEquals($A, $resultado);
    }

    public function testSomaDeMatrizes1x1(): void
    {
        $A = [[15.5]];
        $B = [[4.5]];

        $resultado = $this->controller->somarMatrizes($A, $B);
        $this->assertEqualsWithDelta([[20.0]], $resultado, 0.0001);
    }

    public function testMultiplicacaoPorMatrizIdentidade(): void
    {
        $A = [[4, 7], [2, 9]];
        $I = [[1, 0], [0, 1]];

        $resultado = $this->controller->multiplicarMatrizes($A, $I);
        $this->assertEquals($A, $resultado);
    }

    public function testDeterminanteMatriz1x1(): void
    {
        $A = [[-7.5]];
        $resultado = $this->controller->calcularDeterminante($A);
        $this->assertEqualsWithDelta(-7.5, $resultado, 0.0001);
    }

    public function testSistemaComPivoInicialNuloComTrocaDeLinhas(): void
    {
        $A = [
            [0, 2, 1],
            [1, -1, 1],
            [2, 1, -1]
        ];
        $b = [5, 4, 1];

        $x = $this->controller->resolverSistemaLinear($A, $b);

        $this->assertEqualsWithDelta(1.0, $x[0], 0.0001);
        $this->assertEqualsWithDelta(2.0, $x[1], 0.0001);
        $this->assertEqualsWithDelta(1.0, $x[2], 0.0001);
    }

    public function testSomaComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2, 3], [4, 5, 6]];
        $B = [[1, 2], [3, 4], [5, 6]];

        $this->controller->somarMatrizes($A, $B);
    }

    public function testMultiplicacaoComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2], [3, 4]];
        $B = [[1, 2], [3, 4], [5, 6]];

        $this->controller->multiplicarMatrizes($A, $B);
    }

    public function testDeterminanteMatrizNaoQuadradaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2, 3], [4, 5, 6]];

        $this->controller->calcularDeterminante($A);
    }

    public function testDeterminanteMatrizSingularRetornaZero(): void
    {
        $A = [
            [1, 2, 3],
            [2, 4, 6],
            [1, 1, 1]
        ];
        $this->assertEqualsWithDelta(0.0, $this->controller->calcularDeterminante($A), 0.0001);
    }

    public function testSistemaImpossivelOuIndeterminadoLancaExcecao(): void
    {
        $this->expectException(DomainException::class);
        $A = [
            [1, 1, 1],
            [2, 2, 2],
            [3, 3, 3]
        ];
        $b = [1, 2, 3];

        $this->controller->resolverSistemaLinear($A, $b);
    }

    public function testMatrizComVetorBIncompativelLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[2, 1], [1, 3]];
        $b = [8, 14, 20];

        $this->controller->resolverSistemaLinear($A, $b);
    }

    public function testEntradaComDadosNaoNumericosLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [["texto", 2], [3, 4]];
        $B = [[1, 2], [3, 4]];

        $this->controller->somarMatrizes($A, $B);
    }

    public function testMultiplicacaoComElementosDecimaisEPrecisao(): void
    {
        $A = [[1.5, 2.2], [0.5, 4.0]];
        $B = [[2.0, 1.1], [3.0, 0.5]];

        $resultado = $this->controller->multiplicarMatrizes($A, $B);

        $this->assertEqualsWithDelta(9.6, $resultado[0][0], 0.0001);
        $this->assertEqualsWithDelta(2.75, $resultado[0][1], 0.0001);
    }
}