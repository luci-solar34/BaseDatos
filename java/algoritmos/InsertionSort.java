package algoritmos;

import java.util.Arrays;

/**
 * INSERTION SORT – Ordenamiento por Inserción (resumen del video)
 * ================================================================
 *
 * IDEA PRINCIPAL
 *    Funciona como cuando ordenamos cartas en la mano:
 *    tomamos una carta nueva y la insertamos en la posición correcta
 *    dentro de las cartas que ya tenemos ordenadas.
 *
 *    El arreglo se divide lógicamente en dos partes:
 *      • Parte izquierda (ordenada): empieza con el primer elemento.
 *      • Parte derecha  (sin ordenar): el resto del arreglo.
 *
 *    En cada iteración se toma el primer elemento de la parte sin ordenar
 *    (llamado "clave") y se INSERTA en la posición correcta dentro de la
 *    parte ordenada, desplazando los elementos mayores hacia la derecha.
 *
 * PASOS DEL ALGORITMO (para un arreglo de n elementos)
 *    Para i = 1 hasta n-1:
 *      1. Guardar arr[i] como clave (key).
 *      2. Inicializar j = i - 1.
 *      3. Mientras j >= 0 Y arr[j] > clave:
 *           a. Desplazar arr[j] hacia la derecha: arr[j+1] = arr[j]
 *           b. Decrementar j.
 *      4. Colocar la clave en arr[j+1].
 *
 * EJEMPLO PASO A PASO con {12, 11, 13, 5, 6}
 *    Pasada i=1: clave=11  → {11, 12, 13,  5,  6}
 *    Pasada i=2: clave=13  → {11, 12, 13,  5,  6}  (sin cambio, 13 > 12)
 *    Pasada i=3: clave=5   → { 5, 11, 12, 13,  6}
 *    Pasada i=4: clave=6   → { 5,  6, 11, 12, 13}
 *    Resultado: {5, 6, 11, 12, 13}
 *
 * COMPLEJIDAD
 *    Tiempo mejor caso : O(n)   – arreglo ya ordenado (solo comparaciones).
 *    Tiempo peor caso  : O(n²)  – arreglo en orden inverso.
 *    Tiempo caso medio : O(n²).
 *    Espacio: O(1) – in-place.
 *
 * CARACTERÍSTICAS
 *    ✔ Estable: mantiene el orden relativo de elementos iguales.
 *    ✔ Eficiente para arreglos pequeños o casi ordenados.
 *    ✔ Algoritmo en-place (sin memoria extra).
 *    ✔ Simple de implementar.
 *    ✗ Ineficiente para arreglos grandes con O(n²) en el peor caso.
 *
 * COMPARACIÓN CON SELECTION SORT
 *    - Insertion Sort es ESTABLE; Selection Sort generalmente NO.
 *    - Insertion Sort mejora cuando el arreglo está casi ordenado (O(n));
 *      Selection Sort siempre hace O(n²) comparaciones.
 *    - Selection Sort hace menos intercambios en total; Insertion Sort
 *      hace desplazamientos en lugar de intercambios.
 */
public class InsertionSort {

    /**
     * Ordena un arreglo de enteros usando Insertion Sort (orden ascendente).
     *
     * @param arr el arreglo a ordenar (se modifica en el lugar)
     */
    public static void insertionSort(int[] arr) {
        int n = arr.length;

        for (int i = 1; i < n; i++) {
            int clave = arr[i]; // elemento a insertar en la posición correcta
            int j = i - 1;

            // Desplazar hacia la derecha los elementos mayores que la clave
            while (j >= 0 && arr[j] > clave) {
                arr[j + 1] = arr[j];
                j--;
            }

            // Insertar la clave en su posición correcta
            arr[j + 1] = clave;
        }
    }

    /**
     * Versión con trazado paso a paso para entender visualmente el algoritmo.
     *
     * @param arr el arreglo a ordenar
     */
    public static void insertionSortConTraza(int[] arr) {
        int n = arr.length;
        System.out.println("  Inicio: " + Arrays.toString(arr));

        for (int i = 1; i < n; i++) {
            int clave = arr[i];
            int j = i - 1;

            while (j >= 0 && arr[j] > clave) {
                arr[j + 1] = arr[j];
                j--;
            }

            arr[j + 1] = clave;

            System.out.printf("  Pasada %d: %s  (clave=%d insertada en posición %d)%n",
                    i, Arrays.toString(arr), clave, j + 1);
        }
    }

    public static void main(String[] args) {
        System.out.println("=== INSERTION SORT ===\n");

        // --- Ejemplo básico ---
        int[] arr1 = {12, 11, 13, 5, 6};
        System.out.println("Arreglo original: " + Arrays.toString(arr1));
        insertionSort(arr1);
        System.out.println("Arreglo ordenado: " + Arrays.toString(arr1));

        // --- Ejemplo con trazado ---
        System.out.println("\n--- Trazado paso a paso ---");
        int[] arr2 = {12, 11, 13, 5, 6};
        insertionSortConTraza(arr2);

        // --- Arreglo ya ordenado (mejor caso O(n)) ---
        System.out.println("\n--- Arreglo ya ordenado (mejor caso) ---");
        int[] arr3 = {1, 2, 3, 4, 5};
        System.out.println("Antes: " + Arrays.toString(arr3));
        insertionSort(arr3);
        System.out.println("Después: " + Arrays.toString(arr3));

        // --- Arreglo en orden inverso (peor caso O(n²)) ---
        System.out.println("\n--- Arreglo en orden inverso (peor caso) ---");
        int[] arr4 = {5, 4, 3, 2, 1};
        System.out.println("Antes: " + Arrays.toString(arr4));
        insertionSort(arr4);
        System.out.println("Después: " + Arrays.toString(arr4));

        // --- Arreglo con un solo elemento ---
        System.out.println("\n--- Arreglo con un elemento ---");
        int[] arr5 = {42};
        System.out.println("Antes: " + Arrays.toString(arr5));
        insertionSort(arr5);
        System.out.println("Después: " + Arrays.toString(arr5));

        // --- Arreglo con elementos repetidos ---
        System.out.println("\n--- Arreglo con elementos repetidos ---");
        int[] arr6 = {3, 1, 4, 1, 5, 9, 2, 6, 5};
        System.out.println("Antes: " + Arrays.toString(arr6));
        insertionSort(arr6);
        System.out.println("Después: " + Arrays.toString(arr6));
    }
}
