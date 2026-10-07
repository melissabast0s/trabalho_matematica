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

    public function testID01_SomaDeMatrizesValidas(): void
    {
        $A = [[1, 2], [3, 4]];
        $B = [[5, 6], [7, 8]];
        $esperado = [[6, 8], [10, 12]];

        $resultado = $this->controller->somarMatrizes($A, $B);
        $this->assertEquals($esperado, $resultado);
    }

    public function testID02_SomaComMatrizNula(): void
    {
        $A = [[3, -2], [5, 1]];
        $Nula = [[0, 0], [0, 0]];

        $resultado = $this->controller->somarMatrizes($A, $Nula);
        $this->assertEquals($A, $resultado);
    }

    public function testID03_SomaDeMatrizes1x1(): void
    {
        $A = [[15.5]];
        $B = [[4.5]];

        $resultado = $this->controller->somarMatrizes($A, $B);
        $this->assertEqualsWithDelta([[20.0]], $resultado, 0.0001);
    }

    public function testID04_SomaComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2, 3], [4, 5, 6]]; // 2x3
        $B = [[1, 2], [3, 4], [5, 6]]; // 3x2

        $this->controller->somarMatrizes($A, $B);
    }

    public function testID05_MultiplicacaoValida(): void
    {
        $A = [[1, 2, 3], [4, 5, 6]]; // 2x3
        $B = [[7, 8], [9, 1], [2, 3]]; // 3x2
        $esperado = [[31, 19], [85, 55]]; // 2x2

        $resultado = $this->controller->multiplicarMatrizes($A, $B);
        $this->assertEquals($esperado, $resultado);
    }

    public function testID06_MultiplicacaoPorMatrizIdentidade(): void
    {
        $A = [[4, 7], [2, 9]];
        $I = [[1, 0], [0, 1]];

        $resultado = $this->controller->multiplicarMatrizes($A, $I);
        $this->assertEquals($A, $resultado);
    }

    public function testID07_MultiplicacaoComDimensoesIncompativeisLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2], [3, 4]]; // 2x2
        $B = [[1, 2], [3, 4], [5, 6]]; // 3x2

        $this->controller->multiplicarMatrizes($A, $B);
    }

    public function testID08_MultiplicacaoComElementosDecimais(): void
    {
        $A = [[1.5, 2.2], [0.5, 4.0]];
        $B = [[2.0, 1.1], [3.0, 0.5]];

        $resultado = $this->controller->multiplicarMatrizes($A, $B);

        $this->assertEqualsWithDelta(9.6, $resultado[0][0], 0.0001);
        $this->assertEqualsWithDelta(2.75, $resultado[0][1], 0.0001);
    }

    public function testID09_DeterminanteMatriz2x2E3x3(): void
    {
        $A2 = [[4, 6], [3, 8]];
        $this->assertEqualsWithDelta(14.0, $this->controller->calcularDeterminante($A2), 0.0001);

        $A3 = [
            [6, 1, 1],
            [4, -2, 5],
            [2, 8, 7]
        ];
        $this->assertEqualsWithDelta(-306.0, $this->controller->calcularDeterminante($A3), 0.0001);
    }

    public function testID10_DeterminanteMatriz1x1(): void
    {
        $A = [[-7.5]];
        $this->assertEqualsWithDelta(-7.5, $this->controller->calcularDeterminante($A), 0.0001);
    }

    public function testID11_DeterminanteMatrizSingular(): void
    {
        $A = [
            [1, 2, 3],
            [2, 4, 6],
            [1, 1, 1]
        ];
        $this->assertEqualsWithDelta(0.0, $this->controller->calcularDeterminante($A), 0.0001);
    }

    public function testID12_DeterminanteMatrizNaoQuadradaLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[1, 2, 3], [4, 5, 6]];

        $this->controller->calcularDeterminante($A);
    }

    public function testID13_SistemaPossivelDeterminadoSPD(): void
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

    public function testID14_SistemaComPivoInicialNuloComTrocaDeLinhas(): void
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

    public function testID15_SistemaImpossivelOuIndeterminadoMatrizSingular(): void
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

    public function testID16_MatrizCoeficientesIncompativelComVetorBLancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [[2, 1], [1, 3]];
        $b = [8, 14, 20];

        $this->controller->resolverSistemaLinear($A, $b);
    }

    public function testID17_18_19_20_ValidacoesEntradaInvalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $A = [["texto", 2], [3, 4]];
        $B = [[1, 2], [3, 4]];

        $this->controller->somarMatrizes($A, $B);
    }
}