<?php
/**
 * PlaidComponent
 *
 * A component that handles bank account information from https://www.plaid.com/
 * Full documentation can be found at https://plaid.com/docs
 *
 * PHP version 5
 *
 * @package		PlaidComponent
 * @author		Derek Smart <dereksmart@earthlink.net>
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		https://github.com/mcred/CakePHP-Plaid-API-Plugin
 */

App::uses('Component', 'Controller');

/**
 * PlaidComponent
 *
 * @package		PlaidComponent
 */
class PlaidComponent extends Component {

/**
 * Default Plaid mode to use: Test or Live
 *
 * @var string
 * @access public
 */
	public $mode = 'Test';

/**
 * Controller startup. Loads the Plaid API settings and sets options from
 * /APP/Config/plaid.php.
 *
 * @param Controller $controller Instantiating controller
 * @return void
 * @throws CakeException
 * @throws CakeException
 */
	public function startup(Controller $controller) {
		$this->Controller = $controller;

		//Load Configuration file if it exists
		if(file_exists(APP_DIR.'Config/plaid.php')){
			Configure::load('plaid');
		}

		// if mode is set in plaid.php, use it. otherwise, Test.
		$mode = Configure::read('Plaid.mode');
		switch ($mode) {
			case 'Live':
				$this->url = 'https://api.plaid.com/';
				$this->client_id = Configure::read('Plaid.client_id');
				$this->secret = Configure::read('Plaid.secret');
				break;
			case 'Test':
				$this->url = 'https://tartan.plaid.com/';
				$this->client_id = 'test_id';
				$this->secret = 'test_secret';
				break;
		}

		// check for Plaid API client_id
		if (!$this->client_id) {
			throw new CakeException('Plaid API Client ID is not set.');
		}

		// check for Plaid API secret
		if (!$this->secret) {
			throw new CakeException('Plaid API Secret is not set.');
		}
	}

/**
 * The send method sends and receives all requests to the Plaid API.
 * 
 *
 * @param string $method requested HTTP method to use
 * @param string $service requested API method to call
 * @param array $data request information to be sent.
 * @return array $response if success, boolean false if failure or not found.
 * @access private
 */
	private function send($method, $service, $data=null) {
		$HttpSocket = new HttpSocket();

		if($data){
			$data['client_id'] = $this->client_id;
			$data['secret'] = $this->secret;
		}

		switch ($method) {
			case 'post':
				$response = $HttpSocket->post(
					$this->url . $service,
					$data
				);
				break;
			case 'get':
				$response = $HttpSocket->get(
					$this->url . $service,
					$data
				);
				break;
			case 'delete':
				$response = $HttpSocket->delete(
					$this->url . $service,
					$data
				);
				break;
			case 'patch':
				$response = $HttpSocket->patch(
					$this->url . $service,
					$data
				);
				break;
		}

		return json_decode($response, true);
	}

/**
 * The UserAdd method creates a new user.
 * 
 *
 * @param string $username bank account username.
 * @param string $password bank account password.
 * @param string $type bank account type.
 * @param string $email email address associated with bank account.
 * @return array $response if success, boolean false if failure or not found.
 */
	public function UserAdd($username, $password, $type, $email) {
		$gather['username'] = $username;
		$gather['password'] = $password;
		$credentials = json_encode($gather);
		$data['credentials'] = $credentials;
		$data['type'] = $type;
		$data['email'] = $email;
		self::send('post', 'connect', $data);
	}

/**
 * The UserMFA method answers required auth questinos for a user.
 * 
 *
 * @param array $mfa the required array of authentication answers.
 * @param string $access_token the access_token for the user.
 * @return array $response if success, boolean false if failure or not found.
 */

	public function UserMFA($mfa, $access_token){
		$data['mfa'] = json_encode($mfa);
		$data['access_token'] = $access_token;
		self::send('post', 'connect/step', $data);
	}

/**
 * The UserUpdate method gets updated transactions for a user.
 * 
 *
 * @param string $access_token the access_token for the user.
 * @return array $response if success, boolean false if failure or not found.
 */

	public function UserUpdate($access_token){
		$data['access_token'] = $access_token;
		self::send('get', 'connect', $data);
	}

/**
 * The UserPatch method updates bank credentials for a user.
 * 
 *
 * @param string $username bank account username.
 * @param string $password bank account password.
 * @param string $access_token the access_token for the user.
 * @return array $response if success, boolean false if failure or not found.
 */

	public function UserPatch($username, $password, $access_token){
		$gather['username'] = $username;
		$gather['password'] = $password;
		$credentials = json_encode($gather);
		$data['credentials'] = $credentials;
		$data['access_token'] = $access_token;
		self::send('patch', 'connect', $data);
	}

/**
 * The UserDelete method deletes a new user.
 * 
 *
 * @param string $access_token the access_token for the user.
 * @return array $response if success, boolean false if failure or not found.
 */

	public function UserDelete($access_token){
		$data['access_token'] = $access_token;
		self::send('delete', 'connect', $data);
	}

/**
 * The Entities method gets a information about available entities.
 * 
 *
 * @param string $id financial institution or transaction id.
 * @return array $response if success, boolean false if failure or not found.
 */
	public function Entities($id) {
		self::send('get', 'entities/' . $id);
	}

/**
 * The Institutions method gets a information about available financial
 * institutions.
 * 
 *
 * @param string $id financial institution id.
 * @return array $response if success, boolean false if failure or not found.
 */
	public function Institutions($id = null) {
		if ($id){
			self::send('get', 'institutions/' . $id);
		} else {
			self::send('get', 'institutions');
		}
	}

/**
 * The Categories method gets a information about available financial
 * categories.
 * 
 *
 * @param string $id financial category id.
 * @return array $response if success, boolean false if failure or not found.
 */
	public function Categories($id = null) {
		if ($id){
			self::send('get', 'categories/' . $id);
		} else {
			self::send('get', 'categories');
		}
	}
}	