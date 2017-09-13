<?php

namespace Drupal\obfuscate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\obfuscate\ObfuscateMailFactory;

/**
 * Class ObfuscateConfigForm.
 *
 * @package Drupal\obfuscate\Form
 */
class ObfuscateConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'obfuscate.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'obfuscate_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // @todo use trait for shared settings form between per field override and formatter
    // @todo provide settings description

    $config = $this->config('obfuscate.settings');
    $form['method'] = [
      '#type' => 'radios',
      '#title' => $this->t('Obfuscation method'),
      '#description' => $this->t('System wide obfuscation method for fields et text filter, can be overriden per field configuration.'),
      '#options' => [ObfuscateMailFactory::HTML_ENTITY => $this->t('HTML entity'), ObfuscateMailFactory::ROT_13 => $this->t('ROT 13')],
      '#default_value' => $config->get('method'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('obfuscate.settings')
      ->set('method', $form_state->getValue('method'))
      ->save();
  }

}
