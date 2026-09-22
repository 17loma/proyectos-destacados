# Herramientas para imágenes y PDF

[Volver a las utilidades](../README.md)

Los comandos siguientes se ejecutan desde esta carpeta y requieren Python 3.

## Eliminar imágenes coincidentes en PDF

[remove_matching_images.py](remove_matching_images.py) compara las imágenes incrustadas de un PDF con una imagen de referencia. Usa PyMuPDF y Pillow.

```bash
python -m pip install PyMuPDF Pillow
python remove_matching_images.py
```

Introduce la ruta de la imagen de referencia y la carpeta de los PDF. Procesa archivos terminados en `.pdf`, sin recorrer subcarpetas. Redimensiona cada imagen al tamaño de la referencia y compara los canales RGB con una tolerancia de 10.

Elimina las imágenes coincidentes y reemplaza los PDF modificados. **Trabaja sobre copias:** no crea copias de seguridad y el criterio de comparación puede producir coincidencias no deseadas.

Utiliza la herramienta con documentos propios o para cuya modificación tengas autorización.

## Inspeccionar imágenes

[inspect_image.py](inspect_image.py) muestra las dimensiones y el tamaño en bytes de las imágenes que indiques. Requiere Pillow.

```bash
python -m pip install Pillow
python inspect_image.py
```

Introduce una ruta por consulta; pulsa Enter sin escribir nada para terminar. Su mensaje sobre PyAutoGUI se basa en un umbral de 1024 bytes, no en una prueba de compatibilidad ni de integridad completa.

Las rutas relativas se interpretan desde el directorio de ejecución.
