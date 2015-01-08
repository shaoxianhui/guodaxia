<?php
namespace Org\Wechat;
class WxPay {

    private $appid;
    private $appsecret;
    private $key;
    private $sslcert;
    private $sslkey;
    private $mchid;

	public $openid;
	public $parameters;
	public $prepay_id;
	public $curl_timeout;
	public $data;
	public $returnParameters;
	
	public function __construct() {
		$this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		$this->curl_timeout = 30;
		$this->appid = C('WECHAT.appid');
		$this->appsecret = C('WECHAT.appsecret');
		$this->key = C('WECHAT.key');
		$this->sslcert = C('WECHAT.sslcert');
		$this->sslkey = C('WECHAT.sslkey');
		$this->mchid = C('WECHAT.mchid');
	}
	
	private function trimString($value) {
		$ret = null;
		if (null != $value) {
			$ret = $value;
			if (strlen($ret) == 0) {
				$ret = null;
			}
		}
		return $ret;
	}
	
	public function createNoncestr($length = 32) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str = "";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	private function formatBizQueryParaMap($paraMap, $urlencode) {
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	private function getSign($Obj) {
		foreach ($Obj as $k => $v) {
			$params[$k] = $v;
		}
		ksort($params);
		$String = $this->formatBizQueryParaMap($params, false);
		$String = $String."&key=".$this->key;
		$String = md5($String);
		$result_ = strtoupper($String);
		return $result_;
	}
	
	function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
        	 if (is_numeric($val)) {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 
        	 } else {
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
			}
        }
        $xml.="</xml>";
        return $xml; 
    }
	
	public function xmlToArray($xml) {		      
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
	}

	public function postXmlCurl($xml,$url,$second=30) {		       
       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
		curl_close($ch);
		if($data)
		{
			return $data;
		}
		else 
		{ 
			return false;
		}
	}

	function postXmlSSLCurl($xml,$url,$second=30) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT,$second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER,FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($ch, CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch, CURLOPT_SSLCERT, $this->sslcert);
		curl_setopt($ch, CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch, CURLOPT_SSLKEY, $this->sslkey);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		curl_close($ch);
		if($data){
			return $data;
		} else { 
			return false;
		}
	}
	
	public function createOauthUrlForCode($redirectUrl) {
		$urlObj["appid"] = $this->appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	private function createOauthUrlForOpenid($code) {
		$urlObj["appid"] = $this->appid;
		$urlObj["secret"] = $this->appsecret;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	public function getOpenid($code) {
		$url = $this->createOauthUrlForOpenid($code);
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
		$data = json_decode($res,true);
		$this->openid = $data['openid'];
		return $this->openid;
	}
	
	public function setParameter($parameter, $parameterValue) {
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	public function getParameters() {
		$jsApiObj["appId"] = $this->appid;
		$timeStamp = time();
	    $jsApiObj["timeStamp"] = "$timeStamp";
	    $jsApiObj["nonceStr"] = $this->createNoncestr();
		$jsApiObj["package"] = "prepay_id=$this->prepay_id";
	    $jsApiObj["signType"] = "MD5";
	    $jsApiObj["paySign"] = $this->getSign($jsApiObj);
	    return json_encode($jsApiObj);
	}
	
	public function getPrepayId() {
		$this->parameters["appid"] = $this->appid;
		$this->parameters["mch_id"] = $this->mchid;
		$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];    
		$this->parameters["nonce_str"] = $this->createNoncestr();
		$this->parameters["sign"] = $this->getSign($this->parameters);
		$xml = $this->arrayToXml($this->parameters);
		$this->response = $this->postXmlCurl($xml, $this->url, $this->curl_timeout);
		$this->result = $this->xmlToArray($this->response);
		$this->prepay_id = $this->result["prepay_id"];
		return $this->prepay_id;
	}
	
	public function saveData($xml) {
		$this->data = $this->xmlToArray($xml);
	}
	
	public function checkSign() {
		$tmpData = $this->data;
		unset($tmpData['sign']);
		$sign = $this->getSign($tmpData);//本地签名
		if ($this->data['sign'] == $sign) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function getData() {		
		return $this->data;
	}
	
	public function setReturnParameter($parameter, $parameterValue) {
		$this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}
	
	private function createXml() {
		return $this->arrayToXml($this->returnParameters);
	}
	
	public function returnXml() {
		$returnXml = $this->createXml();
		return $returnXml;
	}

}
