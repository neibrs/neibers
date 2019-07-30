#!/bin/bash
#chmod -R a+rw docroot/sites/default

#rm docroot/sites/default/settings.php

#bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@mariadb/laradrupal --site-name="NIDC"
bin/drush site:install -y --account-pass=admin --db-url=mysql://root:root@127.0.0.1:8889/varbase --site-name="NIDC"

#echo "ini_set('memory_limit', -1);" > docroot/sites/default/settings.php

bin/drupal site:mode dev

#bin/drush pmu -y toolbar
bin/drush en -y adminimal_admin_toolbar \
  coffee \
  config_translation \
  config_update_ui \
  entity_plus \
  neibers_idc \
  neibers_mall \
  neibers_translation \
  memcache \
  memcache_admin \
  user_plus \
  vmi

chmod -R a+rw docroot/sites/default/settings.php

#echo "\$settings['file_private_path'] = '/tmp';" >> docroot/sites/default/settings.php
#echo 'include $app_root . "/" . $site_path . "/settings.local.php";' >> docroot/sites/default/settings.php

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
