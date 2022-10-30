<?php

class ExpedienteLink extends TRecord
{
	const TABLENAME = 'expediente_link';
	const PRIMARYKEY = 'id';
	const IDPOLICY = 'serial'; // {max, serial}

	use SystemChangeLogTrait;
	

	/**
	* Constructor method
	*/
	public function __construct($id = NULL)
	{
		parent::__construct($id);
		parent::addAttribute('source');
		parent::addAttribute('target');
		parent::addAttribute('type');
		parent::addAttribute('id_proyecto');
	}
}
