#!/bin/bash
#rm sites/default/settings.php

#bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@mariadb/varbase --site-name="NIDC"
bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@127.0.0.1:8889/varbase --site-name="NIDC"

bin/drupal site:mode dev

bin/drush pmu -y toolbar
bin/drush en -y adminimal_admin_toolbar \
  coffee \
  config_update_ui \
  entity_plus \
  neibers_translation \
  memcache \
  memcache_admin \
  user_plus \
  vmi
#  webprofiler \
#  neibers_idc \
#  neibers_mall \
#  neibers_translation \

echo "include $app_root . '/' . $site_path . '/settings.local.php';" > docroot/sites/default/settings.php

# Initial demo data.
#bin/drush mim ip_xls
#bin/drush mim product_xls

bin/drupal locale:language:add zh-hans

bin/drush cset -y language.negotiation url.prefixes.en "en"
bin/drush cset -y language.types negotiation.language_interface.enabled.language-browser 0

bin/drush cset -y system.site default_langcode "zh-hans"
bin/drush locale:update

#bin/drupal thi -y neibers_codex
#bin/drush cset -y system.theme admin neibers_codex
#bin/drush cset -y system.theme default neibers_codex
