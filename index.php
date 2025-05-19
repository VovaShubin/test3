<?php

    $file = 'test.log';

    $error_msg = '';
    $res = [];

    if ($logFileHandle = fopen($file, 'a')) {
        fwrite($logFileHandle, print_r($_POST , true) . PHP_EOL);
        fclose($logFileHandle);
    }


    function httpRequest($method, $params = '', $data = [], $headers = [])
    {
        global $error_msg;
        $api_url = "https://permskyvovan.amocrm.ru/api/v4";
        $api_key = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE4NDU3NjJmY2RhOTM4ODI4YmUyOTIwZTNkOGMxNGE1YjFmYmNkZmQ4Y2FiNzg5Mjc5MzE2ZTM0N2Q0MGUwZmFiZmQwNmFlYmQzZjYzMTIxIn0.eyJhdWQiOiI2YzM2NTUyNi04ODJmLTQ4YzItODE1MS0wYWE5NWIwOTUzMDgiLCJqdGkiOiIxODQ1NzYyZmNkYTkzODgyOGJlMjkyMGUzZDhjMTRhNWIxZmJjZGZkOGNhYjc4OTI3OTMxNmUzNDdkNDBlMGZhYmZkMDZhZWJkM2Y2MzEyMSIsImlhdCI6MTc0NzY0MzAxMywibmJmIjoxNzQ3NjQzMDEzLCJleHAiOjE3NzE0NTkyMDAsInN1YiI6IjEyNTA0MDk0IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMyNDI0MjEwLCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiODc5MjJjYTYtYTI0MS00NDgzLWJiNzAtYWE2MmIxZWZkYzFmIiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.HIQ3SzFw_RRXPIeh7kX09Shqs2_OSxZ1iNjXBkWh-MN7U5B-Gyx-gYVDSbtKIhynFpQyhsvOoBSL_eJddnl1r5XtsJqQOERRWuAprTYXTaA7QDN_ObcopVkuaBnELuY5lCx5KIVKFzbfbOlaFJWwONrAOpeCfyULXBOTvtnDKT6WN5ZPERv5opODLjIn5N2GAsiTtHjxuPFkxngJqzPsWojIUkjscfD45bcOt9MA5U_FN2CZ8CQKRU_qN88weP2xXbwfQyJRC64t4tN6n44Z2RshLlBzEyT5cLVddlEQ4UvsFetebtZ2vvz7r24pV0uVK37eJ9hZLN78zGMZxyhjgg";

        $headers[] = 'Content-Type: application/json';
        $headers[] = "Authorization: Bearer ". $api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url . $params );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if($method == 'POST' && !empty($data))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg .= curl_error($ch);
            throw new \Exception($error_msg);
            return false;
        }
        curl_close($ch);
        return $output ? json_decode($output, true) : null;
    }

    function dataHelper($type, $event)
    {
        global $res;
        $responsible_user = httpRequest('GET', '/users/' . $_POST[$type][$event][0]["responsible_user_id"]);
        $str = "name - " . $_POST[$type][$event][0]["name"] ;
        $str .= "\n responsible_user - " . $responsible_user["name"];
        if ($event == 'add')  $str .= "\n created_at - " . $_POST[$type][$event][0]["created_at"];
        if ($event == 'update')
        {
            $str .= "\n updated_at - " . $_POST[$type][$event][0]["updated_at"];
            if ($type == 'leads') $str .= "\n price - " . $_POST[$type][$event][0]["price"];
        }
        $data = [[
            'entity_id' => intval($_POST[$type][$event][0]["id"]),
            'note_type' => "common",
            "params"=> ["text" => $str]
        ]];
        $res = httpRequest('POST', '/'.$type.'/notes', $data);
        return true;
    }

    //print_r(httpRequest('GET', '/webhooks'));
    /*$data = [
				'destination' => 'http://n91946p2.beget.tech/',
				"settings"=> ["add_lead"]
			];

	print_r(json_encode($data));

	print_r(httpRequest('POST', '/webhooks', $data));*/

    if (isset($_POST['leads'])){
        if (isset($_POST["leads"]["add"]))dataHelper('leads','add');
        if (isset($_POST["leads"]["update"]))dataHelper('leads','update');
    }

    if (isset($_POST['contacts'])){
        if (isset($_POST["contacts"]["add"])) dataHelper('contacts','add');
        if (isset($_POST["contacts"]["update"])) dataHelper('contacts','update');
    }

    if ($error_msg!='' or !empty($res)){
        if ($logFileHandle = fopen($file, 'a')) {
            fwrite($logFileHandle, print_r($error_msg , true) . PHP_EOL);
            fwrite($logFileHandle, print_r($res , true) . PHP_EOL);
            fclose($logFileHandle);
        }
    }

?>
