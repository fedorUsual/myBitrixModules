<?php
return array (
  'pull_s1' => 'BEGIN GENERATED PUSH SETTINGS. DON\'T DELETE COMMENT!!!!',
  'pull' => 
  array (
    'value' => 
    array (
      'path_to_listener' => 'http://#DOMAIN#/bitrix/sub/',
      'path_to_listener_secure' => 'https://#DOMAIN#/bitrix/sub/',
      'path_to_modern_listener' => 'http://#DOMAIN#/bitrix/sub/',
      'path_to_modern_listener_secure' => 'https://#DOMAIN#/bitrix/sub/',
      'path_to_mobile_listener' => 'http://#DOMAIN#:8893/bitrix/sub/',
      'path_to_mobile_listener_secure' => 'https://#DOMAIN#:8894/bitrix/sub/',
      'path_to_websocket' => 'ws://#DOMAIN#/bitrix/subws/',
      'path_to_websocket_secure' => 'wss://#DOMAIN#/bitrix/subws/',
      'path_to_publish' => 'http://tk.loc:8895/bitrix/pub/',
      'path_to_publish_web' => 'http://#DOMAIN#/bitrix/rest/',
      'path_to_publish_web_secure' => 'https://#DOMAIN#/bitrix/rest/',
      'nginx_version' => '4',
      'nginx_command_per_hit' => '100',
      'nginx' => 'Y',
      'nginx_headers' => 'N',
      'push' => 'Y',
      'websocket' => 'Y',
      'signature_key' => 'S7Mwp2vgdqWEr0RW3uJxAZH7VIeJsFb1AUOHuCqM2AfiH1ow5xhbA3xYHx14Ik96JAzjKP0bOOvH8bIk1TTSNdOQILWtQD89IgQaPh3Esc4ffjADf00QDtj31pJwU6KD',
      'signature_algo' => 'sha1',
      'guest' => 'N',
    ),
  ),
  'pull_e1' => 'END GENERATED PUSH SETTINGS. DON\'T DELETE COMMENT!!!!',
  'utf_mode' => 
  array (
    'value' => true,
    'readonly' => true,
  ),
  'session' => 
  array (
    'value' => 
    array (
      'mode' => 'default',
      'handlers' => 
      array (
        'general' => 
        array (
          '_fromSecurity' => true,
          'type' => 'database',
        ),
      ),
    ),
    'readonly' => true,
  ),
  'cache_flags' => 
  array (
    'value' => 
    array (
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' => 
  array (
    'value' => 
    array (
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => true,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => 
      array (
        'settings' => 
        array (
          'file' => '/var/log/php/exceptions.log',
          'log_size' => 1000000,
        ),
      ),
    ),
    'readonly' => false,
  ),
  'crypto' => 
  array (
    'value' => 
    array (
      'crypto_key' => '8s61dwv9ksps1s4qi32mnkk1qnq973dh',
    ),
    'readonly' => true,
  ),
  'connections' => 
  array (
    'value' => 
    array (
      'default' => 
      array (
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'host' => 'localhost',
        'database' => 'sitemanager',
        'login' => 'bitrix0',
        'password' => 'Et]L%JaIH(9JU9HryKuS',
        'options' => 2,
      ),
    ),
    'readonly' => true,
  ),
  'smtp' => 
  array (
    'value' => 
    array (
      'enabled' => true,
      'debug' => true,
      'log_file' => '/var/mailer.log',
    ),
  ),
);
