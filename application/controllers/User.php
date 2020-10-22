<?php

class User extends CI_Controller{

    public function all(){

        if($this->access_app()){
            echo json_encode($this->db->get("usuario")->result());
        }
    }

    public function get($id = null){
        if($this->access_app()){
            if($id == null){
                $this->output->set_status_header(404);
                echo json_encode(array("code" => 404,"message" => "No se encuetra el recurso solicitado")); 
            }else{
                $this->load->database();
                $this->db->where("idusuario",$id);
                $query = $this->db->get("usuario");
                if ($query -> num_rows() == 0){
                    $this->output->set_status_header(404);
                    echo json_encode(array("code" => 404,"message" => "No se encuetra el recurso solicitado")); 
                }else{
                    echo json_encode($query->row());
                }
            }
        }
    }

    public function add(){
        
        if($this->access_app()){
            $dataform = $this->input->post();
            $this->load->database();
            $this->db->insert("usuario",$dataform);
            $last_insert_id = $this->db->insert_id();
            $dataform["idusuario"] = $last_insert_id;
            echo json_encode($dataform);
        }

    }

    public function update($id = null){
        
        if($this->access_app()){
            
            $dataform = $this->input->post();
            if(count($dataform) == 0){
                $this->output->set_status_header(400);
                echo json_encode(array("code" => 400,"message" =>"Error en la peticion")); 
            }else{
                $this->load->database();
                $this->db->where('idusuario',$id);
                $this->db->update('usuario',$dataform);
                $this->db->where('idusuario',$id);
                $userupdate=$this->db->get('usuario')->row();
                echo json_encode($userupdate);
            }
        }
    }

    public function delete($id = null){
        if($this->access_app()){
            if($id == null){
                $this->output->set_status_header(404);
                echo json_encode(array("code" => 404,"message" =>"No se eliminó el recurso solicitado")); 
            }else{

                $dataform = $this->input->post();
                $this->load->database();
                $this->db->where('idusuario',$id);
                $this->db->delete('usuario');
                echo json_encode(array("code" => 202, "message" => "Se eliminó el recurso" ));
            }
        }
    }

    public function access_app(){

        $headers = $this->input->request_headers();
        $keyname = 'API-key';

        if(array_key_exists($keyname,$headers)){
            $token= $headers[$keyname];
            $this->load->database();
            $this->db->where("token",$token);
            $query = $this->db->get("app");

            if( $query->num_rows() > 0){
                return true;
            }
        }
        $this->output->set_status_header(401);
        echo json_encode(array("code" => 401,"message" =>"no se ha conseguido el acceso a la app"));
    }
}