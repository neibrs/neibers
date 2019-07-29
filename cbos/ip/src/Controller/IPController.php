<?php

namespace Drupal\neibers_ip\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\neibers_ip\Entity\IPInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class IPController.
 *
 *  Returns responses for IP routes.
 */
class IPController extends ControllerBase implements ContainerInjectionInterface {


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
   * Constructs a new IPController.
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
   * Displays a IP revision.
   *
   * @param int $neibers_ip_revision
   *   The IP revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($neibers_ip_revision) {
    $neibers_ip = $this->entityTypeManager()->getStorage('neibers_ip')
      ->loadRevision($neibers_ip_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('neibers_ip');

    return $view_builder->view($neibers_ip);
  }

  /**
   * Page title callback for a IP revision.
   *
   * @param int $neibers_ip_revision
   *   The IP revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($neibers_ip_revision) {
    $neibers_ip = $this->entityTypeManager()->getStorage('neibers_ip')
      ->loadRevision($neibers_ip_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $neibers_ip->label(),
      '%date' => $this->dateFormatter->format($neibers_ip->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a IP.
   *
   * @param \Drupal\neibers_ip\Entity\IPInterface $neibers_ip
   *   A IP object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(IPInterface $neibers_ip) {
    $account = $this->currentUser();
    $neibers_ip_storage = $this->entityTypeManager()->getStorage('neibers_ip');

    $langcode = $neibers_ip->language()->getId();
    $langname = $neibers_ip->language()->getName();
    $languages = $neibers_ip->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $neibers_ip->label()]) : $this->t('Revisions for %title', ['%title' => $neibers_ip->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all ip revisions") || $account->hasPermission('administer ip')));
    $delete_permission = (($account->hasPermission("delete all ip revisions") || $account->hasPermission('administer ip')));

    $rows = [];

    $vids = $neibers_ip_storage->revisionIds($neibers_ip);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\neibers_ip\IPInterface $revision */
      $revision = $neibers_ip_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $neibers_ip->getRevisionId()) {
          $link = $this->l($date, new Url('entity.neibers_ip.revision', [
            'neibers_ip' => $neibers_ip->id(),
            'neibers_ip_revision' => $vid,
          ]));
        }
        else {
          $link = $neibers_ip->link($date);
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
              Url::fromRoute('entity.neibers_ip.translation_revert', [
                'neibers_ip' => $neibers_ip->id(),
                'neibers_ip_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.neibers_ip.revision_revert', [
                'neibers_ip' => $neibers_ip->id(),
                'neibers_ip_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.neibers_ip.revision_delete', [
                'neibers_ip' => $neibers_ip->id(),
                'neibers_ip_revision' => $vid,
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

    $build['neibers_ip_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
