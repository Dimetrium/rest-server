<?php
//Retrieves all cars
$app->get('/api/{type:((cars)(\.(json|xml|txt|html))?)}', function ($type) use ($app) {

    $type = explode('.', $type);
    array_key_exists('1', $type) ? $type = $type[1] : $type = null;
    $cars = Cars::find();

    $handler = new Cars();

    $data = [];
    foreach ($cars as $car) {
        $data[] = [
            'id' => $car->getId(),
            'brand' => $car->getBrand(),
            'model' => $car->getModel()
        ];
    }

    return $handler->typeHandler($app, $data, $type);
});

//Searches for cars (whithout format responce)
$app->get('/api/cars/search/{year}', function ($year ) use ($app) {
    if ('' !== $year) {
        $cars = new Cars();
        $cars = $cars->parseQuery($app, $year);
    } else {
        $app->response->setStatusCode(415, "Year field can not be empty")->sendHeaders();
    }
    $data = [];

    foreach ($cars as $car) {
        $data[] = [
            'id' => $car->getId(),
            'brand' => $car->getBrand(),
            'color' => $car->getColor(),
            'year' => $car->getYear()
        ];
    }

    if (null == $data) {
        $app->response->setStatusCode(415, "Oops, Sorry no data found")->sendHeaders();
    } else {
        $app->response->setJsonContent($data);
        return $app->response;
    }
});

//Retrieves cars based on primary key
$app->get('/api/cars/{id:(([0-9]+)+(\.(json|xml|txt|html))?)}', function ( $id ) use ( $app ) {
    $data = explode('.', $id);

    $phql = "SELECT * FROM Cars WHERE id = :id:";

    $cars = $app->modelsManager->executeQuery($phql, ['id' => $data[0]])->getFirst();
    $cars = [ 0 => (array) $cars = [
    'model' => $cars->getModel(),
    'year' => $cars->getYear(),
    'volume' => $cars->getVolume(),
    'color' => $cars->getColor(),
    'speed' => $cars->getSpeed(),
    'price' => $cars->getPrice()
    ]];
    $handler = new Cars();
    if (false == $cars) {
        $app->response->setStatusCode(415, "Oops, Sorry no data found")->sendHeaders();
    } else {
        array_key_exists('1', $data) ? $type = $data[1] : $type = 'json';
        return $handler->typeHandler($app, $cars, $type);
    }
});

//Add's a new orders
$app->post('/api/order', function () use ( $app ) {

    $order = $app->request->getJsonRawBody();

    $phql = "INSERT INTO Orders (id, year, color, speed, volume, price, model, brand)
    VALUES (:id:, :year:, :color:, :speed:, :volume:, :price:, :model:, :brand:)";

    $status = $app->modelsManager->executeQuery($phql, [
        'id' => '',
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volume,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand
            ]);

    //Create a response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if (true == $status->success()) {

        // Change the HTTP status
        $response->setStatusCode(201, "Created");

        $car->id = $status->getModel()->getId();

        $response->setJsonContent([ 'status' => 'OK', 'data' => $car]);
    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        //Send errors to the client
        $errors = [];
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent([ 'status' => 'ERROR', 'messages' => $errors]);
    }

    return $response;
});

//Add new user
$app->post('/api/registration', function () use ( $app ) {

    $car = $app->request->getJsonRawBody();

    $phql = "INSERT INTO Users (id, year, color, speed, volume, price, model, brand)
    VALUES (:id:, :year:, :color:, :speed:, :volume:, :price:, :model:, :brand:)";

    $status = $app->modelsManager->executeQuery($phql, [
        'id' => '',
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volume,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand
            ]);

    //Create a response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if (true == $status->success()) {

        // Change the HTTP status
        $response->setStatusCode(201, "Created");

        $car->id = $status->getModel()->getId();

        $response->setJsonContent([ 'status' => 'OK', 'data' => $car]);
    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        //Send errors to the client
        $errors = [];
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent([ 'status' => 'ERROR', 'messages' => $errors]);
    }

    return $response;
});

//Log In
$app->put('/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $car = $app->request->getJsonRawBody();

    $phql = "UPDATE Cars SET
    year = :year:,
    color = :color:,
    speed = :speed:,
    volume = :volume:,
    price = :price:,
    model = :model:,
    brand = :brand:
    WHERE id = :id:";

    $status = $app->modelsManager->executeQuery($phql, [
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volume,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand,
        'id' => $id
            ]);

    //Create response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if (true == $status->success()) {
        $response->setJsonContent([ 'status' => 'OK']);
    } else {

        //Change the HTML status
        $response->setStatusCode(409, "Conflict");

        $errors = [];
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent([ 'status' => 'ERROR', 'messages' => $errors]);
    }

    return $response;
});

//Log Out
$app->delete('/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $phql = "DELETE FROM Cars WHERE id = :id:";
    $status = $app->modelsManager->executeQuery($phql, [
        'id' => $id
            ]);

    $response = new Phalcon\Http\Response();

    if ($status->success() == true) {
        $response->setJsonContent([ 'status' => 'OK']);
    } else {

        $response->setStatusCode(409, "Conflict");

        $errors = [];
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(['status' => 'ERROR', 'messages' => $errors]);
    }

    return $response;
});


/**
 * Not found handler
 */
$app->notFound(function () use ( $app ) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    return $app->response;
});
