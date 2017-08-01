<?php
namespace com\unionpay\acp\sdk;
 
// 签名证书密码
const SDK_SIGN_CERT_PWD = '369369';


class UnionPayConfig
{
    // 签名证书路径SIGN_CERT_PATH
    private static $signCertPath;

    // 签名证书密码
    private static $signCertPwd;

    // 验签证书路径（请配到文件夹，不要配到具体文件）SDK_VERIFY_CERT_DIR
    private static $verifyCertDir;
    
    /**
     * @return mixed
     */
    public static function getSignCertPath()
    {
        return self::$signCertPath;
    }

    /**
     * @param mixed $signCertPath
     */
    public static function setSignCertPath($signCertPath)
    {
        self::$signCertPath = $signCertPath;
    }

    /**
     * @return mixed
     */
    public static function getSignCertPwd()
    {
        return self::$signCertPwd;
    }

    /**
     * @param mixed $signCertPwd
     */
    public static function setSignCertPwd($signCertPwd)
    {
        self::$signCertPwd = $signCertPwd;
    }

    /**
     * @return mixed
     */
    public static function getVerifyCertDir()
    {
        return self::$verifyCertDir;
    }

    /**
     * @param mixed $verifyCertDir
     */
    public static function setVerifyCertDir($verifyCertDir)
    {
        self::$verifyCertDir = $verifyCertDir;
    }

}
?>