#!/bin/bash

# Setting default language to any language other than english results in interface translations being overwritten during module install
# https://www.drupal.org/project/drupal/issues/2905295

vendor/bin/drupal moi drush_language

# core/translation/translations
modules=( \
  'drupal' \
  'inline_entity_form' \
  'rules' \
)
for module in ${modules[@]}; do
  vendor/bin/drush langimp --langcode=zh-hans \
    modules/neibers/core/translation/translations/$module.zh-hans.po
done

# cbos
modules=( \
  'alert' \
  'calendar' \
  'currency' \
  'extra_information' \
  'employee_assignment' \
  'job' \
  'ledger' \
  'location' \
  'organization' \
  'person' \
  'position' \
  'uom' \
)
for module in ${modules[@]}; do
  vendor/bin/drush langimp --langcode=zh-hans \
    modules/neibers/cbos/$module/translations/$module.zh-hans.po
done

# core
modules=( \
  'code' \
  'data_model' \
  'eabax_core' \
  'eabax_layout' \
  'eabax_workflows' \
  'import' \
  'quick_code' \
)
for module in ${modules[@]}; do
  vendor/bin/drush langimp --langcode=zh-hans \
    modules/neibers/core/$module/translations/$module.zh-hans.po
done
