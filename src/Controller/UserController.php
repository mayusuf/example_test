<?php 

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;

use App\Model\UserModel;

/**
 * Class UserController
 */
final class UserController extends Controller
{
	
	public function register(Request $request)
	{
		$model = new UserModel();
	
		$model->loadData($request->getBody());
		
		
		if($model->validate() && $model->save($model) === true){

			$data['success']= "Successfully Created New User";
			$data['status']= 201;
			echo json_encode($data);

		}else{

			echo json_encode($model->errors);
		}
		
		
	}

	public function login(Request $request)
	{	
		$model = new UserModel();

		$model->loadData($request->getBody());	
		$userInfo = $model->login($model);

		if(count($userInfo) > 0){
			$data['success'] = $userInfo;
			$data['status']= 200;
			echo json_encode($data);
		}else{
			$data['unsuccess'] = 'Login Not Successfull';
			echo json_encode($data);
		}	
	}

	public function update(Request $request)
	{
		$model = new UserModel();
	
		$model->loadData($request->getBody());
		
		
		if($model->validate() && $model->update($model) > 0){

			$data['success']= "Successfully Updated User Info";
			$data['status']= 200;
			echo json_encode($data);

		}else{
			
			echo json_encode($model->errors);
		}		
		
	}
	
	public function delete(Request $request)
	{
		$model = new UserModel();
		$model->loadData($request->getParams());		
		
		if($model->delete($model) === true){

			$data['success']= "Successfully Deleted User Info";
			$data['status']= 200;
			echo json_encode($data);

		}else{

			$data['unsuccess']= " Deleted User Info is not successful";
			echo json_encode($data);
		}		
		
	}

}

?>