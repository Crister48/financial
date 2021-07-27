<?php
namespace app\common\validate;
use think\Validate;

class Record extends Validate
{
    protected $rule = [
        'klass_id'  => 'require',
        'user_id' => 'require',
        'type'=> 'require',
        'name'=> 'require',
        'num'=> 'require',
        'another'=> 'require',
    ];
}