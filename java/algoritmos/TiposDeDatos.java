package algoritmos;

/**
 * TIPOS DE DATOS EN JAVA (resumen basado en el video desde el minuto 38:15)
 * =========================================================================
 *
 * En Java existen dos grandes categorías de tipos de datos:
 *
 * 1. TIPOS PRIMITIVOS
 *    Son los tipos de datos básicos del lenguaje. Se almacenan directamente
 *    en la pila (stack) de memoria y NO son objetos.
 *
 *    Tipo       | Tamaño  | Rango / Descripción
 *    -----------|---------|------------------------------------
 *    byte       | 8 bits  | -128 a 127
 *    short      | 16 bits | -32.768 a 32.767
 *    int        | 32 bits | -2.147.483.648 a 2.147.483.647  (el más usado para enteros)
 *    long       | 64 bits | número entero muy grande (se agrega 'L' al final del valor)
 *    float      | 32 bits | número decimal de precisión simple (se agrega 'f' al final)
 *    double     | 64 bits | número decimal de precisión doble  (el más usado para decimales)
 *    char       | 16 bits | un solo carácter Unicode  (se escribe entre comillas simples '')
 *    boolean    | 1 bit   | true o false
 *
 * 2. TIPOS REFERENCIA (Reference Types)
 *    Son objetos. La variable almacena la dirección (referencia) al objeto
 *    en el heap (montón) de memoria.
 *    Ejemplos: String, arrays, clases (Integer, Double, etc.), interfaces.
 *
 *    - String es el tipo referencia más común: "Hola Mundo"
 *    - Los Wrappers (Integer, Double, Boolean, …) son versiones objeto de los primitivos.
 *      Se usan cuando se necesita un objeto, por ejemplo en colecciones (ArrayList<Integer>).
 *
 * CONVERSIÓN DE TIPOS (Casting)
 *    - Implícita (widening): de tipo pequeño a grande, ocurre automáticamente.
 *        int x = 5;
 *        double d = x;   // OK, no se pierde información
 *    - Explícita (narrowing): de tipo grande a pequeño, puede perderse información.
 *        double d = 9.99;
 *        int x = (int) d;  // x = 9, se trunca la parte decimal
 */
public class TiposDeDatos {

    public static void main(String[] args) {

        // --- PRIMITIVOS ---
        byte  numeroByte   = 100;
        short numeroShort  = 30000;
        int   numeroInt    = 2_000_000;      // el guion bajo mejora legibilidad
        long  numeroLong   = 9_000_000_000L; // 'L' obligatorio para literales long grandes
        float numeroFloat  = 3.14f;          // 'f' obligatorio
        double numeroDouble = 3.141592653589793;
        char  letra        = 'A';
        boolean esVerdad   = true;

        System.out.println("=== TIPOS PRIMITIVOS ===");
        System.out.println("byte:    " + numeroByte);
        System.out.println("short:   " + numeroShort);
        System.out.println("int:     " + numeroInt);
        System.out.println("long:    " + numeroLong);
        System.out.println("float:   " + numeroFloat);
        System.out.println("double:  " + numeroDouble);
        System.out.println("char:    " + letra);
        System.out.println("boolean: " + esVerdad);

        // --- TIPOS REFERENCIA ---
        String saludo = "Hola Mundo";
        int[] arreglo = {1, 2, 3, 4, 5};

        System.out.println("\n=== TIPOS REFERENCIA ===");
        System.out.println("String:  " + saludo);
        System.out.print("Array:   ");
        for (int n : arreglo) System.out.print(n + " ");
        System.out.println();

        // --- CASTING ---
        System.out.println("\n=== CASTING ===");
        double original = 9.99;
        int   truncado  = (int) original;  // casting explícito
        System.out.println("double original: " + original);
        System.out.println("int tras casting: " + truncado);  // 9

        int   entero    = 7;
        double ampliado = entero;           // casting implícito (widening)
        System.out.println("int original: " + entero);
        System.out.println("double tras widening: " + ampliado); // 7.0

        // --- WRAPPERS ---
        System.out.println("\n=== WRAPPERS ===");
        Integer wInt    = Integer.valueOf(42);
        Double  wDouble = Double.valueOf(3.14);
        System.out.println("Integer wrapper: " + wInt);
        System.out.println("Double wrapper:  " + wDouble);
        System.out.println("Valor máximo de int: " + Integer.MAX_VALUE);
        System.out.println("Valor mínimo de int: " + Integer.MIN_VALUE);
    }
}
