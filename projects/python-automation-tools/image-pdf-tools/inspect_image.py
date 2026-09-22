from PIL import Image
import os

def verificar_imagen(ruta):
    try:
        img = Image.open(ruta)
        print(f"✔ {ruta}: {img.size} píxeles, {os.path.getsize(ruta)} bytes")
        if os.path.getsize(ruta) < 1024: 
            print("Archivo demasiado pequeño")
        else:
            print("Válida para PyAutoGUI")
    except Exception as e:
        print(f"Error: {e}")

while True:
    ruta_imagen = input("Introduce la ruta de la imagen (o pulsa Enter para salir): ").strip()
    if not ruta_imagen:
        break
    verificar_imagen(ruta_imagen)
