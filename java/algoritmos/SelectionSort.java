package algoritmos;

import java.util.Arrays;

/**
 * SELECTION SORT – Ordenamiento por Selección (resumen del video)
 * ================================================================
 *
 * IDEA PRINCIPAL
 *    Divide el arreglo en dos partes:
 *      • Parte izquierda (ya ordenada): crece con cada iteración.
 *      • Parte derecha  (sin ordenar) : se reduce con cada iteración.
 *
 *    En cada pasada se SELECCIONA el elemento mínimo de la parte
 *    sin ordenar y se intercambia con el primer elemento de esa parte.
 *
 * PASOS DEL ALGORITMO (para un arreglo de n elementos)
 *    Para i = 0 hasta n-2:
 *      1. Asumir que arr[i] es el mínimo (guardar su índice).
 *      2. Recorrer desde i+1 hasta n-1 buscando un elemento menor.
 *      3. Si se encontró un mínimo nuevo, actualizar su índice.
 *      4. Intercambiar arr[i] con arr[índiceMínimo].
 *
 * EJEMPLO PASO A PASO con {64, 25, 12, 22, 11}
 *    Pasada 0: mín=11 (idx 4)  → swap(0,4)  → {11, 25, 12, 22, 64}
 *    Pasada 1: mín=12 (idx 2)  → swap(1,2)  → {11, 12, 25, 22, 64}
 *    Pasada 2: mín=22 (idx 3)  → swap(2,3)  → {11, 12, 22, 25, 64}
 *    Pasada 3: mín=25 (idx 3)  → swap(3,3)  → {11, 12, 22, 25, 64}
 *    Resultado: {11, 12, 22, 25, 64}
 *
 * COMPLEJIDAD
 *    Tiempo: O(n²) en todos los casos (siempre hace n*(n-1)/2 comparaciones).
 *    Espacio: O(1) – ordena en el mismo arreglo (in-place).
 *
 * CARACTERÍSTICAS
 *    ✔ Simple de entender e implementar.
 *    ✔ Número mínimo de intercambios: O(n) (útil si el intercambio es costoso).
 *    ✗ No es estable (puede cambiar el orden relativo de elementos iguales).
 *    ✗ Ineficiente para arreglos grandes.
 *    ✗ No mejora aunque el arreglo esté casi ordenado.
 */
public class SelectionSort {

    /**
     * Ordena un arreglo de enteros usando Selection Sort (orden ascendente).
     *
     * @param arr el arreglo a ordenar (se modifica en el lugar)
     */
    public static void selectionSort(int[] arr) {
        int n = arr.length;

        for (int i = 0; i < n - 1; i++) {
            // Asumir que el elemento actual es el mínimo
            int indiceMenor = i;

            // Buscar el mínimo real en la parte sin ordenar
            for (int j = i + 1; j < n; j++) {
                if (arr[j] < arr[indiceMenor]) {
                    indiceMenor = j;
                }
            }

            // Intercambiar solo si encontramos un mínimo diferente
            if (indiceMenor != i) {
                int temp      = arr[i];
                arr[i]        = arr[indiceMenor];
                arr[indiceMenor] = temp;
            }
        }
    }

    /**
     * Versión con trazado paso a paso para entender visualmente el algoritmo.
     *
     * @param arr el arreglo a ordenar
     */
    public static void selectionSortConTraza(int[] arr) {
        int n = arr.length;
        System.out.println("  Inicio: " + Arrays.toString(arr));

        for (int i = 0; i < n - 1; i++) {
            int indiceMenor = i;

            for (int j = i + 1; j < n; j++) {
                if (arr[j] < arr[indiceMenor]) {
                    indiceMenor = j;
                }
            }

            if (indiceMenor != i) {
                int temp         = arr[i];
                arr[i]           = arr[indiceMenor];
                arr[indiceMenor] = temp;
            }

            System.out.printf("  Pasada %d: %s  (mínimo era arr[%d]=%d → movido a posición %d)%n",
                    i, Arrays.toString(arr), indiceMenor, arr[i], i);
        }
    }

    public static void main(String[] args) {
        System.out.println("=== SELECTION SORT ===\n");

        // --- Ejemplo básico ---
        int[] arr1 = {64, 25, 12, 22, 11};
        System.out.println("Arreglo original: " + Arrays.toString(arr1));
        selectionSort(arr1);
        System.out.println("Arreglo ordenado: " + Arrays.toString(arr1));

        // --- Ejemplo con trazado ---
        System.out.println("\n--- Trazado paso a paso ---");
        int[] arr2 = {64, 25, 12, 22, 11};
        selectionSortConTraza(arr2);

        // --- Arreglo ya ordenado ---
        System.out.println("\n--- Arreglo ya ordenado ---");
        int[] arr3 = {1, 2, 3, 4, 5};
        System.out.println("Antes: " + Arrays.toString(arr3));
        selectionSort(arr3);
        System.out.println("Después: " + Arrays.toString(arr3));

        // --- Arreglo en orden inverso ---
        System.out.println("\n--- Arreglo en orden inverso ---");
        int[] arr4 = {5, 4, 3, 2, 1};
        System.out.println("Antes: " + Arrays.toString(arr4));
        selectionSort(arr4);
        System.out.println("Después: " + Arrays.toString(arr4));

        // --- Arreglo con un solo elemento ---
        System.out.println("\n--- Arreglo con un elemento ---");
        int[] arr5 = {42};
        System.out.println("Antes: " + Arrays.toString(arr5));
        selectionSort(arr5);
        System.out.println("Después: " + Arrays.toString(arr5));

        // --- Arreglo con elementos repetidos ---
        System.out.println("\n--- Arreglo con elementos repetidos ---");
        int[] arr6 = {3, 1, 4, 1, 5, 9, 2, 6, 5};
        System.out.println("Antes: " + Arrays.toString(arr6));
        selectionSort(arr6);
        System.out.println("Después: " + Arrays.toString(arr6));
    }
}
