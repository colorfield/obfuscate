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
    // @todo implement
    $result = '';
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function obfuscateEmail($email) {
    // @todo implement
    $result = '';
    return $result;
  }

}
