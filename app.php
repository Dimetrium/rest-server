<?php
//Retrieves all cars
$app->get( '/api/cars', function () {

    $cars = Cars::find();

    $data = [];

    foreach ( $cars as $car ) {
        $data[ ] = [
            'id' => $car->getId(),
            'brand' => $car->getBrand(),
            'year' => $car->getYear()
        ];
    }
    echo json_encode( $data );
} );

//Searches for cars with $brand
$app->get( '/api/cars/search/{brand}', function ( $brand )  {

    $conditions = 'brand LIKE :brand: ORDER BY brand';
    $parameters =  ['brand' => $brand];
    $cars = Cars::find([$conditions, "bind" => $parameters]);

    $data = [];

    foreach ( $cars as $car ) {
        $data[ ] = [
            'id' => $car->getId(),
            'brand' => $car->getBrand()
        ];
    }
    echo json_encode( $data );
} );

//Retrieves cars based on primary key
$app->get( '/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $phql = "SELECT * FROM Cars WHERE id = :id:";
    $cars = $app->modelsManager->executeQuery($phql, ['id' => $id])->getFirst();
    
    $response = new Phalcon\Http\Response();
    if ( false == $cars ) {
        $response->setJsonContent( [ 'status' => 'NOT-FOUND' ] );
    } else {
        $response->setJsonContent( [
            'status' => 'FOUND',
            'data' => [
                    'id' => $cars->getId(), 
                    'brand' => $cars->getbrand()]
            ]
         );
    }

    return $response;
} );

//Adds a new cars
$app->post( '/api/cars', function () use ( $app ) {

    $car = $app->request->getJsonRawBody();

    $phql = "INSERT INTO cars (year, color, speed, volume, price, model, brand)
                VALUES (:year:, :color:, :speed:, :volume:, :price:, :model:, :brand:)";

    $status = $app->modelsManager->executeQuery( $phql, [
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volume,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand
    ] );

    //Create a response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if ( true == $status->status() ) {

        // Change the HTTP status
        $response->setStatusCode( 201, "Created" );

        $car->id = $status->getModel()->id;

        $response->setJsonContent( [ 'status' => 'OK', 'data' => $car ] );

    } else {

        //Change the HTTP status
        $response->setStatusCode( 409, "Conflict" );

        //Send errors to the client
        $errors = [];
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( [ 'status' => 'ERROR', 'messages' => $errors]  );
    }

    return $response;
} );

//Updates cars based on primary key
$app->put( '/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $car = $app->request->getJsonRawBody();

    $phql = "UPDATE cars SET
                year = :year:,
                color = :color:,
                speed = :speed:,
                volume = :volume:,
                price = :price:,
                model = :model:,
                brand = :brand:
                WHERE id = :id:";

    $status = $app->modelsManager->executeQuery( $phql, [
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volum,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand,
        'id' => $id
    ] );

    //Create response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if ( true == $status->success() ) {
        $response->setJsonContent( [ 'status' => 'OK' ]);
    } else {

        //Change the HTML status
        $response->setStatusCode( 409, "Conflict" );

        $errors = [];
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( [ 'status' => 'ERROR', 'messages' => $errors ] );
    }

    return $response;
} );

//Deletes cars based on primary key
$app->delete( '/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $phql = "DELETE FROM cars WHERE id = :id:";
    $status = $app->modelsManager->executeQuery( $phql, [
        'id' => $id
    ] );

    $response = new Phalcon\Http\Response();

    if ( $status->success() == true ) {
        $response->setJsonContent( [ 'status' => 'OK' ] );
    } else {

        $response->setStatusCode( 409, "Conflict" );

        $errors = [];
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( ['status' => 'ERROR', 'messages' => $errors ] );

    }

    return $response;
} );


/**
 * Not found handler
 */
$app->notFound( function () use ( $app ) {
    $app->response->setStatusCode( 404, "Not Found" )->sendHeaders();
    echo $app[ 'view' ]->render( '404' );
} );
