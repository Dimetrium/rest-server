<?php

use \Phalcon\Mvc\Model;

class Cars extends Model
{

  /**
   *
   * @var integer
   */
  protected $id;

  /**
   *
   * @var integer
   */
  protected $year;

  /**
   *
   * @var string
   */
  protected $color;

  /**
   *
   * @var integer
   */
  protected $speed;

  /**
   *
   * @var double
   */
  protected $volume;

  /**
   *
   * @var double
   */
  protected $price;

  /**
   *
   * @var string
   */
  protected $model;

  /**
   *
   * @var string
   */
  protected $brand;

  /**
   * Method to set the value of field id
   *
   * @param integer $id
   *
   * @return $this
   */
  public function setId ( $id )
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Method to set the value of field year
   *
   * @param integer $year
   *
   * @return $this
   */
  public function setYear ( $year )
  {
    $this->year = $year;

    return $this;
  }

  /**
   * Method to set the value of field color
   *
   * @param string $color
   *
   * @return $this
   */
  public function setColor ( $color )
  {
    $this->color = $color;

    return $this;
  }

  /**
   * Method to set the value of field speed
   *
   * @param integer $speed
   *
   * @return $this
   */
  public function setSpeed ( $speed )
  {
    $this->speed = $speed;

    return $this;
  }

  /**
   * Method to set the value of field volume
   *
   * @param double $volume
   *
   * @return $this
   */
  public function setVolume ( $volume )
  {
    $this->volume = $volume;

    return $this;
  }

  /**
   * Method to set the value of field price
   *
   * @param double $price
   *
   * @return $this
   */
  public function setPrice ( $price )
  {
    if ( $price < 0 ) {
      throw new \InvalidArgumentException( 'price cannot be < 0' );
    }
    $this->price = $price;

    return $this;
  }

  /**
   * Method to set the value of field model
   *
   * @param string $model
   *
   * @return $this
   */
  public function setModel ( $model )
  {
    if ( strlen( $model ) < 2 ) {
      throw new \InvalidArgumentException( 'Model name to short' );
    }
    $this->model = $model;

    return $this;
  }

  /**
   * Method to set the value of field brand
   *
   * @param string $brand
   *
   * @return $this
   */
  public function setBrand ( $brand )
  {
    $this->brand = $brand;

    return $this;
  }

  /**
   * Returns the value of field id
   *
   * @return integer
   */
  public function getId ()
  {
    return $this->id;
  }

  /**
   * Returns the value of field year
   *
   * @return integer
   */
  public function getYear ()
  {
    return $this->year;
  }

  /**
   * Returns the value of field color
   *
   * @return string
   */
  public function getColor ()
  {
    return $this->color;
  }

  /**
   * Returns the value of field speed
   *
   * @return integer
   */
  public function getSpeed ()
  {
    return $this->speed;
  }

  /**
   * Returns the value of field volume
   *
   * @return double
   */
  public function getVolume ()
  {
    return $this->volume;
  }

  /**
   * Returns the value of field price
   *
   * @return double
   */
  public function getPrice ()
  {
    return (double)$this->price;
  }

  /**
   * Returns the value of field model
   *
   * @return string
   */
  public function getModel ()
  {
    return $this->model;
  }

  /**
   * Returns the value of field brand
   *
   * @return string
   */
  public function getBrand ()
  {
    return $this->brand;
  }

  /**
   * Independent Column Mapping.
   * Keys are the real names in the table and the values their names in the application
   *
   * @return array
   */
  public function columnMap ()
  {
    return array(
      'id' => 'id',
      'year' => 'year',
      'color' => 'color',
      'speed' => 'speed',
      'volume' => 'volume',
      'price' => 'price',
      'model' => 'model',
      'brand' => 'brand'
    );
  }

  /**
   * Allows to query a set of records that match the specified conditions
   *
   * @return Cars[]
   *
   * @param mixed $parameters
   */
  public static function find ( $parameters = null )
  {
    return parent::find( $parameters );
  }

  /**
   * Allows to query the first record that match the specified conditions
   *
   * @return Cars
   *
   * @param mixed $parameters
   */
  public static function findFirst ( $parameters = null )
  {
    return parent::findFirst( $parameters );
  }

  public function parseQuery ( Phalcon\Mvc\Micro $app, $year )
  {

    $model = $app[ 'request' ]->getQuery( 'model', 'string' ) ?: '%';
    $brand = $app[ 'request' ]->getQuery( 'brand', 'string' ) ?: '%';
    $color = $app[ 'request' ]->getQuery( 'color', 'string' ) ?: '%';
    $volume = $app[ 'request' ]->getQuery( 'volume', 'float' ) ?: '%';
    $speed = $app[ 'request' ]->getQuery( 'speed', 'int' ) ?: '%';
    $price = $app[ 'request' ]->getQuery( 'price', 'float' ) ?: '%';

    $phql = "SELECT * FROM Cars WHERE id = :id:";

    return $cars = $app->modelsManager->createBuilder()
      ->from( 'Cars' )
      ->where( 'year = :year:', [ 'year' => $year ] )
      ->andWhere( 'model LIKE :model:', [ 'model' => $model ] )
      ->andWhere( 'brand LIKE :brand:', [ 'brand' => $brand ] )
      ->andWhere( 'color LIKE :color:', [ 'color' => $color ] )
      ->andWhere( 'volume LIKE :volume:', [ 'volume' => $volume ] )
      ->andWhere( 'speed LIKE :speed:', [ 'speed' => $speed ] )
      ->andWhere( 'price LIKE :price:', [ 'price' => $price ] )
      ->getQuery()
      ->execute();
  }

  public function typeHandler($app, $data, $type = null)
  {
      
    switch ($type)
    {
    case 'html':
      $html = '<table border=1>';
      for($i = 0; $i < count($data); $i++)
      {
      foreach($data[$i] as $key => $val)
      {
        $html .= "<tr><td>$key</td><td>$val</tr>";
      }}
      $html .= '</table>';
      $app->response->setHeader('Content-Type', 'text/html');
      $app->response->setContent($html);
      break;
    case 'xml':
      $xml = '<root><carDetail>';
      for($i = 0; $i < count($data); $i++)
      {
        foreach($data[$i] as $key => $val){
          $xml .= "<$key>$val</$key>";
        }
      }
      $xml .= '</carDetail></root>';
      $app->response->setHeader('Content-Type', 'application/xml');
      $app->response->setContent($xml);
      break;
    case 'txt':
      header('Content-Type: text/plain');
      $app->response->setContent(print_r($data));
      break;
    case 'json':
      $app->response->setJsonContent( [
        'status' => 'FOUND',
        'data' => $data
        ]
      );
      break;
    default:
      $app->response->setJsonContent( [
        'status' => 'FOUND',
        'data' => $data
        ]
      );
    }

   return $app->response;
  }

}
