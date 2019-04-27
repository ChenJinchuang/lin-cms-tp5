<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/16
 * Time: 11:33
 */

namespace app\lib\token;

use app\lib\exception\token\TokenException;
use Firebase\JWT\JWT;
use think\Exception;
use think\facade\Request;

class Token
{
    public static function getToken($user)
    {
        $accessToken = self::createAccessToken($user);
        $refreshToken = self::createRefreshToken($user);
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    private static function refreshToken()
    {

    }

    private static function createAccessToken($user)
    {
        $key = config('secure.token_salt');
        $payload = [
            'iss' => 'lin-cms-tp5', //签发者
            'iat' => time(), //什么时候签发的
            'exp' => time() + 7200, //过期时间
            'uid' => $user->id,
            'nickname' => $user->nickname
        ];
        $token = JWT::encode($payload, $key);
        return $token;

    }

    private static function createRefreshToken($user)
    {
        $key = config('secure.token_salt');
        $payload = [
            'iss' => 'lin-cms-tp5', //签发者
            'iat' => time(), //什么时候签发的
            'uid' => $user->id,
            'nickname' => $user->nickname
        ];
        $token = JWT::encode($payload, $key);
        return $token;
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUID()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentName()
    {
        $uid = self::getCurrentTokenVar('nickname');
        return $uid;
    }

    /**
     * @param $key
     * @return mixed
     * @throws TokenException
     * @throws Exception
     */
    private static function getCurrentTokenVar($key)
    {
        $authorization = explode(' ', Request::header('authorization'));
        $token = $authorization[1];

        if (!$token) {
            throw new TokenException();
        }

        $secretKey = config('secure.token_salt');

        try {
            $jwt = (array)JWT::decode($token, $secretKey, ['HS256']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw new TokenException(['msg' => '令牌签名不正确']);
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new TokenException(['msg' => '令牌尚未生效']);
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw new TokenException(['msg' => '令牌已过期，刷新浏览器重试']);
        } catch (Exception $e) {  //其他错误
            throw new Exception($e->getMessage());
        }
        if (array_key_exists($key, $jwt)) {
            return $jwt[$key];
        } else {
            throw new Exception('尝试获取的Token变量不存在');
        }

    }
}