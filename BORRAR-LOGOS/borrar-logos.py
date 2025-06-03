import os
import fitz  # PyMuPDF
from PIL import Image
import io
import tempfile
import shutil

def load_image(image_path):
    return Image.open(image_path).convert("RGB")  

def resize_image(image, size):
    return image.resize(size, Image.Resampling.LANCZOS)  

def compare_images(img1, img2, tolerance=10):
    if img1.size != img2.size:
        return False
    pixels1 = img1.load()
    pixels2 = img2.load()
    for x in range(img1.size[0]):
        for y in range(img1.size[1]):
            r1, g1, b1 = pixels1[x, y]
            r2, g2, b2 = pixels2[x, y]
            if abs(r1 - r2) > tolerance or abs(g1 - g2) > tolerance or abs(b1 - b2) > tolerance:
                return False
    return True

def remove_matching_images_from_pdf(pdf_path, reference_image):
    pdf_document = fitz.open(pdf_path)
    modified = False 
    for page_num in range(len(pdf_document)):
        page = pdf_document.load_page(page_num)
        image_list = page.get_images(full=True)
        for img in image_list:
            xref = img[0]
            base_image = pdf_document.extract_image(xref)
            image_bytes = base_image["image"]
            image = Image.open(io.BytesIO(image_bytes)).convert("RGB")  
            resized_image = resize_image(image, reference_image.size)
            if compare_images(reference_image, resized_image):
                print(f"Eliminando imagen en {pdf_path}, página {page_num + 1}")
                page.delete_image(xref)
                modified = True  
    if modified:
        temp_file = tempfile.NamedTemporaryFile(delete=False, suffix=".pdf")
        temp_file.close() 
        pdf_document.save(temp_file.name)
        pdf_document.close()
        shutil.move(temp_file.name, pdf_path)
    else:
        pdf_document.close()

def main():
    reference_image_path = input("Introduce la ruta de la imagen de referencia: ")
    if not os.path.isfile(reference_image_path):
        print(f"No se encontró la imagen de referencia '{reference_image_path}'.")
        return
    reference_image = load_image(reference_image_path)
    folder_path = input("Introduce la ruta de la carpeta que contiene los PDFs: ")
    if not os.path.isdir(folder_path):
        print("La ruta proporcionada no es una carpeta válida.")
        return
    for filename in os.listdir(folder_path):
        if filename.endswith(".pdf"):
            pdf_path = os.path.join(folder_path, filename)
            print(f"Procesando {filename}...")
            remove_matching_images_from_pdf(pdf_path, reference_image)
            print(f"Finalizado {filename}.")

if __name__ == "__main__":
    main()
