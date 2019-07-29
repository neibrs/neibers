<?php

namespace Drupal\eabax_workflows\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\workflows\Plugin\WorkflowTypeStateFormBase;

class EntityStateForm extends WorkflowTypeStateFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\eabax_workflows\EntityState $state */
    $state = $form_state->get('state');

    $form['fields_control'] = [
      '#type' => 'table',
      '#caption' => $this->t('Field control'),
      '#header' => [
        $this->t('Field name'),
        $this->t('Can view'),
        $this->t('Can update'),
        $this->t('Value'),
        $this->t('Usage'),
      ],
    ];
    $entity_type_id = $this->workflowType->getConfiguration()['entity_type_id'];
    $fields = \Drupal::entityManager()->getFieldStorageDefinitions($entity_type_id);
    foreach ($fields as $field) {
      $name = $field->getName();
      if (in_array($name, ['id', 'uuid', 'vid', 'langcode'])) {
        continue;
      }

      $form['fields_control'][$name]['name'] = [
        '#markup' => $field->getLabel(),
      ];
      $form['fields_control'][$name]['view'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Can view'),
        '#title_display' => 'invisible',
        '#default_value' => !empty($state) ? $state->getCanView($name, TRUE) : TRUE,
      ];
      $form['fields_control'][$name]['update'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Can update'),
        '#title_display' => 'invisible',
        '#default_value' => !empty($state) ? $state->getCanUpdate($name, TRUE) : TRUE,
      ];
      if ($field->getType() == 'boolean') {
        $form['fields_control'][$name]['value'] = [
          '#type' => 'checkbox',
          '#title' => $field->getName(),
          '#title_display' => 'invisible',
          '#default_value' => !empty($state) ? $state->getStatusSettingValue($name) : TRUE,
        ];

        $form['fields_control'][$name]['status_setting'] = [
          '#type' => 'select',
          '#title' => $this->t('Status setting'),
          '#title_display' => 'invisible',
          '#options' => [
            'defaults_value' => $this->t('Defaults value'),
            'not_used' => $this->t('Not used'),
            'sets_value' => $this->t('Sets value'),
          ],
          '#default_value' => !empty($state) ? $state->getStatusSetting($name, 'not_used') : TRUE,
        ];
      }
    }

    return $form;
  }

}
