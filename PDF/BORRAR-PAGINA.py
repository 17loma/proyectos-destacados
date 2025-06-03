import os
from PyPDF2 import PdfReader, PdfWriter


directorio = input("Introduce la ruta del directorio con los PDFs: ")


if not os.path.isdir(directorio):
    print("La ruta proporcionada no es válida.")
else:

    for archivo in os.listdir(directorio):
        if archivo.endswith(".pdf"):
            ruta_pdf = os.path.join(directorio, archivo)
            
        
            lector = PdfReader(ruta_pdf)
            escritor = PdfWriter()
            
            
            for pagina in lector.pages[1:]:
                escritor.add_page(pagina)
            
     
            with open(ruta_pdf, "wb") as archivo_salida:
                escritor.write(archivo_salida)
            
            print(f"Primera página eliminada: {archivo}")
