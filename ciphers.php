<?php
echo '-- Алгоритм ГОСТ Р 34.12-2015 "Кузнечик (grasshopper-cbc)":'."<br />";
$iv_size = openssl_cipher_iv_length("grasshopper-cbc");
echo "Длина вектора: ".$iv_size."<br />";
echo "-- ## --"."<br />";

foreach (openssl_get_cipher_methods() as $value) {
    echo $value, "<br />";
  }

?>