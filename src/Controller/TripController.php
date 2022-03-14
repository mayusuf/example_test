<?php 

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;

use App\Model\TripModel;
use App\Model\UserModel;

/**
 * Class AuthController
 */
final class TripController extends Controller
{
	
	public function create(Request $request)
	{
				
		$tripData = $this->loadTripData($request->getBody());
		
		foreach ($tripData as $key => $value) {
			
			if(empty($value)){
				$data[$key] = $key." must not empty";
				echo json_encode($data);
				exit();
			}
		}

		$model = new TripModel();
		$userModel = new UserModel();

		if($userModel->checkApiKey($tripData['apiKey'],$tripData['userId']) > 0 && $model->save($tripData) === true){

			$data['success']= "Successfully new trip started";
			$data['status']= 201;
			echo json_encode($data);

		}else{
			$data['unsuccess'] = 'Trip is not started';
			echo json_encode($data);
		}
		
		
	}

	public function login(Request $request)
	{	
		$model = new ScooterModel();

		$model->loadData($request->getBody());	
		$scooterInfo = $model->login($model);

		if(count($scooterInfo) > 0){
			$data['success'] = $scooterInfo;
			$data['status']= 200;
			echo json_encode($data);
		}else{
			$data['unsuccess'] = 'Login Not Successfull';
			echo json_encode($data);
		}	
	}

	public function update(Request $request)
	{
		$model = new ScooterModel();
	
		$model->loadData($request->getBody());
		
		
		if($model->validate() && $model->update($model) === true){

			$data['success']= "Successfully Updated Scooter Info";
			$data['status']= 200;
			echo json_encode($data);

		}else{

			echo json_encode($model->errors);
		}		
		
	}
	
	public function delete(Request $request)
	{
		$model = new ScooterModel();
		$model->loadData($request->getParams());		
		
		if($model->delete($model) === true){

			$data['success']= "Successfully Deleted Scooter Info";
			$data['status']= 200;
			echo json_encode($data);

		}else{

			$data['unsuccess']= " Deleted Scooter Info is not successful";
			echo json_encode($data);
		}		
		
	}

	private function loadTripData($tripData)
	{
		$data = [];
		$data['apiKey'] = $tripData['apiKey'] ?? '';
		$data['scooterId'] = $tripData['scooterId'] ?? '';
		$data['userId'] = $tripData['userId'] ?? '';
		$data['payment'] = $tripData['payment'] ?? '';
		$data['startLat'] = $tripData['startLat'] ?? '';
		$data['startLon'] = $tripData['startLon'] ?? '';
		$data['endLat'] = $tripData['endLat'] ?? '';
		$data['endLon'] = $tripData['endLon'] ?? '';

		return $data;
	}

}

?>