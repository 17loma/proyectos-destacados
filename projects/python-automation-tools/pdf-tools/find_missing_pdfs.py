import os

ruta_archivo = input("Introduce la ruta del archivo con la lista de PDFs (uno por línea): ").strip()
ruta_directorio = input("Introduce la ruta del directorio donde buscar los PDFs: ").strip()

try:
    with open(ruta_archivo, "r") as archivo:
        lista_archivo = [linea.strip() for linea in archivo.readlines()]
except FileNotFoundError:
    print(f"Error: No se pudo encontrar el archivo en la ruta {ruta_archivo}.")
    exit()
except Exception as e:
    print(f"Error al leer el archivo: {e}")
    exit()

try:
    lista_pdf = []
    for ruta_actual, _, archivos in os.walk(ruta_directorio):
        for archivo in archivos:
            if archivo.lower().endswith('.pdf'):
                lista_pdf.append(os.path.splitext(archivo)[0])
except FileNotFoundError:
    print(f"Error: No se pudo encontrar el directorio en la ruta {ruta_directorio}.")
    exit()
except Exception as e:
    print(f"Error al acceder al directorio: {e}")
    exit()

pdfs_faltantes = [nombre for nombre in lista_archivo if nombre not in lista_pdf]

if pdfs_faltantes:
    print("PDFs faltantes:")
    for pdf in pdfs_faltantes:
        print(pdf)
else:
    print("No faltan PDFs, todos están presentes.")
