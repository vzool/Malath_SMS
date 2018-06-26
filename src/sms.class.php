<?php

namespace Vzool\Malath;

 //   CopyRight ╘ DTTeam | Mobily | Malath SMS   //

class Malath_SMS {

	// Text Encode that will send ( Your Page or PHP File Encode ) -> ( WINDOWS-1256 || UTF-8 );
	private $TextEncode = "UTF-8";

	// UserName & Password
	private $UserName = "";
	private $Password = "";

	// Main URL for Server on HTTPS only
	private static $URLGateway = "https://sms.malath.net.sa";
	// Url 4 Send SMS
	private static $URLGateway_Send = "/httpSmsProvider.aspx";
	// Url 4 Get Balance
	private static $URLGateway_Credit = "/api/getBalance.aspx";
	// Url 4 Get & ADD Sender Names
	private static $URLGateway_Sender = "/apis/users.aspx";

	// Number of Mobile Numbers That Will Send Every Request .
	private $NUM_Send_Per_Req = 120;

/**************************************************************************************
 * #################### Construct #####################
**************************************************************************************/
	function __construct($User,$Pass,$TextEncode){
		$this->setUserName($User);
		$this->setPassword($Pass);
		$this->setTextEncode($TextEncode);
	}
/**************************************************************************************
 * #################### Send SMS #####################
**************************************************************************************/

	public function Send_SMS($Mobiles,$Sender,$Msg){

        $MSG_Length = $this->StrLen($Msg);
        $MSG_Count = $this->Count_MSG($Msg);

        // 1010 -> SMS Text Grater that 6 part .
        if($MSG_Count>6){
          return 1010;
        }

        if($this->TextEncode=='UTF-8')
            $Msg = iconv('UTF-8','WINDOWS-1256',$Msg);

		if($this->IsItUnicode($Msg)){
			$Msg = $this->ToUnicode($Msg);
			$UC = 'U';
		}else{
			$UC = 'E';
		}

		$Msg = urlencode($Msg);


		try {
			$Result = -1;
    		$_EX_Num = explode(',',$Mobiles);
    		$EX_Num_Count = count($_EX_Num);
    		$_Qesmh = ceil($EX_Num_Count/$this->NUM_Send_Per_Req);
    		$counter = 0;
			$COUNT_OK = $COUNT_FAIL = 0;
			$SEND_OK = $SEND_FAIL = 0;
			$MOB_OK = $MOB_FAIL = '';

			  for ($i=1; $i<=$_Qesmh; $i++)  {
			        $slice = array_slice($_EX_Num, $counter, $this->NUM_Send_Per_Req);

			        $Numr = '';
			        foreach($slice as $_Numr){
			            if( $this->IsMobile($_Numr) && !in_array($_Numr,explode(',',$Numr)) ){
					        $Numr .= $_Numr.',';
			            }
			        }

			        $Numr = substr($Numr,0,-1);
			        if(substr($Numr,0,-1)==',')
			            $Numr = substr($Numr,0,-1);

			        $_Count_Num = count(explode(',',$Numr));

					$URL = self::$URLGateway_Send."?username=".$this->UserName."&password=".$this->Password."&mobile=".$Numr."&sender=".$Sender."&message=".$Msg."&unicode=".$UC;

                    $_url = self::$URLGateway.$URL;
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $_url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HEADER, false);

                    // execute and return string (this should be an empty string '')
                    $Result = curl_exec($curl);
                    curl_close($curl);

	    			//$Result = $this->DT_URLGetData(self::$URLGateway,$URL);
	    			$Result = (integer)str_replace(" ","",$Result);

	    			// -1 -> Something Wrong !!
					// 0 -> Your Message has Sent successfully.
					// 101 -> Parameter are missing.
					// 104 -> Either user name or password are missing or your Account is on hold.
					// 105 -> Credit are not available.
					// 106 -> Wrong Unicode.
					// 107 -> Blocked Sender Name.
					// 108 -> Missing Sender name.
					// 1010 -> SMS Text Grater that 6 part .
					// ELSE -> Unknown Error !.

	    			if($Result==0){
	    				$COUNT_OK += $_Count_Num;
	    				$SEND_OK = 'OK';
	    				$MOB_OK .= $Numr.',';
	    			}else{
	    				$COUNT_FAIL += $_Count_Num;
	    				$SEND_FAIL = 'FAIL';
	    				$MOB_FAIL .= $Numr.',';
	    			}
			        $counter += $this->NUM_Send_Per_Req;
			  }


            $SUB_CREDIT = $MSG_Count * $COUNT_OK;

			$SendReply = array(
							'RESULT'	=>$Result,
							'SEND_OK'	=>$SEND_OK,
							'SEND_FAIL'	=>$SEND_FAIL,
							'COUNT_OK'	=>$COUNT_OK,
							'COUNT_FAIL'=>$COUNT_FAIL,
							'MOB_OK'	=>substr($MOB_OK,0,-1),
							'MOB_FAIL'	=>substr($MOB_FAIL,0,-1),
							'MSG_Length'=>$MSG_Length,
							'MSG_Count'	=>$MSG_Count,
							'SUB_CREDIT'=>$SUB_CREDIT
						);

			return $SendReply;

		} catch (\Exception $e) {
			return $e->getMessage();
		}

	}

