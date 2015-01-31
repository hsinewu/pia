#!/bin/sh

# Caution
# =================================================
# This script assume your environment is "Centos 6"
# =================================================

# download the wkhtmltopdf package from http://wkhtmltopdf.org/downloads.html
wget -q -O /tmp/wkhtmltox.rpm http://downloads.sourceforge.net/project/wkhtmltopdf/0.12.2.1/wkhtmltox-0.12.2.1_linux-centos6-amd64.rpm

# install wkhtmltopdf
yum localinstall -y /tmp/wkhtmltox.rpm

# download the open source noto font from https://www.google.com/get/noto/
mkdir -p /usr/share/fonts/source-han-sans
wget -q -O /usr/share/fonts/source-han-sans/SourceHanSans-Regular.ttc https://github.com/adobe-fonts/source-han-sans/raw/release/OTC/SourceHanSans-Regular.ttc

# deploy the font
fc-cache
fc-list # show what font you have now

# clean up
rm /tmp/wkhtmltox.rpm
