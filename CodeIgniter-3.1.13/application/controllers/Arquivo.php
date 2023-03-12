<?php
// atendimento_arquivo
class Arquivo extends CI_Controller {   

    public function index($atendimento_id = 0)
    {
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }

        $this->load->database();
        $this->load->helper('url');         
        // $query = $this->db->query("SELECT * FROM arquivo WHERE atendimento_id = ? ".$atendimento_id.";");             
        
        // https://w3.med.cmu.ac.th/ci/user_guide/database/queries.html
        $query = $this->db->query("SELECT * FROM arquivo WHERE atendimento_id = ?;", array($atendimento_id));             
        
        $data['vetArquivo'] = $query->result();
        $data['atendimento_id'] = $atendimento_id;
        $this->load->view('innerpages/header');
        $this->load->view('arquivo/index', $data);
        $this->load->view('innerpages/footer');
    }
    public function tela_adicionar($atendimento_id)    {
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }
        $this->load->database();
        $this->load->helper('url'); 
        $data['atendimento_id'] = $atendimento_id;
        $data['error'] = "";
        $data['upload_data'] = [];
        $this->load->view('innerpages/header');
        $this->load->view('arquivo/tela_adicionar', $data);
        $this->load->view('innerpages/footer');
    }
    public function tela_editar($id, $atendimento_id)      {
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }
        $this->load->database();
        $this->load->helper('url'); 
        $this->load->view('innerpages/header');
        // $query = $this->db->query("SELECT * FROM arquivo WHERE id = ".$id.";");   
        $query = $this->db->query("SELECT * FROM arquivo WHERE id = ?;", array($id));         
        $data['arquivo'] = $query->result()[0];
        $data['atendimento_id'] = $atendimento_id;
        $this->load->view('arquivo/tela_editar', $data);        
        $this->load->view('innerpages/footer');
    }
    public function editar()    {
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }
        $this->load->database();
        $this->load->helper('url'); 
        $form_data = $this->input->post();        
        $nome = $this->input->post("nome");
        $id = $this->input->post("id");
        $atendimento_id = $this->input->post("atendimento_id");
        // $query = $this->db->query("UPDATE arquivo SET nome = '".$nome."' WHERE id = ".$id.";");
        $query = $this->db->query("UPDATE arquivo SET nome = ? WHERE id = ?;", array($nome, $id));   
        header("Location: /arquivo/index/".$atendimento_id);
    }

    public function remover($id, $atendimento_id)    {
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }
        $this->load->database();
        $this->load->helper('url'); 
        // $query = $this->db->query("SELECT * FROM arquivo WHERE id = ".$id.";");    
        $query = $this->db->query("SELECT * FROM arquivo WHERE id = ?;", array($id));       
        $arquivo = $query->result()[0];        
        if (unlink("./arquivos/".$arquivo->arquivo)) {
            $query = $this->db->query("DELETE FROM arquivo WHERE id = ?;", array($id));       
            // $query = $this->db->query("DELETE FROM arquivo WHERE id = ".$id.";");        
        }
        header("Location: /arquivo/index/".$atendimento_id);
    }

    public function adicionar()    {  
        $this->load->library('session');
        if(!$this->session->userdata('usuario')){
            $this->session->sess_destroy();
            header("Location: /usuario/tela_login");
        }   
        $this->load->database();
        $this->load->helper('url'); 
        $form_data = $this->input->post();        
        $nome = $this->input->post("nome");
        $atendimento_id = $this->input->post("atendimento_id");

        $config['upload_path']          = './arquivos/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 200;
        $config['max_width']            = 3000;
        $config['max_height']           = 3000;    
        $config['encrypt_name'] = TRUE;
    
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('arquivo'))
        {
                $data = array('error' => $this->upload->display_errors());
                $data['atendimento_id'] = $atendimento_id;
                $data['upload_data'] = [];
                $this->load->view('innerpages/header');        
                $this->load->view('arquivo/tela_adicionar', $data);
                $this->load->view('innerpages/footer');
        }
        else
        {
                $file_name = $this->upload->data()["file_name"];  
                // $query = $this->db->query("INSERT INTO arquivo (nome, arquivo, atendimento_id) VALUES('".$nome."', '".$file_name."', ".$atendimento_id.");");              
                $query = $this->db->query("INSERT INTO arquivo (nome, arquivo, atendimento_id) VALUES(?, ?, ?);", array($nome, $file_name, $atendimento_id));       
                
                

                header("Location: /arquivo/index/".$atendimento_id);
                // $data = array('upload_data' => $this->upload->data());                               
                // $data['atendimento_id'] = $atendimento_id;
                // $data['error'] = "";
                // $this->load->view('innerpages/header');
                // $this->load->view('arquivo/tela_adicionar', $data);
                // $this->load->view('innerpages/footer');                
                
        
        }
    }

}