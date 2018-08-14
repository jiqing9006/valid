## PHP接口参数验证小工具，简单实用
支持常用验证

支持正则验证

支持单独使用

支持组合使用

支持多字段验证

基本满足各种需求

欢迎使用！

案例：
```
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
```
