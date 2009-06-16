<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * FFM Hierarchy Class
 *
 * @package   FF Matrix Expansion Pack
 * @author    Brandon Kelly <me@brandon-kelly.com>
 * @copyright Copyright (c) 2009 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */
class Ffm_hierarchy extends Fieldframe_Fieldtype {

	var $info = array(
		'name'     => 'FFM Hierarchy',
		'version'  => '1.0.0',
		'desc'     => 'Adds hierarchical nesting to your FF Matrix fields',
		'docs_url' => 'http://brandon-kelly.com/apps/ffm-pack/docs/hierarchy'
	);

	/**
	 * Display Cell
	 * 
	 * @param  string  $cell_name      The cell's name
	 * @param  mixed   $cell_data      The cell's current value
	 * @param  array   $cell_settings  The cell's settings
	 * @return string  The cell's HTML
	 */
	function display_cell($cell_name, $cell_data, $cell_settings)
	{
		$this->include_css('styles/ffm_hierarchy.css');
		$this->include_js('scripts/jquery.ffm_hierarchy.js');
		$this->insert_js('PATH_CP_IMG="'.PATH_CP_IMG.'";');

		$indent = $cell_data === '' ? '-1' : $cell_data;

		return '<a class="outdent"></a><a class="indent"></a>'
		     . '<input type="hidden" name="'.$cell_name.'" value="'.$indent.'"/>';
	}

}

/* End of file ft.ffm_hierarchy.php */
/* Location: ./system/fieldtypes/ffm_hierarchy/ft.ffm_hierarchy.php */