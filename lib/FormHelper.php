<?php

class FormHelper {
	
	private $error_messages = array();
	
	public function __construct($error_messages="") {
		if(!empty($error_messages) && is_array($error_messages)) {
			$this->error_messages = $error_messages;
		}
	}
	
	public function start($params="") {
		if(is_array($params)) {
			if(isset($params['action'])) {
				$action = $params['action'];
			}
			if(isset($params['method'])) {
				$method = $params['method'];
			}
		}
		else {
			if(empty($params)) {
				$action = $_SERVER['PHP_SELF'];
			}
			else {
				$action = $params;
			}
			$method = "post";
		}
		if(!isset($method)) {
			$method = "post";
		}
		if(!isset($action)) {
			$action = $_SERVER['PHP_SELF'];
		}
		$return =<<<EOD
		<form action="{$action}" method="{$method}">
EOD;
		if(isset($this->error_messages['main'])) {
			$return .= "<p class=\"error\">{$this->error_messages['main']}</p>";
		}
		return $return;
	}
	
	public function end($params="") {
		if(is_array($params)) {
			if(isset($params['name'])) {
				$name = $params['name'];
			}
			if(isset($params['value'])) {
				$value = $params['value'];
			}
		}
		else {
			if($params=="") {
				$name = "submit";
				$value = "Submit";
			}
			else {
				$name = $params;
				$value = $params;
			}
		}
		$return =<<<EOD
			<div class="submit">
				<input type="submit" name="{$name}" value="{$value}" />
			</div>
		</form>
EOD;
		return $return;
	}
	
	public function select($record, $params="") {
		if(isset($record) && is_array($record)) {
			if(is_array($params)) {
				if(isset($params['name'])) {
					$name = $params['name'];
				}
				if(isset($params['id'])) {
					$id = $params['id'];
				}
				if(isset($params['label'])) {
					$label = $params['label'];
				}
			}
			else {
				if(!empty($params)) {
					$name = $params;
				}
			}
		}
		if(!isset($name)) {
			$name = "default";
		}
		if(!isset($id)) {
			$id = $name;
		}
		if(!isset($label)) {
			$label = $name;
		}
		$return = "<div class=\"select\">";
		$return .= "<label for=\"{$id}\">{$label}</label>";
		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .= "<select name=\"{$name}\" id=\"{$id}\">";
		foreach ($record as $key => $value) {
			$return .= "<option value=\"{$key}\">{$value}</option>";
		}
		$return .= "</select>";
		$return .= "</div>";
		
		return $return;
	}
	
	public function text($params="") {
		if(is_array($params)) {
			if(isset($params['id'])) {
				$id = $params['id'];
			}
			if(isset($params['label'])) {
				$label = $params['label'];
			}
			if(isset($params['name'])) {
				$name = $params['name'];
			}
		}
		else {
			if(empty($params)) {
				$id = "default";
				$label = "Default";
				$name = "default";
			}
			else {
				$name = $params;
				$label = $params;
				$id = $params;
			}
		}
		// var $value
		if(isset($_POST[$name])) {
			$value = $_POST[$name];
		}
		else if(isset($params['value'])) {
			$value = $params['value'];
		}
		else {
			$value = "";
		}
		$return =<<<EOD
		<div class="text">
			<label for="{$id}">{$label}</label>
EOD;
		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .=<<<EOD
			<input type="text" name="{$name}" id="{$id}" value="{$value}" />
		</div>
EOD;
		return $return;
	}
	
	public function email($params="") {
		if(is_array($params)) {
			if(isset($params['id'])) {
				$id = $params['id'];
			}
			if(isset($params['label'])) {
				$label = $params['label'];
			}
			if(isset($params['name'])) {
				$name = $params['name'];
			}
		}
		else {
			if(empty($params)) {
				$id = "default";
				$label = "Default";
				$name = "default";
			}
			else {
				$name = $params;
				$label = $params;
				$id = $params;
			}
		}
		// var $value
		if(isset($_POST[$name])) {
			$value = $_POST[$name];
		}
		else if(isset($params['value'])) {
			$value = $params['value'];
		}
		else {
			$value = "";
		}
		
		$return =<<<EOD
		<div class="email">
			<label for="{$id}">{$label}</label>
EOD;
		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .=<<<EOD
			<input type="email" name="{$name}" id="{$id}" value="{$value}" />
		</div>
EOD;
		return $return;
	}
	
	public function password($params="") {
		if(is_array($params)) {
			if(isset($params['id'])) {
				$id = $params['id'];
			}
			if(isset($params['label'])) {
				$label = $params['label'];
			}
			if(isset($params['name'])) {
				$name = $params['name'];
			}
		}
		else {
			if(empty($params)) {
				$id = "default";
				$label = "Default";
				$name = "default";
			}
			else {
				$name = $params;
				$label = $params;
				$id = $params;
			}
		}
		$return =<<<EOD
		<div class="password">
			<label for="{$id}">{$label}</label>
EOD;
		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .=<<<EOD
			<input type="password" name="{$name}" id="{$id}" />
		</div>
EOD;
		return $return;
	}
	
	public function date($params="") {
		if(is_array($params)) {
			if(isset($params['id'])) {
				$id = $params['id'];
			}
			if(isset($params['label'])) {
				$label = $params['label'];
			}
			if(isset($params['name'])) {
				$name = $params['name'];
			}
		}
		else {
			if(empty($params)) {
				$id = "default";
				$label = "Default";
				$name = "default";
			}
			else {
				$name = $params;
				$label = $params;
				$id = $params;
			}
		}
		$return =<<<EOD
		<div class="date">
			<label for="{$id}">{$label}</label>
EOD;
		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .=<<<EOD
			<input type="date" name="{$name}" id="{$id}" />
		</div>
EOD;
		return $return;
	}
	
}

?>