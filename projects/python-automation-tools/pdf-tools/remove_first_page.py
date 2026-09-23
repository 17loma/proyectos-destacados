import os
import tempfile
from PyPDF2 import PdfReader, PdfWriter


directorio = input("Introduce la ruta del directorio con los PDFs: ")

if not os.path.isdir(directorio):
    print("La ruta proporcionada no es válida.")
else:
    for archivo in os.listdir(directorio):
        ruta_pdf = os.path.join(directorio, archivo)
        if archivo.endswith(".pdf") and os.path.isfile(ruta_pdf):
            temp_path = None
            try:
                with open(ruta_pdf, "rb") as archivo_entrada:
                    lector = PdfReader(archivo_entrada)
                    escritor = PdfWriter()
                    for pagina in lector.pages[1:]:
                        escritor.add_page(pagina)
                    with tempfile.NamedTemporaryFile(
                        mode="wb", suffix=".pdf", dir=directorio, delete=False
                    ) as archivo_salida:
                        temp_path = archivo_salida.name
                        escritor.write(archivo_salida)
                os.replace(temp_path, ruta_pdf)
                temp_path = None
                print(f"Primera página eliminada: {archivo}")
            finally:
                if temp_path is not None and os.path.exists(temp_path):
                    os.remove(temp_path)
