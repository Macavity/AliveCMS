<?php
/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */
class ConfigEditor
{
	private $file;
	private $data;

	/**
	 * Initialize the config editor and load the file
	 * @param String $file
	 */
	public function __construct($file)
	{
		$this->file = $file;

		$handle = fopen($this->file, "r");
		$this->data = fread($handle, filesize($this->file));
		fclose($handle);
	}

	/**
	 * Change a config value
	 * @param String $key
	 * @param String $value
	 */
	public function set($key, $value)
	{
		// Create an array
		if(is_array($value))
		{
			$value = "array(".implode(",", $value).")";
		}

		// Create a boolean
		elseif(is_bool($value))
		{
			$value = ($value) ? "true" : "false";
		}

		// Create a boolean from string
		elseif(in_array($value, array("true", "false")))
		{
			$value = $value;
		}

		// Create an integer
		elseif(is_numeric($value))
		{
			$value = $value;
		}

		elseif(empty($value))
		{
			$value = "false";
		}

		// Create an array from a string of numbers separated by comma
		elseif(preg_match("/^([0-9]*,? ?)*$/", $value))
		{
			$value = "array(".$value.")";
		}

		// Create a string
		else
		{
			$value = "\"".addslashes($value)."\"";
		}
		
		// Check for sub array replacement
		if(preg_match("/.*-.*/", $key))
		{
			$parts = explode("-", $key);

			preg_match('/\$config\[["\']'.$parts[0].'["\']\] ?= [^;]*/', $this->data, $matches);

			if(count($matches))
			{
				$matches[0] = preg_replace('/^\$config\[["\']'.$parts[0].'["\']\] ?= /', "", $matches[0]);
				$matches[0] = preg_replace('/;$/', "", $matches[0]);

				$key = $parts[0];
				$value = preg_replace('/["\']'.$parts[1].'["\'] ?=> ?["\']?.*["\']?,?/', "'".$parts[1]."' => ".$value.",", $matches[0]);
			}
		}

		$this->data = preg_replace('/\$config\[["\']'.$key.'["\']\] ?= ?["\']?.*["\']?;/', "\$config['".$key."'] = ".$value.";", $this->data);
	}

	/**
	 * Save the edited config file
	 */
	public function save()
	{
		$file = fopen($this->file, "w");
		fwrite($file, $this->data);
		fclose($file);
	}

	/**
	 * Get the edited config content
	 * @return String
	 */
	public function get()
	{
		return $this->data;
	}
}