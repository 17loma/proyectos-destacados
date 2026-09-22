# Suricata Log Viewer

Proyecto final de Administración de Sistemas Informáticos en Red (ASIR), desarrollado por Álvaro López para procesar y consultar eventos de Suricata mediante una aplicación web.

[Volver al portfolio](../../README.md)

## Objetivo

Facilitar la consulta de eventos de seguridad, desde su generación en Suricata hasta su almacenamiento y visualización. El proyecto integra tratamiento de logs, control de acceso y avisos sobre eventos críticos en un entorno de laboratorio.

## Arquitectura

```text
Suricata → EVE JSON → Python → MySQL → Laravel
```

1. **Suricata**, ejecutado mediante Docker, genera eventos en formato EVE JSON.
2. **Python** procesa los logs y prepara los eventos para su inserción en la base de datos.
3. **MySQL** almacena los eventos procesados.
4. **Laravel** proporciona la aplicación web para consultar y filtrar los eventos, además de gestionar usuarios, roles y redes.

## Tecnologías

| Componente | Tecnología |
| --- | --- |
| Detección y generación de eventos | Suricata |
| Ejecución de Suricata | Docker |
| Formato de logs | EVE JSON |
| Procesamiento e inserción de eventos | Python |
| Almacenamiento | MySQL |
| Aplicación web | PHP y Laravel |
| Programación de tareas | cron |
| Entorno y servidor web | Linux y Apache |

## Funcionalidades

- Gestión de usuarios y roles.
- Asociación de redes a usuarios.
- Visualización y filtrado de eventos.
- Paginación de resultados.
- Alertas de eventos críticos.
- Notificaciones.
- Exportación a PDF.

## Automatización

El proyecto utiliza cron para programar tareas en el entorno de laboratorio. Esta automatización forma parte del flujo de trabajo que conecta la generación de eventos con su procesamiento y consulta.

## Seguridad

La aplicación incorpora autenticación, autorización y validación de entradas. La gestión de usuarios y roles permite controlar el acceso a sus funcionalidades.

Estas medidas se desarrollaron y probaron dentro del alcance del proyecto académico y de su entorno de laboratorio.

## Entorno de despliegue

El despliegue se realizó en un entorno de laboratorio Linux con Apache como servidor web. Suricata se ejecutó mediante Docker, Python se utilizó para el procesamiento de logs y la aplicación Laravel se conectó a MySQL para consultar los eventos almacenados.

Esta ficha describe la arquitectura y el trabajo realizado; la configuración original del laboratorio necesita una revisión específica antes de su publicación.

## Pruebas

Durante el desarrollo se realizaron pruebas funcionales, de seguridad y de rendimiento:

- **Funcionales:** comprobación del flujo de eventos y de las funciones de consulta y gestión.
- **Seguridad:** revisión de la autenticación, la autorización y la validación.
- **Rendimiento:** evaluación del procesamiento y la consulta de eventos.

Esta documentación no incluye métricas de rendimiento ni resultados detallados de las pruebas.

## Estado actual

Suricata Log Viewer fue desarrollado como proyecto final de ASIR. Esta documentación presenta su alcance y las tecnologías utilizadas.

El código completo no está publicado en este repositorio. La versión original contiene configuraciones y elementos propios del entorno de laboratorio, por lo que necesita una limpieza específica antes de publicar todo el código.
