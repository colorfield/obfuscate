<?php

namespace Drupal\obfuscate;

/**
 * Class ObfuscateMailROT13.
 *
 * Based on the Propaganistas vendor.
 *
 * @see https://packagist.org/packages/propaganistas/email-obfuscator
 * @see https://github.com/Propaganistas/Email-Obfuscator
 *
 * @package Drupal\obfuscate
 */
class ObfuscateMailROT13 implements ObfuscateMailInterface {

  /**
   * {@inheritdoc}
   */
  public function getObfuscatedLink($email, array $params = []) {
    $build = [
      '#theme' => 'email_link',
      '#link' => $this->obfuscateEmail($email),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function obfuscateEmail($email) {
    // @fixme Propaganistas global function, should be wrapped into a class
    // ambiguous structure: global function called into a method.
    $result = obfuscateEmail($email);
    return $result;
  }

}
