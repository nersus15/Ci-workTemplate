<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */

	private $views = [];
	private $params = [];
	public function __construct()
	{
		self::$instance =& $this;
		if(IS_CORS_ACTIVE)
        	$_POST = json_decode(file_get_contents('php://input'), true); // ini untuk 

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}
	function setObject($nama, $value){
		$this->okasu= $value;
		self::$instance->okasu = $value;  
	}
	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	public function addViews($views, $params = null){
		if(is_array($views)){
			foreach($views as $v)
				$this->views[] = $v;
		}
		else
			$this->views[] = $views;
		if(!is_array($params))
			$this->params['params'] = $params;
		else{
			foreach($params as $k => $v){
				$this->params[$k] = $v;
			}
		}
			
		
	}
	function add_javascript($js){
		if(isset($js['pos'])){
			$this->params['extra_js'][] = $js;
		}else{
			foreach($js as $j){
				$this->params['extra_js'][] = $j;
			}
		}
	}
	function error_page($file, $params, $type = 'html'){
		$this->load->view('errors/' . $type . '/' . $file, $params);
	}
	function add_cachedJavascript($js, $type = 'file', $data = array()){
		if(!empty($data)){
			foreach($data as $k => $v){
				$this->params['var'][$k] = $v;
			}
		}
		$this->params['extra_js'][] = array(
			'script' => $type == 'file' ? file_get_contents(ASSETS_PATH . 'public/assets/' . $js) : $js,
			'type' => 'inline',
			'pos' => 'body:end'
		);
	}
	function add_cachedStylesheet($css, $type = 'file', $pos = 'head', $data = array()){
		if(!empty($data)){
			foreach($data as $k => $v){
				$this->params['var'][$k] = $v;
			}
		}
		$this->params['extra_css'][] = array(
			'style' => $type == 'file' ? file_get_contents(ASSETS_PATH . 'public/assets/' . $css) : $css,
			'type' => 'inline',
			'pos' => $pos
		);
	}
	function add_stylesheet($css){
		if(isset($css['pos'])){
			$this->params['extra_css'][] = $css;
		}else{
			foreach($css as $c){
				$this->params['extra_css'][] = $c;
			}
		}
	}
	public function render(){
		foreach($this->views as $view){
			$this->load->view($view, $this->params);
		}
		$this->views = [];
		$this->params = [];
	}


}
