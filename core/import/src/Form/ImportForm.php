<?php

namespace Drupal\import\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\import\MigrateBatchExecutable;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ImportForm extends ImportFormBase {

  protected $entity_type_id;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $entity_type_id = NULL) {
    $this->entity_type_id = $entity_type_id;

    $definitions = $this->migrationPluginManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      if ($definition['destination']['plugin'] == "entity:$entity_type_id") {
        $options[$id] = $this->t($definition['label']);
      }
    }
    if (empty($options)) {
      drupal_set_message($this->t('Import template not found.'), 'warning');
      return new RedirectResponse(Url::fromRoute("entity.$entity_type_id.collection")->toString());
    }
    $form['migration'] = [
      '#type' => 'select',
      '#title' => $this->t('Template'),
      '#required' => TRUE,
      '#options' => $options,
      '#ajax' => [
        'callback' => '::migrationSwitch',
        'wrapper' => 'file-wrapper',
      ],
    ];

    $validators = [
      'file_validate_extensions' => ['csv xls xlsx'],
      'file_validate_size' => [file_upload_max_size()],
    ];
    $form['file'] = [
      '#type' => 'file',
      '#title' => $this->t('Data file'),
      '#description' => [
        '#theme' => 'file_upload_help',
        '#description' => $this->t('A import file.'),
        '#upload_validators' => $validators,
      ],
      '#size' => 50,
      '#upload_validators' => $validators,
      '#upload_location' => 'private://import',
      '#prefix' => '<div id="file-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['update'] = [
      '#type' => 'checkbox',
      '#title' => t('Update existing data'),
      '#weight' => 10,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Import'),
      '#button_type' => 'primary',
    ];

    // Polish style
    $form['#attributes']['class'][] = 'form-horizontal';
    $form['#theme_wrappers'] = ['form__box'];

    return $form;
  }

  public function migrationSwitch($form, FormStateInterface $form_state) {
    if ($migration_id = $form_state->getValue('migration')) {
      /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
      $migration = $this->migrationPluginManager->createInstance($migration_id);
      $validators = $form['file']['#upload_validators'];
      switch ($migration->getSourceConfiguration()['plugin']) {
        case 'csv':
          $validators['file_validate_extensions'] = ['csv'];
          break;
        case 'xls':
        case 'xls_plus':
          $validators['file_validate_extensions'] = ['xls xlsx'];
          break;
      }
      $form['file']['#upload_validators'] = $validators;
      $form['file']['#description']['#upload_validators'] = $validators;

      $form['file']['#description']['#description'] = $this->t('A import file. %template', [
        '%template' => Link::createFromRoute($this->t('Download template'), 'import.entity_import.template', [
          'entity_type_id' => $this->entity_type_id,
          'migration_id' => $migration_id,
        ])->toString(),
      ]);
    }

    return $form['file'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->file = _file_save_upload_from_form($form['file'], $form_state, FILE_EXISTS_RENAME);

    // Ensure we have the file uploaded.
    if (!$this->file) {
      $form_state->setErrorByName('file', $this->t('File to import not found.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $configuration['source']['path'] = $this->file->getFileUri();
    $options = [
      'limit' => 0,
      'update' => $form_state->getValue('update'),
      'force' => 0,
      'configuration' => $configuration,
    ];

    $this->doMigrate($options, $form_state);
  }

  protected function doMigrate($options, FormStateInterface $form_state) {
    $migration_id = $form_state->getValue('migration');

    /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
    $migration = $this->migrationPluginManager->createInstance($migration_id, $options['configuration']);
    if (in_array($migration->getSourceConfiguration()['plugin'], ['xls', 'xls_plus'])) {
      $session = \Drupal::request()->getSession();
      $session->set('import_entity.import_form.options', $options);

      $options = [];
      if ($destination = $this->getRequest()->query->get('destination')) {
        $this->getRequest()->query->remove('destination');
        $options['query']['destination'] = $destination;
      }

      $form_state->setRedirect('import.entity_import.select_sheet', [
        'migration_id' => $migration_id,
      ], $options);
      return;
    }

    // MigrateBatchExecutable will recreate the Migration object from $migration->id()
    $migrateMessage = new MigrateMessage();
    $migration->setStatus(MigrationInterface::STATUS_IDLE);
    $executable = new MigrateBatchExecutable($migration, $migrateMessage, $options);
    $executable->batchImport();
  }

}
