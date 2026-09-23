import time
import pyautogui

x = y = None
print("Mueve el mouse sobre el botón y presiona Ctrl+C...")
try:
    while True:
        x, y = pyautogui.position()
        print(f"Posición actual: X={x}, Y={y}", end="\r")
        time.sleep(0.1)
except KeyboardInterrupt:
    if x is not None and y is not None:
        print(f"\nCoordenadas finales: X={x}, Y={y}")
    else:
        print("\nConsulta interrumpida antes de obtener coordenadas.")