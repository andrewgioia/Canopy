<?php

class Dashboard extends Controller{

	public function __construct(){
		parent::__construct();
	}

	public function index( $request = null ) {

		$data[ 'title' ] = 'Dashboard';

		$this->view->rendertemplate( 'header', $data );
		$this->view->render( 'dashboard/dashboard', $data );
		$this->view->rendertemplate( 'footer', $data );
	}

}