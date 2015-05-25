<?php
class User extends \Phalcon\Mvc\Model
{
    
  /**
   *
   * @var integer
   */
  protected $user_id;
  
  /**
   *
   * @var string
   */
  protected $password;
  
  /**
   *
   * @var string
   */
  protected $login;
  
  /**
   *
   * @var integer
   */
  protected $role_id;
  
  /**
   *
   * @var string
   */
  protected $user_hash;
  
  /**
   * Method to set the value of field user_id
   *
   * @param integer $user_id
   *
   * @return $this
   */
  public function setUser_id ( $user_id )
  {
    $this->user_id = $user_id;
    return $this;
  }
  
  /**
   * Method to set the value of field password
   *
   * @param integer $password
   *
   * @return $this
   */
  public function setPassword ( $password )
  {
    $this->password = $password;
    return $this;
  }
  
  /**
   * Method to set the value of field login
   *
   * @param string $login
   *
   * @return $this
   */
  public function setLogin ( $login )
  {
    $this->login = $login;
    return $this;
  }
  
  /**
   * Method to set the value of field role_id
   *
   * @param integer $role_id
   *
   * @return $this
   */
  public function setRole_id ( $role_id )
  {
    $this->role_id = $role_id;
    return $this;
  }
  
  /**
   * Method to set the value of field user_hash
   *
   * @param double $user_hash
   *
   * @return $this
   */
  public function setUser_hash ( $user_hash )
  {
    $this->user_hash = $user_hash;
    return $this;
  }
  
  /**
   * Returns the value of field user_id
   *
   * @return integer
   */
  public function getUser_id ()
  {
    return $this->user_id;
  }
  
  /**
   * Returns the value of field password
   *
   * @return string
   */
  public function getPassword ()
  {
    return $this->password;
  }
  
  /**
   * Returns the value of field login
   *
   * @return string
   */
  public function getLogin ()
  {
    return $this->login;
  }
  
  /**
   * Returns the value of field role_id
   *
   * @return integer
   */
  public function getRole_id ()
  {
    return $this->role_id;
  }
  
  /**
   * Returns the value of field user_hash
   *
   * @return string
   */
  public function getUser_hash ()
  {
    return $this->user_hash;
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
      'user_id' => 'user_id',
      'password' => 'password',
      'login' => 'login',
      'role_id' => 'role_id',
      'user_hash' => 'user_hash'
    );
  }
  
  /**
   * Allows to query a set of records that match the specified conditions
   *
   * @return Users[]
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
   * @return Users
   *
   * @param mixed $parameters
   */
  public static function findFirst ( $parameters = null )
  {
    return parent::findFirst( $parameters );
  }

  /**
   * Allows to query the first record that match the specified conditions
   *
   * @return bool
   *
   */

    
}
