<?php


namespace app\merchantreceive\service;


use app\merchantreceive\model\CompanyStaffT;
use app\merchantreceive\model\CompanyT;

class UserService
{
    public function checkUser($epay_code, $phone)
    {
        if (empty($epayCode) || empty($phone)) {
            return [
                'code' => 1,
                'msg' => '请求数据缺失'
            ];
        }
        $company = CompanyT::where('epay_code', '=', $epay_code)->find();
        if (empty($company)) {
            return [
                'code' => 2,
                'msg' => '企业不存在'
            ];
        }
        //查询用户是否存在
        $staff = CompanyStaffT::where('c_id', $company->id)
            ->where('phone', $phone)
            ->where('state', 1)
            ->find();
        if (empty($staff)) {
            return [
                'code' => 3,
                'msg' => '用户不存在'
            ];
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'staff_id' => $staff->id,
            'company_id' => $company->id,
            'username'=>$staff->username
        ];

    }

}