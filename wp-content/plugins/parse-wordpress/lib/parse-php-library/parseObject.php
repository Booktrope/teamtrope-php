<?php

class parseObject extends parseRestClient{
	public $_includes = array();
	private $_className = '';
	private $_id = '';

	public function __construct($class='', $id=''){
		if($class != ''){
			$this->_className = $class;
		}
		else{
			$this->throwError('include the className when creating a parseObject');
		}

		if($id !='')
		{
			$this->_id = $id;
		}

		parent::__construct();
	}

	public function __set($name,$value)
	{
		if($name == '_className') { return; }
		if($name === 'objectId') { $this->_id = $value; }
		$this->data[$name] = $value;
	}
	
	public function __get($name)
	{
		if(array_key_exists($name, $this->data))
		{
			return $this->data[$name];
		}
		else
		{
			return "";
		}
	}

	public function save(){
		if(count($this->data) > 0 && $this->_className != ''){
			$request = $this->request(array(
				'method' => 'POST',
				'requestUrl' => 'classes/'.$this->_className,
				'data' => $this->data,
			));
			if(isset($request->objectId))
			{
				$this->_id = $request->objectId;
			}			
			return $request;
		}
	}

	public function get($id){
		if($this->_className != '' || !empty($id)){
			$request = $this->request(array(
				'method' => 'GET',
				'requestUrl' => 'classes/'.$this->_className.'/'.$id
			));
			
			if(!empty($this->_includes)){
				$request['include'] = implode(',', $this->_includes);
			}
			
			return $request;
		}
	}
	
	public function objectId()
	{
		if($this->_id === 'none' && isset($this->data["objectId"]))
		{
			$this->_id = $this->data["objectId"];
		}
		return $this->_id;
	}
	
	public function update_object()
	{
		if($this->_id === '')
		{
			return $this->save();
		}
		if($this->_className != ''){
			$request = $this->request(array(
				'method' => 'PUT',
				'requestUrl' => 'classes/'.$this->_className.'/'.$this->_id,
				'data' => $this->data,
			));

			return $request;
		}
	}	

	public function update($id){
		if($this->_className != '' || !empty($id)){
			$request = $this->request(array(
				'method' => 'PUT',
				'requestUrl' => 'classes/'.$this->_className.'/'.$id,
				'data' => $this->data,
			));

			return $request;
		}
	}

	public function increment($field,$amount){
		$this->data[$field] = $this->dataType('increment', $amount);
	}

	public function decrement($id){
		$this->data[$field] = $this->dataType('decrement', $amount);
	}


	public function delete($id){
		if($this->_className != '' || !empty($id)){
			$request = $this->request(array(
				'method' => 'DELETE',
				'requestUrl' => 'classes/'.$this->_className.'/'.$id
			));

			return $request;
		}		
	}

	public function addInclude($name){
		$this->_includes[] = $name;
	}
}

?>