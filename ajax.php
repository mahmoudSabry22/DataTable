
<?php
	if (isset($_POST['key'])) {

		$conn = new mysqli('localhost', 'root', '', 'datatableAjax');


		if ($_POST['key'] == 'getRowData') {
			$rowid = $conn->real_escape_string($_POST['IDrow']);
			$sql = $conn->query("SELECT *FROM user WHERE id = $rowid");
			$data = $sql->fetch_array();
			$jsonData = array(
				'thename'  =>$data['name'],
				'theemail' =>$data['email'],
				'thepass'  =>$data['password'],
				'thephone' =>$data['phone'] ,
			);

			exit(json_encode($jsonData));
		}

		if ($_POST['key'] == 'getExistingData') {
			$start = $conn->real_escape_string($_POST['start']);
			$limit = $conn->real_escape_string($_POST['limit']);
			$sql = $conn->query("SELECT * FROM user LIMIT $start, $limit");
			if ($sql->num_rows > 0) {
				$response = "";
				while($data = $sql->fetch_array()) {
					$response .= '
						<tr>
							<td>'.$data["id"].'</td>
							<td id="user_'.$data["id"].'">'.$data["name"].'</td>
							<td id="theEmail_'.$data["id"].'">'.$data["email"].'</td>
							<td id="thePhone_'.$data["id"].'">'.$data["phone"].'</td>
							<td id="theRole_'.$data["id"].'">'.$data["Role"].'</td>
							<td>
								<input type="button" value="Edit" onclick="edit('.$data["id"].')" class="btn btn-primary">
								<input type="button" value="View" class="btn">
								<input type="button" value="Delete" onclick="deleteUser('.$data["id"].')" class="btn btn-danger">
							</td>
						</tr>
					';
				}
				exit($response);
			} else
				return 'reachedMax';
				exit();
		}

		


		if($_POST['key']== 'DeleteUser')
		{
			$sql = $conn->query("SELECT id FROM user WHERE id =" . $_POST['ID']);
			if($sql> 0 ){
			 $conn->query("DELETE FROM user WHERE id =".$_POST['ID']);
				exit('Deleted');
			}
		}

		$id    = $conn->real_escape_string($_POST['EditRow']);
		$name  = $conn->real_escape_string($_POST['name']);
		$email = $conn->real_escape_string($_POST['email']);
		$phone = $conn->real_escape_string($_POST['phone']);
		$pass  = $conn->real_escape_string($_POST['pass']);
		$Role  = $_POST['Role'];
		$theDesc = sha1($pass);
		//***************************Start Update User********************************//

		if($_POST['key']=='updatRow')
		{
				
						$sql = $conn->query("UPDATE
													 user
												 SET
													 name = '$name', email = '$email', phone = '$phone',password='$theDesc',Role = '$Role'
												WHERE 
													id = '$id'
											");
				exit('Updated');
					

		}	

		//***************************End Update User********************************//

		//***************************Start ADD New User*******************************//

			if ($_POST['key'] == 'addNew') {
				$sql = $conn->query("SELECT id FROM user WHERE name = '$name'");
				if ($sql->num_rows > 0)
					exit("userNAme With This Name Already Exists!");
				else {
				mysqli_query($conn,"INSERT INTO user (`name`, `email`, `phone`, `password`, `Role`)
								VALUES ('$name','$email','$phone','$theDesc','$Role')") 
						or die(mysqli_error($conn));
					// $sql = $conn->query("INSERT INTO user (name, email, phone,password,Role) 
					// 			VALUES ('$name', '$email', '$phone',$theDesc,'$Role')");
						exit('Added New User');
					
					
				}
			}
		//**************************End Add New User*****************************//
	}
?>
