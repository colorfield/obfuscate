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
 * @todo rely on third party vendor, review e.g. https://github.com/Propaganistas/Email-Obfuscator
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
   * Returns an obfuscated link from an email address.
   *
   * @param string $email
   *   Email address.
   * @param array $params
   *   Optional parameters to be used by the a tag.
   *
   * @return string
   *   Obfuscated email link.
   */
  private function getObfuscatedLink($email, array $params = []) {
    if (!is_array($params)) {
      $params = [];
    }

    // Tell search engines to ignore obfuscated uri.
    if (!isset($params['rel'])) {
      $params['rel'] = 'nofollow';
    }

    $neverEncode = [
      '.',
      '@',
      '+',
      // Don't encode those as not fully supported by IE & Chrome.
    ];

    $urlEncodedEmail = '';
    for ($i = 0; $i < strlen($email); $i++) {
      // Encode 25% of characters.
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

    $obfuscatedEmail = $this->obfuscateEmail($email);
    $obfuscatedEmailUrl = $this->obfuscateEmail('mailto:' . $urlEncodedEmail);

    $link = '<a href="' . $obfuscatedEmailUrl . '"';
    foreach ($params as $param => $value) {
      $link .= ' ' . $param . '="' . htmlspecialchars($value) . '"';
    }
    $link .= '>' . $obfuscatedEmail . '</a>';

    return $link;
  }

  /**
   * Obfuscates an email address.
   *
   * @param string $email
   *   Email address.
   *
   * @return string
   *   Obfuscated email string.
   */
  private function obfuscateEmail($email) {
    $alwaysEncode = ['.', ':', '@'];

    $result = '';

    // Encode string using oct and hex character codes.
    for ($i = 0; $i < strlen($email); $i++) {
      // Encode 25% of characters including several
      // that always should be encoded.
      if (in_array($email[$i], $alwaysEncode) || mt_rand(1, 100) < 25) {
        if (mt_rand(0, 1)) {
          $result .= '&#' . ord($email[$i]) . ';';
        }
        else {
          $result .= '&#x' . dechex(ord($email[$i])) . ';';
        }
      }
      else {
        $result .= $email[$i];
      }
    }

    return $result;
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
    // @todo implement settings summary.
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->getObfuscatedLink($item->value)];
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
