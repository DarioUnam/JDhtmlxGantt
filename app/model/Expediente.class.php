<?php

class Expediente extends TRecord
{
	const TABLENAME = 'expediente';
	const PRIMARYKEY = 'id';
	const IDPOLICY = 'serial'; // {max, serial}

	/**
	* Constructor method
	*/
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		parent::addAttribute('titulo');
		parent::addAttribute('fecha');
		parent::addAttribute('tipo');
		parent::addAttribute('fecha_limite');
		parent::addAttribute('id_proyecto');
		parent::addAttribute('deleted');
		parent::addAttribute('parent');
		parent::addAttribute('duration');
		parent::addAttribute('task_gantt_data');
		parent::addAttribute('field_order');
		parent::addAttribute('progress');
		parent::addAttribute('grupo');
		parent::addAttribute('drag_resize');
		parent::addAttribute('drag_move');
		parent::addAttribute('estado');
		parent::addAttribute('monto');		
	}
}