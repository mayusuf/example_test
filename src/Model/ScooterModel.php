<?php 

namespace App\Model;

use App\Core\Db\MysqlDatabaseConn;
use App\Core\Model;

use App\Security\SecurityTrait;

final class ScooterModel extends Model
{
	use SecurityTrait;

	
	private $table = "scooters";
	
	public string $firstName;
	public string $lastName;
	public string $email;
	public string $uuid;
	public string $pass;
	public string $passWordConfirm;
	public string $mobileNumber;
	public string $regNumber;
	public string $city;
	public string $country;
	public string $apiKey;
	public $createDate;
	public $updateDate;
	public $isActive;

	public function __construct(){

		$this->createDate = date("Y-m-d H:i:s");
		$this->updateDate = date("Y-m-d H:i:s");
	}

	public function rules():array
	{
		return[
			'firstName' => [self::RULE_REQUIRED],
			'lastName' => [self::RULE_REQUIRED],
			'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
			'uuid' => [self::RULE_REQUIRED],
			'regNumber' => [self::RULE_REQUIRED],
			'pass' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 6]],
			'passWordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'pass']],
		];
	}
	
	public function save($model){		

		$model->pass = $this->passWordGenerator($model->pass);
		$model->apiKey = $this->apiKeyGenerator($model->email);


		$columns = " (firstName, lastName, email, uuid, pass, mobileNumber, regNumber, city, country, apiKey, createDate) ";

		$values = " VALUES('".$this->escapeSQLinjection($model->firstName)."',
		'".$this->escapeSQLinjection($model->lastName)."',
		'".$this->escapeSQLinjection($model->email)."',
		'".$this->escapeSQLinjection($model->uuid)."',
		'".$this->escapeSQLinjection($model->pass)."',
		'".$this->escapeSQLinjection($model->mobileNumber)."',
		'".$this->escapeSQLinjection($model->regNumber)."',
		'".$this->escapeSQLinjection($model->city)."',
		'".$this->escapeSQLinjection($model->country)."',
		'".$model->apiKey."',
		'".$model->createDate."'
		)";

		$sql = "INSERT INTO $this->table $columns $values";

		if(MysqlDatabaseConn::$conn->query($sql) === TRUE)
			return true;

	}

	public function login($model){	
		
		$model->pass = $this->passWordGenerator($model->pass);

		$select = " * ";

		$where = " email = '".$this->escapeSQLinjection($model->email)."' && pass =	'".$this->escapeSQLinjection($model->pass)."' && 
		isActive = 1";

		$sql = "SELECT $select FROM $this->table WHERE $where";

		if($result = MysqlDatabaseConn::$conn->query($sql)){

			$this->updateScooterStatus($model->email);
			return $result->fetch_all(MYSQLI_ASSOC);
		}			

	}

	private function updateScooterStatus($email){

		$setColumns = " SET status = 1";
		$where = " email = '".$email."'";

		$sql = "UPDATE $this->table $setColumns WHERE $where";

		if(MysqlDatabaseConn::$conn->query($sql) === TRUE){
			return true;
		}
	}

	public function update($model){		

		$model->pass = $this->passWordGenerator($model->pass);

		$setColumns = " SET 
		firstName = '".$this->escapeSQLinjection($model->firstName)."',
		lastName = '".$this->escapeSQLinjection($model->lastName)."',
		email = '".$this->escapeSQLinjection($model->email)."',
		uuid = '".$this->escapeSQLinjection($model->uuid)."',
		pass = '".$this->escapeSQLinjection($model->pass)."',
		mobileNumber = '".$this->escapeSQLinjection($model->mobileNumber)."',
		regNumber = '".$this->escapeSQLinjection($model->regNumber)."',
		city = '".$this->escapeSQLinjection($model->city)."',
		country = '".$this->escapeSQLinjection($model->country)."',
		updateDate = '".$model->updateDate."'";

		$where = " apiKey = '".$this->escapeSQLinjection($model->apiKey)."'";

		$sql = "UPDATE $this->table $setColumns WHERE $where";

		if(MysqlDatabaseConn::$conn->query($sql) === TRUE){
			return MysqlDatabaseConn::$conn->affected_rows;
		}

	}

	public function getStatus(){	
		
		$this->sql = "Select firstName, lastName, email, status From $this->table WHERE isActive=1";

		if($result = MysqlDatabaseConn::$conn->query($this->sql)){
			return $result->fetch_all(MYSQLI_ASSOC);
		}			

	}

	public function logout($model){		

		$setColumns = " SET 
		status = 0,
		updateDate = '".$model->updateDate."'";

		$where = " apiKey = '".$this->escapeSQLinjection($model->apiKey)."'";

		$sql = "UPDATE $this->table $setColumns WHERE $where";

		if(MysqlDatabaseConn::$conn->query($sql) === TRUE){
			return true;
		}

	}

	public function delete($model){		

		$setColumns = " SET 
		isActive = 0,
		updateDate = '".$model->updateDate."'";

		$where = " apiKey = '".$this->escapeSQLinjection($model->apiKey)."'";

		$sql = "UPDATE $this->table $setColumns WHERE $where";

		if(MysqlDatabaseConn::$conn->query($sql) === TRUE){
			return true;
		}

	}

	public function checkApiKey($apiKey){			
		
		$sql = "SELECT id From $this->table WHERE apiKey = '".$apiKey."' AND isActive = 1";		

		if($result = MysqlDatabaseConn::$conn->query($sql)){
			
			return $result->num_rows;
		}
	}

}

 
?>