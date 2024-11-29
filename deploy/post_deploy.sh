#!/bin/bash -l

# Exécuter uniquement sur l'instance principale
if [[ "$INSTANCE_NUMBER" != "0" ]]; then
  echo "Instance number is ${INSTANCE_NUMBER}. This is not the main instance. Exiting."
  exit 0
fi

# Aller dans le répertoire de l'application
cd ${APP_HOME}

echo "Running post-build tasks on the main instance (Instance 0)..."

# Configurer s3cmd pour Cellar Clever Cloud
S3_BUCKET="s3://vinyl-collection" # Remplace par ton bucket Cellar
S3CMD_CONFIG_FILE="s3cmd.conf"

cat > $S3CMD_CONFIG_FILE <<EOL
[default]
access_key = ${AWS_ACCESS_KEY_ID}
secret_key = ${AWS_SECRET_ACCESS_KEY}
host_base = ${AWS_ENDPOINT}
host_bucket = ${AWS_ENDPOINT}
signature_v2 = False
use_https = True
EOL

# Vérifier si les clés Passport existent localement
if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then
    echo "Passport keys not found locally. Fetching from S3..."
    
    # Récupérer les clés depuis Clever Cloud S3
    s3cmd -c $S3CMD_CONFIG_FILE get $S3_BUCKET/oauth-private.key storage/oauth-private.key
    s3cmd -c $S3CMD_CONFIG_FILE get $S3_BUCKET/oauth-public.key storage/oauth-public.key

    # Vérifier si les clés ont bien été récupérées
    if [ -f storage/oauth-private.key ] && [ -f storage/oauth-public.key ]; then
        echo "Passport keys successfully fetched from S3."
        chmod 600 storage/oauth-*.key
    else
        echo "Failed to fetch keys from S3. Generating new keys..."
        php artisan passport:keys --force

        # Uploader les nouvelles clés sur S3
        echo "Uploading new keys to S3..."
        s3cmd -c $S3CMD_CONFIG_FILE put storage/oauth-private.key $S3_BUCKET/
        s3cmd -c $S3CMD_CONFIG_FILE put storage/oauth-public.key $S3_BUCKET/
    fi
else
    echo "Passport keys already exist locally. Skipping generation."
fi

# Exécuter les migrations
echo "Running migrations..."
php artisan migrate --force

# Optimisations Laravel
echo "Clearing and caching configs..."
php artisan optimize:clear

echo "Post-build tasks completed!"
