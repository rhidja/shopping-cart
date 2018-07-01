#!/bin/bash
MY_PATH="`dirname \"$0\"`"

# ==================================================================================================
SERVER_NAME="lexik.hidja.loc"
APP_DIR="html\/lexik"
APACHE_LOG_DIR="lexik"
SITES_AVAILABLE="/etc/apache2/sites-available"
VirtualHost="lexik.hidja.loc.conf"
# ==================================================================================================

echo "Installation de l'application Shopping Cart sur un serveur apache2"
echo "==================================================================\n"

read -p "L'execution de ce script, réinitialisera la base de données. Voulez-vous le confirmer? :" -n 5 confirm

if [[ $confirm = "yes" ]]; then

    composer install --no-interaction

    HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX $MY_PATH/var
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX $MY_PATH/var

    # Clear cache
    php $MY_PATH/bin/console cache:clear --no-debug --no-warmup
    php $MY_PATH/console cache:clear --env=prod --no-warmup

    # Création de la base de données.
    php $MY_PATH/bin/console doctrine:database:drop --force
    php $MY_PATH/bin/console doctrine:database:create
    php $MY_PATH/bin/console make:migration
    php $MY_PATH/bin/console --no-interaction doctrine:migrations:migrate

    # Charger les fixtures
    php $MY_PATH/bin/console --no-interaction doctrine:fixtures:load

    # Dump assetic
    npm install @symfony/webpack-encore --save-dev
    yarn run encore dev

    # Configuration du VirtualHost
    mkdir -p /var/log/apache2/lexik/
    # Copier dans dossier /etc/apache2/sites-available le fichier de configuration du VirtualHost
    cp docker/virtualhost.conf.dist $SITES_AVAILABLE/$VirtualHost
    # Le server name et son alias
    sed -i -e "s/domain\.tld/$SERVER_NAME/g" $SITES_AVAILABLE/$VirtualHost
    # Les fichiers de log de l'application
    sed -i -e "s/project_error/$APACHE_LOG_DIR\/error/g" $SITES_AVAILABLE/$VirtualHost
    sed -i -e "s/project_access/$APACHE_LOG_DIR\/access/g" $SITES_AVAILABLE/$VirtualHost
    # Le dossier de l'application
    sed -i -e "s/project/$APP_DIR/g" $SITES_AVAILABLE/$VirtualHost

    cd $SITES_AVAILABLE/
    a2ensite $VirtualHost
    service apache2 restart

    sed -i -e "s/127.0.0.1     $SERVER_NAME//g" /etc/hosts
    echo "127.0.0.1     $SERVER_NAME" >> /etc/hosts

elif [[ $confirm = "" ]]; then
    echo "Running command canceled"
else
    echo "Incorrect response"
fi
