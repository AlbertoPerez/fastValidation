<?php
class Validate
{
private $valCount;
private $valArray;
private $log;
private $jscript;
private $innerjscript;
private $id;
private $jscriptValCount;

	function __construct()
	{	
		$this->valCount=0;
		$this->jscriptValCount=0;
		$this->valArray=array();
		$this->jscript="";
		$this->innerjscript="";
	}
	/*
	server side validation
	*/
	public function inputEmpty($name)
	{
	$this->valCount++;
		$inputString= $_POST[$name];
		$validateString=trim($inputString," ");
		if ($validateString=="")
		{
			$this->valArray[]=false;
			return false;
		}else
		{
			$this->valArray[]=true;
			return true;
		}
	$this->valCount++;
	}
	public function match($name,$name2)
	{
	$this->valCount++;
	$val1= $_POST[$name];
	$val1=trim($val1," ");
	
	$val2= $_POST[$name2];
	$val2=trim($val2," ");
	
	
	if(($val1!="") and ($val2!="") and ($_POST[$name]==$_POST[$name2]))
	{
	$this->valArray[]=true;
	return true;
	}
	else
	{
	$this->valArray[]=false;
	return false;
	}
	}
	/*beta*/
	public function mail($name)
	{
	$this->valCount++;
		if(filter_has_var ( INPUT_POST ,$name)) 
		{
			if(filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL))
			{
			$this->valArray[]=true;
			return true;
			}
			else
			{
			$this->valArray[]=false;
			return false;
			}	
		}
		else
		{
			return "error not found Post name :".$name;
		}
	}
	public function ipv4($name)
	{
	$this->valCount++;
		if(filter_has_var ( INPUT_POST ,$name)) 
		{
			if(filter_input(INPUT_POST, $name, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
			{
			$this->valArray[]=true;
			return true;
			}
			else
			{
			$this->valArray[]=false;	
			return false;
			}
		}
			else
		{
			return "error not found Post name :".$name;
		}
	}

	public function celphone($name)
	{
	$this->valCount++;
		if (preg_match('/^[0-9]{3,3}[-. ]?[0-9]{3,3}[-. ]?[0-9]{4,4}$/', $_POST[$name])) {
			$this->valArray[]=true;
			return true;
		}
		else
		{
			$this->valArray[]=false;	
			return false;
		}
	}


	public function telephone($name)
	{
	$this->valCount++;
		if (preg_match('/^[0-9]{2,2}[-. ]?[0-9]{2,2}[-. ]?[0-9]{2,2}[-. ]?[0-9]{2,2}$/', $_POST[$name])) {
			$this->valArray[]=true;
			return true;
		}
		else
		{
			$this->valArray[]=false;
			return false;
		}
	}

	public function postalCode($name)
	{
	$this->valCount++;
		if (preg_match('/^[0-9]{5,5}?$/', $_POST[$name])) {
			$this->valArray[]=true;
			
			return true;
		}
		else
		{
			$this->valArray[]=false;
			
			return false;
		}
	}

	public function dateFormat($name)
	{
	$this->valCount++;
	if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $_POST[$name])) {
		$this->valArray[]=true;
		
		return true;
	}else
	{	
		$this->valArray[]=false;
		
		return false;
	}
	}
	public function validateAll()
	{
		$trueValidates=0;
		$falseValidates=0;
		
		foreach($this->valArray as $cuenta)
		{
			if($cuenta)
			{
				
				$trueValidates++;
			}
			else
			{
				$falseValidates++;
			}
		}
		$arrayd["array_values"]=$this->valArray;
		$arrayd["count"]=count($this->valArray);
		$arrayd["Count_false"]=$falseValidates;
		$arrayd["Count_true"]=$trueValidates;
		$arrayd["valCount"]=$this->getValCount();
		$this->log=$arrayd;
		if(($trueValidates==$this->valCount) AND ($trueValidates==count($this->valArray)) AND ($falseValidates==0))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function getLog()
	{
	return $this->log;
	}
	public function getValCount()
	{
		return $this->valCount;
	}
	
	public function getJscript()
	{
	$this->startScript();
	$this->jscript.=$this->innerjscript;
	$this->endScript();
	return $this->jscript;
	}
	/*
	client side  validations
	*/
	public function setId($id)
	{
	$this->id=$id;
	}
	private function startScript()
	{
		$this->jscript.='<script type="text/javascript" > $("#'.$this->id.'").submit(function() {
		var trueValues=0;
		var falseValues=0;';
	}
	private function endScript()
	{
		$this->jscript.='if(trueValues=='.$this->jscriptValCount.')
		{
		return(true);
		}else
		{
		return(false);
		}';
		$this->jscript.="});</script>";
	}
	public function clientMail($name)
	{
		$regx='/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientIpv4($name)
	{
		$regx='/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientCelphone($name)
	{
		$regx='/^[0-9]{3,3}[-. ]?[0-9]{3,3}[-. ]?[0-9]{4,4}$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientTelephone($name)
	{
		$regx='/^[0-9]{2,2}[-. ]?[0-9]{2,2}[-. ]?[0-9]{2,2}[-. ]?[0-9]{2,2}$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientPostalCode($name)
	{
		$regx='/^[0-9]{5,5}?$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientDateFormat($name)
	{
		$regx='/^\d{4}\-\d{1,2}\-\d{1,2}$/';
		$this->jScriptPregMatch($name,$regx);
	}
	public function clientInputEmpty($name)
	{
	$this->jscriptValCount++;
		$this->innerjscript.='
		var val_'.$name.'=$("#'.$name.'").val();
		val_'.$name.'=$.trim(val_'.$name.');
		';
		$this->innerjscript.='if(val_'.$name.'!="")
		{
		'.$this->correctField($name).'
		trueValues++;
		}
		else
		{
		falseValues++;
		'.$this->errorField($name).'
		$("#'.$name.'").focus();
		}';
	}
	
	public function clientMatch($name,$name2)
	{
		$this->jscriptValCount++;
		$this->innerjscript.='
		var val_'.$name.'=$("#'.$name.'").val();
		val_'.$name.'=$.trim(val_'.$name.');
		
		var val_'.$name2.'=$("#'.$name2.'").val();
		val_'.$name2.'=$.trim(val_'.$name2.');
		';
		$this->innerjscript.='if((val_'.$name.'!="") && (val_'.$name2.'!=""))
		{
			if(val_'.$name.' == val_'.$name2.'){
			'.$this->correctField($name).'
			'.$this->correctField($name2).'
			trueValues++;
			}
			else
			{
			falseValues++;
			'.$this->errorField($name).'
			'.$this->errorField($name2).'
			$("#'.$name2.'").focus();
			}
		}
		else
		{
		falseValues++;
		'.$this->errorField($name).'
		'.$this->errorField($name2).'
		$("#'.$name2.'").focus();
		}';
	}
	// 
	private function jScriptPregMatch($name,$regx)
	{
	$this->jscriptValCount++;
		$this->innerjscript.='var regx_'.$name.'='.$regx.';
		var val_'.$name.'=$("#'.$name.'").val();
		';
		$this->innerjscript.='if(regx_'.$name.'.test(val_'.$name.'))
		{
		'.$this->correctField($name).'
		trueValues++;
		}
		else
		{
		falseValues++;
		'.$this->errorField($name).'
		$("#'.$name.'").focus();
		}';
	}
	private function errorField($name)
	{
		return('$("#'.$name.'").css( {"background":"#FFE5EA","border-color":"#B00"});');
	}
	private function correctField($name)
	{
		return('$("#'.$name.'").css( {"background":"#D3FFD5","border-color":"#73EC73"});');
	}
}
?>