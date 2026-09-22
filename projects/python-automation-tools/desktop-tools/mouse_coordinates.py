import pyautogui
print("Mueve el mouse sobre el botón y presiona Ctrl+C...")
try:
    while True:
        x, y = pyautogui.position()
        print(f"Posición actual: X={x}, Y={y}", end="\r")
except KeyboardInterrupt:
    print(f"\nCoordenadas finales: X={x}, Y={y}")