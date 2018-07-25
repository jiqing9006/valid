<?php
/**
 * 测试案例
 */
namespace Jq;
require '../src/Valid.php';
$validators = [
    'tel' => 'required|tel',
    'name' => 'required',
    'email' => 'r|email',
    'password' => 'r|min_len,6|max_len,12'
];

if ($err = Valid::is_valid($validators,$_POST)) {
    echo $err;
}