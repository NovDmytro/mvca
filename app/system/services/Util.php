<?php

namespace Service;

class Util
{

    public function timeRangeFromDate($date,$range)
    {
        list($dateArr['day'], $dateArr['month'], $dateArr['year']) = explode("-", $date);
        if (!$dateArr['month'] || !$dateArr['day'] || !$dateArr['year'] || !checkdate($dateArr['month'], $dateArr['day'], $dateArr['year'])) {
            $currentTime = time();
            $dateArr['day'] = date("d", $currentTime);
            $dateArr['month'] = date("m", $currentTime);
            $dateArr['year'] = date("Y", $currentTime);
            $date = $dateArr['day'] . '-' . $dateArr['month'] . '-' . $dateArr['year'];
        }
        $timeRange['from'] = mktime(0, 0, 0, $dateArr['month'], $dateArr['day'], $dateArr['year']);
        if ($range == 'day') {
        $timeRange['to'] = mktime(23, 59, 59, $dateArr['month'], $dateArr['day'], $dateArr['year']);
    }
        $timeRange['date']=$date;
        return $timeRange;

    }











    public function asyncRequest($scheme, $domain, $request) {
        $port = ($scheme === 'https') ? 443 : 80;
$timeout=2;
        if ($scheme === 'https') {
	$context = stream_context_create(array( 'ssl' => array(
'verify_peer'       => false,
'verify_peer_name'  => false,
'allow_self_signed' => true,
'verify_depth'      => 0 )));
$socket = stream_socket_client('tcp://'.$domain.':'.$port,$errno,$errstr,$timeout,STREAM_CLIENT_CONNECT, $context);
stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_SSLv2_CLIENT|STREAM_CRYPTO_METHOD_SSLv3_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
}else{
    $socket = stream_socket_client('tcp://'.$domain.':'.$port,$errno,$errstr,$timeout);
}
if($socket){
    fwrite($socket,$request);
    fclose($socket);
    return true;
}else{
    fclose($socket);
    return false;}
}


    public function sendRequest($scheme, $domain, $request) {
        $port = ($scheme === 'https') ? 443 : 80;
        $timeout=2;
        if ($scheme === 'https') {
            $context = stream_context_create(array( 'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
                'verify_depth'      => 0 )));
            $socket = stream_socket_client('tcp://'.$domain.':'.$port,$errno,$errstr,$timeout,STREAM_CLIENT_CONNECT, $context);
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_SSLv2_CLIENT|STREAM_CRYPTO_METHOD_SSLv3_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT|STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
        }else{
            $socket = stream_socket_client('tcp://'.$domain.':'.$port,$errno,$errstr,$timeout);
        }
        if($socket){
            fwrite($socket,$request);
            $response = '';
            while (!feof($socket)) {
                $response .= fgets($socket);
            }
            fclose($socket);
            list($headers, $body) = explode("\r\n\r\n", $response, 2);
            return $body;
        }else{
            fclose($socket);
            return false;}
    }

}
