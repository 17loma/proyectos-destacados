import os
import shutil


ruta = input("Introduce la ruta de la carpeta: ")


if not os.path.exists(ruta):
    print("La ruta especificada no existe.")
    exit()


archivos = [archivo for archivo in os.listdir(ruta) if archivo.lower().endswith('.pdf')]


for archivo in archivos:
    nombre_carpeta = os.path.join(ruta, os.path.splitext(archivo)[0])  
    os.makedirs(nombre_carpeta, exist_ok=True) 
    ruta_archivo = os.path.join(ruta, archivo)  
    shutil.move(ruta_archivo, nombre_carpeta)  
    print(f"Archivo '{archivo}' movido a '{nombre_carpeta}'.")
