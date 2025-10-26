<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use chriskacerguis\RestServer\RestController;
class Tes extends RestController
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index_get()
	{
		$data = $this->db->get('tb_role')->result();
		$this->response($data, RestController::HTTP_OK);

	}
}
