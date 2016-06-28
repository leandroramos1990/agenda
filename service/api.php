<?php
	
	require_once("Rest.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "agenda";
		
		private $sql = '';
	
		public function __construct(){
			parent::__construct();				
			$this->dbConnect();				
		}
		
		/**
		 * Função de conexão com o banco de dados
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db){
				mysql_select_db(self::DB,$this->db);
			}		
		}
		
		/**
		 * Função que verifica qual chamada esta sendo feita(POST | GET | DELETE)
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		public function processApi(){
			$func = strtolower($this->get_request_method());
			
			if((int)method_exists($this,$func) > 0){
				$this->$func();
			} else {
				$this->response('',404);
			}					
		}
		
		/**
		 * Função de POST que recebe um novo contato
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		private function post(){
			$contact = (object)$this->_request;
			if(isset($this->_request['contact'])){
				$contact = (object)$this->_request['contact'];
			} else {
				$contact = (object)$this->_request;
			}

			if(isset($contact->id)){
				$this->sqlUpdate($contact);
			} else {
				$this->sqlInsert($contact);
			}

            $query = mysql_query( $this->sql, $this->db);
			$this->getLastRow();
		}
		
		/**
		 * Função de GET que retorna a listagem de contatos
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		private function get(){
			$this->sql = "SELECT id,name,phone,email FROM users";
			$query = mysql_query($this->sql, $this->db);
			$this->makeResult($query);
		}
		
		/**
		 * Função de DELETE que deleta um contato passado por id
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		private function delete(){
			$id = $this->_request[1];

			if(isset($id)){
				$this->sql = "DELETE FROM users WHERE id = ".$id;
				$query = mysql_query($this->sql, $this->db);
				$this->makeResult($query);
			}
		}

		/**
		 * Função que busca o último contato inserido
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @return void
		 */
		private function getLastRow(){
			$this->sql = "SELECT id,name,phone,email FROM users ORDER BY id DESC LIMIT 1";
			$query = mysql_query($this->sql, $this->db);
			$this->makeResult($query);
		}
    
		private function sqlInsert($contact){
			$this->sql = "INSERT INTO users(name, phone, email) VALUES (
				'$contact->name',
				'$contact->phone',
				'$contact->email'
			)";
		}

		private function sqlUpdate($contact){
			$this->sql = "UPDATE users SET
				name = '$contact->name',
				phone = '$contact->phone',
				email = '$contact->email'
				WHERE id = $contact->id";
		}
		
		/**
		 * Função que organiza o resultado das consultas em um array
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @param object $query
		 * @return array $result
		 */
		private function makeResult($query){
			if(mysql_num_rows($query) > 0){
			
				$result = array();
				while($line = mysql_fetch_array($query, MYSQL_ASSOC)){
					$result[] = $line;
				}
				$this->response($this->json($result), 200);
			}
			$this->response('',204);
		}
		
		/**
		 * Função que transforma o array de entrada em um JSON
		 * @author Leandro Ramos
		 * @since 26/05/2016
		 * @param array $data
		 * @return json 
		 */
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	$api = new API;
	$api->processApi();
?>