<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get( '/', function () use ( $app ) {
    echo $app[ 'view' ]->render( 'index' );
} );


//Retrieves all cars
/**
 * @var $phql - PHQL, allow us to write queries using a high-level,
 *              object-oriented SQL dialect that internally translates
 *              to the right SQL statements depending on the database system we are using.
 *
 */
$app->get( '/api/cars', function () use ( $app ) {

    $cars = Cars::find();

    $data = array();

    foreach ( $cars as $car ) {
        $data[ ] = array(
            'id' => $car->id,
            'brand' => $car->brand,
            'year' => $car->year
        );
    }
    echo json_encode( $data );
} );

//Searches for cars with $brand
$app->get( '/api/cars/search/{brand}', function ( $brand ) use ( $app ) {

    $conditions = 'brand LIKE :brand: ORDER BY brand';
    $parameters =  ['brand' => $brand];
    $cars = Cars::find([$conditions, "bind" => $parameters]);

    $data = array();

    foreach ( $cars as $car ) {
        $data[ ] = array(
            'id' => $car->id,
            'brand' => $car->brand
        );
    }
    echo json_encode( $data );
} );

//Retrieves cars based on primary key
$app->get( '/api/car/{id:[0-9]+}', function ( $id ) use ( $app ) {
    $phql = "SELECT * FROM cars WHERE id = :id:";
    $car = $app->modelsManager->executeQuery( $phql, array(
        'id' => $id
    ) )->getFirst();

    //Create a response
    $response = new Phalcon\Http\Response();

    if ( false == $car ) {
        $response->setJsonContent( array( 'status' => 'NOT-FOUND' ) );
    } else {
        $response->setJsonContent( array(
            'status' => 'FOUND',
            'data' => array(
                'id' => $car->id,
                'brand' => $car->brand
            )
        ) );
    }

    return $response;
} );

//Adds a new cars
$app->post( '/api/cars', function () use ( $app ) {

    $car = $app->request->getJsonRawBody();

    $phql = "INSERT INTO cars (year, color, speed, volume, price, model, brand)
                VALUES (:year:, :color:, :speed:, :volume:, :price:, :model:, :brand:)";

    $status = $app->modelsManager->executeQuery( $phql, array(
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volume,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand
    ) );

    //Create a response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if ( true == $status->status() ) {

        // Change the HTTP status
        $response->setStatusCode( 201, "Created" );

        $car->id = $status->getModel()->id;

        $response->setJsonContent( array( 'status' => 'OK', 'data' => $car ) );

    } else {

        //Change the HTTP status
        $response->setStatusCode( 409, "Conflict" );

        //Send errors to the client
        $errors = array();
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( array( 'status' => 'ERROR', 'messages' => $errors ) );
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

    $status = $app->modelsManager->executeQuery( $phql, array(
        'year' => $car->year,
        'color' => $car->color,
        'speed' => $car->speed,
        'volume' => $car->volum,
        'price' => $car->price,
        'model' => $car->model,
        'brand' => $car->brand,
        'id' => $id
    ) );

    //Create response
    $response = new Phalcon\Http\Response();

    //Check if the insertion was successful
    if ( true == $status->success() ) {
        $response->setJsonContent( array( 'status' => 'OK' ) );
    } else {

        //Change the HTML status
        $response->setStatusCode( 409, "Conflict" );

        $errors = array();
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( array( 'status' => 'ERROR', 'messages' => $errors ) );
    }

    return $response;
} );

//Deletes cars based on primary key
$app->delete( '/api/cars/{id:[0-9]+}', function ( $id ) use ( $app ) {

    $phql = "DELETE FROM cars WHERE id = :id:";
    $status = $app->modelsManager->executeQuery( $phql, array(
        'id' => $id
    ) );

    $response = new Phalcon\Http\Response();

    if ( $status->success() == true ) {
        $response->setJsonContent( array( 'status' => 'OK' ) );
    } else {

        $response->setStatusCode( 409, "Conflict" );

        $errors = array();
        foreach ( $status->getMessages() as $message ) {
            $errors[ ] = $message->getMessage();
        }

        $response->setJsonContent( array( 'status' => 'ERROR', 'messages' => $errors ) );

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