/**************************************************************************************
 * #################### Check User Name and Password #####################
**************************************************************************************/


		public function CheckUserPassword(){

	    $URL = self::$URLGateway_Sender."?code=1&username=".$this->UserName."&password=".$this->Password;

	    $Result = $this->DT_URLGetData(self::$URLGateway,$URL);

		if($Result){
			$Result = (integer)str_replace(" ","",$Result);
			// 3101 -> Success.
			// 3102 -> Wrong Password.
			// 3103 -> User Name Don▓t Exist.
			// 3104 -> Account Inactive .
			// 3105 -> Missing Parameter .
			// ELSE -> Unknown Error !.
			$Result = array(
							'RESULT'	=>$Result,
						);
			return $Result;
		}else{
			return 'Connection Error';
		}

	}


/**************************************************************************************
 * #################### Get Sender Names #####################
**************************************************************************************/


	public function GetSenders(){

	    $URL = self::$URLGateway_Sender."?code=9&username=".$this->UserName."&password=".$this->Password;

	    $Result = $this->DT_URLGetData(self::$URLGateway,$URL,1024);

		if($Result){
			// Review senders -> Success.
			// 3102 -> Wrong Password.
			// 3103 -> User Name Don▓t Exist.
			// 3104 -> Account Inactive.
			// 3105 -> Missing Parameter.
			// 3405 -> Time Out Operation.
			// ELSE -> Unknown Error !.
			$Result = str_replace('New SMS','1',$Result);
			$Result = explode(",",$Result);
    		return $Result;
		}else{
			return 'Connection Error';
		}

	}


/**************************************************************************************
 * #################### Get Your Credits #####################
**************************************************************************************/


	public function GetCredits(){

	    $URL = self::$URLGateway_Credit."?username=".$this->UserName."&password=".$this->Password;

	    $Result = $this->DT_URLGetData(self::$URLGateway,$URL);

		if($Result){
			$Result = (integer)str_replace(" ","",$Result);
			return $Result;
		}else{
			return 'Connection Error';
		}

	}

/**************************************************************************************
 * #################### ADD Sender Name #####################
**************************************************************************************/


	public function AddSender($Name){

	    $URL = self::$URLGateway_Sender."?code=2&username=".$this->UserName."&password=".$this->Password."&newsender=".$Name;

	    $Result = $this->DT_URLGetData(self::$URLGateway,$URL);

		if($Result){
			$Result = (integer)str_replace(" ","",$Result);
			// 143 -> Your Sender Name has been received.
			// 3102 -> Wrong Password.
			// 3103 -> User Name Don▓t Exist.
			// 3104 -> Parameter are missing.
			// 443 -> Sender Name Violation Rule.
			// 444 -> Sender Name exist.
			// 3105 -> Missing Parameter.
			// ELSE -> Unknown Error !.
			$Result = array(
							'RESULT'	=>$Result,
						);
			return $Result;
		}else{
			return 'Connection Error';
		}

	}


/**************************************************************************************
 * #################### String Length #####################
**************************************************************************************/


	public function StrLen($Text){

        if($this->TextEncode=='UTF-8')
            $Text = iconv('UTF-8','WINDOWS-1256',$Text);

        return strlen($Text);

    }

