<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cities Controller.
 */
class Cities extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('Cities_model');
        $this->load->helper('html');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->is_admin())
        {
            // redirect them to the login page
            redirect('admin/auth/login', 'refresh');
        }
    }

	# GET /cities
	function index() {
        $em= $this->doctrine->em;
        $citiesRepo = $em->getRepository('Entities\City');
        $data['cities'] = $citiesRepo->findAll();
		$data['content'] = '/cities/index';
        $data["tab"]="cities";
        $data["tabTitle"]="ciudades";

        $configRegionRepo = $em->getRepository('Entities\ConfigRegion');
        $configRegionGlobal = $configRegionRepo->findBy(array('groupRegion'=>'global'), array());
        if (count($configRegionGlobal))
            $data['configRegionGlobal'] = $configRegionGlobal;

        $banner = $configRegionRepo->findBy(array('region'=>'cityStarBanner'), array(), 1);
        if (count($banner))
            $data['banner'] = $banner[0]->getBanner();
        $this->load->view('/includes/contentpage', $data);
	}

	# GET /cities/create
	function create() {
		$data['content'] = '/cities/create';
        $data["tab"]="cities";
        $data["tabTitle"]="crear ciudad";

        $em = $this->doctrine->em;

        $configRegionRepo = $em->getRepository('Entities\ConfigRegion');
        $configRegionGlobal = $configRegionRepo->findBy(array('groupRegion'=>'global'), array());
        if (count($configRegionGlobal))
            $data['configRegionGlobal'] = $configRegionGlobal;

        $banner = $configRegionRepo->findBy(array('region'=>'cityAddBanner'), array(), 1);
        if (count($banner))
            $data['banner'] = $banner[0]->getBanner();
        $this->load->view('/includes/contentpage', $data);
	}

	# GET /cities/edit/1
	function edit($id) {
        $em= $this->doctrine->em;
        $data['cities'] = $em->find('Entities\City',$id);
		$data['content'] = '/cities/create';
        $data["tab"]="cities";
        $data["tabTitle"]="editar ciudad";

        $configRegionRepo = $em->getRepository('Entities\ConfigRegion');
        $configRegionGlobal = $configRegionRepo->findBy(array('groupRegion'=>'global'), array());
        if (count($configRegionGlobal))
            $data['configRegionGlobal'] = $configRegionGlobal;

        $banner = $configRegionRepo->findBy(array('region'=>'cityEditBanner'), array(), 1);
        if (count($banner))
            $data['banner'] = $banner[0]->getBanner();
        $this->load->view('/includes/contentpage', $data);
	}

	# GET /cities/destroy/1
	function destroy($id) {
		$data['cities'] = $this->Cities_model->destroy($id);
        $this->session->set_flashdata('item', array('message'=>'El elemento ha sido eliminado correctamente.', 'class'=>'success', 'icon'=>'fa fa-warning', 'title'=>"<strong>Bien!:</strong>"));
        redirect('admin/cities/index', 'refresh');
	}

	# POST /cities/save
	function save() {
		
		$this->form_validation->set_rules('title', 'Title', 'required');

		if ($this->form_validation->run()) {

			$data[] = array();
            $id =  $this->input->post('id', TRUE);
            $em = $this->doctrine->em;
            if(!$id) {
                $city = new \Entities\City();
            }else {
                $city = $em->find("\Entities\City",$id);
            }
            $city->setTitle($this->input->post('title', TRUE));
            $city->setPriority($this->input->post('priority', TRUE));
            $em->persist($city);
            $em->flush();
            $this->session->set_flashdata('item', array('message'=>'Se han guardado sus cambios correctamente.', 'class'=>'success', 'icon'=>'fa fa-thumbs-up', 'title'=>"<strong>Bien!:</strong>"));
            redirect('admin/cities/index', 'refresh');
		}

        $this->load->view('/includes/contentpage', $data);
	}

	function rebuild() {
		$object = new Cities_model();
		$object->id = $this->input->post('id', TRUE);
		$object->title = $this->input->post('title', TRUE);
		return $object;
	}
	
	# POST /cities/visible
    function visible($id)
    {
		$em = $this->doctrine->em;
		if (!$id) {
			echo "ERROR PARÁMETRO";
		} else {
            $userRepo = $em->getRepository('Entities\City');
            $city = $userRepo->findOneBy(array("id" => $id));
			if (!$city) {
				echo "NO EXISTE PARA MODIFICAR";
			}
			$city->visible = !$city->visible;
			$em->persist($city);
			$em->flush();
        }
		redirect('admin/cities/index', 'refresh');
    }
}

?>
