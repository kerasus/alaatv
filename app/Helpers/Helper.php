<?php
/**
 * Created by PhpStorm.
 * User: mohamamad
 * Date: 10/27/2016
 * Time: 3:38 PM
 */

namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Response;

class Helper
{
    public function __construct()
    {
        $this->response = new Response();
    }

    private function jalali_to_gregorian($j_y, $j_m, $j_d, $mod = '')
    {

        $d_4 = ($j_y + 1) % 4;

        $doy_j = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d : (($j_m - 7) * 30) + $j_d + 186;

        $d_33 = (int)((($j_y - 55) % 132) * .0305);

        $a = ($d_33 != 3 and $d_4 <= $d_33) ? 287 : 286;

        $b = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);

        if ((int)(($j_y - 19) / 63) == 20) {
            $a--;
            $b++;
        }

        if ($doy_j <= $a) {

            $gy = $j_y + 621;
            $gd = $doy_j + $b;

        } else {

            $gy = $j_y + 622;
            $gd = $doy_j - $a;

        }

        foreach (array(0, 31, ($gy % 4 == 0) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {

            if ($gd <= $v) break;

            $gd -= $v;

        }

        return ($mod == '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;

    }

    private function gregorian_to_jalali($g_y, $g_m, $g_d, $mod = '')
    {

        $d_4 = $g_y % 4;

        $g_a = array(0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);

        $doy_g = $g_a[(int)$g_m] + $g_d;

        if ($d_4 == 0 and $g_m > 2) $doy_g++;

        $d_33 = (int)((($g_y - 16) % 132) * .0305);

        $a = ($d_33 == 3 or $d_33 < ($d_4 - 1) or $d_4 == 0) ? 286 : 287;

        $b = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);

        if ((int)(($g_y - 10) / 63) == 30) {
            $a--;
            $b++;
        }

        if ($doy_g > $b) {

            $jy = $g_y - 621;
            $doy_j = $doy_g - $b;

        } else {

            $jy = $g_y - 622;
            $doy_j = $doy_g + $a;

        }

        if ($doy_j < 187) {

            $jm = (int)(($doy_j - 1) / 31);
            $jd = $doy_j - (31 * $jm++);

        } else {

            $jm = (int)(($doy_j - 187) / 30);
            $jd = $doy_j - 186 - ($jm * 30);
            $jm += 7;

        }

        return ($mod == '') ? array($jy, $jm, $jd) : $jy . $mod . $jm . $mod . $jd;

    }

    public  function convertDate($date , $convertType){
        if(strcmp($convertType , 'toJalali') == 0 && strlen($date)>0)
        {
            $explodedDate = explode(" " ,$date);
            $explodedDate= $explodedDate[0];
            $explodedDate = explode("-" , $explodedDate);
            $year = $explodedDate[0];
            $month = $explodedDate[1];
            $day = $explodedDate[2] ;
            return $this->gregorian_to_jalali($year , $month , $day , "/") ;
        }elseif(strcmp($convertType , 'toMiladi') == 0 && strlen($date)>0){
            $explodedDate = explode("/" ,$date);
            $year = $explodedDate[0];
            $month = $explodedDate[1];
            $day = $explodedDate[2] ;
            return $this->jalali_to_gregorian($year , $month , $day , "-") ;
        }
    }

    /**
     * Sending SMS request to Mediana SMS Panel
     *
     * @param array $params
     * @return string
     */
    public function medianaSendSMS(array $params)
    {
        $url = getenv("MEDIANA_API_URL");

//        $rcpt_nm = array('9121111111','9122222222');
        if(isset($params["to"])) $rcpt_nm =  $params["to"];
        if(isset($params["from"]))
            $from = $params["from"];
        else
            $from =getenv("SMS_PROVIDER_DEFAULT_NUMBER") ;

            $param = array
        (
            'uname'=>getenv("MEDIANA_USERNAME"),
            'pass'=>getenv("MEDIANA_PASSWORD"),
            'from'=>$from,
            'message'=>$params["message"],
            'to'=>json_encode($rcpt_nm),
            'op'=>'send'
        );

        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);
        $response =  json_decode($response);
        $res_code = $response[0];
        $res_data = $response[1];

        switch ($res_code)
        {
            case 0 :
                return array("error"=>false , "message"=>"ارسال موفقیت آمیز بود");
                break;
            default:
                return array("error"=>true , "message"=>"پاسخ سرور نا شناخته است");
                break;
        }
    }

    /**
     * Sending SMS request to Mediana SMS Panel
     *
     * @param array $params
     * @return string
     */
    public function medianaSendPatternSMS(array $params)
    {
        $client = new \SoapClient(getenv("MEDIANA_PATTERN_API_URL"));
        $user = getenv("MEDIANA_USERNAME");
        $pass = getenv("MEDIANA_PASSWORD");
        if(isset($params["from"]))
            $from = $params["from"];
        else
            $from =getenv("SMS_PROVIDER_DEFAULT_NUMBER") ;
        if(isset($params["to"])) $rcpt_nm =  $params["to"];
        if(isset($params["patternCode"])) $pattern_code = $params["patternCode"];
        if(isset($params["inputData"])) $input_data = $params["inputData"];

        $response = $client->sendPatternSms($from , $rcpt_nm , $user , $pass , $pattern_code  , $input_data);
        if($response) return $this->response->setStatusCode(200);
        else return $this->response->setStatusCode(503);
    }

    public function medianaGetCredit()
    {
        $url = getenv("MEDIANA_API_URL");
        $param = array
        (
            'uname'=>getenv("MEDIANA_USERNAME"),
            'pass'=>getenv("MEDIANA_PASSWORD"),
            'op' => 'credit',
        );
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);
        $response =  json_decode($response);
        $res_code = $response[0];
        $res_data = $response[1];
        switch ($res_code)
        {
            case 0 :
                return $res_data;
                break;
            default:
                return false;
                break;
        }

    }

    /**
     * Generates a random password that does not belong to anyone
     *
     * @param int $length
     * @return string
     */
    public function generateRandomPassword($length)
    {
        //uncomment for avoiding giving similar passwords
//        for($i=1 ; $i<=100 ; $i++)
//        {
//            $generatedPassword =rand(1000,99999);
//            $generatedPasswordHash = bcrypt($generatedPassword);
//            $similarPasswords = User::all()->where("password" , $generatedPasswordHash);
//            if($similarPasswords->isEmpty()){
//                break;
//            }
//        }
//        if(!isset($generatedPassword) || !isset($generatedPasswordHash)){
//            return false;
//        }
        $generatedPassword = rand(1000,9999);
        $generatedPasswordHash = bcrypt($generatedPassword);
        return ["rawPassword"=>$generatedPassword , "hashPassword"=>$generatedPasswordHash];
    }

    public function timeFilterQuery($list, $sinceDate, $tillDate, $by = 'created_at'){
        $sinceDate = Carbon::parse($sinceDate)->format('Y-m-d') . " 00:00:00";
        $tillDate = Carbon::parse($tillDate)->format('Y-m-d') . " 23:59:59";
        $list = $list->whereBetween($by, [$sinceDate, $tillDate]);
        return $list;
    }

    public function generateSecurePathHash($expires , $client_IP ,  $secret , $url)
    {
        $str =$expires.$url.$client_IP." ".$secret;
        $str = base64_encode(md5($str,true));

        $str = str_replace("+","-",$str);
        $str = str_replace("/","_",$str);
        $str = str_replace("=","",$str);
        return $str;
    }

}