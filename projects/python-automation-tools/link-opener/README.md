# Apertura de enlaces

[Volver a las utilidades](../README.md)

[links.py](links.py) abre enlaces en el navegador predeterminado y los retira de la lista de pendientes.

## Requisitos y uso

Python 3, sin dependencias externas. Desde esta carpeta:

```bash
python links.py
```

1. Prepara un archivo de texto con un enlace por línea. El archivo [links.txt](links.txt) incluido está vacío.
2. Introduce la ruta de ese archivo.
3. Indica una cantidad entera positiva de enlaces que quieras abrir. Una entrada inválida termina la ejecución sin modificar la lista.

El script abre hasta esa cantidad y reescribe el archivo con los enlaces restantes. Retira las entradas aunque una página no llegue a cargar; conserva una copia de la lista si la necesitas.

Usa enlaces de confianza. Las rutas relativas se interpretan desde el directorio de ejecución.
