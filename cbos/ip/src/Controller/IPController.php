<?php

namespace Drupal\ip\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\ip\Entity\IPInterface;

/**
 * Class IPController.
 *
 *  Returns responses for IP routes.
 */
class IPController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a IP  revision.
   *
   * @param int $ip_revision
   *   The IP  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($ip_revision) {
    $ip = $this->entityManager()->getStorage('ip')->loadRevision($ip_revision);
    $view_builder = $this->entityManager()->getViewBuilder('ip');

    return $view_builder->view($ip);
  }

  /**
   * Page title callback for a IP  revision.
   *
   * @param int $ip_revision
   *   The IP  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($ip_revision) {
    $ip = $this->entityManager()->getStorage('ip')->loadRevision($ip_revision);
    return $this->t('Revision of %title from %date', ['%title' => $ip->label(), '%date' => format_date($ip->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a IP .
   *
   * @param \Drupal\ip\Entity\IPInterface $ip
   *   A IP  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(IPInterface $ip) {
    $account = $this->currentUser();
    $langcode = $ip->language()->getId();
    $langname = $ip->language()->getName();
    $languages = $ip->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $ip_storage = $this->entityManager()->getStorage('ip');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $ip->label()]) : $this->t('Revisions for %title', ['%title' => $ip->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all ip revisions") || $account->hasPermission('administer ip')));
    $delete_permission = (($account->hasPermission("delete all ip revisions") || $account->hasPermission('administer ip')));

    $rows = [];

    $vids = $ip_storage->revisionIds($ip);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\ip\IPInterface $revision */
      $revision = $ip_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $ip->getRevisionId()) {
          $link = $this->l($date, new Url('entity.ip.revision', ['ip' => $ip->id(), 'ip_revision' => $vid]));
        }
        else {
          $link = $ip->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
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
              Url::fromRoute('entity.ip.translation_revert', ['ip' => $ip->id(), 'ip_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.ip.revision_revert', ['ip' => $ip->id(), 'ip_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.ip.revision_delete', ['ip' => $ip->id(), 'ip_revision' => $vid]),
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

    $build['ip_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
