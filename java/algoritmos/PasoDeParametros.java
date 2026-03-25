package algoritmos;

/**
 * PASO DE PARÁMETROS EN JAVA (resumen del video)
 * ===============================================
 *
 * Java usa SIEMPRE paso por valor (pass-by-value). Sin embargo, el
 * comportamiento varía según si el parámetro es un tipo primitivo o
 * un tipo referencia:
 *
 * 1. PASO POR VALOR con PRIMITIVOS
 *    Se copia el valor del argumento en el parámetro del método.
 *    Cualquier modificación dentro del método NO afecta la variable original.
 *
 *    Ejemplo:
 *        int x = 5;
 *        duplicar(x);       // x sigue siendo 5 después de la llamada
 *
 * 2. PASO POR VALOR con REFERENCIAS (objetos / arrays)
 *    Se copia la referencia (dirección de memoria) del objeto.
 *    - Si el método modifica el CONTENIDO del objeto (p.ej., cambia
 *      elementos de un array), el cambio SÍ se ve fuera del método
 *      porque ambas variables apuntan al mismo objeto.
 *    - Si el método reasigna la variable a un nuevo objeto, el cambio
 *      NO se ve fuera del método porque solo se modifica la copia local
 *      de la referencia.
 *
 *    Ejemplo array:
 *        int[] arr = {1, 2, 3};
 *        triplicarPrimero(arr);  // arr[0] ahora es 3 (modificación del contenido)
 *
 * RESUMEN: Java NO tiene paso por referencia "puro" como C++.
 *          Siempre se pasa una copia: de valor (primitivos) o de referencia (objetos).
 *
 * PARÁMETROS EN MÉTODOS
 *    - Un método puede tener cero o más parámetros.
 *    - Se declaran como: tipo nombre dentro de los paréntesis.
 *    - Varargs (...): permite pasar cantidad variable de argumentos del mismo tipo.
 *        public static int suma(int... numeros) { ... }
 */
public class PasoDeParametros {

    // --- Ejemplo 1: primitivo (no se modifica el original) ---
    static void duplicar(int numero) {
        numero = numero * 2;
        System.out.println("  Dentro del método: " + numero);
    }

    // --- Ejemplo 2: array (sí se modifica el contenido original) ---
    static void triplicarPrimero(int[] arreglo) {
        arreglo[0] = arreglo[0] * 3;
    }

    // --- Ejemplo 3: reasignación de referencia (NO afecta el original) ---
    static void reasignarArray(int[] arreglo) {
        arreglo = new int[]{99, 99, 99}; // solo cambia la copia local de la referencia
    }

    // --- Ejemplo 4: varargs ---
    static int sumarTodo(int... numeros) {
        int total = 0;
        for (int n : numeros) total += n;
        return total;
    }

    // --- Ejemplo 5: retornar valor modificado (patrón recomendado para primitivos) ---
    static int duplicarYRetornar(int numero) {
        return numero * 2;
    }

    public static void main(String[] args) {

        // Ejemplo 1: primitivo
        System.out.println("=== PASO CON PRIMITIVO ===");
        int x = 5;
        System.out.println("Antes de llamar: x = " + x);
        duplicar(x);
        System.out.println("Después de llamar: x = " + x); // sigue siendo 5

        // Ejemplo 2: array - modificación de contenido
        System.out.println("\n=== PASO CON ARRAY (modifica contenido) ===");
        int[] arr = {1, 2, 3};
        System.out.println("Antes: arr[0] = " + arr[0]);
        triplicarPrimero(arr);
        System.out.println("Después: arr[0] = " + arr[0]); // ahora es 3

        // Ejemplo 3: array - reasignación de referencia
        System.out.println("\n=== PASO CON ARRAY (reasignación) ===");
        int[] arr2 = {10, 20, 30};
        System.out.println("Antes: arr2[0] = " + arr2[0]);
        reasignarArray(arr2);
        System.out.println("Después: arr2[0] = " + arr2[0]); // sigue siendo 10

        // Ejemplo 4: varargs
        System.out.println("\n=== VARARGS ===");
        System.out.println("suma(1,2,3) = "    + sumarTodo(1, 2, 3));
        System.out.println("suma(10,20) = "     + sumarTodo(10, 20));
        System.out.println("suma(5,5,5,5,5) = " + sumarTodo(5, 5, 5, 5, 5));

        // Ejemplo 5: patrón correcto para "modificar" primitivo
        System.out.println("\n=== RETORNAR VALOR ===");
        int y = 7;
        y = duplicarYRetornar(y);
        System.out.println("y duplicado: " + y); // 14
    }
}
