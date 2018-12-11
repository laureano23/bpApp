#!/bin/bash
pathDev="/var/www/proyectos/bpDev/mbp/"
pathProd="/var/www/proyectos/Metbp/"
devFront="/var/www/proyectos/bpDev/mbp/web/frontend/js/MetApp/"
prodFront="/var/www/proyectos/Metbp/web/frontend/js/MetApp/"
if [ $1 = "env" ]; then
cd $pathDev
php app/console cache:clear --env=dev
cd $devFront
sencha app build
cd pathDev
chmod 777 -R app/
else
git checkout .
git pull
cd $pathProd
php app/console cache:clear --env=prod
cd $prodFront
sencha app build
cd $pathProd
chmod 777 -R app/
fi
