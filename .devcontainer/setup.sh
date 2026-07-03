#!/bin/sh
set -e

apk add --no-cache bash git nodejs npm

npm install -g @fission-ai/openspec

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

composer install

npm install
