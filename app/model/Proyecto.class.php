<?php

class Proyecto extends TRecord
{
	const TABLENAME = 'proyecto';
	const PRIMARYKEY = 'id';
	const IDPOLICY = 'serial'; // {max, serial}

	/**
	* Constructor method
	*/
	public function __construct($id = NULL, $callObjectLoad = TRUE)
	{
		parent::__construct($id, $callObjectLoad);

		parent::addAttribute('nombre');
		parent::addAttribute('descripcion');
	}
}