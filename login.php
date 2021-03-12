<?php
$app->post('/login', function (Request $request, Response $response, $args) {

require_once("connection.php");

$params = $request->getParsedBody();

    $SQL = "SELECT *
    FROM users     
    WHERE users.email =?";

    $stmt = $mysqli->prepare($SQL);
    
    $stmt->bind_param("s", $params['email']);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch the data   

if($params['password'] == $data[0]['password']){

    $token = sha1(time());

    $SQL = "UPDATE users SET users.token=? WHERE users.email =? ";
    $stmt = $mysqli->prepare($SQL);
    
    $stmt->bind_param("ss", $token,$params['email']);
    $stmt->execute();

    $response
    ->getBody()
    ->write(json_encode([
        'status' => 'success',
        'token' => $token
        ]));

        return $response->withStatus(200);

}else{

    $response->getBody()->write(json_encode(['status' => 'error']));
    return $response->withStatus(401); 

}


});
?>