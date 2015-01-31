#!/bin/sh

# Caution
# ========================================================
# This script assume your environment has php and composer
# ========================================================

bashDir="$( cd "$( dirname "$0" )" && pwd )"
cd $bashDir

/usr/local/bin/composer update

# in the future I may add some scripts to import dept and person database
# TODO: auto import dept and person
