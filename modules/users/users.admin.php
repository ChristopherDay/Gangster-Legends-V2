<?php

	class adminModule {

		private function getUser($userID, $search = false) {

			if ($userID === false) {
				return array();
			}

			if ($search) {
				$add = " WHERE U_id = :id OR U_name LIKE :search";
			} else {
				$add = " WHERE U_id = :id";
			}
			
			$user = $this->db->prepare("
				SELECT
					U_id as 'id',  
					U_name as 'name', 
					U_userLevel as 'userLevel', 
					(U_userLevel = 1) as 'isUser',  
					(U_userLevel = 2) as 'isAdmin', 
					(U_userLevel = 0) as 'isBanned', 
					(U_userLevel = -1) as 'isDead', 
					US_money as 'money', 
					US_exp as 'exp', 
					US_bank as 'bank', 
					US_credits as 'credits', 
					US_bullets as 'bullets', 
					US_bio as 'bio', 
					US_pic as 'pic'
				FROM users
				INNER JOIN userStats ON US_id = U_id
				" . $add . "
				ORDER BY U_name"
			);
			$user->bindParam(":id", $userID);

			if ($search) {
				$searchTerm = "%".$userID."%";
				$user->bindParam(":search", $searchTerm);
				$user->execute();
				return $user->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$user->execute();
				return $user->fetch(PDO::FETCH_ASSOC);
			}
		}

		private function validateUser($user) {
			$errors = array();

			if (strlen($user["name"]) < 6) {
				$errors[] = "User name is to short, this must be atleast 5 characters";
			}

			if ($user["id"] == 1 && $user["userLevel"] != 2) {
				$errors[] = "User ID 1 must be an admin";
			}

			return $errors;
			
		}

		public function method_edit () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No user ID specified"));
			}

			$user = $this->getUser($this->methodData->id);

			if (isset($this->methodData->submit)) {
				$user = (array) $this->methodData;
				$errors = $this->validateUser($user);

				if (count($errors)) {
					foreach ($errors as $error) {
						$this->html .= $this->page->buildElement("error", array("text" => $error));
					}
				} else {
					$update = $this->db->prepare("
						UPDATE users SET U_name = :name, U_userLevel = :userLevel WHERE U_id = :id;
						UPDATE userStats SET US_pic = :pic, US_bio = :bio, US_bullets = :bullets, US_credits = :credits, US_bank = :bank, US_exp = :exp, US_money = :money WHERE US_id = :id;
					");
					$update->bindParam(":name", $this->methodData->name);
					$update->bindParam(":userLevel", $this->methodData->userLevel);
					$update->bindParam(":pic", $this->methodData->pic);
					$update->bindParam(":bio", $this->methodData->bio);
					$update->bindParam(":bullets", $this->methodData->bullets);
					$update->bindParam(":credits", $this->methodData->credits);
					$update->bindParam(":bank", $this->methodData->bank);
					$update->bindParam(":exp", $this->methodData->exp);
					$update->bindParam(":money", $this->methodData->money);
					$update->bindParam(":id", $this->methodData->id);
					$update->execute();

					$this->html .= $this->page->buildElement("success", array("text" => "This user has been updated"));

				}

			}

			$user["editType"] = "edit";
			$this->html .= $this->page->buildElement("userForm", $user);
		}

		public function method_delete () {

			if (!isset($this->methodData->id)) {
				return $this->html = $this->page->buildElement("error", array("text" => "No user ID specified"));
			}

			$user = $this->getUser($this->methodData->id);

			if (!isset($user["id"])) {
				return $this->html = $this->page->buildElement("error", array("text" => "This user does not exist"));
			}

			if (isset($this->methodData->commit)) {
				$delete = $this->db->prepare("
					DELETE FROM users WHERE C_id = :id;
				");
				$delete->bindParam(":id", $this->methodData->id);
				$delete->execute();

				header("Location: ?page=admin&module=users");

			}


			$this->html .= $this->page->buildElement("userDelete", $user);
		}

		public function method_view () {
			
			if (!isset($this->methodData->user)) {
				$this->methodData->user = false;
			}

			$this->html .= $this->page->buildElement("userList", array(
				"users" => $this->getUser($this->methodData->user, true)
			));

		}

	}