/**************************************************************************************
 * #################### Count MSG #####################
**************************************************************************************/


	public function Count_MSG($Text){

        $StrLen = StrLen($Text);
        $MSG_Num = 0;

        if($this->IsItUnicode($Text)){
            if($StrLen>70){
                while($StrLen>0){
                  $StrLen -= 67;
                  $MSG_Num++;
                }
            }else{
                $MSG_Num++;
            }
        }else{
            if($StrLen>160){
                while($StrLen>0){
                  $StrLen -= 134;
                  $MSG_Num++;
                }
            }else{
                $MSG_Num++;
            }
        }

        return $MSG_Num;
}
/**************************************************************************************
 * #################### IsIt Unicode #####################
**************************************************************************************/

	public function IsItUnicode($Text){

		$unicode=false;
  		$str = "олмнЕзшщчкужьъЦДйгАхМстырФиЛАгядафеАецАцбАб║╨©абцдефгхийклмнопярстужьызшщчъАЦДЕ'╘╝Вв╖ФЛМэПЯРСУЖЬЗ";

	  	for($i=0;$i<=strlen($str);$i++){
	    	$strResult= substr($str,$i,1);

	      	for($R=0;$R<=strlen($Text);$R++){
	        	$msgResult= substr($Text,$R,1);

	            if($strResult==$msgResult && $strResult)
	            	$unicode=true;
	           	}
		}

		return $unicode;
	}

/**************************************************************************************
 * #################### Setter & Getter -> $TextEncode #####################
**************************************************************************************/


	public function setTextEncode($TextEncode) {
		$this->TextEncode = $TextEncode;
	}
	public function getTextEncode() {
		return $this->TextEncode;
	}

/**************************************************************************************
 * #################### Setter & Getter -> $UserName #####################
**************************************************************************************/


	public function setUserName($User) {
		$this->UserName = $User;
	}
	public function getUserName() {
		return $this->UserName;
	}

/**************************************************************************************
 * #################### Setter & Getter -> $Password #####################
**************************************************************************************/


	public function setPassword($Pass) {
		$this->Password = $Pass;
	}
	public function getPassword() {
		return $this->Password;
	}


/**************************************************************************************
 * #################### Get Data from URL #####################
**************************************************************************************/


	// "http://www.dt-live.com","/folder/file.php","120"
	public function DT_URLGetData($URL,$File,$Length=120,$case='curl'){
	    switch($case){
			case 'curl':
	            if(extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec')){
	                $init = curl_init();
	                if(!$init){
	                    return $this->DT_URLGetData($URL,$File,$Length,'fopen');
	                }
                    curl_setopt($init, CURLOPT_URL, $URL.$File);
	                curl_setopt($init, CURLOPT_HEADER, 0);
	                curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);

	                if($result = curl_exec($init)){
	                    curl_close($init);
	                    return $result;
	                }else{
	                    return $this->DT_URLGetData($URL,$File,$Length,'fopen');
	                }
	            }else{
	                return $this->DT_URLGetData($URL,$File,$Length,'fopen');
	            }
	        break;
	/*------------------------------------------------------------------*/
	        case 'fopen':
	            if(@ini_get('allow_url_fopen') && @function_exists('fopen')){
	                $link   = @fopen($URL.$File,'r');
	                if(!$link){
	                    return $this->DT_URLGetData($URL,$File,$Length,'fsockopen');
	                }
	                $result = @fread($link, $Length);
	                @fclose($link);
	                return $result;
	            }else{
	                    return $this->DT_URLGetData($URL,$File,$Length,'fsockopen');
	            }
	        break;
	/*------------------------------------------------------------------*/
	        case 'fsockopen':
	            if(@function_exists('fsockopen')){
	                $URL2 = substr($URL, 7);
	                $link  = @fsockopen($URL2, 80, $errno, $errstr, 8);
	                if(!$link){
	                    return false;
	                }
	                $send  = "GET ".$File." HTTP/1.1\r\n";
	                $send .= "Host: ".$URL2."\r\n";
	                $send .= "User-Agent: MalathSMS \r\n";
	                $send .= "Referer: ".$_SERVER["SERVER_NAME"]."\r\n";
	                $send .= "Accept: text/xml,application/xml,application/xhtml+xml,";
	                $send .= "text/html;q=0.9,text/plain;q=0.8,video/x-mng,image/png,";
	                $send .= "Accept-Language: en-us, en;q=0.50\r\n";
	                $send .= "Accept-Encoding: gzip, deflate, compress;q=0.9\r\n";
	                $send .= "Connection: Close\r\n\r\n";
	                @fputs ( $link, $send );
	                $send = '';
	                do{$send .= @fgets ($link); } while ( @strpos ( $send, "\r\n\r\n" ) === false );
	                if(!$send){
	                    return false;
	                }
	                $info = @$this->decode_header ( $send );
	                $send = '';
	                while ( ! @feof ( $link ) ) { $send .= @fread ( $link, $Length ); }
	                @fclose ( $link );
	                $send = @$this->decode_body ( $info, $send );
	                return $send;
	            }else{
	                return false;
	            }
	        break;

	    }
	}

