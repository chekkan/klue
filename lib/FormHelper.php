<?php

class FormHelper {
	
	private $error_messages = array();
    private $is_horizontal;

    /**
     * @param Array $error_messages
     */
    public function __construct($error_messages=array()) {
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

        $return = "\n\r<form action=\"{$action}\" method=\"{$method}\" role=\"form\"";
        if (isset($params['class'])) {
            $return .= " class=\"{$params['class']}\"";
            if ($params['class'] == "form-horizontal") {
                $this->is_horizontal = true;
            }
        }
        $return .= ">";

        if (isset($params['heading'])) {
            $return .= "\n\r\t<h2>{$params['heading']}</h2>";
        }
		if(isset($this->error_messages['main'])) {
			$return .= "<p class=\"text-danger\">{$this->error_messages['main']}</p>";
		}
		return $return;
	}
	
	public function end($params="") {
        $valid_attributes = array("value", "name", "id");

        $return = "";
        if (is_array($params)) {
            // if $params contain at least one valid attribute
            if (count(array_unique(array_merge(array_keys($params), $valid_attributes))) < count($params) + count($valid_attributes)) {
                $return .= "\n\r\t<div class=\"form-group\">\n\r\t\t";
                if ($this->is_horizontal) {
                    $return .= "<div class=\"col-sm-offset-2 col-sm-10\">\n\r\t\t\t";
                }
                $return .= "<input type=\"submit\" class=\"btn btn-default\" ";
                if (isset($params['name'])) {
                    $return .= "name=\"{$params['name']}\" ";
                }
                if (isset($params['value'])) {
                    $return .= "value=\"{$params['value']}\" ";
                }
                if (isset($params['id'])) {
                    $return .= "id=\"{$params['id']}\" ";
                }
                $return .= "/>";
                if ($this->is_horizontal) {
                    $return .= "\n\r\t\t</div>";
                }
                $return .= "\n\r\t</div>";
            }
        } else if (!empty($params)) {
            $return .= "\n\r\t<div class=\"form-group\">\n\r\t\t";
            if ($this->is_horizontal) {
                $return .= "<div class=\"col-sm-offset-2 col-sm-10\">\n\r\t\t\t";
            }
            $return .= "<input type=\"submit\" class=\"btn btn-default\" value=\"{$params}\" />";
            if ($this->is_horizontal) {
                $return .= "\n\r\t\t</div>";
            }
            $return .= "\n\r\t</div>";
        }
        $return .= "\n\r</form>";
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

    private function input($params) {
        if(is_array($params)) {
            if(isset($params['type'])) {
                $type = $params['type'];
            }
        }
        else if (!empty($params)) {
            $type = $params;
        }

        $return = "\n\r\t<div class=\"form-group\">";

        if (isset($params['label'])) {
            $return .= "\n\r\t\t<label";
            if (isset($params['id'])) {
                $return .= " for=\"{$params['id']}\"";
            }
            $return .= " class=\"control-label";
            if ($this->is_horizontal) {
                $return .= " col-sm-2";
            }
            $return .= "\">";
            $return .= $params['label']."</label>";
        }

        $return .= "\n\r\t\t<div";
        if ($this->is_horizontal) {
            $return .= " class=\"col-sm-10\"";
        }
        $return .= ">";
        $return .= "\n\r\t\t\t<input type=\"{$type}\" class=\"form-control\"";
        if (isset($params['name'])) {
            $return .= " name=\"{$params['name']}\"";
        }
        if (isset($params['id'])) {
            $return .= " id=\"{$params['id']}\"";
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

        if(isset($params['name']) && isset($this->error_messages[$params['name']])) {
            $return .= "\n\r\t\t\t<p class=\"text-danger\">{$this->error_messages[$params['name']]}</p>";
        }

        $return .= "\n\r\t\t</div>\n\r\t</div>" ;

        return $return;
    }
	
	public function text($params="") {
        if (is_array($params)) {
            $dateParams = $params;
            $dateParams['type'] = "text";
        } else if (empty($params)) {
            $dateParams = "text";
        } else {
            $dateParams["name"] = $params;
            $dateParams["type"] = "text";
        }
        return $this->input($dateParams);
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
        if (is_array($params)) {
            $dateParams = $params;
            $dateParams['type'] = "email";
        } else if (empty($params)) {
            $dateParams = "email";
        } else {
            $dateParams["name"] = $params;
            $dateParams["type"] = "email";
        }
        return $this->input($dateParams);
	}
	
	public function password($params="") {
        if (is_array($params)) {
            $dateParams = $params;
            $dateParams['type'] = "password";
        } else if (empty($params)) {
            $dateParams = "password";
        } else {
            $dateParams["name"] = $params;
            $dateParams["type"] = "password";
        }
        return $this->input($dateParams);
	}
	
	public function date($params="") {
        if (is_array($params)) {
            $dateParams = $params;
            $dateParams['type'] = "date";
        } else if (empty($params)) {
            $dateParams = "date";
        } else {
            $dateParams["name"] = $params;
            $dateParams["type"] = "date";
        }
        return $this->input($dateParams);
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