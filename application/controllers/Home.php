<?php 

class Home extends CI_controller{

    public function index(){
        $this->load->database();
        
        $data = $this->db->get("app");

        $json = json_encode($data->result());
    
        echo $json;
    }

    public function adduser(){
        $post = $this->input->post();
        echo json_encode($post);
    }

}