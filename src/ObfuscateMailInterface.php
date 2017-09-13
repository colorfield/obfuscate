<?php

namespace Drupal\obfuscate;

/**
 * Interface ObfuscateMailInterface.
 *
 * @package Drupal\obfuscate
 */
interface ObfuscateMailInterface {

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
  public function getObfuscatedLink($email, array $params = []);

  /**
   * Obfuscates an email address.
   *
   * @param string $email
   *   Email address.
   *
   * @return string
   *   Obfuscated email string.
   */
  public function obfuscateEmail($email);

}
