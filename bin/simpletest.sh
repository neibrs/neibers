#!/bin/bash

ignores=( \
  # cbos
  'account' \
  'activity' \
  'activity_cost'\
  'bom' \
  'budget' \
  'cost' \
  'customer' \
  'department_resource' \
  'discrete_job' \
  'document' \
  'expenditure' \
  'periodic_costing' \
  'project_status' \
  'purchase_order' \
  'item' \
  'journal' \
  'material_subelement' \
  'overhead' \
  'periodic_cost_distribution' \
  'rate_schedule' \
  'resource' \
  'resource_cost' \
  'routing' \
  'solution' \
  'supplier' \
  'supplier_item' \
  'transaction' \
  # industries healthcare
  'hospital_cost' \
  'hospital_cost_rate' \
  'hospital_performance_monitoring' \
  # industries weapon
  'company_fee' \
  'labor_hours' \
  'quote_task' \
  'weapon_cost' \
  'part' \
  'special_expenses' \
  'quote_data' \
  # products
  'projects' \
  'project_collaboration' \
  'project_costing' \
  'project_planning_and_control' \
  'implementation_knowledge' \
  'development_knowledge' \
  'cost_management' \
  'supplier_portal' \
  'eabax_suite' \
  # theme
  'eabax_seven' \
)

sudo rm sites/simpletest/browser_output -rf
OUTPUT="simpletest-`date +%Y%m%d`.txt"
rm ${OUTPUT}

PROJECT="modules/eabax"
for file in `find ${PROJECT} -name "*.info.yml"`; do
  module=$(basename $(dirname ${file}))
  FOUND=0
  for ignore in ${ignores[@]}; do
    if [[ ${module} == ${ignore} ]]; then
      FOUND=1
      break;
    fi
  done
  if [[ ${FOUND} == 1 ]]; then
    echo "Ignore $file"
  else
    echo "Testing $file"
    sudo -u www-data php \
      ./core/scripts/run-tests.sh --url http://localhost --verbose \
      $module >> $OUTPUT
  fi
done