<?php
//Retrieves all cars
$app->get( '/api/cars', function () {

  $cars = Cars::find();

  $data = [];

  foreach ( $cars as $car ) {
    $data[ ] = [
      'id' => $car->getId(),
        'brand' => $car->getBrand(),
        'model' => $car->getModel()
        ];
  }
  echo json_encode( $data );
} );

//Searches for cars 
$app->get( '/api/cars/search/{year}', function ($year ) use ($app) {

  $model = $app['request']->getQuery('model', 'string') ?: '%';
  $brand = $app['request']->getQuery('brand', 'string') ?: '%';
  $color = $app['request']->getQuery('color', 'string') ?: '%';
  $volume = $app['request']->getQuery('volume', 'float') ?: '%';
  $speed = $app['request']->getQuery('speed', 'int') ?: '%';
  $price = $app['request']->getQuery('price', 'float') ?: '%';

  $phql = "SELECT * FROM Cars WHERE id = :id:";
  $cars = $app->modelsManager->createBuilder()
    ->from('Cars')
    ->where('year = :year:', ['year' => $year])
    ->andWhere('model LIKE :model:', ['model' => $model])
    ->andWhere('brand LIKE :brand:', ['brand' => $brand])
    ->andWhere('color LIKE :color:', ['color' => $color])
    ->andWhere('volume LIKE :volume:', ['volume' => $volume])
    ->andWhere('speed LIKE :speed:', ['speed' => $speed])
    ->andWhere('price LIKE :price:', ['price' => $price])
    ->getQuery()
    ->execute();

  $data = [];

  foreach ( $cars as $car ) {
    $data[ ] = [
      'id' => $car->getId(),
        'brand' => $car->getBrand(),
        'color' => $car->getColor(),
        'year' => $car->getYear()
        ];
  }
  echo json_encode( $data );
} );

//Retrieves cars based on primary key
//TODO: parse parameters (JSON/XML etc.)
$app->get( '/api/cars/{id}', function ( $id ) use ( $app ) {
  var_dump($id);
  exit;
  $phql = "SELECT * FROM Cars WHERE id = :id:";
  $cars = $app->modelsManager->executeQuery($phql, ['id' => $id])->getFirst();

  $response = new Phalcon\Http\Response();
  if ( false == $cars ) {
    $response->setJsonContent( [ 'status' => 'NOT-FOUND' ] );
  } else {
    $response->setJsonContent( [
      'status' => 'FOUND',
      'data' => [
                'model' => $cars->getModel(), 
                'year' => $cars->getYear(), 
                'volume' => $cars->getVolume(), 
                'color' => $cars->getColor(), 
                'speed' => $cars->getSpeed(), 
                'price' => $cars->getPrice()
                ]
        ]
    );
  }

  return $response;
} );

//Adds a new cars
$app->post( '/api/cars', function () use ( $app ) {

  $car = $app->request->getJsonRawBody();

  $phql = "INSERT INTO Cars (id, year, color, speed, volume, price, model, brand)
    VALUES (:id:, :year:, :color:, :speed:, :volume:, :price:, :model:, :brand:)";

  $status = $app->modelsManager->executeQuery( $phql, [
    'id' => '',
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
  if ( true == $status->success() ) {

    // Change the HTTP status
    $response->setStatusCode( 201, "Created" );

    $car->id = $status->getModel()->getId();

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

  $phql = "UPDATE Cars SET
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
    'volume' => $car->volume,
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

  $phql = "DELETE FROM Cars WHERE id = :id:";
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
} );
