<?php

/* w7G506tgBv */

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('portlets_model');
    }

    public function index($slug = FALSE) {
        //print_r($this->session->userdata());
        if ($this->session->userdata('uid')!=null) { // Redirects logged in user
            return redirect('dashboard');
        }
        //$this->load->view('templates/header', $data);

        $data['items'] = $this->dashboard_model->get_items();
        $data['title'] = 'HOME';
        $this->load->view('dashboard/index', $data);
        //$this->load->view('templates/footer');
    }

    public function login() {
        $username = strtolower($this->input->post("username"));
        $password = $this->input->post("password");
        //$hpassword = hash('sha256',$password);

        $result = $this->dashboard_model->login($username, $password);

        if ($result) { //Successfully logged in
            
            $this->session->set_userdata(array(
                "loggedin" => true,
                "uid" => $result["id"],
                "username" => $result["username"],
                "fullname" => $result["fullname"],
                "lastlogin" => $result["lastlogin"],
                "usergroup" => $result["user_group"],
                "allowed_page" => json_encode($this->dashboard_model->menuPermissionBySlugAndPage($result["user_group"]))
            ));
//            print_r($this->session->userdata());
//            exit;
            $success = 1;
            //logging to be done later. sleepy
        } else {
            $success = 0;
        }
        //$this->output->enable_profiler(TRUE);
        //print_r($this->session->all_userdata());
        echo $success;
    }


    public function toexcel() {
        $post = $this->input->post();
        if ((!$post) || (!isset($post['jsondata'])))
            die();
        $this->load->view('dashboard/toexcel', array("post" => $post['jsondata']));
    }
	
    public function topdf() {
        $post = $this->input->post();
        if ((!$post) || (!isset($post['id'])))
            die();
			
		$print = (isset($post['print']) && ($post['print'] == 1));
		$id = $post["id"];
		$fullslug = $this->dashboard_model->getSlugFromPageId($id);
		
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$port = $_SERVER['SERVER_PORT'];
		$tempfile = tempnam(sys_get_temp_dir(), "pdftemp");
		$phantomdir = getcwd()."\\application\\binary\\";
		$phantomexec = $phantomdir."phantomjs.exe";
		$phantomscript = $phantomdir."topdf.js";
		
		$cmd = $phantomexec . " " . $phantomscript . " " .  $protocol . "localhost:" . $port . "/ppms/" . $fullslug . ($print ? "?print=1" : "") . " " . $tempfile;
		//echo $cmd;
		shell_exec($cmd);
		if (file_exists($tempfile)) {
		/* CURRENT WILL NOT UNLINK THE TEMP FILE, PLEASE DO SO IN THE FUTURE */
			$this->load->view('dashboard/topdf', array("file" => $tempfile));
		} else {
		die();
		}
    }

    public function logout() {
        $this->session->sess_destroy();
        return redirect('/');
    }

    public function debug() {
        var_dump($this->session->all_userdata());
    }

    public function test() {
        $data['title'] = 'test';

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/view', $data);
        $this->load->view('templates/footer');
    }

    public function dashboard() {
        
        if (!$this->session->userdata('uid'))
            return redirect('/');
        
        $data_dates = $this->portlets_model->get_data_dates("tbl_project_prgs_master");
        $overall_progress = $this->portlets_model->get_overall_progress($data_dates[0]["data_date"]);
        $data = array('data' => array(
            'overall_actual' => $overall_progress["actual"],
            'overall_early' => $overall_progress["early"],
            'overall_late' => $overall_progress["late"],
            'overall_variance' =>array(
                "early" => $overall_progress["variance"]["early"],
                "late" => $overall_progress["variance"]["late"]
                ),
            'progress_date' => $data_dates,
            "trend"=>"up"
        ));
        $this->load->view('index', $data);
    }

    public function view($item = FALSE, $query_type = FALSE, $query_key = FALSE) {
        //print_r($this->session->userdata('allowed_page'));

        if (!$this->session->userdata('uid')) {
            //commented by Jane on 19/10/2016
           /* if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', "::1"))) {
                $this->session->set_userdata(array(
                    "loggedin" => true,
                    "uid" => '',
                    "username" => '',
                    "fullname" => 'Hummingsoft Robot',
                    "lastlogin" => '',
                    "usergroup" => 1,
                    "allowed_page" => json_encode($this->dashboard_model->menuPermissionBySlugAndPage(1))
                ));
            } else*/
                return redirect('/');
        }

        $date = $this->input->get("date");
        //if($_SERVER['REQUEST_URI'] != "/mpxd/assets/js/backbone-min.map" && $_SERVER['REQUEST_URI'] != "/mpxd/assets/js/underscore-min.map") // Why these scripts call view?
        //$this->session->set_userdata(array("selected_date" => $date));
        //$this->session->set_userdata(array($_SERVER['REQUEST_URI'] => $date));

        $data['menu'] = $this->dashboard_model->getMenu();
        $data['permission'] = $this->dashboard_model->menuPermission();
        $data['title'] = 'View Page';
        $data['userdata'] = $this->session->all_userdata(); 

//        $this->load->view('templates/default_header', $data);
//        $this->load->view('dashboard/view', $data);
//        $this->load->view('templates/default_footer');
        $this->load->view('portlets_holder',$data);
    }

    public function portlet($slug = FALSE, $page = FALSE) {
        if (!$slug)
            return show_404();
        else {
            $page = ($page == false ? 1 : $page);
            $result = $this->dashboard_model->getPortlet($slug, $page);
        }

        /*Added by Sebin : Starts Here*/
        if($page=="comparison"){
            $vals = $this->session->userdata('cmpid');
            if(sizeof($vals)>0) {
                $result = array_slice($result, 0,sizeof($vals));
            }
        }
        /*Ends here*/

        $data['title'] = 'portlet configuration';
        $data['item'] = $result;
//        var_dump($data);
        $this->load->view('dashboard/api', $data);
        //$this->output->enable_profiler(TRUE);
    }
    /**
     * @sebin
     * date:23/10/2016
     * Parameter:
     * Return type: ajax call , Array
     * Description: Attain compare ids of the viaducts and save them in the session for further processing in the api method under viaduxts/comparison.
     */
    public function compare(){
        $vals = $this->input->post("data");
        $this->session->set_userdata('cmpid', $vals);
        $data['item'] = true;
        $this->load->view('dashboard/api', $data);
    }
    /**
     * @sebin(Modified), @jane(Modified)
     * date:25/10/2016 (Date last Modified)
     * Parameter:
     * Return type: ajax call , Array
     * Description: Attain the portlet wise data.
     */
    public function api() {
        //$result = array();
        if ($this->input->get()) {
            $gets = $this->input->get(); 
			//Unset unneeded array
			unset($gets['item_id']);
			unset($gets['date']);
            unset($gets['p']);
			unset($gets['_']);

            $query = array_keys($gets);
            $itemID = $this->input->get('item_id');
            $page = $this->input->get('p');

            //Data archive date list
            if ($this->input->get("date_list")) {
                $slug = $this->input->get("date_list");
                $data['item'] = $this->dashboard_model->get_date_list($slug);
            }else if($this->input->get("comments")){
                $result = $this->dashboard_model->getComment();
                if($result){
                    $data['status']="success";
                    $data['comment']= $result;
                }else{
                    $data['status']="fail";
                    $data['comment']= $result;
                }
              echo json_encode($data);
            }
            //Default api get portlet content and data.
            else {
                $date = $this->input->get("date");
                if ($itemID) //Use item ID to retrieve items meta
                    $item_meta = $this->dashboard_model->get_meta($query, $itemID);
                else
                    $item_meta = $this->dashboard_model->get_meta($query);

                $data_source = $this->dashboard_model->get_source_archivable($item_meta[0]['item_id'], $date);
				$data_source_static = $this->dashboard_model->get_static_source($itemID);
                $data['title'] = 'api';
                $slug_name= $this->dashboard_model->get_slug($itemID);
                switch($slug_name[0]['slug']){
                    case "v201": case "v202": case "v203": case "v204": case "v205": case "v206": case "v207": case "v208": case "v209": case "v210":
                        switch($page) {
                            case "index":
                                $tpack = array();
                                $pkg_info = $this->subString($this->portlets_model->package_info($slug_name[0]['slug']));
                                $kad = $this->subString($this->portlets_model->kad($slug_name[0]['slug'], $date));
                                $gallary = $this->subString($this->portlets_model->gallary($slug_name[0]['slug']));
                                $scurve = $this->subString($this->portlets_model->scurve($slug_name[0]['slug'], $date));
                                $hsse = $this->subString($this->portlets_model->hsse($slug_name[0]['slug']));
                                $qrm = $this->subString($this->portlets_model->kpi($slug_name[0]['slug'], $date));
                                $piers = $this->subString($this->portlets_model->piers($slug_name[0]['slug']));
                                array_push($tpack, $qrm, $kad, $pkg_info, $hsse, $gallary, $scurve, $piers);
                                $slug_data = $this->slugConcat($slug_name[0]['slug'], $tpack);
                                $outer = array();
                                $inner = array(
                                    "name" => strtoupper($slug_name[0]['slug']),
                                    "value" => $slug_data
                                );
                                array_push($outer, $inner);
                                $data['item'] = array('item' => $item_meta, 'data' => $outer, 'static_data' => '[]');
                                break;
                            case "piers":
                                $piers = $this->portlets_model->piers($slug_name[0]['slug'], $date);
                                $outer = array();
                                $inner = array(
                                    "name" => strtoupper($slug_name[0]['slug'])."PIERS",
                                    "value" => $piers
                                );
                                array_push($outer, $inner);
//                                print_r($outer);
//                                exit;
                                $data['item'] = array('item' => $item_meta, 'data' => $outer, 'static_data' => $data_source_static);
                                break;
                            case "kpiv201": case "kpiv202": case "kpiv203": case "kpiv204": case "kpiv205": case "kpiv206": case "kpiv207": case "kpiv208": case "kpiv209": case "kpiv210":
                                $qrm = $this->portlets_model->kpi_piers($slug_name[0]['slug'], $date);
                                $outer = array();
                                $inner = array(
                                    "name" => "KPI- ".strtoupper($slug_name[0]['slug']),
                                    "value" => $qrm
                                );
                                array_push($outer, $inner);
                                $data['item'] = array('item' => $item_meta, 'data' => $outer, 'static_data' => $data_source_static);
                                break;
                            default:
                                $data['item'] = array('item' => $item_meta, 'data' => $data_source, 'static_data' => $data_source_static);
                                break;
                        }
                        break;
                    case "viaducts":
                        switch($page){
                            case "summary":
                                $summary  = $this->portlets_model->viaducts_summary($date);
                                $outer = array();
                                $inner = array("name" => "Viaducts Summary","value" => $summary);
                                array_push($outer, $inner);
                                $data['item'] = array('item' => $item_meta, 'data' => $outer, 'static_data' => '[]');
                                break;
                            case "comparison":
                                $ids = $this->session->userdata('cmpid');
                                if(sizeof($ids)>0){
                                    $cmp = $ids;
                                }else{
                                    $r = $this->portlets_model->get_viaducts();
                                    $bs = array();
                                    foreach($r as $s){
                                        foreach($s as $v) {
                                            array_push($bs, $v);
                                        }
                                    }
                                    $cmp = $bs;
                                }
                                $arr_cmp = array();
                                $slug_data =array();
                                foreach($cmp as $pid) {
                                    $cmp_slug = $this->portlets_model->get_project($pid);
                                    $kad = $this->subString($this->portlets_model->kad($cmp_slug, $date));
                                    $scurve = $this->subString($this->portlets_model->scurve($cmp_slug, $date));
                                    $qrm = $this->subString($this->portlets_model->kpi($cmp_slug, $date));
                                    $arr_cmp[strtoupper($cmp_slug)]=array();
                                    array_push($arr_cmp[strtoupper($cmp_slug)], $qrm, $kad, $scurve);
                                }

                                $ref = $this->portlets_model->get_ref($item_meta,$cmp);
                                $mdata = $this->slugConcat2("CMP", $arr_cmp, $ref);
                                $outer = array();
                                $inner = array("name" => "Viaducts Comparison","value" => $mdata);
                                array_push($outer, $inner);
                                $data['item'] = array('item' => array_reverse($item_meta), 'data' => $outer, 'static_data' => "[]");
                                break;
                            default:
                                $data['item'] = array('item' => $item_meta, 'data' => $data_source, 'static_data' => $data_source_static);
                                break;
                        }
                        break;
                    case "programme":
                        switch($page){
                            case "scurve":
                                $pgm_data_source = $this->portlets_model->p_scurve($date);
                                $outer = array();
                                $inner = array(
                                    "name" => "Programme",
                                    "value" => $pgm_data_source
                                );
                                array_push($outer, $inner);
                                $data['item'] = array('item' => $item_meta, 'data' => $outer, 'static_data' => '[]');
                                break;
                            default:
                                $data['item'] = array('item' => $item_meta, 'data' => $data_source, 'static_data' => $data_source_static);
                                break;
                        }
                        break;
                    default:
                        $data['item'] = array('item' => $item_meta, 'data' => $data_source, 'static_data' => $data_source_static);
                        break;
                }
            }
        } else {
            return show_404();
        }
		//$data = array_merge($data[, array(0));
        $this->load->view('dashboard/api', $data);
    }
	
 	public function subString($json){
		return substr($json,1,-1);
	}

    public function slugConcat($slug,$pack){
       $data = '{"'.$slug.'":{';
        foreach ($pack as $v) {
            $data = $data.$v.',';
        }

        return rtrim($data,',').'}}';
    }
    public function slugConcat2($slug,$pack, $ref){
        $data = '{"'.$slug.'":{';
        foreach ($pack as $k=>$v) {
            $data = $data . '"'.$k.'"'.':{';
            foreach ($v as $z) {
                $data = $data . $z . ',';
            }
            $data = rtrim($data,',').'},';
        }

        return rtrim($data,',').'},"REF":'.$ref.'}';
    }

    public function setapi() {
        $result = array();
        if ($this->input->post()) {
            $gets = $this->input->post();
            foreach ($gets as $k => $get) {
                $query = null;
                $object = new stdClass();
                $keys = explode(":", $get);
                if (sizeof($keys) == 4) {

                    $query['item'] = $keys[0];
                    $query['type'] = $keys[1];
                    $query['meta_key'] = $keys[2];
                    $query['meta_value'] = base64_decode($keys[3]);

                    $log = $this->dashboard_model->set_item_by_allkey($query['item'], $query['type'], $query['meta_key'], $query['meta_value']);
                    //array_push($result[$k], array('id' => $k));
                    $result[$k] = Array();
                    $result[$k]['id'] = $k;
                    $result[$k]['log'] = $log;
                } else {
                    $result[$k] = 'Parameters passed are not correct' . implode(",", $gets);
                }
            }
            //var_dump($result);die();
        } else {
            return show_404();
        }

        //var_dump($result);
        $data['title'] = 'api';
        $data['item'] = $result;
        //$this->output->enable_profiler(TRUE);
        $this->load->view('dashboard/api', $data);
    }

    public function draw() {
        $items = $this->dashboard_model->get_items();
        //var_dump($items);die();
        $pages = Array();
        foreach ($items as $idx => $i) {
            $items[$idx]['temp_pages'] = $this->dashboard_model->getPortletBySlug($i['slug']);
            //$pages[$i['id']] = $this->dashboard_model->getPortletBySlug($i['slug']);
        }
        //$pages = $this->dashboard_model->getPortletBySlug('programme');
        $this->load->view('dashboard/draw', Array('data' => Array(
                'items' => $items
        )));
    }
    public function save() {

        //header('Content-type: application/json');

        $input_data = $this->input->post(); //'[{"id":"2","key":"overall_elevated","slug":"programme","type":"scurve","col":1,"row":1,"size_x":6,"size_y":1},{"id":"7","key":"underground","slug":"programme","type":"scurve","col":7,"row":1,"size_x":6,"size_y":1},{"id":"8","key":"elevated_north","slug":"programme","type":"scurve","col":1,"row":2,"size_x":6,"size_y":1},{"id":"9","key":"elevated_south_underground","slug":"programme","type":"scurve","col":7,"row":2,"size_x":6,"size_y":1},{"id":"10","key":"overall_elevated_underground","slug":"programme","type":"scurve","col":1,"row":3,"size_x":6,"size_y":1},{"id":"11","key":"elevated_south","slug":"programme","type":"scurve","col":7,"row":3,"size_x":6,"size_y":1}]';
        if ($input_data) {
            $portlets = $input_data['portlets'];
            //$page = 0;//$input_data['page'];
            $portlet_array = json_decode($portlets, true);

            $log = $this->dashboard_model->updatePortlet($portlet_array);
            //$a = $this->dashboard_model->updatePortlet(json_decode('[{"id":"2","key":"overall_elevated","slug":"programme","type":"scurve","col":1,"row":1,"size_x":6,"size_y":1},{"id":"7","key":"underground","slug":"programme","type":"scurve","col":7,"row":1,"size_x":6,"size_y":1},{"id":"8","key":"elevated_north","slug":"programme","type":"scurve","col":1,"row":2,"size_x":6,"size_y":1},{"id":"9","key":"elevated_south_underground","slug":"programme","type":"scurve","col":7,"row":2,"size_x":6,"size_y":1},{"id":"10","key":"overall_elevated_underground","slug":"programme","type":"scurve","col":1,"row":3,"size_x":6,"size_y":1},{"id":"11","key":"elevated_south","slug":"programme","type":"scurve","col":7,"row":3,"size_x":6,"size_y":1}]', true), "1");
            printf($log);
        } else {
            return show_404();
        }
    }

    public function apiapi($item = FALSE, $query_type = FALSE, $query_key = FALSE) {

        if ($item && !$query_type) {
            $data['item'] = $this->dashboard_model->get_items($item);
        } else if ($query_type && $query_key) {
            $allowed_type = array('type', 'meta_key');

            if (in_array($query_type, $allowed_type)) {
                $data['item'] = $this->dashboard_model->get_items_by_type($item, $query_type, $query_key);
            }
        } else {
            return show_404();
        }


        if (empty($data['item'])) {
            show_404();
        }

        //var_dump($data);
        $data['title'] = 'api';
        //$this->output->enable_profiler(TRUE);
        $this->load->view('dashboard/api', $data);
    }

    /*
     *
     * TEST TEST BELOW
     *
     */

    public function create($test1 = 'test1', $test2 = 'test2') {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');

        $newdata = array(
            'username' => 'johndoe',
            'email' => 'johndoe@some-site.com',
            'logged_in' => TRUE
        );

        $this->session->set_userdata($newdata);

        var_dump($this->session->all_userdata());
        var_dump($test2);

        $data['title'] = 'Create a news item';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'text', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');
        } else {
            $this->news_model->set_news();
            $this->load->view('news/success');
        }
    }
    public function buildTree($ar, $pid = null)
    {
        $op = array();
        foreach ($ar as $item) {
            if ($item['parentId'] == $pid) {
                $op[$item['id']] = array(
                    'name' => $item['name'],
                    'parentId' => $item['parentId']
                );
                // using recursion
                $children = $this->buildTree($ar, $item['id']);
                if ($children) {
                    $op[$item['id']]['children'] = $children;
                }
            }
        }
        return $op;
    }
}
