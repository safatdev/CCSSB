<?php

// Global importing of the Classes
import('authenticate');
import('user');
import('post');


class Controller {

	// The Properties
	private $con;
	private $pages;
	private $pageTitle;
	private $auth;


	// Handles the URL
	public function __construct($con, $pages) {

		// Setting up the properties
		$this->con = $con;
		$this->pages = $pages;

		// The Authenticate
		$this->auth = new Authenticate($this->con);

		// Loading the Pages depending on the Url
		if (isset($_GET['page'])) {

			// The url
			$page_url = $_GET['page'];

			// Render the Page
			$this->displayPage($page_url);

		} else {
			$this->displayPage('home');
		}
	}


	// get Level 2
	public function getL2Url() {

		// Loading the Pages depending on the Url
		if (isset($_GET['page2'])) {

			// The url
			$page_url = $_GET['page2'];
			return $page_url;

		} else {
			return false;
		}
	}


	// get Level 3
	public function getL3Url() {

		// Loading the Pages depending on the Url
		if (isset($_GET['page3'])) {

			// The url
			$page_url = $_GET['page3'];
			return $page_url;

		} else {
			return false;
		}
	}



	// Include the Page
	private function displayPage($page) {
		if ($this->checkPage($page)) {
			$this->renderPage($page);
		} else {
			$this->renderPage('404');
		}
	}


	// Redirect to another page
	public function redirect($page) {
		header('Location: '.HOME.$page);
	}


	// Checks if the page actually exists
	private function checkPage($page) {

		// the page exists
		$exists = false;

		// loops through the available pages
		foreach ($this->pages as $p) {
			$p = strtolower($p);
			if ($p == $page) {
				$exists = true;
				break;
			}
		}

		// return exists
		if ($exists) {
			return true;
		} else {
			return false;
		}
	}


	// Display sections of the Page
	private function render($s, $data, $controller = null) {
		include(INC.'sections/'.$s.'.php');
	}


	// Display the page and pass
	private function renderPage($p) {

		// Passing some functionality and data to the Page
		$controller = $this;
		$data = array(
			'cur_page' => $p,							// The current page
			'loggedin' => $this->auth->isLoggedIn()	// Whether the User is loggedin or not
		);

		// Only send verified if the User is loggedin
		if ($data['loggedin']) {
			$data['user_id'] = $_SESSION['uid'];			// The user id
			$data['verified'] = $this->auth->isVerified();	// Whether the User is verified or not
		}

		// Including the Page
		include(PAGE . $p . '.php');
	}

}

?>