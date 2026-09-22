import requests
from openpyxl import load_workbook


workbook = load_workbook('listapdf.xlsx')
sheet = workbook.active

save_dir = input('Introduce el directorio donde quieres que se guarden los PDFs: ')


for row in sheet.rows:

    url = row[0].value
    name = row[1].value


    response = requests.get(url)


    with open(save_dir + '/' + name + '.pdf', 'wb') as f:
        f.write(response.content)
