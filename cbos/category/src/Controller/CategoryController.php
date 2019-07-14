<?php

namespace Drupal\neibers_category\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\neibers_category\Entity\CategoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CategoryController.
 *
 *  Returns responses for Category routes.
 */
class CategoryController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new CategoryController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Category revision.
   *
   * @param int $neibers_category_revision
   *   The Category revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($neibers_category_revision) {
    $neibers_category = $this->entityTypeManager()->getStorage('neibers_category')
      ->loadRevision($neibers_category_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('neibers_category');

    return $view_builder->view($neibers_category);
  }

  /**
   * Page title callback for a Category revision.
   *
   * @param int $neibers_category_revision
   *   The Category revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($neibers_category_revision) {
    $neibers_category = $this->entityTypeManager()->getStorage('neibers_category')
      ->loadRevision($neibers_category_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $neibers_category->label(),
      '%date' => $this->dateFormatter->format($neibers_category->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Category.
   *
   * @param \Drupal\neibers_category\Entity\CategoryInterface $neibers_category
   *   A Category object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CategoryInterface $neibers_category) {
    $account = $this->currentUser();
    $neibers_category_storage = $this->entityTypeManager()->getStorage('neibers_category');

    $langcode = $neibers_category->language()->getId();
    $langname = $neibers_category->language()->getName();
    $languages = $neibers_category->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $neibers_category->label()]) : $this->t('Revisions for %title', ['%title' => $neibers_category->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all category revisions") || $account->hasPermission('administer category entities')));
    $delete_permission = (($account->hasPermission("delete all category revisions") || $account->hasPermission('administer category entities')));

    $rows = [];

    $vids = $neibers_category_storage->revisionIds($neibers_category);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\neibers_category\CategoryInterface $revision */
      $revision = $neibers_category_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $neibers_category->getRevisionId()) {
          $link = $this->l($date, new Url('entity.neibers_category.revision', [
            'neibers_category' => $neibers_category->id(),
            'neibers_category_revision' => $vid,
          ]));
        }
        else {
          $link = $neibers_category->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.neibers_category.translation_revert', [
                'neibers_category' => $neibers_category->id(),
                'neibers_category_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.neibers_category.revision_revert', [
                'neibers_category' => $neibers_category->id(),
                'neibers_category_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.neibers_category.revision_delete', [
                'neibers_category' => $neibers_category->id(),
                'neibers_category_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['neibers_category_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