/**************************************************************************************
 * #################### Convert To Unicode #####################
**************************************************************************************/

	private function ToUnicode($Text) {

		$Backslash = "\ ";
		$Backslash = trim($Backslash);

		$UniCode = Array
		(
		    "║" => "060C",
		    "╨" => "061B",
		    "©" => "061F",
		    "а" => "0621",
		    "б" => "0622",
		    "ц" => "0623",
		    "д" => "0624",
		    "е" => "0625",
		    "ф" => "0626",
		    "г" => "0627",
		    "х" => "0628",
		    "и" => "0629",
		    "й" => "062A",
		    "к" => "062B",
		    "л" => "062C",
		    "м" => "062D",
		    "н" => "062E",
		    "о" => "062F",
		    "п" => "0630",
		    "я" => "0631",
		    "р" => "0632",
		    "с" => "0633",
		    "т" => "0634",
		    "у" => "0635",
		    "ж" => "0636",
		    "ь" => "0637",
		    "ы" => "0638",
		    "з" => "0639",
		    "ш" => "063A",
		    "щ" => "0641",
		    "ч" => "0642",
		    "ъ" => "0643",
		    "А" => "0644",
		    "Ц" => "0645",
		    "Д" => "0646",
		    "Е" => "0647",
		    "Ф" => "0648",
		    "Л" => "0649",
		    "М" => "064A",
		    "э" => "0640",
		    "П" => "064B",
		    "Я" => "064C",
		    "Р" => "064D",
		    "С" => "064E",
		    "У" => "064F",
		    "Ж" => "0650",
		    "Ь" => "0651",
		    "З" => "0652",
		    "!" => "0021",
		    '"' => "0022",
		    "#" => "0023",
		    "$" => "0024",
		    "%" => "0025",
		    "&" => "0026",
		    "'" => "0027",
		    "(" => "0028",
		    ")" => "0029",
		    "*" => "002A",
		    "+" => "002B",
		    "," => "002C",
		    "-" => "002D",
		    "." => "002E",
		    "/" => "002F",
		    "0" => "0030",
		    "1" => "0031",
		    "2" => "0032",
		    "3" => "0033",
		    "4" => "0034",
		    "5" => "0035",
		    "6" => "0036",
		    "7" => "0037",
		    "8" => "0038",
		    "9" => "0039",
		    ":" => "003A",
		    ";" => "003B",
		    "<" => "003C",
		    "=" => "003D",
		    ">" => "003E",
		    "?" => "003F",
		    "@" => "0040",
		    "A" => "0041",
		    "B" => "0042",
		    "C" => "0043",
		    "D" => "0044",
		    "E" => "0045",
		    "F" => "0046",
		    "G" => "0047",
		    "H" => "0048",
		    "I" => "0049",
		    "J" => "004A",
		    "K" => "004B",
		    "L" => "004C",
		    "M" => "004D",
		    "N" => "004E",
		    "O" => "004F",
		    "P" => "0050",
		    "Q" => "0051",
		    "R" => "0052",
		    "S" => "0053",
		    "T" => "0054",
		    "U" => "0055",
		    "V" => "0056",
		    "W" => "0057",
		    "X" => "0058",
		    "Y" => "0059",
		    "Z" => "005A",
		    "[" => "005B",
		    $Backslash => "005C",
		    "]" => "005D",
		    "^" => "005E",
		    "_" => "005F",
		    "`" => "0060",
		    "a" => "0061",
		    "b" => "0062",
		    "c" => "0063",
		    "d" => "0064",
		    "e" => "0065",
		    "f" => "0066",
		    "g" => "0067",
		    "h" => "0068",
		    "i" => "0069",
		    "j" => "006A",
		    "k" => "006B",
		    "l" => "006C",
		    "m" => "006D",
		    "n" => "006E",
		    "o" => "006F",
		    "p" => "0070",
		    "q" => "0071",
		    "r" => "0072",
		    "s" => "0073",
		    "t" => "0074",
		    "u" => "0075",
		    "v" => "0076",
		    "w" => "0077",
		    "x" => "0078",
		    "y" => "0079",
		    "z" => "007A",
		    "{" => "007B",
		    "|" => "007C",
		    "}" => "007D",
		    "~" => "007E",
		    "╘" => "00A9",
		    "╝" => "00AE",
		    "В" => "00F7",
		    "в" => "00F7",
		    "╖" => "00A7",
		    " " => "0020",
		    "\n" => "000D",
			"\r" => "000A",
		    "\t" => "0009",
		    "И" => "00E9",
		    "Г" => "00E7",
		    "Ю" => "00E0",
		    "Ы" => "00F9",
		    "╣" => "00B5",
		    "Х" => "00E8"
		);

		$Result="";
		$StrLen = strlen($Text);
		for($i=0;$i<$StrLen;$i++){

			$currect_char = substr($Text,$i,1);

			if(array_key_exists($currect_char,$UniCode)){
				$Result .= $UniCode[$currect_char];
			}

		}

	 	return $Result;

	}

