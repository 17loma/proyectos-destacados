# Organización de archivos

[Volver a las utilidades](../README.md)

[organize_pdfs.py](organize_pdfs.py) crea una subcarpeta con el nombre de cada PDF, sin su extensión, y mueve el archivo a ella.

## Requisitos y uso

Python 3, sin dependencias externas. Desde esta carpeta:

```bash
python organize_pdfs.py
```

Introduce la ruta de la carpeta que quieras organizar. Selecciona los archivos terminados en `.pdf`, sin distinguir mayúsculas y minúsculas, y no recorre subcarpetas. Las rutas relativas se interpretan desde el directorio de ejecución.

**Mueve los originales; no los copia.** Los conflictos con archivos ya existentes pueden interrumpir el proceso. Trabaja sobre una copia si necesitas conservar la organización original.
