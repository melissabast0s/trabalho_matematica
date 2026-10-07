<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller;

$controller = new Controller();

$resultadoMatriz = null;
$resultadoSistema = null;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    try {
        if ($acao === 'operacao_matriz') {
            $op = $_POST['operacao'] ?? 'soma';
            $matrizA = json_decode($_POST['matriz_a'] ?? '[]', true);
            $matrizB = json_decode($_POST['matriz_b'] ?? '[]', true);

            if (!is_array($matrizA)) throw new InvalidArgumentException("Matriz A com formato invalido.");

            switch ($op) {
                case 'soma':
                    $resultadoMatriz = $controller->somarMatrizes($matrizA, $matrizB);
                    break;
                case 'subtração':
                    $resultadoMatriz = $controller->subtrairMatrizes($matrizA, $matrizB);
                    break;
                case 'multiplicacao':
                    $resultadoMatriz = $controller->multiplicarMatrizes($matrizA, $matrizB);
                    break;
                case 'transposta':
                    $resultadoMatriz = $controller->transporMatriz($matrizA);
                    break;
                case 'determinante':
                    $resultadoMatriz = $controller->calcularDeterminante($matrizA);
                    break;
            }
        } elseif ($acao === 'sistema_linear') {
            $matrizA = json_decode($_POST['matriz_a'] ?? '[]', true);
            $vetorB = json_decode($_POST['vetor_b'] ?? '[]', true);

            $resultadoSistema = $controller->resolverSistemaLinear($matrizA, $vetorB);
        }
    } catch (InvalidArgumentException | DomainException | Exception $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/View/index.php';