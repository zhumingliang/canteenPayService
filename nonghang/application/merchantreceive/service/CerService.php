<?php


namespace app\merchantreceive\service;


class CerService
{

    public function save($cer)
    {
      /*  $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/static/resources/certificate';
        if (!is_dir($path)) {
            mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
        }*/
       // $info = $cer->move($path);
        $info= $cer->move('../public/static/resources/certificate/','');
        return ['url' => $info->getSaveName()];
    }
}