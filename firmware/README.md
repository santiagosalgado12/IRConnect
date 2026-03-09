# Firmware ESP32

Esta carpeta contiene el firmware utilizado por el dispositivo **IRConnect**, encargado de la comunicación entre el hardware infrarrojo y el servidor web.

El programa fue desarrollado para ejecutarse en una **ESP32**, permitiendo capturar señales infrarrojas desde controles remotos y emitirlas nuevamente cuando el sistema lo solicita.

---

# Funcionalidades del firmware

El firmware implementa las siguientes funciones principales:

- Conexión a una red WiFi
- Configuración inicial mediante **SmartConfig** si no existe una red conocida
- Consulta periódica al servidor para verificar si hay tareas pendientes (HTTP short polling)
- Emisión de señales infrarrojas hacia dispositivos electrónicos
- Captura de señales IR desde controles remotos
- Envío de señales capturadas al servidor para su almacenamiento

---

# Comunicación con el servidor

La comunicación con el servidor se realiza mediante **peticiones HTTP**.

El dispositivo realiza consultas periódicas (polling) al backend para verificar si existe alguna acción a ejecutar.

Las acciones principales que puede recibir el dispositivo son:

- **emitir_senal**  
  La ESP32 recibe el protocolo, código hexadecimal y cantidad de bits de una señal IR y la emite mediante uno de los emisores infrarrojos.

- **grabar_senal**  
  El dispositivo espera la recepción de una señal IR desde un control remoto y envía los datos capturados al servidor.

---

# Hardware utilizado

El dispositivo fue construido utilizando los siguientes componentes:

- ESP32
- Receptor infrarrojo
- LEDs emisores infrarrojos
- LEDs de estado
- Protoboard
- Resistencias
- Cables dupont

---

# Librerías utilizadas

El firmware utiliza las siguientes librerías principales:

- **WiFi.h**
- **HTTPClient.h**
- **ArduinoJson**
- **IRremoteESP8266**

La librería **IRremoteESP8266** permite trabajar con múltiples protocolos infrarrojos, incluyendo televisores, ventiladores y aires acondicionados.

---

# Pines utilizados

| Componente | Pin ESP32 |
|------------|-----------|
| Receptor IR | GPIO 27 |
| Emisor IR 1 | GPIO 23 |
| Emisor IR 2 | GPIO 17 |
| Emisor IR 3 | GPIO 15 |
| LED estado conexión (verde) | GPIO 12 |
| LED estado conexión (rojo) | GPIO 25 |

---

# Compilación y carga

El firmware puede cargarse utilizando **Arduino IDE**.

Pasos generales:

1. Instalar soporte para **ESP32** en Arduino IDE.
2. Instalar las librerías necesarias.
3. Seleccionar la placa **ESP32 Dev Module**.
4. Compilar y cargar el firmware en la placa.

---

# Notas

El sistema utiliza múltiples emisores infrarrojos para mejorar la cobertura y permitir controlar distintos dispositivos desde una misma ubicación.