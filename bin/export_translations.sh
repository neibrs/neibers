#!/bin/bash

vendor/bin/drupal moi potx -y

# print the directory and file.
#cd /var/www/html
echo $(pwd)

for file in `find modules/neibers -name "*.info.yml"`; do
  echo $(dirname $file) -- $(basename $(dirname $file));
  $(pwd)/vendor/bin/drush potx single --include=modules/contrib/potx --folder="$(dirname $file)/" --api=8
  if [ ! -d $(dirname $file)/translations ]; then
    mkdir $(dirname $file)/translations;
    echo "mkdir $(dirname $file)/translations";
  fi
  mv "$(pwd)/general.pot" "$(dirname $file)/translations/$(basename $(dirname $file)).pot"
done
