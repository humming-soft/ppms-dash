<?php

class Dashboard_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function login($username, $password)
    {
        $hpassword = hash('sha256', $password);
        $this->db->select('id,username,password,fullname,lastlogin,user_group');
        $this->db->from('users');
        $this->db->where(array('username' => $username));
        $query = $this->db->get();
        $user = $query->row_array();
        //var_dump($user);
        if (count($user) != 0) {
            if ($user['password'] == $hpassword) {
                /* Password available in DB, login through DB */
                $this->update_login_lastlog($user);
                $this->log_login_attempt(array('data' => 'Successfull login for user ' . $username . ' using custom user in DB', 'ip_address' => $_SERVER['REMOTE_ADDR']));
                return $user;
            } else if ($user['password'] === "") {
                $ldap = $this->login_ad($username, $password);
                /* Empty password in DB, login through AD instead */
                if ($ldap) {
                    $this->update_login_lastlog($user);
                    $this->log_login_attempt(array('data' => 'Successfull login ' . $username . ' from AD, username exists in DB', 'ip_address' => $_SERVER['REMOTE_ADDR']));
                    return $user;
                } else {
                    /* Wrong username/password in AD */
                    $this->log_login_attempt(array('data' => 'Wrong username/password for ' . $username, 'ip_address' => $_SERVER['REMOTE_ADDR']));
                    return false;
                }
            } else {
                /* Wrong password in DB */

                /* Login through AD */
                if ($ldap) {
                    $ldap = $this->login_ad($username, $password);
                    /* Login through AD */
                    $this->log_login_attempt(array('data' => 'Successfull login ' . $username . ' from AD', 'ip_address' => $_SERVER['REMOTE_ADDR']));
                    return $user;
                } else {
                    /* Nope. */
                    $this->log_login_attempt(array('data' => 'Wrong password for user ' . $username, 'ip_address' => $_SERVER['REMOTE_ADDR']));
                    return false;
                }
                return false;
            }

            // if ($user['password'] == $hpassword) {
            //     /* Password available in DB, login through DB */
            //     $this->update_login_lastlog($user);
            //     $this->log_login_attempt(array('data' => 'Successfull login for user ' . $username . ' using custom user in DB', 'ip_address' => $_SERVER['REMOTE_ADDR']));
            //     return $user;
            // } else if ($user['password'] === "") {
            //     /* Empty password in DB, login through AD instead */
            //     $ldap = $this->login_ad($username, $password);
            //     if ($ldap) {
            //         $this->update_login_lastlog($user);
            //         $this->log_login_attempt(array('data' => 'Successfull login ' . $username . ' from AD, username exists in DB', 'ip_address' => $_SERVER['REMOTE_ADDR']));
            //         return $user;
            //     } else {
            //         /* Wrong username/password in AD */
            //         $this->log_login_attempt(array('data' => 'Wrong username/password for ' . $username, 'ip_address' => $_SERVER['REMOTE_ADDR']));
            //         return false;
            //     }
            // } else {
            //     /* Wrong password in DB */

            //     /* Login through AD */
            //     $ldap = $this->login_ad($username, $password);
            //     if ($ldap) {
            //         /* Login through AD */
            //         $this->log_login_attempt(array('data' => 'Successfull login ' . $username . ' from AD', 'ip_address' => $_SERVER['REMOTE_ADDR']));
            //         return $user;
            //     } else {
            //         /* Nope. */
            //         $this->log_login_attempt(array('data' => 'Wrong password for user ' . $username, 'ip_address' => $_SERVER['REMOTE_ADDR']));
            //         return false;
            //     }
            //     return false;
            // }
        }/* Enable to allow login if the username does not exist in DB *
          else {
          $ldap = $this->login_ad($username, $password);
          if ($ldap) {
          /* User does not exist in DB, but exists in AD! *
          $user = Array(
          'id'=>'',
          'username'=>$username,
          'fullname'=>$username,
          'lastlogin'=>'');
          $this->log_login_attempt(array('data'=>'Successfull login '.$username.' from AD, username does not exist in DB','ip_address'=>$_SERVER['REMOTE_ADDR']));
          return $user;
          } else {
          /* Nope. *
          $this->log_login_attempt(array('data'=>'Unexistant username '.$username,'ip_address'=>$_SERVER['REMOTE_ADDR']));
          return false;
          }
          } */
    }

    public function login_ad($username, $password)
    {
        $backslash = strpos($username, "\\");
        $domainuser = "MYMRT\\" . (($backslash === FALSE) ? $username : substr($username, $backslash + 1));
        //$ldap = ldap_connect("eagle.office.hummingsoft.com.my"); //172.16.2.10
        $ldap = ldap_connect("172.16.2.10"); //172.16.2.10
        return ldap_bind($ldap, $domainuser, $password);

        // $ldap = ldap_connect("172.16.2.10"); //172.16.2.10
        // return ldap_bind($ldap, $domainuser, $password);
    }

    public function update_login_lastlog($user)
    {
        $this->db->where('id', $user['id']);
        $this->db->set('lastlogin', 'NOW()', FALSE);
        return $this->db->update('users');
    }

    public function log_login_attempt($data)
    {
        return $this->db->insert('users_log', $data);
    }

    public function getSlugFromPageId($id)
    {
        /* Function to return full slug including item and the page slug */
        if (!is_numeric($id)) {
            die();
        }
        $this->db->select('items.slug,pages.page');
        $this->db->from('pages');
        $this->db->join('items', 'items.id = pages.item_id');
        $this->db->where('pages.id', $id);
        $page_query = $this->db->get();
        $page_result = $page_query->result_array();
        if (sizeOf($page_result) < 1) {
            die();
        }
        $page = $page_result[0]["page"];
        $slug = $page_result[0]["slug"];
        if (($page == "") || ($slug == "")) {
            die("No full page");
        }
        $fullslug = $slug . "/" . $page;
        return $fullslug;
    }

    public function menuPermission()
    {
        //Get permission list by page.
        $this->db->select('page_id');
        $this->db->from('group_permissions');
        $this->db->where('group_id', $this->session->userdata('usergroup'));
        $query_perm = $this->db->get();

        $results_perm = $query_perm->result_array();
        foreach ($results_perm as $k => $result) {
            $permission[$k] = $result['page_id'];
        }
        //end get permission
        return $permission;
    }


    public function menuPermissionBySlugAndPage($usergroup)
    {
        $this->db->select('items.slug,pages.page');
        $this->db->from('group_permissions');
        $this->db->join('pages', 'pages.id = group_permissions.page_id');
        $this->db->join('items', 'items.id = pages.item_id');
        $this->db->where('group_id', $usergroup);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function getMenu()
    {
        $this->db->select('pages.id,items.name,items.slug,pages.page,pages.page_name,pages.parent,pages.external_url, pages.hidden');
        $this->db->from('pages');
        $this->db->join('items', 'items.id = pages.item_id');
        $this->db->order_by('pages.order');
        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        $results = $query->result_array();

        $permission = $this->menuPermission();
        $permissionTree = $this->findParentMenu($results, $permission); //Menu tree to build menu list based on parent child relation.


        $menu = array();

        $count = 0;
        foreach ($results as $k => $result) {
            if (in_array($result['id'], $permissionTree)) {
                $url = $result['external_url'] == 0 ? $result['slug'] . '/' . $result['page'] : '#';
                $url = $result['slug'] == '#' ? $result['slug'] : $url;
                $urlout = $result['external_url'] == 1 ? $result['slug'] . '/' . $result['page'] : '#';
                $allow = false;
                if (in_array($result['id'], $permission))
                    $allow = true;

                if ($result['external_url'] == 1) {
                    $menu[$count] = array(
                        'id' => $result['id'],
                        'name' => $result['page_name'],
                        'url' => $url,
                        'outurl' => $urlout,
                        'parent' => (int)$result['parent'],
                        'allow' => $allow,
                        'hidden' => (bool)$result['hidden']
                    );
                } else {
                    $menu[$count] = array(
                        'id' => $result['id'],
                        'name' => $result['page_name'],
                        'url' => $url,
                        'parent' => (int)$result['parent'],
                        'allow' => $allow,
                        'hidden' => (bool)$result['hidden']
                    );
                }
                $count++;
            }
        }
        //print_r($menu);

        return $menu;
    }

    function findParentMenu($menu, $item)
    {
        $menu = array_reverse($menu);
        foreach ($menu as $m) {
            if (in_array($m['id'], $item)) {
                if ($m['parent'] != 0) {
                    if (!in_array($m['parent'], $item))
                        array_push($item, $m['parent']);
                }
            }
        }
        return $item;
    }

    function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

    public function get_items($item = FALSE)
    {
        if ($item === FALSE) {
            $query = $this->db->get('items');
            if ($query) return $query->result_array();
        }

        //
        $this->db->select('type_name,meta_key,slug,title,meta_value');
        $this->db->from('items_meta');
        $this->db->join('types', 'types.id = items_meta.type_id');
        $this->db->join('items', 'items.id = items_meta.item_id');
        $this->db->where('items.slug', $item);
        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        if ($query) return $query->result_array();
    }

    public function get_items_by_types($item, $query_type = FALSE, $query_key = FALSE)
    {
        if ($query_type === FALSE || $query_key === FALSE) {
            die('No type/key given.');
        }
        if ($query_type == 'type') {
            $this->db->select('*');
            $this->db->from('items_meta');
            $this->db->join('types', 'types.id = items_meta.type_id');
            $this->db->join('items', 'items.id = items_meta.item_id');
            $this->db->where(array('types.type_name' => $query_key, 'items.slug' => $item));
        } else if ($query_type == 'meta_key') {
            $this->db->select('*');
            $this->db->from('items_meta');
            $this->db->join('types', 'types.id = items_meta.type_id');
            $this->db->join('items', 'items.id = items_meta.item_id');
            $this->db->where(array('items_meta.meta_key' => $query_key, 'items.slug' => $item));
        }

        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        return $query->row_array();
    }

    public function get_meta($arr, $itemID = FALSE)
    {
        $this->db->select('items_meta.id, title, type as type_name, items_meta.meta_group_id as meta_group_id, meta_key, pages.item_id as item_id');
        $this->db->from('meta_group');
        $this->db->join('items_meta', 'meta_group.id = items_meta.meta_group_id');
        $this->db->join('pages', 'meta_group.id = pages.meta_group_id');
        $this->db->where_in('items_meta.id', $arr);
        if ($itemID)
            $this->db->where('pages.item_id', $itemID);


        $query = $this->db->get();
//        echo "here1111";
//        /*print_r($query->result_array());*/
//        echo "here2222";
        //$query = $this->db->get_where('items', array('slug' => $item));
        if ($query) return $query->result_array();
    }

    /*
        public function get_source($id) {

            $this->db->select('name,value');
            $this->db->from('data_sources');
            $this->db->join('meta_sources', 'data_sources.id = meta_sources.source_id');
            $this->db->where_in('meta_sources.meta_group_id', $id);

            $query = $this->db->get();

            //$query = $this->db->get_where('items', array('slug' => $item));

            if ($query) return $query->result_array();
        }

    */
    public function get_date_list($slug)
    {
        $this->db->select("to_char(data_sources.date, 'DD-Mon-YY') as date", FALSE); //Postgres
        //$this->db->select("DATE_FORMAT(data_sources.date, '%d-%b-%y') as date", FALSE); //MYSQL
        $this->db->from('data_sources');
        $this->db->join('items', 'items.id = data_sources.item_id');
        $this->db->where('items.slug', $slug);
        $this->db->order_by('data_sources.date', "desc");

        $query = $this->db->get();
        //var_dump($query);
        //$query = $this->db->get_where('items', array('slug' => $item));

        if ($query) return $query->result_array();
    }

    public function get_static_source($id)
    { // Static data
        $this->db->from('data_sources_static');
        $this->db->where('data_sources_static.item_id', $id);
        $this->db->select('name,value');

        $query = $this->db->get();

        if ($query) return $query->result_array();
    }


    public function get_source_archivable($id, $date = FALSE)
    { //Archive
        $this->db->from('data_sources');
        //$this->db->join('meta_sources', 'data_sources.id = meta_sources.source_id');
        $this->db->where('data_sources.item_id', $id);

        if ($date) { //If date is selected
            $this->db->select('name,value');
            $timestamp = date('Y-m-d', strtotime($date));
            $this->db->where('DATE(data_sources.date)', $timestamp);
        } else { //Get latest record if no date is selected
            $this->db->select('name,value');
            $this->db->order_by("date", "desc");
            $this->db->limit(1);
        }

        //$this->db->limit(1); //Return the latest result only.
        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        if ($query) return $query->result_array();
    }

    public function get_item_meta($id)
    {

        $this->db->select('type_name,meta_key,title,meta_value');
        $this->db->from('items_meta');
        $this->db->join('types', 'types.id = items_meta.type_id');
        $this->db->join('items', 'items.id = items_meta.item_id');
        $this->db->where(array('items_meta.meta_key' => $meta_key, 'items.slug' => $item, 'types.type_name' => $type));

        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        return $query->row_array();
    }

    public function set_item_by_allkey($item, $type, $meta_key, $meta_value)
    {
        $log = "";
        $this->db->select('items_meta.id, items_meta.type_id, items_meta.item_id, items_meta.meta_key');
        $this->db->from('items_meta');
        $this->db->join('types', 'types.id = items_meta.type_id');
        $this->db->join('items', 'items.id = items_meta.item_id');
        $this->db->where(array('items_meta.meta_key' => $meta_key, 'items.slug' => $item, 'types.type_name' => $type));

        $query = $this->db->get();


        $get = $query->row_array();
        if (sizeOf($get) < 1) {
            /* Meta does not exist, insert. */

            /* Check item first */
            $qitem_arr = $this->db->select('id')->from('items')->where('slug', $item)->get()->row_array();
            if (sizeOf($qitem_arr) < 1) {
                /* Item does not exist, create new (should have a different function for this) */
                $newitem = Array(
                    'name' => $item,
                    'slug' => $item,
                    'parent' => 0
                );
                $this->db->insert('items', $newitem);
                $qitem = $this->db->insert_id();
                $log .= "Created item " . $qitem . "\n";
            } else {
                $qitem = $qitem_arr['id'];
            }


            /* Check types */
            $qtypes_arr = $this->db->select('id')->from('types')->where('type_name', $type)->get()->row_array();
            if (sizeOf($qtypes_arr) < 1) {
                /* Type does not exist, create new (should have a different function for this) */
                $newtype = Array(
                    'type_name' => $type
                );
                $this->db->insert('types', $newtype);
                $qtype = $this->db->insert_id();
                $log .= "Created type " . $qtype . "\n";
            } else {
                $qtype = $qtypes_arr['id'];
            }


            $newmeta = Array(
                'type_id' => $qtype,
                'item_id' => $qitem,
                'title' => 'New portlet',
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            );

            $this->db->insert('items_meta', $newmeta);
            $qmeta = $this->db->insert_id();
            $log .= "Created item_meta " . $qmeta . "\n";
        } else {
            /* Meta exists. Update */
            $newmeta = $get;
            $newmeta['meta_value'] = $meta_value;

            $this->db->where('id', $newmeta['id']);
            $this->db->update('items_meta', $newmeta);
            $log .= "Updated item_meta";
        }
        return $log;
    }

    public function getPortlet($slug, $page)
    {
        //$this->db->select('*');
        $this->db->select('items_meta.id as id,slug,pages.id as page,type,meta_key,portlet_configuration.value as value, items_meta.meta_group_id, items_meta.portlet_id, items.id as item_id, items_meta.pdf_order as order');
        $this->db->from('items');
        $this->db->join('pages', 'pages.item_id = items.id');
        $this->db->join('meta_group', 'meta_group.id = pages.meta_group_id');
        $this->db->join('items_meta', 'meta_group.id = items_meta.meta_group_id');
        $this->db->join('portlet_configuration', 'portlet_configuration.id = items_meta.portlet_id');
        $this->db->where(array('items.slug' => $slug, 'pages.page' => $page));

        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        $results = $query->result_array();
        $return = array();

        foreach ($results as $k => $result) {
            $portlet_value = json_decode($result['value']);
            //add new value to the portlet
            $portlet_value->slug = $result['slug'];
            $portlet_value->type = $result['type'];
            $portlet_value->key = $result['meta_key'];

            $return[$k]['value'] = json_encode($portlet_value);
            $return[$k]['id'] = $result['id'];
            $return[$k]['slug'] = $result['slug'];
            $return[$k]['meta_group_id'] = $result['meta_group_id'];
            $return[$k]['portlet_id'] = $result['portlet_id'];
            $return[$k]['item_id'] = $result['item_id'];
            $return[$k]['order'] = $result['order'];
        }

//        var_dump($return);
        return $return;
    }

    public function getPortletOld($slug, $page)
    {

        $this->db->select('portlet_configuration.id as id,slug,value, portlet_configuration.page_id as page');
        $this->db->from('items');
        $this->db->join('pages', 'pages.item_id = items.id');
        $this->db->join('portlet_configuration', 'portlet_configuration.page_id = pages.id');
        $this->db->where(array('items.slug' => $slug, 'pages.page' => $page));

        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        if ($query) return $query->result_array();
    }

    public function getPortletBySlug($slug)
    {

        $this->db->select('portlet_configuration.id as id,slug,value, portlet_configuration.page_id as page');
        $this->db->from('items');
        $this->db->join('pages', 'pages.item_id = items.id');
        $this->db->join('portlet_configuration', 'portlet_configuration.page_id = pages.id');
        $this->db->where(array('items.slug' => $slug));


        $query = $this->db->get();

        //$query = $this->db->get_where('items', array('slug' => $item));
        if ($query) return $query->result_array();
    }

    public function updatePortletold($portlets)
    {
        /* Because of cross-database non-interoperability of "ON DUPLICATE UPDATE", had to first determine what to update and what to insert. */
        $log = "";
        $getids = function ($a) {
            if (isset($a['id']))
                return $a['id'];
            else
                return "";
        };

        $ids = array_map($getids, $portlets);
        $this->db->select('id, portlet_id');
        $this->db->from('items_meta');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $result_ids = $query->result_array();
        $existing_ids = array_map(function ($a) {
            return $a['id'];
        }, $result_ids);

        $to_update = Array();
        $to_insert = Array();
        //$portlets['asd'] = Array('id'=>'21');
        foreach ($portlets as $p) {

            //{"id":"2","key":"overall_elevated","slug":"programme","type":"scurve","col":1,"row":1,"size_x":6,"size_y":1}
            $newp = Array(
                'col' => $p['col'],
                'row' => $p['row'],
                'size_x' => $p['size_x'],
                'size_y' => $p['size_y']
            );

            /* If no id specified, go to insert */
            if (isset($p['id']) && $p['id'] != "") {
                $newp['id'] = $p['id'];
            } else {
                $newp['id'] = "";
            }

            $newps = json_encode($newp);

            $pobject = Array(
                'id' => $newp['id'],
                'value' => $newps,
                'page_id' => $p['page']
            );

            if (($newp['id'] != "") && (in_array($newp['id'], $existing_ids))) {
                array_push($to_update, $pobject);
            } else {
                array_push($to_insert, $pobject);
            }
        }
        $this->db->trans_start();
        if (sizeOf($to_update) > 0) {
            $this->db->update_batch('portlet_configuration', $to_update, 'id');
        }
        if (sizeOf($to_insert) > 0) {
            $this->db->insert_batch('portlet_configuration', $to_insert);
        }
        $this->db->trans_complete();


        if ($this->db->trans_status() === TRUE) {
            $log .= sprintf('Updated %1$d records and inserted %2$d records', sizeOf($to_update), sizeOf($to_insert));
        } else {
            $log .= "Error!";
        }

        return $log;
        //print_r($to_update);
        //die();
    }

    public function updatePortlet($portlets)
    {
        /* Because of cross-database non-interoperability of "ON DUPLICATE UPDATE", had to first determine what to update and what to insert. */
        $log = "";
        $getids = function ($a) {
            if (isset($a['id']))
                return $a['id'];
            else
                return "";
        };


        /* First, query for existing items_meta */
        $ids = array_map($getids, $portlets);
        $this->db->select('id, portlet_id');
        $this->db->from('items_meta');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        $result_ids = $query->result_array();
        $existing_ids = array_map(function ($a) {
            return $a['id'];
        }, $result_ids);
        //$existing_items = array_map(function($a){return Array($a['id'] => $a['portlet_id']);},$result_ids);
        $existing_items = Array();
        foreach ($result_ids as $r) {
            $existing_items[$r['id']] = $r['portlet_id'];
        }

        $to_update_items = Array();
        $to_update_portlets = Array();
        $to_insert_items = Array();
        $to_insert_portlets = Array();
        //$portlets['asd'] = Array('id'=>'21');


        $this->db->trans_start();

        foreach ($portlets as $p) {
            if (!isset($p['id']))
                $p['id'] = "";
            //{"id":"2","key":"overall_elevated","slug":"programme","type":"scurve","col":1,"row":1,"size_x":6,"size_y":1}
            $newp = Array(
                'col' => $p['col'],
                'row' => $p['row'],
                'size_x' => $p['size_x'],
                'size_y' => $p['size_y']
            );

            if (isset($p['screenTablet'])) {
                $newp['screenTablet'] = json_encode($p['screenTablet']);
            }

            if (isset($p['screen1024'])) {
                $newp['screen1024'] = json_encode($p['screen1024']);
            }

            /* If no id specified, go to insert */
            //if (isset($p['id']) && $p['id'] != "") { $newp['id'] = $p['id']; }
            //else { $newp['id'] = ""; }
            $pobject = Array(
                'value' => json_encode($newp)
            );
            if (isset($existing_items[$p['id']])) {
                /* Push existing portlet for update */
                //print_r($existing_items[$p['id']]);
                //die();
                $pobject['id'] = $existing_items[$p['id']];
                array_push($to_update_portlets, $pobject);
            } else {
                /* Insert a new portlet. Cannot batch it because we need the returning ID */

                $this->db->insert("portlet_configuration", $pobject);
                $pobject['id'] = $this->db->insert_id();
            }


            $iobject = Array(
                'portlet_id' => $pobject['id'],
                //'title' => $p['title'],
                'meta_group_id' => $p['meta_group_id'],
                'type' => $p['type'],
                'meta_key' => $p['key']
            );

            if (isset($p['id']))
                $iobject['id'] = $p['id'];
            if (isset($p['title']))
                $iobject['title'] = $p['title'];

            //$newps = json_encode($newp);
            //var_dump($newps);
            /*
              $pobject = Array(
              'id' => $newp['id'],
              'value' => $newps,
              'page_id' => $p['page']
              );
             */
            if (isset($p['id']) && ($p['id'] != "") && (in_array($p['id'], $existing_ids))) {
                array_push($to_update_items, $iobject);
            } else {
                array_push($to_insert_items, $iobject);
            }
        }

        if (sizeOf($to_update_portlets > 0)) {
            //var_dump($to_update_portlets);
            $this->db->update_batch('portlet_configuration', $to_update_portlets, 'id');
        }
        if (sizeOf($to_update_items) > 0) {
            $this->db->update_batch('items_meta', $to_update_items, 'id');
        }
        if (sizeOf($to_insert_items) > 0) {
            $this->db->insert_batch('items_meta', $to_insert_items);
        }
        //var_dump($this->db->queries);
        $this->db->trans_complete();


        if ($this->db->trans_status() === TRUE) {
            $log .= sprintf('Updated %1$d records and inserted %2$d records', sizeOf($to_update_items), sizeOf($to_insert_items));
        } else {
            $log .= "Error!";
        }

        return $log;
        //print_r($to_update);
        //die();
    }

    //reference functions
    public function get_news1($slug = FALSE)
    {
        if ($slug === FALSE) {
            $query = $this->db->get('news');
            if ($query) return $query->result_array();
        }

        $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row_array();
    }

    public function set_news2()
    {
        $this->load->helper('url');

        $slug = url_title($this->input->post('title'), 'dash', TRUE);

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => $slug,
            'text' => $this->input->post('text')
        );

        return $this->db->insert('news', $data);
    }
