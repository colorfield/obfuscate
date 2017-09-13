<?php

namespace Drupal\obfuscate\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\obfuscate\ObfuscateMailFactory;

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
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    // Gets the default Field Formatter settings
    // from the system wide configuration.
    $config = \Drupal::config('obfuscate.settings');
    $method = $config->get('method');
    return [
      'obfuscate_method' => $method,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    // @todo use trait for shared settings form between per field override and formatter

    $config = \Drupal::config('obfuscate.settings');
    $method = $config->get('method');

    $form['obfuscate_method'] = [
      '#title' => t('Obfuscation method'),
      '#type' => 'radios',
      '#options' => [
        ObfuscateMailFactory::HTML_ENTITY => $this->t('HTML entity'),
        ObfuscateMailFactory::ROT_13 => $this->t('ROT 13'),
      ],
      // Field override, gets default from system wide configuration.
      '#default_value' => $this->getSetting($method),
    ];

    return $form + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    if ($this->getSetting(ObfuscateMailFactory::HTML_ENTITY)) {
      $summary[] = $this->t('Obfuscates email addresses by relying on PHP only.');
    }
    elseif ($this->getSetting(ObfuscateMailFactory::ROT_13)) {
      $summary[] = $this->t('Obfuscates email addresses by relying on ROT 13.');
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    // @todo get current setting
    $obfuscateMail = ObfuscateMailFactory::get(ObfuscateMailFactory::HTML_ENTITY);
    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $obfuscateMail->getObfuscatedLink($item->value)];
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
