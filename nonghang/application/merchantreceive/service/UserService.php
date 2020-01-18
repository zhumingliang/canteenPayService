<?php


namespace app\merchantreceive\service;


use app\merchantreceive\model\CompanyStaffT;
use app\merchantreceive\model\CompanyT;

class UserService
{
    public function checkUser($epay_code, $phone)
    {
        $company = CompanyT::where('epay_code', '=', $epay_code)->find();
        if (empty($company)) {
            return 0;
        }
        //查询用户是否存在
        $staff = CompanyStaffT::where('c_id', $company)
            ->where('phone',$phone)
            ->where('state', 1)
            ->find();
        if (empty($staff)) {
            return 0;
        }
        return $staff->id;

    }

}