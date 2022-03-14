<?php 

namespace App\Model;

use App\Core\Db\MysqlDatabaseConn;
use App\Core\Model;

use App\Security\SecurityTrait;

final class TripModel
{
	use SecurityTrait;

	
	private $table = "trips";
	private $tableLoc = "trip_locations";
	private $tableScooter = "scooters";
	private $tableUser = "users";

	public string $scooterId;
	public string $userId;
	public string $payment;
	public $createDate;
	public $updateDate;

	public string $tripId;
	public string $latitude;
	public string $longitude;
	
	public $isActive;



	public function __construct(){

		$this->createDate = date("Y-m-d H:i:s");
		$this->updateDate = date("Y-m-d H:i:s"); 
		// $this->tripDuration = 15; // seconds
		// $this->locUpdateInterval = 3;

	}
	
	public function save($data){	

		$columns = " (scooterId, userId, payment, createDate) ";

		for($i=0; $i<3; $i++){

			$createDate = date("Y-m-d H:i:s");

			$values = " VALUES('".$this->escapeSQLinjection($data['scooterId']+$i)."',
		'".$this->escapeSQLinjection($data['userId']+$i)."',
		'".$this->escapeSQLinjection($data['payment'])."',
		'".$createDate."'
		)";

			$sql = "INSERT INTO $this->table $columns $values";

			if(MysqlDatabaseConn::$conn->query($sql) === TRUE){
				
				$this->updateScooterStatus($data['scooterId'],3);

				$data['tripId'] = MysqlDatabaseConn::$conn->insert_id;
				$this->saveTripLocation($data,$i);
			}	
			$this->updateScooterStatus($data['scooterId'],1);
			sleep(5);
		}		

	}

	private function saveTripLocation($data,$tripNumber)
	{
		$locData[0] = [
			[$data['startLat'],$data['startLon']],
			[$data['startLat']+1,$data['startLon']-1],
			[$data['startLat']+2,$data['startLon']-2],
			[$data['startLat']+3,$data['startLon']-3],
			[$data['endLat'],$data['endLon']]
		];
		
		$locData[1] = [
			[$data['startLat'],$data['startLon']],
			[$data['startLat']+0.5,$data['startLon']-0.5],
			[$data['startLat']+1,$data['startLon']-1],
			[$data['startLat']+1.5,$data['startLon']-1.5],
			[$data['endLat'],$data['endLon']]
		];

		$locData[2] = [
			[$data['startLat'],$data['startLon']],
			[$data['startLat']+0.8,$data['startLon']-0.8],
			[$data['startLat']+1.5,$data['startLon']-1.5],
			[$data['startLat']+2,$data['startLon']-2],
			[$data['endLat'],$data['endLon']]
		];

		$columns = " (tripId, latitude, longitude, createDate) ";

		for($i=0; $i<5; $i++){

			$loc = $locData[$tripNumber];			
			
			$createDate = date("Y-m-d H:i:s");

			$values = " VALUES('".$data['tripId']."','".$loc[$i][0]."','".$loc[$i][1]."','".$createDate."')";

			$sql = "INSERT INTO $this->tableLoc $columns $values";

			MysqlDatabaseConn::$conn->query($sql);

			sleep(5);
		}
	}

	private function updateScooterStatus($userId,$status){

		$setColumns = " SET status = $status";
		$where = " id = '".$userId."'";

		$sql = "UPDATE $this->tableScooter $setColumns WHERE $where";

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
			return true;
		}

	}

	

}

 
?>