<?php

namespace Drupal\obfuscate\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a filter to obfuscate email addresses.
 *
 * @Filter(
 *   id = "obfuscate_mail",
 *   title = @Translation("Email address obfuscation filter."),
 *   description = @Translation("Attempt to hide email addresses from spam-bots."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE
 * )
 */
class ObfuscateMail extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    drupal_set_message(t('Obfuscate mail filter not implemented yet.', 'warning'));
    return $this->t('Each email address will be obfuscated with the sytem wide configuration.');
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $result = new FilterProcessResult($text);

    if (stristr($text, 'mailto') === FALSE) {
      return $result;
    }

    $dom = Html::load($text);

    // @todo apply obfuscation with the system wide configuration.

    $result->setProcessedText(Html::serialize($dom));

    return $result;
  }

}
