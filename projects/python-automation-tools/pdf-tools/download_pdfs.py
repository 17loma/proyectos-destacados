import os
import tempfile
import requests
from openpyxl import load_workbook


try:
    workbook = load_workbook('listapdf.xlsx')
except FileNotFoundError:
    print('No se encontró listapdf.xlsx en el directorio de ejecución.')
    raise SystemExit(1)
sheet = workbook.active

save_dir = input('Introduce el directorio donde quieres que se guarden los PDFs: ').strip()
if not os.path.isdir(save_dir):
    print('El directorio de destino no existe.')
    raise SystemExit(1)
save_dir = os.path.realpath(save_dir)
reserved_names = {'CON', 'PRN', 'AUX', 'NUL', 'CONIN$', 'CONOUT$'}
reserved_names.update(prefix + number for prefix in ('COM', 'LPT') for number in '123456789¹²³')

for row_number, row in enumerate(sheet.rows, start=1):
    if not any(cell.value is not None for cell in row):
        continue
    if len(row) < 2:
        print(f'Fila {row_number}: faltan la URL o el nombre.')
        continue
    url = row[0].value
    name = row[1].value
    if not isinstance(url, str) or not url.strip() or not isinstance(name, str) or not name:
        print(f'Fila {row_number}: la URL y el nombre deben ser textos no vacíos.')
        continue
    if (
        name != name.strip()
        or name.endswith('.')
        or any(character in '<>:"/\\|?*' or ord(character) < 32 for character in name)
        or name.split('.')[0].rstrip(' ').upper() in reserved_names
        or len((name + '.pdf').encode('utf-8')) > 255
    ):
        print(f'Fila {row_number}: el nombre no es un nombre de archivo válido.')
        continue

    destination = os.path.join(save_dir, name + '.pdf')
    try:
        inside_destination = os.path.normcase(
            os.path.commonpath([save_dir, os.path.realpath(destination)])
        ) == os.path.normcase(save_dir)
    except ValueError:
        inside_destination = False
    if not inside_destination:
        print(f'Fila {row_number}: el archivo quedaría fuera del directorio de destino.')
        continue

    temp_path = None
    try:
        response = requests.get(url.strip(), timeout=30)
        response.raise_for_status()
        with tempfile.NamedTemporaryFile(mode='wb', suffix='.pdf', dir=save_dir, delete=False) as temp_file:
            temp_path = temp_file.name
            temp_file.write(response.content)
        os.replace(temp_path, destination)
        temp_path = None
    except (requests.RequestException, OSError) as error:
        print(f'Fila {row_number}: no se pudo descargar el documento ({type(error).__name__}).')
    finally:
        if temp_path is not None and os.path.exists(temp_path):
            os.remove(temp_path)

workbook.close()
