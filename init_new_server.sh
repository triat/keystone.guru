#!/usr/bin/env bash
# TODO?
# Add root SSH key
# Increase PHP memory limit
# Create databases?
# Create users & permissions?


# create directories
tput setaf 2;
echo "Creating directories..."
tput sgr0;
# cache for image library
mkdir storage/app/public/imagecache
mkdir storage/app/public/expansions
mkdir storage/debugbar

# ensure www-data permissions
tput setaf 2;
echo "Setting www-data ownership to some folders..."
tput sgr0;
chown -R www-data:www-data storage/*
chown -R www-data:www-data bootstrap/cache

# ensure some other permissions
tput setaf 2;
echo "Ensuring file permissions..."
tput sgr0;
chmod 755 *.sh
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# make sure setfacl is installed on the server
sudo apt-get install acl
# Give www-data user permission to write in this folder regardless of ownership. See https://stackoverflow.com/a/29882246/771270
setfacl -d -m g:www-data:rwx storage/logs

# Prior to performing any artisan commands, we need to update composer. Normally composer also calls artisan, but the
# --no-scripts tag prevents that from happening. After this, artisan will work normally. Otherwise you get this error:
# Fatal error: Uncaught Error: Class 'Illuminate\Foundation\Application' not found in /home/vagrant/Git/private/keystone.guru/bootstrap/app.php:14
tput setaf 2;
composer update --no-scripts
tput sgr0;

# ensure any uploaded file may be accessed directly (symlinks public/storage to storage/app/public)
tput setaf 2;
echo "Ensuring storage link..."
tput sgr0;
php artisan storage:link

# Prevent not being able to compile because cross-env is missing
tput setaf 2;
echo "Installing cross-env globally..."
tput sgr0;
sudo npm install --global cross-env

#make sure we have the correct versions for everything
tput setaf 2;
echo "Updating dependencies..."
tput sgr0;
./update_dependencies.sh

# This was somehow needed to get the image library to work
tput setaf 2;
echo "Publishing service providers..."
tput sgr0;
php artisan vendor:publish --provider="Folklore\Image\ImageServiceProvider"
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
php artisan vendor:publish --provider="PragmaRX\Tracker\Vendor\Laravel\ServiceProvider"

# In case Tracker is not working, check this -> https://github.com/antonioribeiro/tracker#installing
tput setaf 2;
echo "Setting up tracker..."
tput sgr0;
php artisan tracker:tables

# Generate encryption key
tput setaf 2;
echo "Setting up private encryption key..."
tput sgr0;
php artisan key:generate

# Run migrate again to fix the tracker
./migrate.sh

# Install some packages
git clone https://github.com/BlackrockDigital/startbootstrap-sb-admin-2.git public/templates/sb-admin-2
cd public/templates/sb-admin-2
git checkout tags/v3.3.7+1
git checkout -b v3.3.7+1

# Back to where we came from
cd ../../..

# Setup argon dashboard
php artisan preset argon
# This does way too much though, undo the damage it did (we require SOME of it though)
git checkout .
git clean -f
rm -rf resources/views/layouts/footers
rm -rf resources/views/layouts/headers
rm -rf resources/views/layouts/navbars
rm -rf resources/views/users

sudo npm install -g laravel-echo-server
sudo npm install -g handlebars
sudo apt-get install supervisor
sudo apt-get install pngquant

tput setaf 2;
echo "Seeding database..."
tput sgr0;
# Seed Laratrust (initial users etc)
php artisan db:seed --class=LaratrustSeeder --database=migrate

# Install lua
sudo apt-get install lua5.3
sudo apt-get install liblua5.3

sudo ln -s /usr/include/lua5.3/ /usr/include/lua
sudo cp /usr/lib/x86_64-linux-gnu/liblua5.3.a /usr/lib/liblua.a
sudo cp /usr/lib/x86_64-linux-gnu/liblua5.3.so.0.0.0 /usr/lib/liblua.so

sudo ln -s /usr/local/lib/lua /usr/local/lib/lua/5.3
sudo ln /bin/lua5.3 /bin/lua
sudo ln /usr/bin/lua5.3 /usr/bin/lua

sudo ln /usr/include/lua5.3/lua.h /usr/include/lauxlib.h
sudo ln /usr/include/lua5.3/lua.h /usr/include/lua.h
sudo ln /usr/include/lua5.3/lua.h /usr/include/luaconf.h

sudo pecl install lua-2.0.6

mkdir -p /usr/lib/lua/5.3/
cp storage/lua/* /usr/lib/lua/5.3/

echo "Now you just need to add 'extension: lua.so' to your PHP.ini"

# Seeding database
./refresh_db_seed.sh