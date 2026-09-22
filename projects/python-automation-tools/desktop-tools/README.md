# Herramientas de escritorio

[Volver a las utilidades](../README.md)

[mouse_coordinates.py](mouse_coordinates.py) muestra las coordenadas X e Y del cursor para preparar automatizaciones de escritorio.

## Requisitos y uso

Python 3, PyAutoGUI y acceso a una sesión gráfica. Desde esta carpeta:

```bash
python -m pip install pyautogui
python mouse_coordinates.py
```

Mueve el cursor hasta la posición que quieras consultar. Pulsa Ctrl+C en la terminal para detener el script y mostrar las últimas coordenadas leídas.

El bucle de consulta no incluye una pausa y puede mantener un consumo de CPU elevado mientras está activo.