//    Author:Sebin Thomas
//    Usage : Get Comments
//    Created:
    public function getComment()
    {
        $this->db->select('*');
        $this->db->from('prognosis');
        $this->db->order_by('timestamp', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    //    Author:Sebin Thomas
//    Usage : Get Comments
//    Created:
    public function removeComment($comment_id)
    {
        $this->db->where('id', $comment_id);
        $ret = $this->db->delete('prognosis');
        return $ret;
    }
//    Author:AncY Mathew
//    Usage : Baseline and forecast table data
//    Created: 29/04/2016
    public function getBaselineM($data_date)
    {
        $manufacture = array();
        /*$this->db->select('*');
        $this->db->from('tbl_manf_baseline_forecast');
        $this->db->where_in('DATA_DATE',$data_date);
        $this->db->order_by('TRAIN_NO');
        $query = $this->db->get();
        return $query->result_array();*/
        $sql = "select a.\"TRAIN_NO\",a.\"BASE_DATE\",COALESCE(a.\"FORE_DATE\",'ROLLED_OUT') as fore_cast ,a.\"REV_INT\",b.\"DATA_DATE\",b.\"CAR1_NO\",b.\"CAR1_PERC\",b.\"CAR2_NO\",b.\"CAR2_PERC\",b.\"CAR3_NO\",b.\"CAR3_PERC\",b.\"CAR4_NO\",b.\"CAR4_PERC\",b.\"CAR1_ROLL_OUT\",b.\"CAR2_ROLL_OUT\",b.\"CAR3_ROLL_OUT\",b.\"CAR4_ROLL_OUT\" from \"tbl_manf_baseline_forecast\" a LEFT OUTER JOIN \"tbl_puzhen_manufacture\" b on a.\"TRAIN_NO\" = b.\"TRAIN_NO\" and a.\"DATA_DATE\" = b.\"DATA_DATE\"  where a.\"DATA_DATE\" = '$data_date' order by a.\"TRAIN_NO\"";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $key => $val) {
            $manufacture[$i]["TRAIN_NO"] = $val['TRAIN_NO'];
            $manufacture[$i]["BASE_DATE"] = $val['BASE_DATE'];
            $manufacture[$i]["FORE_DATE"] = $val['fore_cast'];
            $manufacture[$i]["REV_INT"] = $val['REV_INT'];
            $manufacture[$i]["CAR1_PERC"] = $val['CAR1_PERC'];
            $manufacture[$i]["CAR2_PERC"] = $val['CAR2_PERC'];
            $manufacture[$i]["CAR3_PERC"] = $val['CAR3_PERC'];
            $manufacture[$i]["CAR4_PERC"] = $val['CAR4_PERC'];
            $i++;
        }
        return $manufacture;
    }
//    Author: Ancy Mathew, Sebin Thomas
//    Usage : Get Train Data [manufacturing,assembly,subd,kjd].
//    Created: 04/05/2016
    public function getTrainData($data_date)
    {
        $rel = array(
            "manufacturing" => array(),
            "assembly" => array(),
            "subd" => array(),
            "kjd" => array(),
            "fore_date" => array()

        );
        /* $this->db->select('TRAIN_NO,CAR1_NO,CAR1_PERC,CAR2_NO,CAR2_PERC,CAR3_NO,CAR3_PERC,CAR4_NO,CAR4_PERC,CAR1_ROLL_OUT,CAR2_ROLL_OUT,CAR3_ROLL_OUT,CAR4_ROLL_OUT');
         $this->db->from('tbl_puzhen_manufacture');
         $this->db->where_in('DATA_DATE',$data_date);
         $this->db->order_by('TRAIN_NO');*/
        $sql = "select a.\"TRAIN_NO\",a.\"CAR1_NO\",a.\"CAR1_PERC\",a.\"CAR2_NO\",a.\"CAR2_PERC\",a.\"CAR3_NO\",a.\"CAR3_PERC\",a.\"CAR4_NO\",a.\"CAR4_PERC\",a.\"CAR1_ROLL_OUT\",a.\"CAR2_ROLL_OUT\",a.\"CAR3_ROLL_OUT\",a.\"CAR4_ROLL_OUT\",COALESCE(b.\"FORE_DATE\",'ROLLED_OUT') as fore_cast from \"tbl_puzhen_manufacture\" a LEFT OUTER JOIN \"tbl_manf_baseline_forecast\" b on a.\"TRAIN_NO\" = b.\"TRAIN_NO\" and  a.\"DATA_DATE\" = b.\"DATA_DATE\" where a.\"DATA_DATE\" = '$data_date' order by a.\"TRAIN_NO\"";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        /*  $query = $this->db->get();
          $result=$query->result_array();*/

        /*$this->db->select('TRAIN_NO,CAR1_NO,CAR1_PERC,CAR2_NO,CAR2_PERC,CAR3_NO,CAR3_PERC,CAR4_NO,CAR4_PERC,CAR1_ARRIVED,CAR2_ARRIVED,CAR3_ARRIVED,CAR4_ARRIVED');
        $this->db->from('tbl_SMH_Assmbly_Progress');
        $this->db->where_in("DATA_DATE",$data_date);
        $query = $this->db->get();*/
        $sql = "select a.\"TRAIN_NO\",a.\"CAR1_NO\",a.\"CAR1_PERC\",a.\"CAR2_NO\",a.\"CAR2_PERC\",a.\"CAR3_NO\",a.\"CAR3_PERC\",a.\"CAR4_NO\",a.\"CAR4_PERC\",a.\"CAR1_ARRIVED\",a.\"CAR2_ARRIVED\",a.\"CAR3_ARRIVED\",a.\"CAR4_ARRIVED\",COALESCE(b.\"FORE_DATE\",'ROLLED_OUT') as fore_cast from \"tbl_SMH_Assmbly_Progress\" a LEFT OUTER JOIN \"tbl_assembly_baseline_forecast\" b on a.\"TRAIN_NO\" = b.\"TRAIN_NO\" and a.\"DATA_DATE\" = b.\"DATA_DATE\"  where a.\"DATA_DATE\" = '$data_date' order by a.\"TRAIN_NO\"";
        // echo 'take';
        //echo $sql;
        $query = $this->db->query($sql);
        /* $result = $query->result_array();*/
        $result1 = $query->result_array();
//        $this->db->select('TRAIN_FROM,TRAIN_TO,CAR1,CAR2,CAR3,CAR4,DATE_DELIVERED,COMMENTS,H_MANUFACTURED,H_ASSEMBLY');
//        $this->db->from('tbl_SUBD_DT_CS');
//        $this->db->where_in("DATA_DATE",$data_date);
//        $this->db->order_by('TRAIN_FROM');
//        $query = $this->db->get();
//        $subd=$query->result_array();

        $sql = "Select \"TRAIN_FROM\",\"TRAIN_TO\",COALESCE(\"CAR1\",'1000') AS CAR1,\"TRAIN_PUZHEN_CAR1\",\"TRAIN_SMH_CAR1\",COALESCE(\"CAR2\",'1001') AS CAR2,\"TRAIN_PUZHEN_CAR2\",\"TRAIN_SMH_CAR2\",COALESCE(\"CAR3\",'1002') AS CAR3,\"TRAIN_PUZHEN_CAR3\",\"TRAIN_SMH_CAR3\",COALESCE(\"CAR4\",'1003') AS CAR4,\"TRAIN_PUZHEN_CAR4\",\"TRAIN_SMH_CAR4\",\"DATE_DELIVERED\",\"COMMENTS\" from \"tbl_SUBD_DT_CS\" WHERE \"DATA_DATE\"='$data_date' order by \"TRAIN_FROM\"";
        $query = $this->db->query($sql);
        $subd = $query->result_array();

        $this->db->select('TRAIN_NUMBER,CAR1,CAR2,CAR3,CAR4,DATE_DELIVERED,COMMENTS');
        $this->db->from('tbl_KJD_DT_CS');
        $this->db->where_in("DATA_DATE", $data_date);
        //$this->db->order_by('KJD_MASTER_ID');
        $query = $this->db->get();
        //$sql = "Select \"TRAIN_NUMBER\",\"TRAIN_TO\",COALESCE(\"CAR1\",'1000') AS CAR1,COALESCE(\"CAR2\",'1001') AS CAR2,COALESCE(\"CAR3\",'1002') AS CAR3,COALESCE(\"CAR4\",'1003') AS CAR4,\"DATE_DELIVERED\",\"COMMENTS\",\"H_MANUFACTURED\",\"H_ASSEMBLY\" from \"tbl_KJD_DT_CS\" WHERE \"DATA_DATE\"='$data_date' order by \"TRAIN_FROM\"";
        //echo $sql;
        //$query = $this->db->query($sql);
        $kjd = $query->result_array();
        /*$sql = "select \"TRAIN_NO\",\"FORE_DATE\" from tbl_assembly_baseline_forecast where \"DATA_DATE\"='$data_date' order by \"TRAIN_NO\"";
        $query = $this->db->query($sql);
        $final = $query->result_array();
        $i=0;
        foreach($final as $key=> $val1){
            $rel[$i]["fore_date"] =array("FOR_TRAIN"=>$val1['TRAIN_NO'],"FORE_DATE"=>$val1['FORE_DATE']);
            $i++;
            $i++;
        }*/
        foreach ($result as $key => $val) {
            $rel["manufacturing"]["Train " . $val['TRAIN_NO']] = array(
                "cars" => array(
                    $val['CAR1_NO'] => array(
                        "progress" => ($val['CAR1_PERC'] == null) ? 0 : $val['CAR1_PERC'],
                        "rollout" => ($val['CAR1_ROLL_OUT'] == null) ? '' : $val['CAR1_ROLL_OUT'],
                        "foreDate" => ($val['fore_cast'] == null) ? '' : $val['fore_cast']
                    ),
                    $val['CAR2_NO'] => array(
                        "progress" => ($val['CAR2_PERC'] == null) ? 0 : $val['CAR2_PERC'],
                        "rollout" => ($val['CAR2_ROLL_OUT'] == null) ? '' : $val['CAR2_ROLL_OUT'],
                        "foreDate" => ($val['fore_cast'] == null) ? '' : $val['fore_cast']
                    ),
                    $val['CAR3_NO'] => array(
                        "progress" => ($val['CAR3_PERC'] == null) ? 0 : $val['CAR3_PERC'],
                        "rollout" => ($val['CAR3_ROLL_OUT'] == null) ? '' : $val['CAR3_ROLL_OUT'],
                        "foreDate" => ($val['fore_cast'] == null) ? '' : $val['fore_cast']
                    ),
                    $val['CAR4_NO'] => array(
                        "progress" => ($val['CAR4_PERC'] == null) ? 0 : $val['CAR4_PERC'],
                        "rollout" => ($val['CAR4_ROLL_OUT'] == null) ? '' : $val['CAR4_ROLL_OUT'],
                        "foreDate" => ($val['fore_cast'] == null) ? '' : $val['fore_cast']
                    )
                )
            );
        }
        foreach ($result1 as $key => $val1) {
            $rel["assembly"]["Train " . $val1['TRAIN_NO']] = array(
                "delivery" => "",
                "cars" => array(
                    $val1['CAR1_NO'] => array(
                        "progress" => ($val1['CAR1_PERC'] == null) ? 0 : $val1['CAR1_PERC'],
                        "rollout" => "",
                        "arrived" => ($val1['CAR1_ARRIVED'] == null) ? '' : $val1['CAR1_ARRIVED'],
                        "foreDateA" => ($val1['fore_cast'] == null) ? '' : $val1['fore_cast']
                    ),
                    $val1['CAR2_NO'] => array(
                        "progress" => ($val1['CAR2_PERC'] == null) ? 0 : $val1['CAR2_PERC'],
                        "rollout" => "",
                        "arrived" => ($val1['CAR2_ARRIVED'] == null) ? '' : $val1['CAR2_ARRIVED'],
                        "foreDateA" => ($val1['fore_cast'] == null) ? '' : $val1['fore_cast']
                    ),
                    $val1['CAR3_NO'] => array(
                        "progress" => ($val1['CAR3_PERC'] == null) ? 0 : $val1['CAR3_PERC'],
                        "rollout" => "",
                        "arrived" => ($val1['CAR3_ARRIVED'] == null) ? '' : $val1['CAR3_ARRIVED'],
                        "foreDateA" => ($val1['fore_cast'] == null) ? '' : $val1['fore_cast']
                    ),
                    $val1['CAR4_NO'] => array(
                        "progress" => ($val1['CAR4_PERC'] == null) ? 0 : $val1['CAR4_PERC'],
                        "rollout" => "",
                        "arrived" => ($val1['CAR4_ARRIVED'] == null) ? '' : $val1['CAR4_ARRIVED'],
                        "foreDateA" => ($val1['fore_cast'] == null) ? '' : $val1['fore_cast']
                    )

                )
            );
        }

        foreach ($subd as $key => $subd_val) {
            if ($subd_val["TRAIN_FROM"] != '' && $subd_val["TRAIN_TO"] != '') {
                $rel["subd"]["Train " . $subd_val['TRAIN_FROM'] . " - Train " . $subd_val['TRAIN_TO']] = array(
                    "delivery" => $subd_val["DATE_DELIVERED"],
                    "testingcompleted" => $subd_val['COMMENTS'],
                    "cars" => array(
                        " " . $subd_val['car1'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => "",
                                "assembly" => "",
                            )
                        ),
                        " " . $subd_val['car2'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => "",
                                "assembly" => "",
                            )
                        ),
                        " " . $subd_val['car3'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => "",
                                "assembly" => "",
                            )
                        ),
                        " " . $subd_val['car4'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => "",
                                "assembly" => "",
                            )
                        )

                    )
                );
            } else {
                $rel["subd"]["Train " . $subd_val['TRAIN_FROM']] = array(
                    "delivery" => $subd_val["DATE_DELIVERED"],
                    "testingcompleted" => ($subd_val['COMMENTS'] == null) ? '' : $subd_val['COMMENTS'],
                    "cars" => array(
                        " " . $subd_val['car1'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => ($subd_val['TRAIN_PUZHEN_CAR1'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_PUZHEN_CAR1'],
                                "assembly" => ($subd_val['TRAIN_SMH_CAR1'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_SMH_CAR1'],
                                "car" => $subd_val['car1']
                            )
                        ),
                        " " . $subd_val['car2'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => ($subd_val['TRAIN_PUZHEN_CAR2'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_PUZHEN_CAR2'],
                                "assembly" => ($subd_val['TRAIN_SMH_CAR2'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_SMH_CAR2'],
                                "car" => $subd_val['car2']
                            )
                        ),
                        " " . $subd_val['car3'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => ($subd_val['TRAIN_PUZHEN_CAR3'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_PUZHEN_CAR3'],
                                "assembly" => ($subd_val['TRAIN_SMH_CAR3'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_SMH_CAR3'],
                                "car" => $subd_val['car3']
                            )
                        ),
                        " " . $subd_val['car4'] => array(
                            "progress" => "",
                            "rollout" => "",
                            "arrived" => "",
                            "history" => array(
                                "manufacturing" => ($subd_val['TRAIN_PUZHEN_CAR3'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_PUZHEN_CAR3'],
                                "assembly" => ($subd_val['TRAIN_SMH_CAR3'] == null) ? 'N/A' : "Train " . $subd_val['TRAIN_SMH_CAR3'],
                                "car" => $subd_val['car4']
                            )
                        )

                    )
                );
            }
        }
        foreach ($kjd as $key => $kjd_val) {
//        if($kjd_val["TRAIN_FROM"]!='' && $kjd_val["TRAIN_TO"]!=''){
//            $rel["kjd"]["Train " . $kjd_val['TRAIN_FROM']." - Train ". $kjd_val['TRAIN_TO']] = array(
//                "delivery"=>$kjd_val["DATE_DELIVERED"],
//                "testingcompleted"=>$kjd_val['COMMENTS'],
//                "cars" => array(
//                    " ".$kjd_val['car1'] => array(
//                        "progress" => "",
//                        "rollout" => "",
//                        "arrived" => ""
//                    ),
//                    " ".$kjd_val['car2'] => array(
//                        "progress" => "",
//                        "rollout" => "",
//                        "arrived" => ""
//                    ),
//                    " ".$kjd_val['car3'] => array(
//                        "progress" => "",
//                        "rollout" => "",
//                        "arrived" => ""
//                    ),
//                    " ".$kjd_val['car4'] => array(
//                        "progress" => "",
//                        "rollout" => "",
//                        "arrived" => ""
//                    )
//
//                )
//            );
//        }else {
            $rel["kjd"]["Train " . $kjd_val['TRAIN_NUMBER']] = array(
                "delivery" => $kjd_val["DATE_DELIVERED"],
                "testingcompleted" => $kjd_val['COMMENTS'],
                "cars" => array(
                    " " . $kjd_val['CAR1'] => array(
                        "progress" => "",
                        "rollout" => "",
                        "arrived" => ""
                    ),
                    " " . $kjd_val['CAR2'] => array(
                        "progress" => "",
                        "rollout" => "",
                        "arrived" => ""
                    ),
                    " " . $kjd_val['CAR3'] => array(
                        "progress" => "",
                        "rollout" => "",
                        "arrived" => ""
                    ),
                    " " . $kjd_val['CAR4'] => array(
                        "progress" => "",
                        "rollout" => "",
                        "arrived" => ""
                    )

                )
            );
//        }
        }
        return $rel;
    }

    public function getOverallProgress($data_date)
    {
        $overall = array();
        $this->db->select('TRAIN_NO,OPEN_JOBS,CLOSED_JOBS');
        $this->db->from('tbl_overall_progress');
        $this->db->where_in("DATA_DATE", $data_date);
        $this->db->order_by('TRAIN_NO');
        $query = $this->db->get();
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $key => $val) {
            $overall[$i]["OPEN_JOBS"] = $val['OPEN_JOBS'];
            $overall[$i]["CLOSED_JOBS"] = $val['CLOSED_JOBS'];
            $overall[$i]["TRAIN_NO"] = $val['TRAIN_NO'];
            $i++;
        }
        return $overall;
//        return
    }

    public function getOutStandingProgress($data_date)
    {
        $outstand = array();
        $sql = "select \"OUT_DATE\",\"JOBS_DONE\",\"TARGET\" FROM tbl_outstanding_item_progress WHERE \"DATA_DATE\"='$data_date' ORDER BY TO_DATE(\"OUT_DATE\", 'DD-Mon-YYYY')";
        /*        $this->db->select('OUT_DATE,JOBS_DONE,TARGET');
                $this->db->from('tbl_outstanding_item_progress');
                $this->db->where_in("DATA_DATE",$data_date);
                $this->db->order_by((to_date("OUT_DATE", 'DD-Mon-YYYY')));
                $query = $this->db->get();*/
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $key => $val) {
            $outstand[$i]["JOBS_DONE"] = $val['JOBS_DONE'];
            $outstand[$i]["TARGET"] = $val['TARGET'];
            $outstand[$i]["OUT_DATE"] = $val['OUT_DATE'];
            $i++;
        }
        return $outstand;
//        return
    }
//    Author:Sebin Thomas
//    Usage : Store Comments
//    Created:
    public function setComment($data)
    {
        $this->db->insert('prognosis', $data);
        return $this->db->affected_rows();

    }
//    Author:AncY Mathew
//    Usage : Baseline and forecast table data for assembly progress
//    Created: 29/04/2016
    public function getBaselineAssembly($data_date)
    {
        $manufacture = array();
        $sql = "select a.\"TRAIN_NO\",a.\"BASE_DATE\",COALESCE(a.\"FORE_DATE\",'ROLLED_OUT') as fore_cast ,a.\"REV_INT\",b.\"DATA_DATE\",b.\"CAR1_NO\",b.\"CAR2_NO\",b.\"CAR3_NO\",b.\"CAR4_NO\",b.\"CAR4_PERC\" , b.\"CAR3_PERC\" ,b.\"CAR2_PERC\" , b.\"CAR1_PERC\" from \"tbl_assembly_baseline_forecast\" a LEFT OUTER JOIN \"tbl_SMH_Assmbly_Progress\" b on a.\"TRAIN_NO\" = b.\"TRAIN_NO\" and a.\"DATA_DATE\" = b.\"DATA_DATE\"  where a.\"DATA_DATE\" = '$data_date' order by a.\"TRAIN_NO\"";
        //echo $sql;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $key => $val) {
            $manufacture[$i]["TRAIN_NO"] = $val['TRAIN_NO'];
            $manufacture[$i]["BASE_DATE"] = $val['BASE_DATE'];
            $manufacture[$i]["FORE_DATE"] = $val['fore_cast'];
            $manufacture[$i]["REV_INT"] = $val['REV_INT'];
            $manufacture[$i]["CAR1_PERC"] = $val['CAR1_PERC'];
            $manufacture[$i]["CAR2_PERC"] = $val['CAR2_PERC'];
            $manufacture[$i]["CAR3_PERC"] = $val['CAR3_PERC'];
            $manufacture[$i]["CAR4_PERC"] = $val['CAR4_PERC'];
            $i++;
        }
        return $manufacture;
        /* $this->db->select('*');
         $this->db->from('tbl_assembly_baseline_forecast');
         $this->db->where_in("DATA_DATE",$data_date);
         $this->db->order_by('TRAIN_NO');
         $query = $this->db->get();
         return $query->result_array();*/
    }

//    Author:Agaile Victor
//    Usage : Testing reports of 58 trains
//    Created: 09/05/2016
    public function GetTestingData($data_date)
    {
        //$sql = "Select \"TRAIN_NO\",\"Static_Total\",\"Static_Pass\",\"Static_Incomplete\",\"Static_Fail\",\"Dynamic_Total\",\"Dynamic_Pass\",\"Dynamic_Incomplete\",\"Dynamic_Fail\",\"SAT_Total\",\"SAT_Incomplete\",\"SAT_Fail\",\"IT_Incomplete\",\"IT_Total\",\"SAT_Pass\",\"IT_Fail\",\"SIT_Pass\",\"SIT_Total\",\"SIT_Incomplete\",\"SIT_Fail\",\"IT_Pass\" from \"tbl_testing_completion\" WHERE \"DATA_DATE\"='$data_date' order by to_number(split_part(\"TRAIN_NO\", ' ', 2), '99G999D9S')";
        $sql = "Select 'Train ' ||\"TRAIN_NO\" as TRAIN_NO,\"Static_Total\",\"Static_Pass\",\"Static_Incomplete\",\"Static_Fail\",\"Dynamic_Total\",\"Dynamic_Pass\",\"Dynamic_Incomplete\",\"Dynamic_Fail\",\"SAT_Total\",\"SAT_Pass\",\"SAT_Incomplete\",\"SAT_Fail\",\"IT_Total\",\"IT_Pass\",\"IT_Incomplete\",\"IT_Fail\",\"SIT_Total\",\"SIT_Pass\",\"SIT_Incomplete\",\"SIT_Fail\" from \"tbl_testing_completion\" WHERE \"DATA_DATE\"='$data_date' order by \"TRAIN_NO\"";
        //echo $sql;
        $query = $this->db->query($sql);
        $final = $query->result_array();
        return $final;
    }

    public function getFullyCompletedTrain($data_date)
    {
        $outFully = array();
        //$sql = "select \"TRAIN_NO\"from tbl_testing_completion where \"Static_Total\"=\"Static_Pass\" and \"Dynamic_Total\"=\"Dynamic_Pass\" and \"SAT_Total\"=\"SAT_Pass\" and \"SIT_Total\" = \"SIT_Pass\" and \"IT_Total\"=\"IT_Pass\" and \"TRAIN_NO\" in(select \"TRAIN_NO\" from tbl_overall_progress  where \"DATA_DATE\" = '$data_date' and \"OPEN_JOBS\" =0 and \"CLOSED_JOBS\" !=0 order by \"TRAIN_NO\")and \"DATA_DATE\" = '$data_date'";
        $sql = "select \"TRAIN_NO\" from tbl_testing_completion where \"Static_Total\"=\"Static_Pass\" and COALESCE(\"Static_Incomplete\",'0')='0' and COALESCE(\"Static_Fail\",'0') = '0' and \"Dynamic_Total\"=\"Dynamic_Pass\" and COALESCE(\"Dynamic_Incomplete\",'0')='0' and COALESCE(\"Dynamic_Fail\",'0')='0' and \"SAT_Total\"=\"SAT_Pass\" and COALESCE(\"SAT_Incomplete\",'0')='0' and COALESCE(\"SAT_Fail\",'0')='0' and \"SIT_Total\" = \"SIT_Pass\" and COALESCE(\"SIT_Incomplete\",'0')='0' and COALESCE(\"SIT_Fail\",'0')='0'  and \"IT_Total\"=\"IT_Pass\" and COALESCE(\"IT_Incomplete\",'0')='0' and COALESCE(\"IT_Fail\",'0')='0' and \"TRAIN_NO\" in(select \"TRAIN_NO\" from tbl_overall_progress  where \"DATA_DATE\" = '$data_date' and \"CLOSED_JOBS\" !=0 and \"OPEN_JOBS\" =0 order by \"TRAIN_NO\")and \"DATA_DATE\" = '$data_date' order by \"TRAIN_NO\"";
        $query = $this->db->query($sql);
        $final = $query->result_array();
        $i = 0;
        foreach ($final as $key => $val) {
            $outFully[$i]["TRAIN_NO"] = $val['TRAIN_NO'];
            $i++;
        }
        $trainData = "select \"TRAIN_NO\",\"OPEN_JOBS\",\"CLOSED_JOBS\" from tbl_overall_progress  where \"DATA_DATE\" = '$data_date' and \"CLOSED_JOBS\" !=0 and \"OPEN_JOBS\" =0 order by \"TRAIN_NO\"";
        $query = $this->db->query($trainData);
        $finalData = $query->result_array();
        $j = 0;
        foreach ($finalData as $key => $val) {
            $outFully[$j]["TRAIN_NUMBER"] = $val['TRAIN_NO'];
            $outFully[$j]["OPEN_JOBS"] = $val['OPEN_JOBS'];
            $outFully[$j]["CLOSED_JOBS"] = $val['CLOSED_JOBS'];
            $j++;
        }
        return $outFully;
    }
    //Author : Ancy Mathew
    //Usage : Slug name using slug id.
    //Created on : 17/06/2016
    public function get_slug($slug_id)
    {
        $sql = "select \"slug\" from \"items\" where \"id\" = '$slug_id'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    //Author : Sebin Thomas
    //Usage : Page Name using slug id.
    //Created on : 15/07/2016
    /**
     * @param $slug_id
     * @return mixed
     */
    public function get_page($slug_id)
    {
        $sql = "select \"page\" from \"pages\" where \"item_id\" = '$slug_id'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function get_pscada_status(){
        $pscada_status = array();
        $sql = 'SELECT "PSCADA_PAT","PSCADA_SAT","STATION_CODE" FROM  "tbl_testing_and_commission" where "DATA_DATE" IN (SELECT MAX("DATA_DATE") FROM "tbl_testing_and_commission") ORDER BY "STATION_CODE"';
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach ($result as $val) {
            $status = ($val['PSCADA_SAT'] == 1 && $val['PSCADA_PAT'] == 1) ? 'Completed' : (($val['PSCADA_SAT'] == null && $val['PSCADA_PAT'] == null) ? 'N/A' : 'In Progress');
            $pscada_status[$val['STATION_CODE']] = array($status);
        }
        return $pscada_status;
    }
    public function get_station_status()
    {
        $station_status = array();
        $sql = 'SELECT "STATION_CODE","STATION_STATUS","LINE_STATUS" FROM "tbl_psds_station_status" WHERE "DATA_DATE" = (SELECT MAX("DATA_DATE") FROM "tbl_psds_station_status") ORDER BY "STATION_CODE"';
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if (sizeof($result) > 0) {
            foreach ($result as $val) {
                if (strtolower(str_replace(' ', '', $val['STATION_CODE']))=="stn14phase1"){
                    $station_status['station_status']["STN14P1"] = $val['STATION_STATUS'];
                    $station_status['line_status']["STN14P1"] = $val['LINE_STATUS'];
                }else if(strtolower(str_replace(' ', '', $val['STATION_CODE']))=="stn14phase2"){
                    $station_status['station_status']["STN14P2"] = $val['STATION_STATUS'];
                    $station_status['line_status']["STN14P2"] = $val['LINE_STATUS'];
                }else {
                    if (strlen(str_replace(' ', '', $val['STATION_CODE'])) > 5) {
                        $station_status['station_status'][strtoupper(substr(str_replace(' ', '', $val['STATION_CODE']), 0, 5))] = $val['STATION_STATUS'];
                        $station_status['line_status'][strtoupper(substr(str_replace(' ', '', $val['STATION_CODE']), 0, 5))] = $val['LINE_STATUS'];
                    } else {
                        $station_status['station_status'][strtoupper(str_replace(' ', '', $val['STATION_CODE']))] = $val['STATION_STATUS'];
                        $station_status['line_status'][strtoupper(str_replace(' ', '', $val['STATION_CODE']))] = $val['LINE_STATUS'];
                    }
                }
            }
        } else {
            $station_status['station_status']['SUBD'] = 0;
            $station_status['line_status']['SUBD'] = 0;
            $station_status['station_status']['KAJD'] = 0;
            $station_status['line_status']['KAJD'] = 0;
            $station_status['station_status']['KWDE2'] = 0;
            $station_status['line_status']['KWDE2'] = 0;
            $station_status['station_status']['SEMAN'] = 0;
            $station_status['line_status']['SEMAN'] = 0;
            for ($i = 0; $i < 36; $i++) {
                if ($i < 10) {
                    $station_status['station_status']['STN0' . $i] = 0;
                    $station_status['line_status']['STN0' . $i] = 0;
                } else {
                    $station_status['station_status']['STN' . $i] = 0;
                    $station_status['line_status']['STN' . $i] = 0;
                }

            }
        }
        return $station_status;
    }

    //PS&DS CODE Starts Here//

    //Author : Sebin Thomas, Ancy Mathew
    //Usage : Retrives PSDS testing and Commission reports based on and latest date
    //Created on : 20/06/2016
    /**
     * @param $ring_no
     * @param bool $date
     * @return array
     */
    public function get_psds_test_comm($ring_no, $date = FALSE)
    {
        $i = 0;
        $a = array();
        $test_completion = array(
            "value" => array()
        );
        if ($date) { //If date is selected
            $sql = "SELECT * FROM \"tbl_testing_and_commission\" where \"RING_NUMBER\"='$ring_no' and \"DATA_DATE\"='$date'";
        } else {
            $sql = 'SELECT * FROM "tbl_testing_and_commission" where "RING_NUMBER"=' . $ring_no . ' and "DATA_DATE"=(SELECT MAX("DATA_DATE") FROM "tbl_testing_and_commission")';
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach ($result as $val) {
            $a[$i] = array(
                "ring_number" => $val['RING_NUMBER'],
                "station_name" => $val['STATION_NAME'],
                "station_code" => $val['STATION_CODE'],
                "install_status" => ($val['INSTALL_STATUS'] == 1) ? 'Completed' : (($val['INSTALL_STATUS'] == 2) ? 'In Progress' : (($val['INSTALL_STATUS'] == 3) ? 'Pending' : (($val['INSTALL_STATUS'] == -1) ? 'N/A' : (($val['INSTALL_STATUS'] == 4) ? 'Handed Over' : '-')))),
                "33kv_pat" => ($val['33KV_PAT'] == 1) ? 'Completed' : ($val['33KV_PAT'] == 2 ? 'In Progress' : ($val['33KV_PAT'] == 3 ? 'Pending' : ($val['33KV_PAT'] == -1 ? 'N/A' : ($val['33KV_PAT'] == 4 ? 'Handed Over' : '-')))),
                "750v_pat" => ($val['750V_PAT'] == 1) ? 'Completed' : ($val['750V_PAT'] == 2 ? 'In Progress' : ($val['750V_PAT'] == 3 ? 'Pending' : ($val['750V_PAT'] == -1 ? 'N/A' : ($val['750V_PAT'] == 4 ? 'Handed Over' : '-')))),
                "pscada_pat" => ($val['PSCADA_PAT'] == 1) ? 'Completed' : ($val['PSCADA_PAT'] == 2 ? 'In Progress' : ($val['PSCADA_PAT'] == 3 ? 'Pending' : ($val['PSCADA_PAT'] == -1 ? 'N/A' : ($val['PSCADA_PAT'] == 4 ? 'Handed Over' : '-')))),
                "33kv_sat" => ($val['33KV_SAT'] == 1) ? 'Completed' : ($val['33KV_SAT'] == 2 ? 'In Progress' : ($val['33KV_SAT'] == 3 ? 'Pending' : ($val['33KV_SAT'] == -1 ? 'N/A' : ($val['33KV_SAT'] == 4 ? 'Handed Over' : '-')))),
                "750v_sat" => ($val['750V_SAT'] == 1) ? 'Completed' : ($val['750V_SAT'] == 2 ? 'In Progress' : ($val['750V_SAT'] == 3 ? 'Pending' : ($val['750V_SAT'] == -1 ? 'N/A' : ($val['750V_SAT'] == 4 ? 'Handed Over' : '-')))),
                "pscada_sat" => ($val['PSCADA_SAT'] == 1) ? 'Completed' : ($val['PSCADA_SAT'] == 2 ? 'In Progress' : ($val['PSCADA_SAT'] == 3 ? 'Pending' : ($val['PSCADA_SAT'] == -1 ? 'N/A' : ($val['PSCADA_SAT'] == 4 ? 'Handed Over' : '-')))),
                "33kv_forecast_date" => ($val['33KV_FORECAST_DATE'] == null || $val['33KV_FORECAST_DATE'] == "") ? ($val['33KV_PAT'] == -1 && $val['33KV_SAT'] == -1) ? 'N/A' : '-' : $val['33KV_FORECAST_DATE'],
                "750v_forecast_date" => ($val['750V_FORECAST_DATE'] == null || $val['750V_FORECAST_DATE'] == "") ? ($val['750V_PAT'] == -1 && $val['750V_SAT'] == -1) ? 'N/A' : '-' : $val['750V_FORECAST_DATE'],
                "pscada_forecast_date" => ($val['PSCADA_FORECAST_DATE'] == null || $val['PSCADA_FORECAST_DATE'] == "") ? ($val['PSCADA_PAT'] == -1 && $val['PSCADA_SAT'] == -1) ? 'N/A' : '-' : $val['PSCADA_FORECAST_DATE'],
                "33kv_actual_date" => ($val['33KV_ACTUAL_DATE'] == null || $val['33KV_ACTUAL_DATE'] == "") ? ($val['33KV_ACTUAL_STATUS'] == 1 ? 'Energized' : ($val['33KV_ACTUAL_STATUS'] == 2 ? 'Pending' : ($val['33KV_ACTUAL_STATUS'] == 3 ? 'N/A' : '-'))) : $val['33KV_ACTUAL_DATE'],
                "750v_actual_date" => ($val['750V_ACTUAL_DATE'] == null || $val['750V_ACTUAL_DATE'] == "") ? ($val['750V_ACTUAL_STATUS'] == 1 ? 'Energized' : ($val['750V_ACTUAL_STATUS'] == 2 ? 'Pending' : ($val['750V_ACTUAL_STATUS'] == 3 ? 'N/A' : '-'))) : $val['750V_ACTUAL_DATE'],
                "pscada_actual_date" => ($val['PSCADA_ACTUAL_DATE'] == null || $val['PSCADA_ACTUAL_DATE'] == "") ? ($val['PSCADA_ACTUAL_STATUS'] == 1 ? 'Energized' : ($val['PSCADA_ACTUAL_STATUS'] == 2 ? 'Pending' : ($val['PSCADA_ACTUAL_STATUS'] == 3 ? 'N/A' : '-'))) : $val['PSCADA_ACTUAL_DATE'],
                "ac_or_dc_one" => "33KV",
                "ac_or_dc_two" => "750V",
                "ac_or_dc_three" => (($val['PSCADA_PAT'] == null || $val['PSCADA_PAT'] == '') && ($val['PSCADA_SAT'] == null || $val['PSCADA_SAT'] == '') && ($val['PSCADA_FORECAST_DATE'] == null || $val['PSCADA_FORECAST_DATE'] == '') && ($val['PSCADA_ACTUAL_DATE'] == null || $val['PSCADA_ACTUAL_DATE'] == '') && ($val['PSCADA_ACTUAL_STATUS'] == null || $val['PSCADA_ACTUAL_STATUS'] == '')) ? 'PSCADA' : 'PSCADA'
            );
            $i++;
        }
        $test_completion["value"] = json_encode($a);
        return $test_completion;
    }
    //Author : Ancy Mathew
    //Modified by: Sebin Thomas
    //Usage : Retrives PSDS TRIP Cable Status reports based on and latest date
    //Created on : 02/08/2016
    /**
     * @param $ring_no
     * @param bool $date
     * @return array
     */
    public function get_psds_trip_status($ring_no, $date = FALSE)
    {
        $i = 0;
        $a = array();
        $cable_status = array(
            "value" => array()
        );
        if ($date) { //If date is selected
            $sql = "SELECT DISTINCT tts.*  FROM \"tbl_trip_status\" AS tts, \"tbl_testing_and_commission\" AS ttc WHERE ((tts.\"STATION_FROM\"= ttc.\"STATION_CODE\") OR (tts.\"STATION_TO\"= ttc.\"STATION_CODE\"))  AND ttc.\"RING_NUMBER\"='$ring_no'  AND tts.\"DATA_DATE\"='$date'";
//            $sql = 'select * from "tbl_trip_status" where "RING_NUMBER"='.$ring_no.' and "DATA_DATE"='.$date.'';
        } else {
            $sql = 'SELECT DISTINCT tts.*  FROM "tbl_trip_status" AS tts, "tbl_testing_and_commission" AS ttc WHERE ((tts."STATION_FROM"= ttc."STATION_CODE") OR (tts."STATION_TO"= ttc."STATION_CODE"))  AND ttc."RING_NUMBER"=' . $ring_no . '  AND tts."DATA_DATE"=(SELECT MAX("DATA_DATE") FROM "tbl_trip_status")';
        }
        $query = $this->db->query($sql);
        $result1 = $query->result_array();
        foreach ($result1 as $val) {
            $a[$i] = array(
                "station_from" => $val['STATION_FROM'],
                "station_to" => $val['STATION_TO'],
                "33kv_laying_status" => ($val['33KV_LAYING'] == 1) ? 'Completed' : ($val['33KV_LAYING'] == 2 ? 'In Progress' : ($val['33KV_LAYING'] == 3 ? 'Pending' : ($val['33KV_LAYING'] == -1 ? 'N/A' : '-'))),
                "750v_laying_status" => ($val['750V_LAYING'] == 1) ? 'Completed' : ($val['750V_LAYING'] == 2 ? 'In Progress' : ($val['750V_LAYING'] == 3 ? 'Pending' : ($val['750V_LAYING'] == -1 ? 'N/A' : '-'))),
                "33kv_termination_status" => ($val['33KV_TERMINATION'] == 1) ? 'Completed' : ($val['33KV_TERMINATION'] == 2 ? 'In Progress' : ($val['33KV_TERMINATION'] == 3 ? 'Pending' : ($val['33KV_TERMINATION'] == -1 ? 'N/A' : '-'))),
                "750v_termination_status" => ($val['750V_TERMINATION'] == 1) ? 'Completed' : ($val['750V_TERMINATION'] == 2 ? 'In Progress' : ($val['750V_TERMINATION'] == 3 ? 'Pending' : ($val['750V_TERMINATION'] == -1 ? 'N/A' : '-'))),
                "33kv_pat" => ($val['33KV_PAT'] == 1) ? 'Completed' : ($val['33KV_PAT'] == 2 ? 'In Progress' : ($val['33KV_PAT'] == 3 ? 'Pending' : ($val['33KV_PAT'] == -1 ? 'N/A' : '-'))),
                "750v_pat" => ($val['750V_PAT'] == 1) ? 'Completed' : ($val['750V_PAT'] == 2 ? 'In Progress' : ($val['750V_PAT'] == 3 ? 'Pending' : ($val['750V_PAT'] == -1 ? 'N/A' : '-'))),
                "33kv_sat" => ($val['33KV_SAT'] == 1) ? 'Completed' : ($val['33KV_SAT'] == 2 ? 'In Progress' : ($val['33KV_SAT'] == 3 ? 'Pending' : ($val['33KV_SAT'] == -1 ? 'N/A' : '-'))),
                "750v_sat" => ($val['750V_SAT'] == 1) ? 'Completed' : ($val['750V_SAT'] == 2 ? 'In Progress' : ($val['750V_SAT'] == 3 ? 'Pending' : ($val['750V_SAT'] == -1 ? 'N/A' : '-'))),
                "33kv_energized_date" => ($val['33KV_ENERGIZED_DATE'] == null || $val['33KV_ENERGIZED_DATE'] == "") ? ($val['33KV_ENERGIZED_STATUS'] == 1 ? 'Energized' : ($val['33KV_ENERGIZED_STATUS'] == 2 ? 'Pending' : ($val['33KV_ENERGIZED_STATUS'] == 3 ? 'N/A' : '-'))) : $val['33KV_ENERGIZED_DATE'],
                "750v_energized_date" => ($val['750V_ENERGIZED_DATE'] == null || $val['750V_ENERGIZED_DATE'] == "") ? ($val['750V_ENERGIZED_STATUS'] == 1 ? 'Energized' : ($val['750V_ENERGIZED_STATUS'] == 2 ? 'Pending' : ($val['750V_ENERGIZED_STATUS'] == 3 ? 'N/A' : '-'))) : $val['750V_ENERGIZED_DATE']
            );
            $i++;
        }
        $cable_status['value'] = json_encode($a);
        return $cable_status;
    }
    //coded by :ANCY MATHEW
    //used to get comments in PS AND DS
    //Created on : 22/06/2016
    /**
     * @return array
     */
    public function get_comments_ps()
    {
        $comments_ps = array();
        $sql = "SELECT \"MESSAGE_ID\", \"MESSAGE\", to_char(\"TIMESTAMP\", 'DD Mon YYYY') as timestamp,to_char(\"DATE_SELECTED\", 'DD Mon YYYY') as date,\"RING_NUMBER\" FROM \"tbl_psds_comment\" ORDER BY \"TIMESTAMP\" desc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $val) {
            $comments_ps[$i]["message_id"] = $val['MESSAGE_ID'];
            $comments_ps[$i]["message"] = $val['MESSAGE'];
            $comments_ps[$i]["timestamp"] = $val['date'];
            $comments_ps[$i]["ring"] = $val['RING_NUMBER'];
            $i++;
        }
        return $comments_ps;
    }
    //    Author:ANCY MATHEW 23/06/2016
    //    Usage : Store ps and ds Comments
    //    Created:
    /**
     * @param $data
     * @return mixed
     */
    public function set_psds_comments($data)
    {
//        print_r($data);
        $this->db->insert('tbl_psds_comment', $data);
        return $this->db->affected_rows();

    }
    //    Author:ANCY MATHEW
    //    Usage : Summary PS&DS
    //    Created: 24/06/2016
    /**
     * @return array
     */
    public function get_status_ps(){
        $status_ps = array();
        $sql = 'SELECT * FROM "tbl_psds_summary" WHERE "DATA_DATE"=(SELECT MAX("DATA_DATE") FROM "tbl_psds_summary")';
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $val) {
            $status_ps[$i]["summary"] = $val['SUMMARY'];
            $status_ps[$i]["progress_completion"] = $val['PROGRESS_COMPLETION'];
            $status_ps[$i]["progress_completion_ef"] = $val['PROGRESS_COMPLETION_EF'];
            $status_ps[$i]["ac_progress_completion"] = $val['AC_PROGRESS_COMPLETION'];
            $status_ps[$i]["ac_ef"] = $val['AC_EF'];
            $status_ps[$i]["ac_lf"] = $val['AC_LF'];
            $status_ps[$i]["dc_progress_completion"] = $val['DC_PROGRESS_COMPLETION'];
            $status_ps[$i]["dc_ef"] = $val['DC_EF'];
            $status_ps[$i]["dc_lf"] = $val['DC_LF'];
            $status_ps[$i]["data_date"] = $val['DATA_DATE'];
            $i++;
        }
        return $status_ps;
    }
    //coded by :ANCY MATHEW
    //used to get PS and DS trending and progress details
    //Created on : 05/09/2016
    /**
     * @return array
     */
    public function get_trending_and_progress()
    {
        $trending = array();
        $sql = 'SELECT * FROM "tbl_psds_trending_and_progress"  where "data_date"=(select max("data_date") from "tbl_psds_trending_and_progress" )';
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $i = 0;
        foreach ($result as $val) {
            $trending['trending']["design_trending"] = $val['design_trending'];
            $trending['progress']["design_progress"] = $val['design_progress'];
            $trending['trending']["installation_trending"] = $val['installation_trending'];
            $trending['progress']["installation_progress"] = $val['installation_progress'];
            $trending['trending']["test_trending"] = $val['test_trending'];
            $trending['progress']["test_progress"] = $val['test_progress'];
            $trending['trending']["handover_trending"] = $val['handover_trending'];
            $trending['progress']["handover_progress"] = $val['handover_progress'];
            $trending["data_date"] = $val['data_date'];
            $i++;
        }
        return $trending;
    }
    //TRACK WORKS CODE Starts Here//
    //    Author: ANCY MATHEW
    //    Usage : NORTH, SOUTH, and UG KEydate status
    //    Created: 08/04/2016
    /**
     * @param $page
     * @param bool $date
     * @return array
     */
    public function get_keydate_status()
    {
        $keydate_status = array();
        $sql = 'SELECT region, kd_number,tp_plan, tp_actual,CASE WHEN cast(tp_plan as float) = 100 AND cast(tp_actual as float) = 100 THEN 1 WHEN cast(tp_plan as float) != 100 AND cast(tp_actual as float) != 100  THEN 2 ELSE 0 END FROM tbl_tw_progress';
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (sizeof($result) > 0) {
            $a = 0;
            $b = 0;
            foreach ($result as $val) {
                if (strtoupper($val['kd_number']) == "KD12") {
                    $b += (33.333 * $val['tp_actual']) / $val['tp_plan'];
                } else {
                    switch ($val['case']) {
                        case 1:
                            $station_status[$val['kd_number']] = 0;
                            break;
                        case 0:
                            if ($val['tp_actual'] >= 50) {
                                $station_status[$val['kd_number']] = 1;
                            } else {
                                $station_status[$val['kd_number']] = -1;
                            }
                            break;
                        case 2:
                            if ((($val['tp_actual'] * 100) / $val['tp_plan']) >= 50) {
                                $station_status[$val['kd_number']] = 1;
                            } else {
                                $station_status[$val['kd_number']] = -1;
                            }
                            break;
                        default :
                            $station_status[$val['kd_number']] = 2;
                    }
                }
            }
            $station_status["KD12"] = (ceil($b) >= 100) ? 0 : ((ceil($b) >= 50) ? 1 : ((ceil($b) < 50) ? -1 : 2));
        } else {
            $station_status['KD9a'] = 2;
            $station_status['KD11a'] = 2;
            for ($i = 8; $i < 17; $i++) {
                $station_status['KD' . $i] = 2;
            }
        }
        return $station_status;
    }
    //    Author: SEBIN THOMAS
    //    Usage : NORTH, SOUTH, and UG Overall Summary
    //    Created: 15/07/2016
    /**
     * @param $page
     * @param bool $date
     * @return array
     */
    public function get_tw_overall_summary($page, $date = FALSE)
    {
        $i = 0;
        $a = array();
        $overall = array(
            "value" => array()
        );
        if ($page == 'north' || $page == 'south') {
            $sub = " " . "and \"kd_number\" != 'KD12'";
        } else if ($page == 'ug') {
            $sub = " " . "or \"kd_number\" ~* 'KD12'";
        } else {
            $sub = "";
        }
        if ($date) {
            $sql = "SELECT \"region\", \"kd_number\", \"tp_plan\", \"tp_actual\", \"tp_variance_precent\", \"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE region ~* '$page'" . $sub . " and \"data_date\"='$date'";
        } else {
            //~* is used in the query to check caseless(Upper/Lower)
            $sql = "SELECT \"region\", \"kd_number\", \"tp_plan\", \"tp_actual\", \"tp_variance_precent\", \"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE region ~* '$page'" . $sub . " and \"data_date\" in (SELECT max(\"data_date\") FROM \"tbl_tw_progress\")";
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach ($result as $val) {
            $a[$i] = array(
                "kd" => (strtolower($val['kd_number'])=='kd12')?((strtolower($val['region'])=="north")?"KD12-V4":((strtolower($val['region'])=="south")?"KD12-V5":"KD12-UG")):$val['kd_number'],
                "kd_url" => (strtolower($val['kd_number']) != 'kd12') ? strtolower($val['kd_number']) : (strtolower($val['region']) == 'north' ? "kd12n" : (strtolower($val['region']) == 'ug' ? "kd12u" : (strtolower($val['region']) == 'south' ? "kd12s" : (strtolower($val['region']) == 'ugw' ? "kd12u" : "#")))),
                "plan" => $val['tp_plan'],
                "actual" => $val['tp_actual'],
                "precent" => $val['tp_variance_precent'],
                "weeks" => $val['tp_variance_weeks']
            );
            $i++;
        }
        $overall['value'] = json_encode($a);
        return $overall;
    }
    //    Author: SEBIN THOMAS
    //    Usage : NORTH, SOUTH, and UG Overall Progress, Individual Summary of KD9 - KD16
    //    Created: 18/07/2016
    /**
     * @param $page
     * @param bool $date
     * @param $filter
     * @return array
     */
    public function get_tw_overall_progress($page, $date = FALSE, $filter)
    {
        $i = 0;
        $a = array();
        $overall = array();
        if ($filter) {
            if ($page == 'north' || $page == 'south') {
                $sub = " " . "and \"kd_number\" != 'KD12'";
            } else if ($page == 'ug') {
                $sub = " " . "or \"kd_number\" ~* 'KD12'";
            } else {
                $sub = "";
            }
            if ($date) {
                $sql = "SELECT sum(ts_plan) as ts_plan, sum(ts_actual) as ts_actual, sum(sp_plan) as sp_plan, sum(sp_actual) as sp_actual, sum(lrd_plan) as lrd_plan,sum(lrd_actual) as lrd_actual, sum(rsa_plan) as rsa_plan, sum(rsa_actual) as rsa_actual, sum(rfs_plan) as rfs_plan, sum(rfs_actual) as rfs_actual, sum(con_plan) as con_plan,sum(con_actual) as con_actual, sum(dw_plan) as dw_plan, sum(dw_actual) as dw_actual, sum(wd_plan) as wd_plan, sum(wd_actual) as wd_actual, sum(ra_plan) as ra_plan,sum(ra_actual) as ra_actual, sum(prbi_plan) as prbi_plan, sum(prbi_actual) as prbi_actual, sum(pria_plan) as pria_plan, sum(pria_actual) as pria_actual, sum(prci_plan) as prci_plan,sum(prci_actual) as prci_actual, sum(ew_plan) as ew_plan, sum(ew_actual) as ew_actual, sum(ctc_plan) as ctc_plan, sum(ctc_actual) as ctc_actual, sum(comm_plan) as comm_plan,sum(comm_actual) as comm_actual FROM \"tbl_tw_progress\" WHERE region ~* '$page'" . $sub . " and \"data_date\"='$date'";
            } else {
                $sql = "SELECT sum(ts_plan) as ts_plan, sum(ts_actual) as ts_actual, sum(sp_plan) as sp_plan, sum(sp_actual) as sp_actual, sum(lrd_plan) as lrd_plan,sum(lrd_actual) as lrd_actual, sum(rsa_plan) as rsa_plan, sum(rsa_actual) as rsa_actual, sum(rfs_plan) as rfs_plan, sum(rfs_actual) as rfs_actual, sum(con_plan) as con_plan,sum(con_actual) as con_actual, sum(dw_plan) as dw_plan, sum(dw_actual) as dw_actual, sum(wd_plan) as wd_plan, sum(wd_actual) as wd_actual, sum(ra_plan) as ra_plan,sum(ra_actual) as ra_actual, sum(prbi_plan) as prbi_plan, sum(prbi_actual) as prbi_actual, sum(pria_plan) as pria_plan, sum(pria_actual) as pria_actual, sum(prci_plan) as prci_plan,sum(prci_actual) as prci_actual, sum(ew_plan) as ew_plan, sum(ew_actual) as ew_actual, sum(ctc_plan) as ctc_plan, sum(ctc_actual) as ctc_actual, sum(comm_plan) as comm_plan,sum(comm_actual) as comm_actual FROM \"tbl_tw_progress\" WHERE region ~* '$page'" . $sub . " group by \"data_date\" order by \"data_date\" desc limit 1";
            }
        } else {
            if ($date) {
                if ($page == "kd12n") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and \"region\" ~* 'north' and \"data_date\"='$date'";
                } else if ($page == "kd12u") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and (\"region\" ~* 'ug' or \"region\" ~* 'ugw' or \"region\" ~* 'underground') and \"data_date\"='$date'";
                } else if ($page == "kd12s") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and \"region\" ~* 'south' and \"data_date\"='$date'";
                } else {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* '$page' and \"data_date\"='$date'";
                }
            } else {
                if ($page == "kd12n") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and \"region\" ~* 'north' order by \"data_date\" desc limit 1";
                } else if ($page == "kd12u") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and (\"region\" ~* 'ug' or \"region\" ~* 'ugw' or \"region\" ~* 'underground') order by \"data_date\" desc limit 1";
                } else if ($page == "kd12s") {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* 'kd12' and \"region\" ~* 'south' order by \"data_date\" desc limit 1";
                } else {
                    $sql = "SELECT \"ts_plan\", \"ts_actual\", \"sp_plan\", \"sp_actual\", \"lrd_plan\",\"lrd_actual\", \"rsa_plan\", \"rsa_actual\", \"rfs_plan\", \"rfs_actual\", \"con_plan\", \"con_actual\", \"dw_plan\", \"dw_actual\", \"wd_plan\", \"wd_actual\", \"ra_plan\",\"ra_actual\", \"prbi_plan\", \"prbi_actual\", \"pria_plan\", \"pria_actual\", \"prci_plan\",\"prci_actual\", \"ew_plan\", \"ew_actual\", \"ctc_plan\", \"ctc_actual\", \"comm_plan\",\"comm_actual\",\"tp_plan\",\"tp_actual\",\"tp_variance_precent\",\"tp_variance_weeks\" FROM \"tbl_tw_progress\" WHERE \"kd_number\" ~* '$page' order by \"data_date\" desc limit 1";
                }
            }
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $a['region'] = $page;
        $a["category"] = array('Track Survey', 'Surface Preparation', 'Long Rail..', 'Rail & Sleeper..', 'Rebar & Form..', 'Concreting', 'Derailment Wall', 'Welding..', 'Rail Alignment', 'PR Bracket..', 'PR Install/Align', 'PR Cover..', 'Emergency..', 'Cable Through &..', 'Commissioning');
        foreach ($result as $val) {
            $a["planned"] = array_map('intval', array($val['ts_plan'], $val['sp_plan'], $val['lrd_plan'], $val['rsa_plan'], $val['rfs_plan'], $val['con_plan'], $val['dw_plan'], $val['wd_plan'], $val['ra_plan'], $val['prbi_plan'], $val['pria_plan'], $val['prci_plan'], $val['ew_plan'], $val['ctc_plan'], $val['comm_plan']));
            $a["actual"] = array_map('intval', array($val['ts_actual'], $val['sp_actual'], $val['lrd_actual'], $val['rsa_actual'], $val['rfs_actual'], $val['con_actual'], $val['dw_actual'], $val['wd_actual'], $val['ra_actual'], $val['prbi_actual'], $val['pria_actual'], $val['prci_actual'], $val['ew_actual'], $val['ctc_actual'], $val['comm_actual']));
        }
        if (!$filter) {
            foreach ($result as $val) {
                $a["summary"] = array('ts_plan' => $val['ts_plan'], 'ts_actual' => $val['ts_actual'], 'sp_plan' => $val['sp_plan'], 'sp_actual' => $val['sp_actual'], 'lrd_plan' => $val['lrd_plan'], 'lrd_actual' => $val['lrd_actual'], 'rsa_plan' => $val['rsa_plan'], 'rsa_actual' => $val['rsa_actual'], 'rfs_plan' => $val['rfs_plan'], 'rfs_actual' => $val['rfs_actual'], 'con_plan' => $val['con_plan'], 'con_actual' => $val['con_actual'], 'dw_plan' => $val['dw_plan'], 'dw_actual' => $val['dw_actual'], 'wd_plan' => $val['wd_plan'], 'wd_actual' => $val['wd_actual'], 'ra_plan' => $val['ra_plan'], 'ra_actual' => $val['ra_actual'], 'prbi_plan' => $val['prbi_plan'], 'prbi_actual' => $val['prbi_actual'], 'pria_plan' => $val['pria_plan'], 'pria_actual' => $val['pria_actual'], 'prci_plan' => $val['prci_plan'], 'prci_actual' => $val['prci_actual'], 'ew_plan' => $val['ew_plan'], 'ew_actual' => $val['ew_actual'], 'ctc_plan' => $val['ctc_plan'], 'ctc_actual' => $val['ctc_actual'], 'comm_plan' => $val['comm_plan'], 'comm_actual' => $val['comm_actual'], 'tp_plan' => $val['tp_plan'], 'tp_actual' => $val['tp_actual'], 'tp_variance_precent' => $val['tp_variance_precent'], 'tp_variance_weeks' => $val['tp_variance_weeks']);
            }
        }
        $overall['value'] = json_encode($a);
        return $overall;
    }
    //Done by :Jane Elizabeth Jose
    //to retrieve track works region data
    //Created on : 15/07/2016
    /**
     * @param bool $date
     * @return array
     */
    public function get_tw_region_data($date = FALSE)
    {
        $i = 0;
        $tem_array = array();
        $region_progress = array(
            "value" => array()
        );

        if ($date) { // if date selected
            $query = "SELECT * FROM tbl_tw_region_progress WHERE data_data=$date";
        } else {
            $query = "SELECT * FROM tbl_tw_region_progress";
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
            $tem_array[$i] = array(
                "region" => $val['region'],
                "region_url" => (strtolower($val['region']) == 'south') ? 'south' : (strtolower($val['region']) == 'north' ? 'north' : ((strtolower($val['region']) == 'ugw' ? 'ug' : (strtolower($val['region']) == 'ug' ? 'ug' : (strtolower($val['region']) == 'underground' ? 'ug' : 'ug'))))),
                "plan" => $val['plan'],
                "actual" => $val['actual'],
                "week_difference" => $val['week_difference'],
                "data_date" => $val['data_date']
            );
            $i++;
        }
        $region_progress['value'] = json_encode($tem_array);
        return $region_progress;
    }
    public function get_tw_area_data($depotname,$date = FALSE)
    {
        $i = 0;
        $count_area=0;
        $aver=0;
        $sum_area=0;
        $tem_array = array(
            "area1"=>array(),
            "area2"=>array(),
            "area3"=>array(),
            "area4"=>array()
        );
        $region_progress = array(
            "value" => array()
        );
        if($depotname=='dpt1'){
            if ($date) { // if date selected
                $query = "SELECT depot_name, area_no, area_master_property, area_sub_property,area_plan, area_done, area_percentage_completed FROM tbl_tw_area where depot_name='sungai buloh depot' and data_date=$date";
            } else {
                $query = "SELECT depot_name, area_no, area_master_property, area_sub_property,area_plan, area_done, area_percentage_completed  FROM tbl_tw_area where depot_name='sungai buloh depot' and data_date in (SELECT max(data_date) from tbl_tw_area)";
            }
        }
        if($depotname=='dpt2') {
            if ($date) { // if date selected
                $query = "SELECT depot_name, area_no, area_master_property, area_sub_property,area_plan, area_done, area_percentage_completed FROM tbl_tw_area where depot_name='kajang depot' and data_date=$date";
            } else {

                $query = "SELECT depot_name, area_no, area_master_property, area_sub_property,area_plan, area_done, area_percentage_completed FROM tbl_tw_area where depot_name='kajang depot' and data_date in (SELECT max(data_date) from tbl_tw_area)";

            }
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
            if($val['area_percentage_completed']!=null)
            {
                $count_area=$count_area+1;
                $sum_area=$sum_area+$val['area_percentage_completed'];
                $aver=$sum_area/$count_area;
            }
            if($val['area_no']==1) {
                array_push($tem_array["area1"], array(
                    "depot_name" => $val['depot_name'],
                    "area_no" => $val['area_no'],
                    "area_master_property" => $val['area_master_property'],
                    "area_sub_property" => $val['area_sub_property'],
                    "area_plan" => $val['area_plan'],
                    "area_done" => $val['area_done'],
                    "area_percentage_completed" => $val['area_percentage_completed']
                ));
            }
            if($val['area_no']==2) {
                array_push($tem_array["area2"], array(
                    "depot_name" => $val['depot_name'],
                    "area_no" => $val['area_no'],
                    "area_master_property" => $val['area_master_property'],
                    "area_sub_property" => $val['area_sub_property'],
                    "area_plan" => $val['area_plan'],
                    "area_done" => $val['area_done'],
                    "area_percentage_completed" => $val['area_percentage_completed']
                ));
            }
            if($val['area_no']==3) {
                array_push($tem_array["area3"], array(
                    "depot_name" => $val['depot_name'],
                    "area_no" => $val['area_no'],
                    "area_master_property" => $val['area_master_property'],
                    "area_sub_property" => $val['area_sub_property'],
                    "area_plan" => $val['area_plan'],
                    "area_done" => $val['area_done'],
                    "area_percentage_completed" => $val['area_percentage_completed']

                ));
            }
            if($val['area_no']==4) {
                array_push($tem_array["area4"], array(
                    "depot_name" => $val['depot_name'],
                    "area_no" => $val['area_no'],
                    "area_master_property" => $val['area_master_property'],
                    "area_sub_property" => $val['area_sub_property'],
                    "area_plan" => $val['area_plan'],
                    "area_done" => $val['area_done'],
                    "area_percentage_completed" => $val['area_percentage_completed']
                ));
            }

           $i++;
        }
        $tem_array["average"]=$aver;
        $region_progress['value'] = json_encode($tem_array);
        return $region_progress;
    }
    public function get_tw_overall_percentage()
    {
        $query = 'SELECT * FROM "tbl_tw_overall_percentage" WHERE "data_date" = (SELECT MAX("data_date") FROM "tbl_tw_overall_percentage")';
        $query = $this->db->query($query);
        return $query->result_array();

    }
    //STCS CODE Starts Here//
    //    Author: ANCY MATHEW
    //    Usage : Train progress of STCS
    //    Created: 09/04/2016
    public function get_stcs_trian_progree($date = FALSE)
    {
        $i = 0;
        $tem_array = array();
        $train_stcs_progress = array(
            "value" => array()
        );
        if ($date) { // if date selected
            $query = "SELECT train_number, static_test_perc, dynamic_test_perc, overall_stat_perc FROM tbl_stcs_train_status where data_date=$date";
        } else {
            $query = "SELECT train_number, static_test_perc, dynamic_test_perc, overall_stat_perc FROM tbl_stcs_train_status";
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
           /* $tem_array[$val['train_number']]=$val['train_number'];
            $tem_array[$val['train_number']]["status"]=$val['overall_stat_perc'];
            $tem_array[$val['train_number']]["static"]=$val['static_test_perc'];
            $tem_array[$val['train_number']]["dynamic"]=$val['dynamic_test_perc'];*/
            $tem_array[$val['train_number']] = array(
                "train_number" => $val['train_number'],
                "status" => $val['overall_stat_perc'],
                "static" => $val['static_test_perc'],
                "dynamic" => $val['dynamic_test_perc']
            );
            $i++;
        }
        $train_stcs_progress['value'] = json_encode($tem_array);
        return $train_stcs_progress;
    }
    //    Author: ANCY MATHEW
    //    Usage : Get issue  of STCS
    //    Created: 09/04/2016
    public function get_stcs_comments($date = FALSE)
    {
        $i = 0;
        $tem_array = array(
            "comment"=>array()
        );
        $train_stcs_comment = array(
            "value" => array()
        );
        if ($date) { // if date selected
            $query = "SELECT comment_id,comment, station_code, time_stamp, date_selected FROM tbl_stcs_comments where date_selected=$date";
        } else {
            $query = "SELECT comment_id,comment, station_code, time_stamp, date_selected FROM tbl_stcs_comments";
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
            array_push($tem_array["comment"],array(
                "comment_id" => $val['comment_id'],
                "comment" => $val['comment'],
                "station_code" => $val['station_code'],
                "time_stamp" => $val['time_stamp']
            ) );
            $i++;
        }
        $train_stcs_comment['value'] = json_encode($tem_array);
        return $train_stcs_comment;
    }
    //    Author: ANCY MATHEW
    //    Usage : Station Progress of STCS
    //    Created: 09/04/2016
    public function get_stcs_station_progres($date = FALSE)
    {
        $i = 0;
        $tem_array = array();
        $station_stcs_progress = array(
            "value" => array()
        );
        if ($date) { // if date selected
            $query1 = "SELECT station_name,stat_install_type,stat_status, stat_progress_perc, equip_name, equip_progress,\"PAT_status\", \"SAT_status\", data_date, stat_install_type FROM tbl_stcs_station_status where data_date='$date'  ";

        } else {
            $query1 = "SELECT station_name,stat_install_type, stat_status, stat_progress_perc, equip_name, equip_progress,\"PAT_status\", \"SAT_status\", data_date, stat_install_type FROM tbl_stcs_station_status  ";

        }
        $query1 = $this->db->query($query1);
        $result1 = $query1->result_array();

        foreach ($result1 as $val) {
            if($val['stat_install_type']==1) {
                $tem_array[$val['station_name']][$i]=array(
                    "roomside" =>'"'.$val['equip_name'].'": ['.$val['equip_progress'].','.$val['PAT_status'].','.$val['SAT_status'].']'
                );
            }else{
                $tem_array[$val['station_name']][$i]=array(
                    "wayside" =>'"'.$val['equip_name'].'": ['.$val['equip_progress'].','.$val['PAT_status'].','.$val['SAT_status'].']'
                );
            }
            $i++;
        }
        $newArray=array();
        foreach($tem_array as $key_outer => $value_outer){
            $roomside='{';
            $wayside='{';
            foreach($value_outer as $key_inner => $value_inner) {
                foreach ($value_inner as $k => $v) {
                    if($k=="roomside"){
                        $roomside.=$v.',';
                    }else{
                        $wayside.=$v.',';
                    }
                }
            }
            $roomside = substr_replace($roomside, '}',strlen($roomside)-1,1);
            $wayside = substr_replace($wayside, '}',strlen($wayside)-1,1);
            $newArray[strtolower($key_outer)]=array(
                "roomside"=>$roomside,
                "wayside"=>$wayside
            );
        }

//        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($tem_array));
//        $list = iterator_to_array($it,false);
//        $list = call_user_func_array('array_merge', $tem_array);
        $station_stcs_progress['value'] = json_encode($newArray);
        return $station_stcs_progress;
    }
    //    Author:ANCY MATHEW 10/08/2016
    //    Usage : Store STCS Comments
    public function set_stcs_comments($data)
    {
        $this->db->insert('tbl_stcs_comments', $data);
        return $this->db->affected_rows();

    }
    public function get_stcs_train_total_progress($date = FALSE)
    {
        $i = 0;
        $tem_array = array();
        $train_progress = array(
            "value" => array()
        );
        if ($date) { // if date selected
            $query = "SELECT round(avg( static_test_perc),2) as static, round(avg(dynamic_test_perc),2) as dynamic, round(avg(overall_stat_perc),2) as overall FROM tbl_stcs_train_status where data_date=$date";
        } else {
            $query = "SELECT round(avg( static_test_perc),2) as static, round(avg(dynamic_test_perc),2) as dynamic, round(avg(overall_stat_perc),2) as overall FROM tbl_stcs_train_status";
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
            $tem_array[$i] = array(
                "static" => $val['static'],
                "dynamic" => $val['dynamic'],
                "overall" => $val['overall']
            );
            $i++;
        }
        $train_progress['value'] = json_encode($tem_array);
        return $train_progress;
    }
    public function get_region_progress($date = FALSE)
    {
        $i = 0;
        $tem_array = array(
            "R1"=>array(),
            "R2"=>array(),
            "R3"=>array(),
            "R4"=>array(),
            "R5"=>array(),
            "R6"=>array(),
            "R7"=>array()
        );
        $region_progress = array(
            "value" => array()
        );
        if ($date) { // if date selected
            $query = "SELECT region_no, station_no, station_progress, roomside_progress, wayside_progress,\"PAT_progress\", \"SAT_progress\", data_date FROM tbl_stcs_region_progress where data_date=$date";
        } else {
            $query = "SELECT region_no, station_no, station_progress, roomside_progress, wayside_progress,\"PAT_progress\", \"SAT_progress\", data_date FROM tbl_stcs_region_progress";
        }
        $query = $this->db->query($query);
        $result = $query->result_array();
        foreach ($result as $val) {
            if($val['region_no']==1)
            {
                array_push($tem_array["R1"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']

                ));
            }
            if($val['region_no']==2)
            {
                array_push($tem_array["R2"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            if($val['region_no']==3)
            {
                array_push($tem_array["R3"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            if($val['region_no']==4)
            {
                array_push($tem_array["R4"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            if($val['region_no']==5)
            {
                array_push($tem_array["R5"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            if($val['region_no']==6)
            {
                array_push($tem_array["R6"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            if($val['region_no']==7)
            {
                array_push($tem_array["R7"],array(
                        "region_no" => $val['region_no'],
                        "station_no" => str_replace(' ', '', $val['station_no']),
                        "station_progress" => $val['station_progress'],
                        "roomside_progress" => $val['roomside_progress'],
                        "wayside_progress" => $val['wayside_progress'],
                        "PAT_progress" => $val['PAT_progress'],
                        "SAT_progress" => $val['SAT_progress']
                    )
                );
            }
            $i++;
        }
        $region_progress['value'] = json_encode($tem_array);
        return $region_progress;
    }
}
