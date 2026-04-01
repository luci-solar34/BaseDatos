# Explicación paso a paso de `SelectionSort`

Este archivo resume, en lenguaje sencillo, qué hace el código de **Selection Sort** y cómo funcionan los `for`, los corchetes `[]` y los métodos que aparecen en el ejemplo clásico con números aleatorios.

El fragmento que se explica es este:

```java
int [] numbers = new int[10];
Random random = new Random();
for (int i = 0; i < numbers.length; i++) {
    numbers[i] = random.nextInt(100);
}
System.out.println(Arrays.toString(numbers));
selectionSort(numbers);
System.out.println(Arrays.toString(numbers));
```

y los métodos auxiliares:

```java
private static void selectionSort(int[] numbers) {
    int length = numbers.length;
    for (int i = 0; i < length - 1; i++) {
        int min = numbers[i];
        int indexOfMin = i;
        for (int j = i + 1; j < length; j++) {
            if (numbers[j] < min) {
                min = numbers[j];
                indexOfMin = j;
            }
        }
        swap(numbers, i, indexOfMin);
    }
}

private static void swap(int[] numbers, int a, int b) {
    int temp = numbers[a];
    numbers[a] = numbers[b];
    numbers[b] = temp;
}
```

## Conceptos rápidos

- `[]` se usan para **arreglos (arrays)** y para acceder a una posición específica: `numbers[i]` es el elemento en la posición `i`.
- `for (inicio; condición; actualización)` repite un bloque mientras la condición sea verdadera.
- Los métodos `selectionSort` y `swap` están marcados como `static` porque se pueden llamar sin crear un objeto de la clase.

## Línea por línea

1. `int [] numbers = new int[10];`  
   Crea un arreglo de **10 enteros**. Al inicio todos valen `0`.

2. `Random random = new Random();`  
   Prepara un generador de números aleatorios.

3. `for (int i = 0; i < numbers.length; i++) { ... }`  
   - `int i = 0`: comienza en la posición 0 del arreglo.  
   - `i < numbers.length`: se ejecuta mientras `i` sea menor que el tamaño del arreglo (10).  
   - `i++`: aumenta `i` en 1 después de cada vuelta.
   - Dentro del ciclo: `numbers[i] = random.nextInt(100);` asigna un número aleatorio entre **0 y 99** a cada posición.

4. `System.out.println(Arrays.toString(numbers));`  
   Muestra el arreglo en formato `[a, b, c, ...]` **antes** de ordenar.

5. `selectionSort(numbers);`  
   Llama al método que ordena el arreglo **en el mismo arreglo** (no crea otro).

6. `System.out.println(Arrays.toString(numbers));`  
   Muestra el arreglo **después** de ordenar.

### Dentro de `selectionSort`

7. `int length = numbers.length;`  
   Guarda el tamaño del arreglo para no calcularlo en cada iteración.

8. `for (int i = 0; i < length - 1; i++) { ... }`  
   Recorre todas las posiciones salvo la última (cuando lo demás ya está ordenado, el último queda en su sitio).

9. `int min = numbers[i];` y `int indexOfMin = i;`  
   Supone que el mínimo está en la posición actual (`i`) y guarda su valor e índice.

10. Segundo `for (int j = i + 1; j < length; j++) { ... }`  
    Busca en la **parte sin ordenar** (desde `i + 1` hasta el final) un valor más pequeño.

11. `if (numbers[j] < min) { ... }`  
    Si encuentra un número menor, actualiza `min` y `indexOfMin` para recordar dónde está el nuevo mínimo.

12. `swap(numbers, i, indexOfMin);`  
    Intercambia el valor mínimo encontrado con el que está en la posición `i`. Así, la parte izquierda del arreglo va quedando ordenada.

### Dentro de `swap`

13. `int temp = numbers[a];`  
    Guarda temporalmente el valor en la posición `a`.

14. `numbers[a] = numbers[b];`  
    Copia el valor de la posición `b` en la posición `a`.

15. `numbers[b] = temp;`  
    Coloca el valor original de `a` (guardado en `temp`) en la posición `b`. Esto completa el intercambio.

## Qué hace el algoritmo en resumen

1. Llena el arreglo con 10 números aleatorios entre 0 y 99.
2. Imprime el arreglo desordenado.
3. Usa **Selection Sort**: en cada pasada **selecciona** el número más pequeño de la parte sin ordenar y lo coloca al inicio.
4. Imprime el arreglo ya ordenado de menor a mayor.

Con esto puedes seguir el flujo completo y entender qué significa cada parte de la sintaxis.
