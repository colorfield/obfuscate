<?php

namespace Drupal\obfuscate\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'obfuscate_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "obfuscate_field_formatter",
 *   label = @Translation("Obfuscate"),
 *   field_types = {
 *     "email"
 *   }
 * )
 */
class ObfuscateFieldFormatter extends FormatterBase {

  /**
   * Obfuscates an email link.
   * @param $email
   * @param array $params
   * @return string
   */
  private function obfuscateLink($email, $params = []) {
    if (!is_array($params)) {
      $params = array();
    }

    // Tell search engines to ignore obfuscated uri
    if (!isset($params['rel'])) {
      $params['rel'] = 'nofollow';
    }

    $neverEncode = array(
      '.',
      '@',
      '+'
    ); // Don't encode those as not fully supported by IE & Chrome

    $urlEncodedEmail = '';
    for ($i = 0; $i < strlen($email); $i++) {
      // Encode 25% of characters
      if (!in_array($email[$i], $neverEncode) && mt_rand(1, 100) < 25) {
        $charCode = ord($email[$i]);
        $urlEncodedEmail .= '%';
        $urlEncodedEmail .= dechex(($charCode >> 4) & 0xF);
        $urlEncodedEmail .= dechex($charCode & 0xF);
      }
      else {
        $urlEncodedEmail .= $email[$i];
      }
    }

    $obfuscatedEmail = $this->obfuscateLink($email);
    $obfuscatedEmailUrl = $this->obfuscateLink('mailto:' . $urlEncodedEmail);

    $link = '<a href="' . $obfuscatedEmailUrl . '"';
    foreach ($params as $param => $value) {
      $link .= ' ' . $param . '="' . htmlspecialchars($value) . '"';
    }
    $link .= '>' . $obfuscatedEmail . '</a>';

    return $link;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
