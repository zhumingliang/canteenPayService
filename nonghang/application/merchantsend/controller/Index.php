<?php
namespace app\merchantsend\controller;


use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('Index@');
    }
}
