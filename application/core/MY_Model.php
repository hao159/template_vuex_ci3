<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

	protected $_collection;
	protected $_now;
	protected $_current_user;

	public function __construct()
	{
		parent::__construct();
		$this->_now = time();
		$this->_cur_user = $this->session->userdata('UserName');
	}

	public function addNew($data)
	{
		$data['createdBy'] = $this->_cur_user;
        $data['createdOn'] = $this->_now;
        $data['modifiedBy'] = $this->_cur_user;
        $data['modifiedOn'] = $this->_now;

        return $this->mongo_db->insert($this->_collection, $data);
	}

    public function addNew_many($data)
    {
        foreach ($data as $key => $value) {
            $value['createdBy'] = $this->_cur_user;
            $value['createdOn'] = $this->_now;
            $value['modifiedBy'] = $this->_cur_user;
            $value['modifiedOn'] = $this->_now;
        }
        return $this->mongo_db->insertAll($this->_collection, $data);
    }

	public function getAll($wheres = null, $orderBy = null, $fieldSelect = null)
	{
		if (empty($wheres) && empty($orderBy) && empty($fieldSelect)) {
            return $this->mongo_db
                            ->get($this->_collection);
        } else if (!empty($wheres) && empty($orderBy) && empty($fieldSelect)) {
            return $this->mongo_db
                            ->where($wheres)
                            ->get($this->_collection);
        } else if (empty($wheres) && !empty($orderBy) && empty($fieldSelect)) {
            return $this->mongo_db
                            ->order_by($orderBy)
                            ->get($this->_collection);
        } else if (empty($wheres) && empty($orderBy) && !empty($fieldSelect)) {
            return $this->mongo_db
                            ->select($fieldSelect)
                            ->get($this->_collection);
        } else if (!empty($wheres) && !empty($orderBy) && empty($fieldSelect)) {
            return $this->mongo_db
                            ->where($wheres)
                            ->order_by($orderBy)
                            ->get($this->_collection);
        } else if (!empty($wheres) && empty($orderBy) && !empty($fieldSelect)) {
            return $this->mongo_db
                            ->where($wheres)
                            ->select($fieldSelect)
                            ->get($this->_collection);
        } else if (empty($wheres) && !empty($orderBy) && !empty($fieldSelect)) {
            return $this->mongo_db
                            ->order_by($orderBy)
                            ->select($fieldSelect)
                            ->get($this->_collection);
        } else {
            return $this->mongo_db
                            ->where($wheres)
                            ->order_by($orderBy)
                            ->select($fieldSelect)
                            ->get($this->_collection);
        }
	}

	public function isValid($value)
    {
        if ($value instanceof MongoDB\BSON\ObjectID) {
            return true;
        }
        try {
            new MongoDB\BSON\ObjectID($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

	public function getOneByConditions($wheres, $fieldSelect = null)
	{
        if ($fieldSelect) {
            return $this->mongo_db->where($wheres)->select($fieldSelect)->getOne($this->_collection);
        }else{
		    return $this->mongo_db->where($wheres)->getOne($this->_collection);
        }
	}

	public function getOneById($id)
	{
		return $this->mongo_db
							->where(array('_id' => new MongoDB\BSON\ObjectId($id)))
							->getOne($this->_collection);
	}

	public function updateOne($data, $wheres)
	{
		$data['modifiedBy'] = $this->_cur_user;
		$data['modifiedOn'] = time();
		$data['modifiedOn_show'] = date(FormatDateTime::_YmdHis, time());

		$result = $this->mongo_db->where($wheres)->set($data)->update($this->_collection);

		return $result;
	}

	public function updateAll($data, $wheres)
	{
		$data['modifiedBy'] = $this->_cur_user;
        $data['modifiedOn'] = $this->_now;
        return $this->mongo_db->where($wheres)->set($data)->updateAll($this->_collection);
	}

	public function countByConditions($wheres = null)
	{
		if ($wheres) {
			return $this->mongo_db
								->where($wheres)
								->count($this->_collection);
		}else{
			return $this->mongo_db
								->count($this->_collection);
		}
	}

	public function delete($wheres)
	{
		if ($wheres and is_array($wheres)) {
			return $this->mongo_db
								->where($wheres)
								->delete_all($this->_collection);
		}else{
			return FALSE;
		}
	}

	public function checkExists($wheres)
	{
		if ($this->countByConditions($wheres) > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
     * get all paging from kendo request
     * Vẫn còn đang fix lỗi
     *
     * @param array $request
     * @param array $fieldSelect
     * 
     * @author hao.nguyen
     * @return dataSource for kendo
     */ 

	public function getAllPaging($request, $fieldSelect = null)
    {
    	//convert request
    	$wheres = array();
    	$sort = array();
        $return = array(
    		'total' => 0,
    		'data' => []
    	);

    	if (isset($request['filter'])) {
    		$this->convert($request['filter'], $wheres);
    	}
        // print_r($wheres);
    	//count dataa
        if ($wheres) {
    	   $return['total'] = $this->mongo_db->where($wheres)->count($this->_collection);
            
        }else{
            $return['total'] = $this->mongo_db->count($this->_collection);
        }

    	if (isset($request['sort'])) {
    		$sort = $this->sorting($request['sort'][0]);
    	}else{
            $sort = array('_id'=> 'desc');
        }
        //aggregate
        if(isset($request['aggregate']) and $request['aggregate']){
            $pipline = array(
                array(
                    '$match' => $wheres
                ),
                array(
                    '$group' => $this->maps_aggregate($request['aggregate'])
                )
            );
            $options = array(
                'cursor' => array(
                    'batchSize' => 0
                ),
                'allowDiskUse' => TRUE
            );
            
            $data_aggregate = $this->mongo_db->aggregate($this->_collection, $pipline, $options);
            if($data_aggregate){
                $return['aggregates'] = $this->render_aggregate($data_aggregate[0]);

            }else{
                #không có data aggregate
                foreach ($request['aggregate'] as $value) {
                    $return['aggregates'][$value['field']][$value['aggregate']] =  0 ;
                }
            }
        }

    	//select data
    	if ($fieldSelect) {
    		$this->mongo_db->select($fieldSelect);
    	}
        if ($wheres) {
            $this->mongo_db->where($wheres);
        }
        if(isset($request['take'])){
            $this->mongo_db->limit($request['take']);
        }
        if(!isset($request['skip'])){
            $request['skip'] = 0;
        }
    	$return['data'] = $this->mongo_db->order_by($sort)
    							->offset($request['skip'])
    							->get($this->_collection);
    	return $return;

    }

	//author: haonh1502@gmail.com

    private function maps_aggregate($aggregates)
    {

        // out_put
        // array(
        //      '_id' => null,
        //      'sum' => array(
        //          '$sum' => '$smscount',
        //      ),
        // )
        $func = array(
            'average' => '$avg',
            'min' => '$min',
            'max' => '$max',
            'count' => '$sum',
            'sum' => '$sum'
        );
        #khúc này bắt chước sếp Bình
        $return = array('_id' => null);
        foreach ($aggregates as $aggregate) {
            $return[$aggregate['field'].'___'.$aggregate['aggregate']] = array(
                $func[$aggregate['aggregate']] => ($aggregate['aggregate'] === 'count') ? 1 : '$'.$aggregate['field']
            );
            
        }
        return $return;
    }
    private function render_aggregate($data_aggregate)
    {
        $return = array();
        unset($data_aggregate['_id']);
        foreach ($data_aggregate as $key => $value) {
            $split = explode('___', $key);
            $return[$split[0]][$split[1]] = $value;
        }
        return $return;
    }
    
    /*
    Map filter operation kendoui -> where mongodb

    */
    public function maps($operator , $value = null)
    {
        $maps_simple = array(
            'eq' => '$eq',
            'neq' => '$ne',
            'gte' => '$gte',
            'gt' => '$gt',
            'lte' => '$lte',
            'lt' => '$lt',
            'in' => '$in',
            'nin' => '$nin',
            'isnotempty' => '$ne',
            'isempty' => '$eq',
        );
        ## đống này tạm thế
        $maps_exist = array(
            'isnull' => '$exists',
            'isnotnull' => '$exists',
            'isnullorempty' => '$exists',
            'isnotnullorempty' => '$exists',
        );

        $maps_regex = array(
            'startswith' => '$regex',
            'contains' => '$regex',
            'doesnotcontain' => '$regex',
            'endswith' => '$regex',
        );

        $maps_more = array(
            
        );
        $return = array();

        if (is_bool($value) or in_array($value, ['true', 'false'])) {
            $value = ($value == 'true' or (is_bool($value) and $value == true)) ? true:false;
            $value = boolval($value);  
        }elseif(is_numeric($value)){

            if(is_int($value)){
                $value = intval($value);   
            }else{
                $value = floatval($value);
            }
        }elseif (!is_array($value) and is_string($value)) { 
    	   $value = trim($value);
        }

        switch ($operator) {
        	#simple: $eq, $neq, $gte, $lt,...
            case in_array($operator, array_keys($maps_simple)):
                if (strpos($operator, 'empty')) {
                    $return =  array($maps_simple[$operator] => '');
                }else{
                    $return =  array($maps_simple[$operator] => $value);
                }
                break;

        	#exist:  null, not null, ...
            case in_array($operator, array_keys($maps_exist)):
                if (strpos($operator, 'not')) {
                    $tmp_exist = true;
                }else{
                    $tmp_exist = false;
                }
                $return = array($maps_exist[$operator] => $tmp_exist);
                break;
            #regex: startswith, containts, ....
            case in_array($operator, array_keys($maps_regex)):
                if ($operator == 'startswith') {
                    $tmp_regex = array(
                        '$regex' => new MongoDB\BSON\Regex('^'.$value),
                        '$options' => 'i'
                    );
                }elseif ($operator == 'contains') {
                    $tmp_regex = array(
                        '$regex' => new MongoDB\BSON\Regex($value),
                        '$options' => 'i'
                    );
                }elseif ($operator == 'doesnotcontain') {
                    $tmp_regex = array(
                            '$regex' => new MongoDB\BSON\Regex('^((?!'.$value.').)*$'),
                            '$options' => 'i'
                        );
                }elseif( $operator == 'endswith'){
                    $tmp_regex = array(
                        '$regex' =>  new MongoDB\BSON\Regex($value.'$'),
                        '$options' => 'i'
                    );
                }

                $return = $tmp_regex;
                break;
            #more in, nin kendo méo có thứ này ai rảnh thì viết thêm đi


        }
        return $return;
    }
    // conver 
	/*
	    "logic" => [
	        ['key' => 'value'],
	        [
	            ['logic' => 
	                [
	                    ['key' => 'value']
	                ]
	            ]
	        ]
	    ]
	*/


    public function convert($filter, &$retr)
    {
        // return [ 'logic' => ['key' => 'vale'] ]
        if (isset($filter['logic']) and isset($filter['filters'])) {
            foreach ($filter['filters'] as $sub) {
                if (isset($sub['filters'])) {
                    $this->convert($sub, $retr['$'.$filter['logic']][]) ;
                }else{
                    $retr['$'.$filter['logic']][] = [$sub['field'] => $this->maps($sub['operator'], $sub['value'])];
                }
            }
        }else if (isset($filter['filters'])) {
            foreach ($filter['filters'] as $sub) {
                $retr[] = [$sub['field'] => $this->maps($sub['operator'], $sub['value'])];
            }
        }
        
    }


    public function sorting($sorts = array())
    {
        return array($sorts['field'] => $sorts['dir']);
    }


    /**
     * Lấy địa chỉ ip của client
     */
    protected function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED']) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR']) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED']) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */