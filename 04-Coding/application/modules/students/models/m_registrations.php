<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_registrations extends CI_Model {

    function findAllRegistrations($num_row, $from_row) {
//
        if ($this->input->post('gen_id') != '') {
            $this->db->like('tbl_generation_gen_id', $this->input->post('gen_id'));
        }
        if ($this->input->post('cla_id') != '') {
            $this->db->where('cl.cla_id', $this->input->post('cla_id'));
        }
        if ($this->input->post('stucla_year_study') != '') {
            $this->db->like('stucla_year_study', $this->input->post('stucla_year_study'));
        }
        if ($this->input->post('maj_id') != '') {
            $this->db->like('cla_maj_id', $this->input->post('maj_id'));
        }
        if ($this->input->post('stucla_degree') != '') {
            $this->db->like('stucla_degree', $this->input->post('stucla_degree'));
        }
        if ($this->input->post('fac_id') != '') {
            $this->db->like('fac_id', $this->input->post('fac_id'));
        }
        if ($this->input->post('stu_en_firstname') != '') {
            $this->db->like('stu_en_firstname', $this->input->post('stu_en_firstname'));
        }
        if ($this->input->post('stu_en_lastname') != '') {
            $this->db->like('stu_en_lastname', $this->input->post('stu_en_lastname'));
        }


        $this->db->order_by('s.stu_id', 'desc');
        $this->db->limit($num_row, $from_row);
        $this->db->from(TABLE_PREFIX . 'students s');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 's.stu_id = sc.stucla_stu_id');
        $this->db->join(TABLE_PREFIX . 'classes cl', 'cl.cla_id = sc.stucla_cla_id');
        $this->db->join(TABLE_PREFIX . 'shift sh', 'cl.tbl_shift_shi_id = sh.shi_id');
        $this->db->join(TABLE_PREFIX . 'generation ge', 'cl.tbl_generation_gen_id = ge.gen_id');
        $this->db->join(TABLE_PREFIX . 'majors ma', 'ma.maj_id = cl.cla_maj_id');
        $this->db->join(TABLE_PREFIX . 'faculties fa', 'ma.maj_fac_id = fa.fac_id');


        $result = $this->db->get();
        $this->session->set_userdata('lastQuery', $this->db->last_query());  // Set cooki for last query for export to excel
        return $result;
//        return $this->db->get(TABLE_PREFIX . 'students');
    }

    function countAllRegistrations() {
        if ($this->input->post('gen_id') != '') {
            $this->db->like('tbl_generation_gen_id', $this->input->post('gen_id'));
        }
        if ($this->input->post('cla_id') != '') {
            $this->db->where('cl.cla_id', $this->input->post('cla_id'));
        }
        if ($this->input->post('stucla_year_study') != '') {
            $this->db->like('stucla_year_study', $this->input->post('stucla_year_study'));
        }
        if ($this->input->post('maj_id') != '') {
            $this->db->like('cla_maj_id', $this->input->post('maj_id'));
        }
        if ($this->input->post('stucla_degree') != '') {
            $this->db->like('stucla_degree', $this->input->post('stucla_degree'));
        }
        if ($this->input->post('fac_id') != '') {
            $this->db->like('fac_id', $this->input->post('fac_id'));
        }
        if ($this->input->post('stu_en_firstname') != '') {
            $this->db->like('stu_en_firstname', $this->input->post('stu_en_firstname'));
        }
        if ($this->input->post('stu_en_lastname') != '') {
            $this->db->like('stu_en_lastname', $this->input->post('stu_en_lastname'));
        }
        $this->db->from(TABLE_PREFIX . 'students s');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 's.stu_id = sc.stucla_stu_id');
        $this->db->join(TABLE_PREFIX . 'classes cl', 'cl.cla_id = sc.stucla_cla_id');
        $this->db->join(TABLE_PREFIX . 'shift sh', 'cl.tbl_shift_shi_id = sh.shi_id');
        $this->db->join(TABLE_PREFIX . 'majors ma', 'ma.maj_id = cl.cla_maj_id');
        $this->db->join(TABLE_PREFIX . 'faculties fa', 'ma.maj_fac_id = fa.fac_id');
        $data = $this->db->get();
        return $data->num_rows();
    }

    /**
     * Add new group
     * @return true/false
     */
    function add() {
        $class_id = "";
        $descount = 0; // === get descound 30% for any scholarship
        //var_dump($this->input->post());die();
        $stu_card_id = "";
        $data = $this->input->post();
        $exp_date = $data['exp_date'];
        if ($data['exp_shift'] = !"") {
            $exp_shift = $data['exp_shift'];
        }
        $exp_position = $data['exp_position'];
        $exp_employer_tel = $data['exp_employer_tel'];
        $exp_responsibility = $data['exp_responsibility'];
//        =====for payment info==========
        $discount_value = 0;
        $discount_period = 0;
        if (isset($data['pm_discount_value'])) {
            $discount_value = $data['pm_discount_value'];
            $discount_period = $data['pm_discount_period'];
            unset($data['pm_discount_value']);
            unset($data['pm_discount_period']);
        }
        $pay_type = $data['pay_type'];
        unset($data['pay_type']);
//============end payment info===========
        $acadamic = $data['tbl_generation_gen_id'];
        unset($data['tbl_generation_gen_id']);
        unset($data['exp_date']);
        unset($data['exp_shift']);
        unset($data['exp_position']);
        unset($data['exp_employer_tel']);
        unset($data['exp_responsibility']);
        unset($data['stu_image']);
        $stucla_year_study = $data['stucla_year_study'];
        unset($data['stucla_year_study']);

//        ========Create studetn id card==============
        $dataClass = $this->getAClassById($data['class']);
        $dataClass->result_array();
        $dataClass = $dataClass->result_array[0];

        $dataStudent = $this->getStudentByClass($data['class']);
//        $dataStudent = $this->getStudentByClass(3);
        $dataStudent->result_array();
        if ($dataStudent->num_rows() != NULL) {
            $dataStudent = $dataStudent->result_array[0];
            $idCard = substr($dataStudent['stu_card_id'], -3) + 1;  //get only 3 chars at end of id card
        } else {
            $idCard = 1;
        }
        $numStudent = sprintf("%03s", $idCard); // Number of student after count exiting student with format "001"
        $stu_id_card = $dataClass['shi_abbriviation'] . "-" . $dataClass['maj_abbriviation'] . '-1-' . $numStudent; ///// to be chang for number of student
        $data['stu_card_id'] = $stu_id_card;
//        ===============End student ID Card================
        unset($data['shift']);
        $major = $data['major'];
        unset($data['major']);
        $degree = $data['degree'];
        unset($data['degree']);
        $class_id = $data['class'];
        unset($data['class']);
        //$this->db->set('gro_created', 'NOW()', false);
        //var_dump($data);
        $this->db->insert(TABLE_PREFIX . 'students', $data);
        $stu_id = $this->db->insert_id();
        //Insert data to class of student
        $class_date = array(
            'stucla_stu_id' => $stu_id,
            'stucla_cla_id' => $class_id,
            'stucla_degree' => $degree,
            'stucla_year_study' => $stucla_year_study
        );
        $this->db->insert(TABLE_PREFIX . 'student_class', $class_date);

        //        ==============Insert payment info===========
        $payment_info = array(
            'spf_stu_id' => $stu_id,
            'spf_spt_id' => $pay_type,
            'spf_discount_value' => $discount_value,
            'spf_discount_period' => $discount_period
        );
        $this->db->insert(TABLE_PREFIX . 'student_payment_fee', $payment_info);
//        ========Fee for each year===============
        //        ==============Payment fee============
        $this->db->from(TABLE_PREFIX . 'majors ma');
        $this->db->join(TABLE_PREFIX . 'major_fee maf', 'maf.maf_maj_id = ma.maj_id');
        $this->db->where('maf_academic_year', $acadamic);   // ==========Whare seleted academic year======= to be update
        $this->db->where('ma.maj_id', $major);  // ==========Whare seleted class===
        $this->db->where('maf.maf_deg_id', $degree);  // ==========Whare seleted class===
        $this->db->order_by('maf_year_number', 'asc');
        $fee_info = $this->db->get(); //=====Get school free for each year==========

        $fee = 0;
        $total_fee = array(0, 0, 0, 0);
        $payment_fee_data = array();
        if ($fee_info->num_rows() > 0) {
            $j = 0;
            foreach ($fee_info->result_array() as $row) {
                $fee = $row['maf_price'];
                if ($j <= $discount_period) {
                    $total_fee[$j] = $fee - ($fee * 0.01 * $discount_value);
                } else {
                    $total_fee[$j] = $fee;
                }
                $j++;
            }
        }
        $userid = $this->session->userdata('user');
        for ($i = 0; $i < 4; $i++) {
            $payment_fee_data[$i] = array(
                'sp_stu_id' => $stu_id,
                'sp_year' => $i + 1, ////// increate array index 0--->1 for year number
                'sp_fee' => $total_fee[$i],
                'sp_cla_id' => $class_id,
                'sp_balance' => $total_fee[$i],
                'tbl_users_id' => $userid['use_id']
            );
        }
        $this->db->insert_batch(TABLE_PREFIX . 'student_payments', $payment_fee_data);
//        ===========end peyment info==============
        $i = 0;
        foreach ($exp_date as $value) {
            $ext['exp_stu_id'] = $stu_id;
            $ext['exp_date'] = $exp_date[$i];
            $ext['exp_shift'] = $exp_shift[$i];
            $ext['exp_position'] = $exp_position[$i];
            $ext['exp_employer_tel'] = $exp_employer_tel[$i];
            $ext['exp_responsibility'] = $exp_responsibility[$i];
            $this->db->insert('tbl_experiences', $ext);
            $ext = NULL;
        }
        return true;
    }

    /**
     * Edit a group
     * @return true/false
     */
    function update() {
        $data = $this->input->post();
        $stu_id = $this->uri->segment(4);
        $exp_date = $data['exp_date'];
        if ($data['exp_shift'] = !"") {
            $exp_shift = $data['exp_shift'];
        }
        $exp_position = $data['exp_position'];
        $exp_employer_tel = $data['exp_employer_tel'];
        $exp_responsibility = $data['exp_responsibility'];
        $pay_type = $data['pay_type'];
        unset($data['exp_date']);
        unset($data['exp_shift']);
        unset($data['exp_position']);
        unset($data['exp_employer_tel']);
        unset($data['exp_responsibility']);
        unset($data['stu_image']);
        $degree = $data['degree'];
        unset($data['degree']);
//=====for payment info==========
        $discount_value = 0;
        $discount_period = 0;
        if (isset($data['pm_discount_value'])) {
            $discount_value = $data['pm_discount_value'];
            $discount_period = $data['pm_discount_period'];
            unset($data['pm_discount_value']);
            unset($data['pm_discount_period']);
        }
        $pay_type = $data['pay_type'];
        unset($data['pay_type']);
        $acadamic = $data['tbl_generation_gen_id'];
        unset($data['tbl_generation_gen_id']);
        $major = $data['major'];
        unset($data['major']);
        $class_id = $data['class'];
        unset($data['class']);
//============end payment info===========
//
//        ========Create studetn id card==============
        $dataClass = $this->getAClassById($class_id);
        $dataClass->result_array();
        $dataClass = $dataClass->result_array[0];
        $dataStudent = $this->getStudentByClass($class_id);
//        $dataStudent = $this->getStudentByClass(3);
        $dataStudent->result_array();
        if ($dataStudent->num_rows() != NULL) {
            $dataStudent = $dataStudent->result_array[0];
            $idCard = substr($dataStudent['stu_card_id'], -3) + 1;  //get only 3 chars at end of id card
        } else {
            $idCard = 1;
        }
        $numStudent = sprintf("%03s", $idCard); // Number of student after count exiting student with format "001"
        $stu_id_card = $dataClass['shi_abbriviation'] . "-" . $dataClass['maj_abbriviation'] . '-1-' . $numStudent; ///// to be chang for number of student
        $data['stu_card_id'] = $stu_id_card;
//        ===============End student ID Card================
        //        ==============Insert payment info===========
        $payment_info = array(
            'spf_spt_id' => $pay_type,
            'spf_discount_value' => $discount_value,
            'spf_discount_period' => $discount_period
        );
        $where_data = array(
            'spf_stu_id' => $stu_id,
        );
        $this->db->where($where_data);
        $this->db->update(TABLE_PREFIX . 'student_payment_fee', $payment_info);
//        ==============End payment fee==============
//        ==============Payment fee============
        $this->db->from(TABLE_PREFIX . 'majors ma');
        $this->db->join(TABLE_PREFIX . 'major_fee maf', 'maf.maf_maj_id = ma.maj_id');
        $this->db->where('maf_academic_year', $acadamic);   // ==========Whare seleted academic year======= to be update
        $this->db->where('ma.maj_id', $major);  // ==========Whare seleted class===
        $this->db->where('maf.maf_deg_id', $degree);  // ==========Whare seleted class===
        $this->db->order_by('maf_year_number', 'asc');
        $fee_info = $this->db->get(); //=====Get school free for each year==========

        $fee = 0;
        $total_fee = array(0, 0, 0, 0);
        $payment_fee_data = array();
        if ($fee_info->num_rows() > 0) {
            $j = 0;
            foreach ($fee_info->result_array() as $row) {
                $fee = $row['maf_price'];
                if ($j <= $discount_period) {
                    $total_fee[$j] = $fee - ($fee * 0.01 * $discount_value);
                } else {
                    $total_fee[$j] = $fee;
                }
                $j++;
            }
        }
        $userid = $this->session->userdata('user');
        for ($i = 0; $i < 4; $i++) {
            $payment_fee_data[$i] = array(
                'sp_stu_id' => $stu_id,
                'sp_year' => $i + 1, ////// increate array index 0--->1 for year number
                'sp_fee' => $total_fee[$i],
                'sp_cla_id' => $class_id,
                'sp_balance' => $total_fee[$i],
                'tbl_users_id' => $userid['use_id']
            );
        }
        $this->db->update_batch(TABLE_PREFIX . 'student_payments', $payment_fee_data, 'sp_stu_id','sp_year');
//        ===========end peyment info==============
//        ========Fee for each year===============
        $acadamic = $data['tbl_generation_gen_id'];
        unset($data['tbl_generation_gen_id']);
        unset($data['shift']);
        unset($data['major']);
        $degree = $data['degree'];
        unset($data['degree']);
        $stucla_year_study = $data['stucla_year_study'];
        unset($data['stucla_year_study']);
//        if ($data['class'] = !"") {
//            $class_id = $data['class'];
//        }

        $this->db->where('stu_id', $stu_id);
        $this->db->update(TABLE_PREFIX . 'students', $data);
        //$this->db->set('gro_created', 'NOW()', false);
//        $this->db->insert(TABLE_PREFIX . 'students', $data);
//        $stu_id = $this->db->insert_id();
//        
//================Insert data to class of student
        $class_data = array(
            'stucla_stu_id' => $stu_id,
            'stucla_cla_id' => $class_id,
            'stucla_degree' => $degree,
            'stucla_year_study' => $stucla_year_study
        );
        $where_data = array(
            'stucla_stu_id' => $stu_id,
            'stucla_cla_id' => $class_id
        );
        $this->db->where($where_data);
        $this->db->update(TABLE_PREFIX . 'student_class', $class_data);

//        =========== End data to class of student==========

        $i = 0;
        foreach ($exp_date as $value) {
            $ext['exp_stu_id'] = $stu_id;
            $ext['exp_date'] = $exp_date[$i];
            $ext['exp_shift'] = $exp_shift[$i];
            $ext['exp_position'] = $exp_position[$i];
            $ext['exp_employer_tel'] = $exp_employer_tel[$i];
            $ext['exp_responsibility'] = $exp_responsibility[$i];
            $this->db->where('exp_stu_id', $stu_id);
            $this->db->update(TABLE_PREFIX . 'experiences', $ext);
            $ext = NULL;
        }
//        $this->db->set('gro_modified', 'NOW()', false);
        // if checkbox is not checked

        return true;
    }

    function upgradeClass() {
        $data = $this->input->post();
        $arraydata = array();
        for ($i = 0; $i < count($data['stu_id']); $i++) {
            $arraydata[$i] = array(
                'stucla_stu_id' => $data['stu_id'][$i],
                'stucla_year_study' => $data['stucla_year_study'][$i] + 1
            );
        }
        $result = $this->db->update_batch(TABLE_PREFIX . 'student_class', $arraydata, "stucla_stu_id");
        return TRUE;
    }

    function getStudentByClass($id = NULL) { ///// select student in a class to cound number of student
        $this->db->order_by('stu_card_id', 'desc');
        $this->db->limit(1);
        $this->db->select('stu_card_id');
        $this->db->from(TABLE_PREFIX . 'students s');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 's.stu_id = sc.stucla_stu_id');
        $this->db->join(TABLE_PREFIX . 'classes cl', 'cl.cla_id = sc.stucla_cla_id');
        $this->db->where('cl.cla_id', $id);
        return $this->db->get();
    }

    function getStudentById($id = NULL) {
        $this->db->from(TABLE_PREFIX . 'students s');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 's.stu_id = sc.stucla_stu_id');
        $this->db->join(TABLE_PREFIX . 'classes cl', 'cl.cla_id = sc.stucla_cla_id');
        $this->db->join(TABLE_PREFIX . 'majors ma', 'ma.maj_id = cl.cla_maj_id');
        $this->db->where('s.stu_id', $id);
        return $this->db->get();
    }

    function getPaymentById($id = NULL) {
        $this->db->from(TABLE_PREFIX . 'student_payment_fee spf');
//        $this->db->join(TABLE_PREFIX . 'student_class sc', 's.stu_id = sc.stucla_stu_id');
//        $this->db->join(TABLE_PREFIX . 'classes cl', 'cl.cla_id = sc.stucla_cla_id');
//        $this->db->join(TABLE_PREFIX . 'majors ma', 'ma.maj_id = cl.cla_maj_id');
        $this->db->where('spf_stu_id', $id);
        return $this->db->get();
    }

    function deleteStudentById($id = null) {
        $this->db->where('stu_id', $id);
        return $this->db->delete(TABLE_PREFIX . 'students');
    }

    // get major
    function getMajorByMasterId($id) {
        $this->db->where('maj_fac_id', $id);
        $this->db->from(TABLE_PREFIX . 'majors');
        $this->db->join(TABLE_PREFIX . 'faculties', 'fac_id=maj_fac_id');
        return $this->db->get();
    }

    function getFaculties() {

        return $this->db->get(TABLE_PREFIX . 'faculties');
    }

    function getClassById($shift = NULL, $generation = NULL, $major = NULL) {
        $array = array('tbl_shift_shi_id' => $shift, 'cla_maj_id' => $major, 'tbl_generation_gen_id' => $generation);
        $this->db->where($array);
        $this->db->select('cl.cla_id,cl.cla_name,count("sc.stucla_stu_id") as "studnetNumber"');
        $this->db->from(TABLE_PREFIX . 'classes cl');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 'cl.cla_id=sc.stucla_cla_id', 'left');
        $this->db->group_by("sc.stucla_cla_id");
        return $this->db->get();
    }

    function getAClassById($id = NULL) {
        $this->db->where('cla_id', $id);
        $this->db->join(TABLE_PREFIX . 'majors', 'cla_maj_id=maj_id');
        $this->db->join(TABLE_PREFIX . 'shift', 'tbl_shift_shi_id=shi_id');
        $this->db->from(TABLE_PREFIX . 'classes cl');
        return $this->db->get();
    }

    function getUdateClassById($classId = NULL, $studentId = NULL) {
        $array = array('tbl_shift_shi_id' => $studentId, 'stucla_cla_id' => $classId);
        $this->db->where($array);
        $this->db->select('cl.cla_id,cl.cla_name,count("sc.stucla_stu_id") as "studnetNumber"');
        $this->db->from(TABLE_PREFIX . 'classes cl');
        $this->db->join(TABLE_PREFIX . 'student_class sc', 'cl.cla_id=sc.stucla_cla_id', 'left');
        $this->db->group_by("sc.stucla_cla_id");
        return $this->db->get();
    }

    /**
     * Select query to be render to csv
     * $fields: 
     * @return array/mixed
     */
    public function exportcsv() {
        $fields = 'SELECT ' .
                'stu_card_id AS "ID Card",' .
                'CONCAT(stu_kh_firstname," ",stu_kh_lastname) AS "Khmer Name",' .
                'CONCAT(stu_en_firstname," ",stu_en_lastname) AS "EN Name",' .
                'stu_gender AS `Gander`,' .
                'stu_dob AS `Date of birth`,' .
                'stu_degree AS `Level`,' .
                'maj_name AS `Major`,' .
                'stu_tel AS `Phone`,' .
                'shi_name AS `Shift`,' .
                'stu_highschool_name AS `Hight School`,' .
                'stu_study_type AS `Study type`,' .
                'stu_descount AS `Percentage`,' .
                'stu_father_name AS `Father`,' .
                'stu_mother_name AS `Mother`,' .
                'stu_father_current_address AS `Address`,' .
                'CONCAT(stu_mother_tel,"/",stu_father_tel) AS "Parents Phone"';
//        $selectQuery = $this->db->select($fields);
//                ->join(TABLE_PREFIX . 'staff_position p', 'p.sta_pos_id = s.sta_position')
//                ->join(TABLE_PREFIX . 'staff_job_type j', 'j.sta_job_id = s.sta_job_type')
//                ->where('b.boo_id', 1);
        $query = str_replace('SELECT *', $fields, $this->session->userdata('lastQuery'));
//        $query = str_replace("e","oo","Hello");
        $result = $this->db->query($query);
//        $this->session->set_userdata('lastQuery2',  $query);  
//        return $this->db->get('user');
//        return $query;
        return $result;
    }

}
