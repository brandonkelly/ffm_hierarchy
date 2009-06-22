<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * FFM Shoutout Class
 *
 * @package   FF Matrix Expansion Pack
 * @author    Brandon Kelly <me@brandon-kelly.com>
 * @copyright Copyright (c) 2009 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */
class Ffm_shoutout extends Fieldframe_Fieldtype {

	var $info = array(
		'name'     => 'FFM Shoutout',
		'version'  => '0.0.4',
		'desc'     => 'Allows access to FF Matrix rows from within other fields',
		'docs_url' => 'http://brandon-kelly.com/apps/ffm-pack/docs/shoutout'
	);

	var $hooks = array(
		'ff_matrix_tag_field_data',
		'weblog_entries_row'
	);

	var $default_cell_settings = array(
		'row_template' => ''
	);

	var $search = array();
	var $replace = array();

	/**
	 * Display Cell Settings
	 * 
	 * @param  array  $cell_settings  The cell's settings
	 * @return array  Settings HTML
	 */
	function display_cell_settings($cell_settings)
	{
		global $DSP, $LANG;

		$r = '<label class="itemWrapper">'
		   .   $DSP->qdiv('defaultBold', $LANG->line('row_template_label'))
		   .   $DSP->input_textarea('row_template', $cell_settings['row_template'], '10', 'textarea')
		   . '</label>';

		return $r;
	}

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
		global $DSP;

		return $DSP->input_text($cell_name, $cell_data, '', '', 'text', '140px');
	}

	/**
	 * FF Matrix Tag Field Data hook
	 *
	 * Check to see if this matrix includes an FFM Shoutout cell,
	 * and if so, return the restructured field data
	 *
	 * @param  string  $field_data      Currently saved FF Matrix field value
	 * @param  array   $field_settings  The FF Matrix field's settings
	 * @return string  Modified $field_data
	 */
	function ff_matrix_tag_field_data($field_data, $field_settings)
	{
		global $FF, $FFM;

		$field_data = $this->get_last_call($field_data);

		// backup FFM vars
		$FFM_params = $FFM->params;
		$FFM_tagdata = $FFM->tagdata;
		$FFM_field_settings = $FFM->field_settings;

		foreach($field_settings['cols'] as $col_id => &$col)
		{
			if ($col['type'] == 'ffm_shoutout')
			{
				foreach($field_data as $row_num => &$row)
				{
					if ($shoutout = $row[$col_id])
					{
						$this->search[] = '{'.$shoutout.'}';
						$this->replace[] = '<<'.$FFM->display_tag($FFM->default_tag_params, $col['settings']['row_template'], array($row), $field_settings, FALSE).'>>';
					}
				}

				break;
			}
		}

		// restore FFM vars
		$FFM->params = $FFM_params;
		$FFM->tagdata = $FFM_tagdata;
		$FFM->field_settings = $FFM_field_settings;

		return $field_data;
	}

	/**
	 * Weblog - Entries - Row hook
	 */
	function weblog_entries_row($Weblog, $row)
	{
		$row = $this->get_last_call($row);

		foreach($row as $field_id => &$field_data)
		{
			if (substr($field_id, 0, 9) == 'field_id_')
			{
				$field_data = str_replace($this->search, $this->replace, $field_data, $count);
			}
		}

		return $row;
	}

}

/* End of file ft.ffm_shoutout.php */
/* Location: ./system/fieldtypes/ffm_shoutout/ft.ffm_shoutout.php */