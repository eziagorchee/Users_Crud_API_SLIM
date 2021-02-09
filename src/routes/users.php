<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response, array $args) {

    $response->getBody()->write("Hello, Welcome to the home page.");

    return $response;
});


//Get All User
$app->get("/api/users", function (Request $request, Response $response, array $args) {
    $sql = "SELECT * FROM users";

    try {
    
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->query($sql);
        $users =$stmt->fetchAll();
        $pdo = null;
        echo json_encode(
            [
                "message" => "Success",
                "data" => $users
            ]
        );
    } catch (\PDOException $e) {
        echo json_encode(
            [
                "message" => "Error Occured",
                "Error" => $e->getMessage()
            ]
        );
    }
});

//Get Single User
$app->get("/api/get_single/{id}", function (Request $request, Response $response, array $args) {
    //One way of getting the ID from the PATH
    //$id = $args['id'];
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM users WHERE id=$id";

    try {
    
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->query($sql);
        $users =$stmt->fetch();
        $pdo = null;
        echo json_encode(
            [
                "message" => "Success",
                "data" => $users
            ]
        );
    } catch (\PDOException $e) {
        echo json_encode(
            [
                "message" => "Error Occured",
                "Error" => $e->getMessage()
            ]
        );
    }
});


//Create a new user and update on Duplicate
$app->post("/api/create_user", function (Request $request, Response $response, array $args) {

    $val = $request->getParsedBody();
    $id = $val['id'];
    ($id == "0") ? $id = null : $id =$id;
    $first_name = $val['first_name'];
    $last_name = $val['last_name'];
    $phone = $val['phone'];
    $email = $val['email'];
    $city = $val['city'];
    $address = $val['address'];
    $state = $val['state'];
    $sql = "INSERT INTO users (id, first_name, last_name, phone, email, city, address, state) VALUES (?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE first_name=?, last_name=?,phone =?, email = ?, city=?,address=?,state=? ";

    try {
    
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $first_name, $last_name, $phone, $email, $city, $address, $state, $first_name, $last_name, $phone, $email, $city, $address, $state]);
        $pdo = null;
        echo json_encode(
            [
                "message" => "User has been created.",
                "status" => "success"
            ]
        );
    } catch (\PDOException $e) {
        echo json_encode(
            [
                "message" => "Error Occured",
                "Error" => $e->getMessage()
            ]
        );
    }
});

//Create a new user
$app->delete("/api/delete_user/{id}", function (Request $request, Response $response, array $args) {

    $id = $request->getAttribute('id');

    $sql = "DELETE FROM users WHERE id=$id";
    try {
    
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $pdo = null;
        echo json_encode(
            [
                "message" => "User has been deleted.",
                "status" => "success"
            ]
        );
    } catch (\PDOException $e) {
        echo json_encode(
            [
                "message" => "Error Occured",
                "Error" => $e->getMessage()
            ]
        );
    }
});


$app->run();
