#! /usr/bin/env bash

if [ -z $OMEKA_BRANCH ]; then
  OMEKA_BRANCH=stable-1.5
fi

export OMEKA_DIR=`pwd`/omeka

$DBNAME=omeka_test

mysql -e "create database IF NOT EXISTS $DBNAME;" -uroot;
git clone https://github.com/omeka/Omeka.git $OMEKA_DIR

# check out the correct branch
cd $OMEKA_DIR && git checkout $OMEKA_BRANCH
cd
# move configuration files
mv $OMEKA_DIR/application/config/config.ini.changeme $OMEKA_DIR/application/config/config.ini
mv $OMEKA_DIR/application/tests/config.ini.changeme $OMEKA_DIR/application/tests/config.ini

# set up testing config
sed -i 's/db.host = ""/db.host = "localhost"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/db.username = ""/db.username = "root"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/db.dbname = ""/db.dbname = "omeka_test"/' $OMEKA_DIR/application/tests/config.in 
sed -i 's/email.to = ""/email.to = "test@example.com"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/email.administator = ""/email.administrator = "admin@example.com"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/paths.maildir = ""/paths.maildir = "\/tmp"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/paths.imagemagick = ""/paths.imagemagick = "\/usr\/bin\/"/' $OMEKA_DIR/application/tests/config.ini
sed -i 's/256M/512M/' $OMEKA_DIR/application/tests/bootstrap.php

