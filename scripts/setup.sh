#!/bin/bash
#
# Configuration script to be run when a new client is first checked out

# for OS X compatibility, do not use readlink
cd `dirname $0`
CWD=`pwd`
ROOT_DIR=`dirname $CWD`
cd $ROOT_DIR
echo "The root of your client appears to be $ROOT_DIR"

# Create a copy of the config file unless it already exists
if [ ! -e lib/Config.php ]; then
  echo "* copying lib/Config.php.sample to lib/Config.php"
  cp lib/Config.php.sample lib/Config.php
else
  echo "* lib/Config.php already exists, skipping"
fi

# Create a copy of the .htaccess file unless it already exists
if [ ! -e www/.htaccess ]; then
  echo "* copying www/.htaccess.sample to www/.htaccess"
  cp www/.htaccess.sample www/.htaccess
else
  echo "* www/.htaccess already exists, skipping"
fi

# Make the Smarty compiled templates directory world-writable
echo "* making some directories and files world-writable"
mkdir -p /tmp/templates_c
chmod 777 /tmp/templates_c

# Allow PHP scripts to generate merged CSS/JS files
chmod 777 www/css/merged www/js/merged

# Symlink hooks unless they already exist
if [ ! -e .git/hooks/pre-commit ]; then
  echo "* symlinking scripts/git-hooks/pre-commit.php as .git/hooks/pre-commit"
  ln -s $ROOT_DIR/scripts/git-hooks/pre-commit.php .git/hooks/pre-commit
else
  echo "* .git/hooks/pre-commit already exists, skipping"
fi
