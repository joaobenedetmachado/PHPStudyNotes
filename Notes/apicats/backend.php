<?php
    // Tipo de resposta = JSON
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Resposta padrão do backend: 
    $response = array(
        "image" => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/0.png"
    );

        $url = "https://api.thecatapi.com/v1/images/search";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $apiResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        
 
    // Se for bem sucedido e ter resposta (code 200), pega as informações json e decoda
    if($httpCode == 200 && $apiResponse){
        $imageCat = json_decode($apiResponse, true);

        $response = array(
            "image" => $imageCat,
        );
    } else {
        $response = "Erro HTTP: $httpCode";
    }

    echo json_encode($response);
?>

