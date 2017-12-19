<?php

namespace Ubiquity\controllers\rest;

use Ubiquity\utils\StrUtils;

class ResponseFormatter {

	/**
	 * @param array $datas
	 * @return array
	 */
	public function get($datas){
		return $this->format(["datas"=>$datas,"count"=>\sizeof($datas)]);
	}

	public function getOne($datas){
		return $this->format(["data"=>$datas]);
	}

	/**
	 * Formats a response array
	 * @param array $arrayResponse
	 * @return string
	 */
	public function format($arrayResponse){
		return \json_encode($arrayResponse);
	}

	public function getModel($controllerName){
		$array=\explode("\\", $controllerName);
		$result= \ucfirst(end($array));
		if(StrUtils::endswith($result, "s")){
			$result=\substr($result, 0,-1);
		}
		return $result;
	}

	public function toJson($data){
		return \json_encode($data);
	}

	public function formatException($e){
		return $this->format(["status"=>"error","message"=>\utf8_encode($e->getMessage()),"code"=>@$e->getCode()]);
	}

	public static function toXML($data,&$xml_data){
		foreach( $data as $key => $value ) {
			if( is_numeric($key) ){
				$key = 'item'.$key; //dealing with <0/>..<n/> issues
			}
			if( is_array($value) ) {
				$subnode = $xml_data->addChild($key);
				array_to_xml($value, $subnode);
			} else {
				$xml_data->addChild("$key",htmlspecialchars("$value"));
			}
		}
	}
}
