<?php

class Main extends Controller {

	function Main()
	{
		parent::Controller();	
	}
	
	function index()
	{
            $this->load->helper('url');
            redirect("main/login");
	}
        
        function login()
        {
            $this->load->view('welcome_message');
        }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */