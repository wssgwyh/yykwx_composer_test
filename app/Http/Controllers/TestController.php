<?php
/**
 * Created by PhpStorm.
 * User: adam_wang
 * Date: 17/1/19
 * Time: 下午7:36
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class TestController extends Controller
{
    public function request($url, $https = true, $method = 'get', $data = null)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($https == true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $rs = curl_exec($ch);

        curl_close($ch);
        return $rs;
    }


    public function createMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=jHJL5QeO0Dg7mp2XkZADSs2xfgiMBzAfpLDQo-Q0yzjngBRFH_BDwT1TyZr8tu0R5kzFZjL089IqLYni_noXsGGLd38LzxljXMzQ7hleRCKkRP6hIeuUPy988xxZ80R3RRLeADAIIC';

        $data = '
        {
    "button": [
        {
          "type":"click",
          "name":"找老师",
          "key":"find_teacher"
        },
        {
          "type":"click",
          "name":"联系客服",
          "key":"connect_service"
        }
    ]
}';

        $content = $this->request($url, true, 'post', $data);
        $content = json_decode($content);

        if ($content->errmsg == 'ok') {
            echo '创建菜单成功';
        } else {
            echo '创建菜单未成功' . '<br />';
            echo '错误代码为:' . $content->errcode;
        }


    }

    public function getMessage(Request $request)
    {
        $xml = '<document>
 <title>Forty What?</title>
 <from>Joe</from>
 <to>Jane</to>
 <body>
 I know that\'s the answer -- but what\'s the question?
 </body>
</document>';
        $xml=simplexml_load_string($xml);
        $data = json_decode(json_encode($xml),TRUE);
        var_dump( $xml );echo '<br>';
        var_dump( $data );

die;
        $postStr = file_get_contents("php://input");
        $file = fopen("log.txt", "a+");
        fwrite($file, $postStr);
        fclose($file);
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "image":
                    $resultStr = $this->receiveImage($postObj);
                    break;
                case "location":
                    $resultStr = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $resultStr = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $resultStr = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $resultStr = $this->receiveLink($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }
    public function receiveText($object)
    {
        $funcFlag = 0;
        $contentStr = "你发送的是文本，内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }

    public function transmitText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }


    public function wxServiceTest(Request $request)
    {
//        $signature = $_GET["signature"];
//        $timestamp = $_GET["timestamp"];
//        $nonce = $_GET["nonce"];
//        $echoStr = $_GET["echostr"];
//        $token = 'wyh1234567890';
//        $tmpArr = array($token, $timestamp, $nonce);
//        sort($tmpArr, SORT_STRING);
//        $tmpStr = implode($tmpArr);
//        $tmpStr = sha1($tmpStr);
//        // echo $signature;
//        // echo '<br>';
//        // echo $tmpStr;
//
//        $log = $signature . '----' . $tmpStr;
//        // echo '<br>';
//        // echo $log;
//        $file = fopen("log.txt", "a+");
//        fwrite($file, $log);
//        fclose($file);
//        if ($tmpStr == $signature) {
//            echo $echoStr;
//            exit;
//        } else {
//            return false;
//        }
        $postStr = file_get_contents("php://input");
        $file = fopen("log.txt", "a+");
        fwrite($file, $postStr);
        fclose($file);
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            $data = json_decode(json_encode($postObj),TRUE);
            $a = $data['Content'];
            $file1 = fopen("info.txt", "a+");
            fwrite($file1, $a);
            fclose($file1);
            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "image":
                    $resultStr = $this->receiveImage($postObj);
                    break;
                case "location":
                    $resultStr = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $resultStr = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $resultStr = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $resultStr = $this->receiveLink($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }
}