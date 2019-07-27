#!/bin/bash
#rm sites/default/settings.php

#bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@mariadb/neibrs
bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@127.0.0.1/varbase --site-name="NIDC"

bin/drupal site:mode dev

bin/drush pmu -y toolbar
bin/drush en -y coffee \
  config_update_ui \
  entity_plus \
  webprofiler \
  memcache_admin \
  neibers_category \
  neibers_idc \
  neibers_mall \
  neibers_translation \
  user_plus \
  vmi

echo "include $app_root . '/' . $site_path . '/settings.local.php';" > sites/default/settings.php

# Initial demo data.
bin/drush mim ip_xls
#bin/drush mim product_xls

bin/drupal locale:language:add zh-hans

bin/drush cset -y language.negotiation url.prefixes.en "en"
bin/drush cset -y language.types negotiation.language_interface.enabled.language-browser 0

#bin/drush cset -y system.site default_langcode "zh-hans"
bin/drush locale:update

bin/drupal thi -y neibers_codex
bin/drush cset -y system.theme admin neibers_codex
bin/drush cset -y system.theme default neibers_codex
