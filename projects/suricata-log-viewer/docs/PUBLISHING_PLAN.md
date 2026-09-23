# Plan de publicación de Suricata Log Viewer

[Volver al proyecto](../README.md)

El repositorio contiene la documentación y la [presentación de la defensa](SuricataLogViewer_Defensa_Alvaro_Lopez_25.pptx). El código fuente original todavía no está incorporado. Esta lista recoge el trabajo pendiente cuando se recupere.

## Recuperar e inventariar

- [ ] Localizar la versión definitiva del código del proyecto final de ASIR.
- [ ] Identificar la aplicación Laravel y sus archivos de configuración.
- [ ] Identificar los scripts Python de procesamiento e inserción.
- [ ] Identificar los archivos Docker y la configuración de Suricata.
- [ ] Inventariar los archivos y distinguir código, configuración, datos y recursos.

## Preparar una copia publicable

- [ ] Buscar credenciales en el código y en la configuración.
- [ ] Revisar IPs privadas y referencias al entorno de laboratorio.
- [ ] Retirar los datos de prueba innecesarios de la copia que se publicará.
- [ ] Crear un `.env.example` con variables documentadas y valores de ejemplo sin secretos.
- [ ] Crear un `.gitignore` específico para los componentes seleccionados.
- [ ] Separar la configuración del código.
- [ ] Revisar las dependencias y los requisitos de cada componente.

## Documentar y comprobar

- [ ] Preparar instrucciones de instalación, configuración y uso.
- [ ] Seleccionar capturas y revisar que no muestren información sensible.
- [ ] Contrastar y completar la [arquitectura documentada](architecture.md) con el código recuperado.
- [ ] Probar la instalación desde cero en un entorno limpio.
- [ ] Revisar licencias y permisos de distribución.
- [ ] Decidir qué componentes pueden publicarse y si conviene un repositorio independiente.
- [ ] Comprobar que la copia final no contiene datos personales.
- [ ] Comprobar que la copia final no contiene secretos.

Las casillas permanecen pendientes: esta documentación no confirma que el código original esté disponible ni que estas comprobaciones se hayan realizado.
