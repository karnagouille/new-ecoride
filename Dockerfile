# 1. Image de base
FROM php:8.2-cli

# 2. Définir le dossier de travail
WORKDIR /app

# 3. Copier les fichiers
COPY . .

# 4. Exposer le port
EXPOSE 8000

# 5. Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

