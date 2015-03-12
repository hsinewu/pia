#!/bin/bash

cd $(dirname $0)
echo "Starting the laravel self-built development server on http://0.0.0.0:80 ..."
php ./artisan serve --host=0.0.0.0 --port=80 &
echo "You can still use command now like composer dump, php artisan migrate ..."
echo "enter 'exit' to exit and close the dev env"
bash