/**************************************************************************************
 * #################### FSockOpen Header #####################
**************************************************************************************/

	private function IsMobile(&$M){
	    $count = strlen($M);
	    $New = '';
	    $ARRAY_NUM = array('0','1','2','3','4','5','6','7','8','9');
	    for($x=0;$x<=$count;$x++){
	    if(in_array(substr($M, $x, 1),$ARRAY_NUM)){
	            $New .= substr($M, $x, 1);
	        }
	    }
	    $M = $New;
	    if(substr($New, 0, 2)=="00" || substr($New, 0, 1)=="0"){
	        return false;
	    }else{
	        if(substr($New, 0, 3)=="966"){
	            if(substr($New, 3, 1)=="0" || strlen($New)!="12"){
	                return false;
	            }else{
	                return true;
	            }
	        }else{
	            return true;
	        }
	    }
	}
/**************************************************************************************
 * #################### FSockOpen Header #####################
**************************************************************************************/

	#+++++++++++++++++++++++++++++ Start fsockopen ++++++++++++++++++++++++++++++++#
	private function decode_header ( $str )
	{
	    $part = preg_split ( "/\r?\n/", $str, -1, PREG_SPLIT_NO_EMPTY );

	    $out = array ();

	    for ( $h = 0; $h < sizeof ( $part ); $h++ )
	    {
	        if ( $h != 0 )
	        {
	            $pos = strpos ( $part[$h], ':' );

	            $k = strtolower ( str_replace ( ' ', '', substr ( $part[$h], 0, $pos ) ) );

	            $v = trim ( substr ( $part[$h], ( $pos + 1 ) ) );
	        }
	        else
	        {
	            $k = 'status';

	            $v = explode ( ' ', $part[$h] );

	            $v = $v[1];
	        }

	        if ( $k == 'set-cookie' )
	        {
	                $out['cookies'][] = $v;
	        }
	        else if ( $k == 'content-type' )
	        {
	            if ( ( $cs = strpos ( $v, ';' ) ) !== false )
	            {
	                $out[$k] = substr ( $v, 0, $cs );
	            }
	            else
	            {
	                $out[$k] = $v;
	            }
	        }
	        else
	        {
	            $out[$k] = $v;
	        }
	    }

	    return $out;
	}

	private function decode_body ( $info, $str, $eol = "\r\n" )
	{
	    $tmp = $str;

	    $add = strlen ( $eol );

	    $str = '';

	    if ( isset ( $info['transfer-encoding'] ) && $info['transfer-encoding'] == 'chunked' )
	    {
	        do
	        {
	            $tmp = ltrim ( $tmp );

	            $pos = strpos ( $tmp, $eol );

	            $len = hexdec ( substr ( $tmp, 0, $pos ) );

	            if ( isset ( $info['content-encoding'] ) )
	            {
	                $str .= gzinflate ( substr ( $tmp, ( $pos + $add + 10 ), $len ) );
	            }
	            else
	            {
	                $str .= substr ( $tmp, ( $pos + $add ), $len );
	            }

	            $tmp = substr ( $tmp, ( $len + $pos + $add ) );

	            $check = trim ( $tmp );

	        } while ( ! empty ( $check ) );
	    }
	    else if ( isset ( $info['content-encoding'] ) )
	    {
	        $str = gzinflate ( substr ( $tmp, 10 ) );
	    }

	    return $str;
	}
	#++++++++++++++++++++++++++++++ End fsockopen +++++++++++++++++++++++++++++++++#

}