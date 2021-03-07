<?php
/**
 * EN: Class for Encrypt/Decrypt data.
 * RU: Класс для шифрования/расшифрования данных.
 *
 */
class Pch_Cipher
{
  private $_iv_size; // Длина (IV) вектора инициализации.
  private $_cipher; // Название шифра (Список шифров - openssl_get_cipher_methods()).
  private $_md_hash; // Хэш-функция, используемая совместно с шифром (openssl_get_md_methods()).
  private $_use_openssl; // Проверка доступности расширения php_openssl.

// Инициализация
public function __construct($crypt = 'bf-cbc')
{
  $this->_use_openssl = extension_loaded('openssl') ? true: false;
  if ($this->_use_openssl) {
    $this->_cipher = strtolower($crypt);
    if (!in_array($this->_cipher, openssl_get_cipher_methods()))
      $this->_cipher = 'bf-cbc';
    $this->_iv_size = openssl_cipher_iv_length($this->_cipher);
  }
}

// Генерация (IV) вектора инициализации.
public function makeIv()
{
  return openssl_random_pseudo_bytes($this->_iv_size);
}

// Шифрование данных.
public function encrypt($data, $iv, $key)
{
  if (!$data || !$iv || !$key) return '';
  if ($m = strlen($data)%$this->_iv_size)
    $data .= str_repeat("\x00",  $this->_iv_size - $m);
  $data = openssl_encrypt($data, $this->_cipher, $key,
    OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
  return base64_encode($data);
}

// Расшифровка данных.
public function decrypt($data, $iv, $key)
{
  if (!$data || !$iv || !$key) return '';
  $data = openssl_decrypt(base64_decode($data), $this->_cipher, $key,
    OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
  return trim($data);
}

// Генератор случайных чисел
public function genRandom($num = 1, $min = 0, $max = 1000000000)
{
  if ($num < 1) {
    $num = 1;
  }
  $data = array();
  for ($i = 0; $i <= $num; $i++) {
    $data[$i] = random_int($min, $max);
  }
  if ($num == 1)
    $data = $data[0];
  return $data;
}

// Хэширование данных.
public function mdHash($value, $md_hash='md5')
{
  $this->_md_hash = strtolower($md_hash);
  if (in_array($this->_md_hash, openssl_get_md_methods()))
    return openssl_digest($value, $this->_md_hash);
  else
    return openssl_digest($value, 'md5');
}

// Версия OpenSSL
public function openssl_version()
{
  return substr(OPENSSL_VERSION_TEXT,0,strpos(OPENSSL_VERSION_TEXT, ' ',
    strpos(OPENSSL_VERSION_TEXT,' ',0)+1));
}

}
?>
