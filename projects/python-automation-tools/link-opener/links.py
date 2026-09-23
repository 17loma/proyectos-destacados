import webbrowser
import os

ruta = input("Introduce la ruta del archivo de enlaces (ejemplo: C:\\Users\\TuUsuario\\links.txt): ").strip()

if not os.path.isfile(ruta):
    print("El archivo no existe.")
    exit()

with open(ruta, 'r') as file:
    links = file.readlines()

links = [link.strip() for link in links if link.strip()]

if not links:
    print("No hay enlaces en el archivo.")
    exit()

try:
    cantidad = int(input(f"Hay {len(links)} enlaces. ¿Cuántos quieres abrir? "))
except ValueError:
    print("Introduce una cantidad entera positiva.")
    raise SystemExit(1)

if cantidad <= 0:
    print("La cantidad debe ser mayor que cero.")
    raise SystemExit(1)

cantidad = min(cantidad, len(links))

for i in range(cantidad):
    webbrowser.open(links[i])

with open(ruta, 'w') as file:
    file.writelines(link + '\n' for link in links[cantidad:])

print(f"Se abrieron {cantidad} enlaces y se eliminaron del archivo.")
