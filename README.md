 Motor de Álgebra Linear Computacional 

Aplicação web desenvolvida em PHP que implementa e valida algoritmos fundamentais de Álgebra Linear (operações matriciais e resolução de sistemas lineares) com suporte a testes unitários automatizados via PHPUnit.


Instruções de Instalação

1. Abra o terminal na pasta raiz do projeto (trabalho_física).
2. Instale as dependências do projeto (incluindo o PHPUnit): compositor install

Lista de Algoritmos Implementados

Adição de Matrizes ($A + B$): Soma elemento a elemento para matrizes de mesma dimensão.
Subtração de Matrizes ($A - B$): Subtração elemento a elemento para matrizes de mesma dimensão.
Multiplicação de Matrizes ($A \times B$): Produto matricial respeitando a condição $Col(A) = Lin(B)$.
Transposição de Matriz ($A^T$): Troca de linhas por colunas.
Determinante $\det(A)$: Cálculo recursivo via Teorema de Laplace (expansão por cofatores).
Resolução de Sistemas Lineares ($A \cdot x = b$): Algoritmo de Eliminação de Gauss com Pivoteamento Parcial (com suporte a troca de linhas para pivô nulo).

