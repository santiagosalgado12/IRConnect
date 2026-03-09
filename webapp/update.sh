#!/bin/bash
cd /var/www/html/irconnect  # Asegúrate de que este es el directorio correcto


git stash

# Hacer pull de la última versión del repo
git pull origin main

# Restaurar cambios locales si los había
git stash pop

# Opcional: reiniciar el servidor si es necesario
systemctl restart apache2
