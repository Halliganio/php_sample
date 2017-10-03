<?php
use GuzzleHttp\Client;

class Halligan{

	private $client;

	private $token;

	public function __construct($username, $password){
		$this->client = new Client([
		    'base_uri' => 'https://app.halligan.io',
		    'timeout'  => 5.0,
		]);
		$this->authorize($username, $password);
	}

	private function authorize($username, $password){
		$response = $this->client->request("POST", "/authorize", [
			'json' => [
				'email' => $username,
				'password' => $password
			]
		]);
		$this->token = $response->getHeader('Set-Cookie')[0];
	}

	/**
	 * GET /api/v1/apparatus - https://app.halligan.io/swagger-ui.html#!/apparatus-controller/listUsingGET_2
	 */
	public function listApparatus(){
		$data = $this->_getRequest('/api/v1/apparatus')->getBody();
		return json_decode($data);
	}

	/**
	 * GET /api/v1/ticket/apparatus/{apparatusId}/tickets - https://app.halligan.io/swagger-ui.html#!/ticket-controller/getOpenTicketsForApparatusUsingGET
	 */
	public function listOpenApparatusWorkOrders($apparatusId){
		$data = $this->_getRequest('/api/v1/ticket/apparatus/' . $apparatusId . '/tickets')->getBody();
		return json_decode($data);
	}

	/**
	 * GET /api/v1/ticket/workflows - https://app.halligan.io/swagger-ui.html#!/ticket-controller/listTicketTypeWorkflowsUsingGET
	 */
	public function listWorkflows(){
		$data = $this->_getRequest('/api/v1/ticket/workflows')->getBody();
		return json_decode($data);
	}

	private function _getRequest($url){		
		return $this->client->request("GET", $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'Cookie' => $this->token
			]
		]);
	}
}