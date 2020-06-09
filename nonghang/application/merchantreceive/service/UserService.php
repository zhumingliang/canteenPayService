<?php


namespace app\merchantreceive\service;


use app\merchantreceive\model\CompanyStaffT;
use app\merchantreceive\model\CompanyT;
use app\merchantreceive\model\LogT;
use app\merchantreceive\model\PayNonghangConfigT;
use think\Config;

class UserService
{
    public function checkUser($epay_code, $phone)
    {
        $company = PayNonghangConfigT::where('code', '=', $epay_code)
            ->where('state', 1)->find();
        if (empty($company)) {
            return [
                'code' => 2,
                'msg' => '企业不存在'
            ];
        }
        //查询用户是否存在
        $staff = CompanyStaffT::where('company_id', $company->company_id)
            ->where('phone', $phone)
            ->where('state', 1)
            ->find();
        if (empty($staff)) {
            return [
                'code' => 3,
                'msg' => '用户不存在'
            ];
        }
        //配置支付参数
        Config::set([
            'prikey' => $company->prikey,
            'pfxName' => $company->pfx
        ]);

        return [
            'code' => 0,
            'msg' => 'success',
            'staff_id' => $staff->id,
            'company_id' => $company->company_id,
            'username' => $staff->username,
            'phone'=>$staff->phone
        ];

    }

}