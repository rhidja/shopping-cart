#!/bin/bash
MY_PATH="`dirname \"$0\"`"

read -p "Do you want to re-install database (yes or no)? choosing 'yes' will delete all data :" -n 5 confirm

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

    # Run the server
    php -S 127.0.0.1:8000 -t public &

elif [[ $confirm = "" ]]; then
    echo "Running command canceled"
else
    echo "Incorrect response"
fi
