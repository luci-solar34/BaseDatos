package algoritmos;

import java.util.Arrays;

/**
 * Main – Punto de entrada que ejecuta todos los ejemplos del módulo.
 *
 * Contenido del módulo:
 *   1. TiposDeDatos      – tipos primitivos, tipos referencia, casting y wrappers en Java
 *   2. PasoDeParametros  – paso por valor con primitivos y con objetos/arrays en Java
 *   3. OrdenamientoFundamentos – swap, conceptos de complejidad y estabilidad
 *   4. SelectionSort     – algoritmo de ordenamiento por selección (O(n²))
 *   5. InsertionSort     – algoritmo de ordenamiento por inserción (O(n²) / O(n) mejor caso)
 */
public class Main {

    static final String SEPARADOR = "\n" + "=".repeat(60) + "\n";

    public static void main(String[] args) {

        System.out.println(SEPARADOR + "1. TIPOS DE DATOS" + SEPARADOR);
        TiposDeDatos.main(new String[]{});

        System.out.println(SEPARADOR + "2. PASO DE PARÁMETROS" + SEPARADOR);
        PasoDeParametros.main(new String[]{});

        System.out.println(SEPARADOR + "3. FUNDAMENTOS DE ORDENAMIENTO" + SEPARADOR);
        OrdenamientoFundamentos.main(new String[]{});

        System.out.println(SEPARADOR + "4. SELECTION SORT" + SEPARADOR);
        SelectionSort.main(new String[]{});

        System.out.println(SEPARADOR + "5. INSERTION SORT" + SEPARADOR);
        InsertionSort.main(new String[]{});

        // --- Comparación lado a lado ---
        System.out.println(SEPARADOR + "COMPARACIÓN: selectionSort vs insertionSort" + SEPARADOR);
        int[] original = {29, 10, 14, 37, 13};

        int[] paraSelection = Arrays.copyOf(original, original.length);
        int[] paraInsertion  = Arrays.copyOf(original, original.length);

        System.out.println("Arreglo original:  " + Arrays.toString(original));

        SelectionSort.selectionSort(paraSelection);
        System.out.println("Selection Sort:    " + Arrays.toString(paraSelection));

        InsertionSort.insertionSort(paraInsertion);
        System.out.println("Insertion Sort:    " + Arrays.toString(paraInsertion));

        System.out.println("\nAmbos producen el mismo resultado: "
                + Arrays.equals(paraSelection, paraInsertion));
    }
}
