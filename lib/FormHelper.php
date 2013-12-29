<?php

class FormHelper {
	
	private $error_messages = array();
    private $is_horizontal;
	
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
		}
		if(!isset($method)) {
			$method = "post";
		}
		if(!isset($action)) {
			$action = $_SERVER['PHP_SELF'];
		}

        $return = "<form action=\"{$action}\" method=\"{$method}\" role=\"form\"";
        if (isset($params['class'])) {
            $return .= " class=\"{$params['class']}\"";
            if ($params['class'] == "form-horizontal") {
                $this->is_horizontal = true;
            }
        }
        $return .= ">";

        if (isset($params['heading'])) {
            $return .= "<h2>{$params['heading']}</h2>";
        }
		if(isset($this->error_messages['main'])) {
			$return .= "<p class=\"text-danger\">{$this->error_messages['main']}</p>";
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
				$name = lcfirst(strtolower($params));
				$value = $params;
			}
		}

		$return = "<div class=\"form-group\">";
        if ($this->is_horizontal) {
            $return .= "<div class=\"col-sm-offset-2 col-sm-10\">";
        }
		$return .= "<input type=\"submit\" name=\"{$name}\" value=\"{$value}\" class=\"btn btn-default\" />";
        if ($this->is_horizontal) {
            $return .= "</div>";
        }
		$return .="</div>
		        </form>";

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
			if(isset($params['name'])) {
				$name = $params['name'];
			}
		}
		else if (!empty($params)) {
            $name = $params;
        }

        if (!isset($id)) {
            // check if name is given
            if (!isset($name)) {

            } else {
                $id = ucfirst(strtolower($name));
            }
        }

        if (!isset($name)) {
            if (!isset($id)) {
            } else {
                $name = strtolower($id);
            }
        }

		$return = "<div class=\"form-group\">";

        if (isset($params['label'])) {
        $return .= "<label for=\"{$id}\" class=\"control-label";
            if ($this->is_horizontal) {
                $return .= " col-sm-2";
            }
            $return .= "\">";
            $return .= $params['label']."</label>";
        }

		$return .= "\n\r\t<div";
        if ($this->is_horizontal) {
            $return .= " class=\"col-sm-10\"";
        }
        $return .= ">";
        $return .= "\n\r\t\t<input type=\"text\" class=\"form-control\"";
        if (isset($name)) {
            $return .= " name=\"{$name}\"";
        }
        if (isset($id)) {
            $return .= " id=\"{$id}\"";
        }

        if (isset($params['value'])) {
            $return .= " value=\"{$params['value']}\"";
        } else if ( isset($name) && isset($_POST[$name])) {
            $return .= " value=\"{$_POST[$name]}\"";
        }

        if (isset($params['placeholder'])) {
            $return .= " placeholder=\"{$params['placeholder']}\"";
        }

        $return .= " />";

        if(isset($name) && isset($this->error_messages[$name])) {
            $return .= "<p class=\"text-danger\">{$this->error_messages[$name]}</p>";
        }

        $return .= "\n\r\t</div>\n\r</div>" ;

		return $return;
	}

    /**
     * @param string|array $params
     * if $params is string, defaults to name attribute
     * @return string
     *
     */
    public function textarea($params="") {
        if (is_array($params)) {
			if (isset($params['id'])) {
				$id = $params['id'];
			}
			if (isset($params['name'])) {
				$name = $params['name'];
			}
		} else if (!empty($params)) {
            $name = strtolower($params);
        }

		if (!isset($id)) {
            // check if name is given
            if (!isset($name)) {
                $name = "default";
            } else {
                $id = ucfirst(strtolower($name));
            }
		}

        if (!isset($name)) {
            if (!isset($id)) {
                $name = "default";
            } else {
                $name = strtolower($id);
            }
        }
		
		$return = "<div class=\"form-group\">";
        if (isset($params['label'])) {
            $return .= "<label for=\"{$id}\" class=\"control-label";
            if ($this->is_horizontal) {
                $return .= " col-sm-2";
            }
            $return .= "\">";
            $return .= $params['label']."</label>";
        }

        $return .= "<div";
        if ($this->is_horizontal) {
            $return .= " class=\"col-sm-10\"";
        }
        $return .= ">";

        $return .="<textarea name=\"{$name}\" class=\"form-control\"";
        if (isset($params['placeholder'])) {
            $return .= " placeholder=\"{$params['placeholder']}\"";
        }
        if (isset($id)) {
            $return .= " id=\"{$id}\"";
        }
        $return .= " >";
        if (isset($_POST[$name])) {
            $return .= $_POST[$name];
        }
        $return .="</textarea>";
		if (isset($this->error_messages[$name])) { 
			$return .= "<p class=\"text-danger\">{$this->error_messages[$name]}</p>";
		}
		$return .= "</div>
		           </div>";
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
        if (is_array($params)) {
            if (isset($params['id'])) {
                $id = $params['id'];
            }
            if (isset($params['name'])) {
                $name = $params['name'];
            }
        } else if (!empty($params)) {
            $name = strtolower($params);
        }

        if (!isset($id)) {
            // check if name is given
            if (!isset($name)) {
                $name = "default";
            } else {
                $id = ucfirst(strtolower($name));
            }
        }

        if (!isset($name)) {
            if (!isset($id)) {
                $name = "default";
            } else {
                $name = strtolower($id);
            }
        }

		$return = "<div class=\"form-group\">";
        if (isset($params['label'])) {
            $return .= "<label class=\"control-label";
            if ($this->is_horizontal) {
                $return .= " col-sm-2";
            }
            if (isset($id)) {
                $return .= "\" for=\"{$id}\"";
            }
            $return .= ">{$params['label']}</label>";
        }

		if(isset($this->error_messages[$name])) {
			$return .= "<p class=\"error\">{$this->error_messages[$name]}</p>";
		}
		$return .= "<div";
        if ($this->is_horizontal) {
            $return .= " class=\"col-sm-10\"";
        }
        $return .= ">";
        $return .= "<input type=\"date\" name=\"{$name}\" class=\"form-control\" ";
        if (isset($id)) {
            $return .= "id=\"{$id}\"";
        }
        $return .= "/>";

        $return .= "</div>";

	    $return .= "</div>";
		return $return;
	}

    public function checkbox($params="") {
        $return = "<div class=\"form-group\">
            <div class=\"col-sm-offset-2 col-sm-10\">
                <div class=\"checkbox\">
                    <label><input type=\"checkbox\" name=\"draft\"";
        if (isset($_POST['draft'])) {
            $return .= " checked=\"checked\"";
        }
        $return .= ">Draft</label>
                </div>
            </div>
        </div>";
        return $return;
    }
}

?>