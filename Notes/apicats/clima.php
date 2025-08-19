<?php
    // Tipo de resposta = JSON
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    $response = array(
        "city" => "sem cidade"
    );

        $url = "http://ip-api.com/json/";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $apiResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        
 
    if($httpCode == 200 && $apiResponse){
        $city = json_decode($apiResponse, true);

        $response = array(
            "city" => $city,
        );
    } else {
        $response = "Erro HTTP: $httpCode";
    }

    echo json_encode($response);
?>

