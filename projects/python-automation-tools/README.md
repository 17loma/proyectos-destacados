# Python Automation Tools

Scripts independientes para trabajar con enlaces, documentos, imágenes y archivos.
Se agrupan por función y complementan el [proyecto principal del portfolio](../../README.md).

## Herramientas

| Herramienta | Función | Dependencias |
| --- | --- | --- |
| [Apertura de enlaces](link-opener/) | Abrir enlaces de una lista y guardar los pendientes. | Biblioteca estándar |
| [Herramientas PDF](pdf-tools/) | Buscar PDF faltantes, eliminar la primera página y descargar documentos desde Excel. | PyPDF2, requests, openpyxl |
| [Imágenes y PDF](image-pdf-tools/) | Inspeccionar imágenes y eliminar imágenes coincidentes dentro de PDF. | Pillow, PyMuPDF |
| [Organización de archivos](file-utilities/) | Mover cada PDF a una carpeta con su mismo nombre. | Biblioteca estándar |
| [Herramientas de escritorio](desktop-tools/) | Consultar las coordenadas del cursor. | PyAutoGUI |

## Instalación

Requieren Python 3. Desde esta carpeta, instala las dependencias:

```bash
python -m pip install -r requirements.txt
```

[requirements.txt](requirements.txt) contiene los paquetes externos utilizados, sin versiones fijadas.

## Uso

Cada carpeta contiene un README con instrucciones, archivos de entrada y precauciones específicas. Las rutas relativas se interpretan desde el directorio en el que ejecutes el comando.

## Nota

Algunas herramientas modifican o mueven archivos. Trabaja sobre copias para conservar los originales.
