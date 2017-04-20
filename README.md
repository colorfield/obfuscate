# Obfuscate Email

Drupal 8 module that obfuscates email addresses as a Field Formatter
of the core Email field.

Provides a simple and flexible way to prevent spam by using the core
default Email field.
For some reasons, you should prefer to leave the field formatter of
some view modes to Plain text or Email.

All the view modes (default / full, teaser, search index, ...) that
exposes publicly email addresses should define the fields to Obfuscate.

## Related modules

[SpamSpan filter](https://www.drupal.org/project/spamspan)
