<?php

namespace app\merchantreceive\controller;

use app\lib\exception\ParameterException;
use app\lib\exception\SuccessMessageWithData;
use app\merchantreceive\service\CerService;

class Index
{
    public function index()
    {
        echo '接受接口入口index';
    }

    public function saveCer()
    {
        $cer = request()->file('cer');
        if (is_null($cer)) {
            throw  new ParameterException(['msg' => '缺少证书文件']);
        }
        $name = (new CerService())->save($cer);
        return json(new SuccessMessageWithData(['data' => ['name' => $name]]));

    }
}
