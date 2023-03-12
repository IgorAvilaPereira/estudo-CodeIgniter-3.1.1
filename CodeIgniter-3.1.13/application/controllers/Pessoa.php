<?php

class Pessoa extends CI_Controller {



    public function index()
    {          
        $this->load->database();
        $this->load->helper('url'); 
        $query = $this->db->query('SELECT * FROM pessoa order by id');
        $data['vetPessoa'] = $query->result();
        $this->load->view('innerpages/header');
        $this->load->view('pessoa/index', $data);
        $this->load->view('innerpages/footer');
    }
    public function tela_adicionar()    {
        $data['error'] = "";
        $data['upload_data'] = [];
        $this->load->view('innerpages/header');
        $this->load->view('pessoa/tela_adicionar', $data);        
        $this->load->view('innerpages/footer');
    }
    public function tela_editar($id)    {
        $this->load->database();
        $this->load->helper('url'); 
        $query = $this->db->query('SELECT * FROM pessoa WHERE id = '.$id);
        $data['pessoa'] = $query->result()[0]; 
        $this->load->view('innerpages/header');
        $this->load->view('pessoa/tela_editar', $data);
        $this->load->view('innerpages/footer');
    }
    public function editar()    
    {

        $this->load->database();
        $this->load->helper('url'); 
        $form_data = $this->input->post();        
        $id = $this->input->post("id");
        $nome = $this->input->post("nome");
        $data_nascimento = $this->input->post("data_nascimento");
        $cpf = $this->input->post("cpf");
        $rg = $this->input->post("rg");
        $rua = $this->input->post("rua");
        $bairro = $this->input->post("bairro");
        $complemento = $this->input->post("complemento");
        $cep =  $this->input->post("cep");
        $sexo =  $this->input->post("sexo");
    // Foto: <input type="file" name="foto">  <br>
        $query = $this->db->query("UPDATE pessoa SET nome = '".$nome."',  data_nascimento = '".$data_nascimento."', cpf = '".$cpf."', rg='".$rg."', rua ='".$rua."', bairro = '".$bairro."', complemento = '".$complemento."', cep = '".$cep."', sexo = '".$sexo."' where id = ".$id.";");        
        header("Location: /pessoa/index");   
    }
    public function remover($id)    {
        $this->load->database();
        $this->load->helper('url'); 
        $query = $this->db->query("SELECT * FROM documento WHERE pessoa_id = ".$id.";");        
        $vetDocumento = $query->result();       
        $query = ""; 
        if (count($vetDocumento) > 0) {
            foreach($vetDocumento as $documento){
                if (unlink("./documentos/".$documento->arquivo)) {
                    $query.="DELETE FROM documento WHERE id = ".$id.";";
                }
            }
        }
        $query = $this->db->query("BEGIN;".$query."DELETE FROM pessoa WHERE id = ".$id."; COMMIT;");                
        header("Location: /pessoa/index");        
    }
    public function adicionar()    
    {      
        $this->load->database();
        $this->load->helper('url'); 
        $form_data = $this->input->post();        
        $nome = $this->input->post("nome");
        $data_nascimento = $this->input->post("data_nascimento");
        $cpf = $this->input->post("cpf");
        $rg = $this->input->post("rg");
        $rua = $this->input->post("rua");
        $bairro = $this->input->post("bairro");
        $complemento = $this->input->post("complemento");
        $cep =  $this->input->post("cep");
        $sexo =  $this->input->post("sexo");

        $config['upload_path']          = './fotos/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 200;
        $config['max_width']            = 3000;
        $config['max_height']           = 3000;    
        $config['encrypt_name'] = TRUE;
    
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('foto'))
        {
                $data = array('error' => $this->upload->display_errors());
                // $data['pessoa_id'] = $pessoa_id;
                $data['upload_data'] = [];
                $this->load->view('innerpages/header');        
                $this->load->view('documento/tela_adicionar', $data);
                $this->load->view('innerpages/footer');
        }
        else
        {
                $file_name = $this->upload->data()["file_name"];          
                $query = $this->db->query("INSERT INTO pessoa (foto, nome, data_nascimento, cpf, rg, rua, bairro, complemento, cep, sexo) VALUES ('".$file_name."', '".$nome."', '".$data_nascimento."', '".$cpf."', '".$rg."','".$rua."','".$bairro."','".$complemento."', '".$cep."', '".$sexo."')");        
                header("Location: /pessoa/index");    
        }
    }
}