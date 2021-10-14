#!/bin/bash
#
# CSS compilation script to be run when modifying www/scss/main.scss.
cd `dirname $0`
CWD=`pwd`
ROOT_DIR=`dirname $CWD`
cd $ROOT_DIR
echo "The root of your client appears to be $ROOT_DIR"

INPUT=www/scss/main-dark.scss
OUTPUT=www/css/third-party/bootstrap.min.css
export SASS_PATH=www/scss/third-party/bootstrap/

echo "Recompiling $INPUT into $OUTPUT"
sassc -t nested $INPUT > $OUTPUT
