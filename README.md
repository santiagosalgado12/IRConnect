# IRConnect

Sistema IoT para controlar dispositivos electrónicos mediante señales infrarrojas desde una aplicación web.

IRConnect permite **grabar señales de controles remotos tradicionales y emitirlas nuevamente a través de internet**, eliminando la necesidad de utilizar el control físico original. El sistema combina un **dispositivo basado en ESP32** con una **aplicación web** que funciona como interfaz de control.

Inicialmente, la idea fue planteada en el ámbito escolar. Sin embargo, es un proyecto adaptable a cualquier espacio en el que se utilicen dispositivos controlados a través de señales infrarrojas (escuelas, oficinas u otros espacios de trabajo, hogares, entre otros).

Este proyecto fue desarrollado como **proyecto final integrador** de la Tecnicatura en Informática Profesional y Personal del Instituto Técnico Río Tercero.

---

# Descripción del proyecto

IRConnect surge como solución a un problema común: la dependencia de controles remotos físicos para manejar dispositivos electrónicos como:

- Televisores
- Aires acondicionados
- Ventiladores
- Otros dispositivos que utilicen señales infrarrojas

El sistema permite **grabar señales infrarrojas** utilizando un receptor IR y almacenarlas en una base de datos. Luego, estas señales pueden ser enviadas nuevamente mediante emisores IR controlados por un microcontrolador ESP32.

El usuario interactúa con el sistema mediante una **aplicación web**, desde donde puede administrar dispositivos, emitir señales o programar automatizaciones. El sitio web fue alojado en internet, utilizando AWS como servicio y el nombre de dominio de **irconnect.site**. (El servidor ya no se encuentra activo).

El sistema cuenta con una **jerarquía de usuarios** que permite administrar el acceso y las funcionalidades disponibles dentro de la plataforma. Existen dos tipos principales de usuario: **Administrador** y **Profesor**. El **Administrador** posee control total sobre el sistema, pudiendo gestionar usuarios, dispositivos, señales infrarrojas y configuraciones generales de la plataforma. Por otro lado, el usuario **Profesor** cuenta con permisos más limitados, orientados principalmente al uso del sistema para controlar dispositivos y utilizar las señales previamente registradas, sin acceso a las funciones de administración o configuración global del sistema. Los usuarios de tipo **Administrador** serán los encargados de dar de alta a los profesores, pudiendo administrar los permisos de acceso a los distintos dispositivos vinculados.

---

# Arquitectura del sistema

El sistema está compuesto por tres partes principales:

## 1. Dispositivo IoT

Basado en una **ESP32**, encargado de:

- Conectarse a la red WiFi
- Realizar consultas periódicas al servidor (HTTP short polling) para verificar si existen acciones pendientes
- Emitir señales infrarrojas hacia los dispositivos
- Capturar señales IR desde controles remotos para su registro en el sistema

Componentes principales:

- ESP32
- Receptor IR
- Emisores IR
- LEDs de estado de conexión
- Protoboard y cables dupont 

---

## 2. Aplicación Web

La aplicación web funciona como interfaz principal del sistema.

Permite:

- Registro e inicio de sesión de usuarios
- Administración de dispositivos
- Grabación y emisión de señales IR
- Programación de eventos automáticos
- Control por voz básico (encendido y apagado de dispositivos)
- Gestión de permisos de usuarios 

---

## 3. Infraestructura

El sistema utiliza:

- Servidor web para alojar la aplicación (actualmente inactivo)
- Base de datos relacional
- Comunicación HTTP entre el servidor y la ESP32

---

# Tecnologías utilizadas

## Hardware

- ESP32
- Receptor infrarrojo
- Emisores infrarrojos
- LEDs de estado
- Protoboard
- Cables dupont 

## Software

- PHP (CodeIgniter 4)
- MySQL
- Bootstrap
- Arduino IDE
- Librerías IR para ESP32
- XAMPP (desarrollo local)
- AWS y Hostinger (hosting)
- GitHub (control de versiones) 

---

# Funcionalidades principales

- Control remoto desde navegador
- Grabación de señales IR
- Emisión de señales IR
- Administración de usuarios
- Control por voz
- Programación automática de señales
- Gestión de dispositivos

---

# Estructura del repositorio

```
IRConnect/
│
├── firmware/     # Código del microcontrolador ESP32
├── webapp/       # Aplicación web (CodeIgniter 4)
├── database/     # Scripts de base de datos
├── docs/         # Documentación del proyecto
├── demo/         # Videos de demostración del funcionamiento del sistema
│
└── README.md
```

---

# Autores

Proyecto desarrollado por:

- Catriel Garay  
- Santiago Salgado  

Instituto Técnico Río Tercero  
Tecnicatura en Informática Profesional y Personal  
Año 2025

---

# Licencia

Este proyecto fue desarrollado con fines educativos.