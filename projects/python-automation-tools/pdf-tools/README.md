# Herramientas PDF

[Volver a las utilidades](../README.md)

Tres scripts independientes. Los comandos siguientes se ejecutan desde esta carpeta.

## Buscar documentos faltantes

[find_missing_pdfs.py](find_missing_pdfs.py) compara una lista de nombres sin extensión con los PDF de un directorio y sus subcarpetas. Muestra los nombres que no encuentra.

Requiere Python 3, sin dependencias externas.

```bash
python find_missing_pdfs.py
```

Introduce la ruta del archivo de texto —un nombre por línea, sin `.pdf`— y la carpeta donde buscar. La comparación de nombres distingue mayúsculas y minúsculas. Si el directorio de búsqueda no existe, termina con un mensaje antes de comparar.

## Eliminar la primera página

[remove_first_page.py](remove_first_page.py) elimina la primera página de cada archivo terminado en `.pdf` dentro de la carpeta indicada, sin recorrer subcarpetas.

```bash
python -m pip install PyPDF2
python remove_first_page.py
```

**Sustituye los PDF originales tras completar la escritura en un temporal de la misma carpeta.** Si esa escritura falla, conserva el original. Sigue siendo una operación que modifica documentos: trabaja sobre copias si necesitas conservarlos.

## Descargar PDF desde una hoja de cálculo

[download_pdfs.py](download_pdfs.py) lee `listapdf.xlsx` desde el directorio de ejecución. Ese archivo es una entrada externa y no está incluido en el repositorio.

Prepara la hoja activa sin cabecera: URL en la primera columna y nombre de destino sin extensión en la segunda. Si ejecutas estos comandos desde esta carpeta, coloca aquí la hoja de cálculo:

```bash
python -m pip install requests openpyxl
python download_pdfs.py
```

Introduce un directorio de destino que ya exista. El script omite filas vacías, rechaza campos incompletos y nombres que contengan rutas o caracteres no portables, y comprueba que el destino resuelto permanezca dentro de esa carpeta. Cada petición tiene un timeout de 30 segundos y debe superar la comprobación de estado HTTP antes de guardarse.

Guarda primero en un temporal y sustituye el destino al terminar; puede reemplazar archivos con el mismo nombre después de una descarga correcta. Ante errores de petición o escritura informa de la fila y continúa. No valida completamente el contenido PDF; revisa los resultados.

Las rutas relativas se resuelven desde el directorio de ejecución.
