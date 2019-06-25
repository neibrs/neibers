#!/bin/bash
#rm sites/default/settings.php

#vendor/bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@mariadb/neibrs
vendor/bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@127.0.0.1/neibrs

vendor/bin/drupal site:mode dev

vendor/bin/drush pmu -y toolbar
vendor/bin/drush en -y coffee \
  entity_plus \
  webprofiler \
  memcache_admin \
  neibers_idc \
  neibers_mall \
  neibers_translation

echo "include $app_root . '/' . $site_path . '/settings.local.php';" > sites/default/settings.php

# Initial demo data.
vendor/bin/drush mim ip_xls
#vendor/bin/drush mim product_xls

vendor/bin/drupal locale:language:add zh-hans

vendor/bin/drush cset -y language.negotiation url.prefixes.en "en"
vendor/bin/drush cset -y language.types negotiation.language_interface.enabled.language-browser 0

#vendor/bin/drush cset -y system.site default_langcode "zh-hans"
vendor/bin/drush locale:update

vendor/bin/drupal thi -y neibers_basestrap
vendor/bin/drush cset -y system.theme admin neibers_basestrap
vendor/bin/drush cset -y system.theme default neibers_basestrap
