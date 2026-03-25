package algoritmos;

import java.util.Arrays;

/**
 * FUNDAMENTOS BÁSICOS DE ORDENAMIENTO (resumen del video)
 * ========================================================
 *
 * ¿Qué es ordenar?
 *    Ordenar (sorting) es el proceso de reorganizar un conjunto de datos
 *    en un orden específico (generalmente ascendente o descendente).
 *
 * ¿Por qué es importante?
 *    - Facilita la búsqueda de elementos (búsqueda binaria requiere datos ordenados).
 *    - Mejora la presentación de información al usuario.
 *    - Es la base de muchos algoritmos más complejos.
 *
 * CONCEPTOS CLAVE
 *    - Comparación: los algoritmos de ordenamiento comparan pares de elementos
 *      para decidir cuál va primero.
 *    - Intercambio (swap): operación fundamental que intercambia dos elementos
 *      de posición dentro del arreglo.
 *    - Iteración: la mayoría de algoritmos recorre el arreglo múltiples veces
 *      (con bucles anidados en los algoritmos O(n²)).
 *
 * COMPLEJIDAD TEMPORAL (Big-O notation)
 *    Mide cuánto tiempo tarda el algoritmo según el tamaño n de la entrada.
 *
 *    Notación | Significado         | Ejemplo de algoritmo
 *    ---------|---------------------|----------------------------
 *    O(1)     | constante           | acceso a elemento de array
 *    O(log n) | logarítmico         | búsqueda binaria
 *    O(n)     | lineal              | búsqueda lineal
 *    O(n²)    | cuadrático          | Selection Sort, Insertion Sort, Bubble Sort
 *    O(n log n)| quasi-lineal       | Merge Sort, Quick Sort (promedio)
 *
 * ALGORITMOS ELEMENTALES (O(n²))
 *    Adecuados para arreglos pequeños o casi ordenados.
 *    - Bubble Sort   : compara e intercambia vecinos adyacentes repetidamente.
 *    - Selection Sort: busca el mínimo y lo ubica en su posición final.
 *    - Insertion Sort: toma cada elemento e inserta en su lugar correcto.
 *
 * ESTABILIDAD
 *    Un algoritmo es ESTABLE si mantiene el orden relativo de elementos iguales.
 *    - Insertion Sort: ESTABLE
 *    - Selection Sort: NO estable (en su versión clásica)
 *
 * ORDENAMIENTO EN-PLACE vs EXTRA ESPACIO
 *    - En-place: ordena el arreglo sin usar memoria adicional significativa.
 *      Insertion Sort y Selection Sort son en-place.
 *    - Merge Sort necesita O(n) memoria extra.
 */
public class OrdenamientoFundamentos {

    /**
     * Intercambia dos elementos en un arreglo.
     * Esta operación es fundamental en todos los algoritmos de ordenamiento.
     *
     * @param arr  el arreglo
     * @param i    índice del primer elemento
     * @param j    índice del segundo elemento
     */
    public static void swap(int[] arr, int i, int j) {
        int temp = arr[i];
        arr[i] = arr[j];
        arr[j] = temp;
    }

    /**
     * Muestra el estado del arreglo con un mensaje descriptivo.
     *
     * @param mensaje descripción del estado
     * @param arr     arreglo a mostrar
     */
    public static void mostrar(String mensaje, int[] arr) {
        System.out.println(mensaje + ": " + Arrays.toString(arr));
    }

    public static void main(String[] args) {
        int[] arreglo = {64, 25, 12, 22, 11};

        System.out.println("=== FUNDAMENTOS DE ORDENAMIENTO ===\n");
        mostrar("Arreglo original      ", arreglo);

        // Demostración del swap
        System.out.println("\nDemostrando swap de índice 0 y 4:");
        mostrar("  Antes del swap      ", arreglo);
        swap(arreglo, 0, 4);
        mostrar("  Después del swap    ", arreglo);

        // Restaurar
        swap(arreglo, 0, 4);
        mostrar("  Restaurado          ", arreglo);

        System.out.println("\nEn los siguientes archivos encontrarás:");
        System.out.println("  -> SelectionSort.java : implementación y explicación de Selection Sort");
        System.out.println("  -> InsertionSort.java : implementación y explicación de Insertion Sort");
    }
}
