<?php 

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;

use App\Model\ScooterModel;
use App\Model\UserModel;

/**
 * Class AuthController
 */
final class ScooterController extends Controller
{
	
	public function register(Request $request)
	{
		$model = new ScooterModel();
	
		$model->loadData($request->getBody());
		
		
		if($model->validate() && $model->save($model) === true){

			$data['success']= "Successfully Created New Scooter";
			$data['status']= 201;
			echo json_encode($data);

		}else{

			echo json_encode($model->errors);
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
		
		
		if($model->validate() && $model->update($model) > 0){

			$data['success']= "Successfully Updated Scooter Info";
			$data['status']= 200;
			echo json_encode($data);

		}else{

			echo json_encode($model->errors);
		}		
		
	}
	
	public function getStatus(Request $request){

		$model = new ScooterModel();
		$model->loadData($request->getParams());

		$userModel = new UserModel();

		if($userModel->checkApiKey($model->apiKey) > 0){

			$scooterList = $model->getStatus();
			
			if(count($scooterList) > 0){

				$data['success'] = $scooterList;
				$data['status']= 200;
				echo json_encode($data);
			}

		}else{

			$data['unsuccess']= " Scooters are not available or check credential";
			echo json_encode($data);
		}	
	}

	public function logout(Request $request){

		$model = new ScooterModel();
		$model->loadData($request->getParams());

		if($model->checkApiKey($model->apiKey) > 0 && $model->logout($model)===true){

			$data['success'] = "Successfully Logout";
			$data['status']= 200;
			echo json_encode($data);

		}else{

			$data['unsuccess']= "Problem in Logout";
			echo json_encode($data);
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

}

?>