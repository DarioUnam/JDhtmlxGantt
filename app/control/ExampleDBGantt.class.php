<?php

class ExampleDBGantt extends TPage
{
	protected $loaded;

	private $form;      // search form
	private $gantt;
	private $pageNavigation;
	private $row;
	private $database;

	/**
	* erci sinos
	* Class constructor
	* Creates the page, the search form and the listing
	*/
	public function __construct($param)
	{
		parent::__construct($param);

		$this->database = 'database';

		$method = isset($param['method'])?$param['method']:'';
		TSession::setValue('params_'.__CLASS__.'_'.$method,$_GET);

		$this->form = new BootstrapFormBuilder('form_search_'.__CLASS__);

		$id_proyecto = new TDBUniqueSearch('id_proyecto',$this->database,'Proyecto','id','nombre','nombre');
		$id_proyecto->setMinLength(3);
		$id_proyecto->setSize('100%');
		$id_proyecto->setChangeAction( new TAction( array($this,'onProyectoChange' )) );

		$tasks = new THidden('tasks');

		$id_proyecto->addValidation('Proyecto',new TRequiredValidator);

		$row   = $this->form->addFields( [new TLabel('Proyecto'),$id_proyecto]);
		$row   = $this->form->addFields( [$tasks]);
		$row->layout = ['col-sm-12'];

		// add form actions
		$save = $this->form->addAction(_t('Save'), new TAction(array($this,'onSave')), 'fa:save');
		$clear = $this->form->addAction(_t('Clear'), new TAction(array($this,'clear')), 'fas:eraser red');
		$reload = $this->form->addAction('Recargar Gantt',new TAction(array($this,'reloadGantt')),'fas:redo');
		
		$save->class = 'btn btn-primary';
		$clear->onclick = 'onClear();return false;';

		$this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
		
		$action = new TAction( ['TimeLineActividad', 'onLoad' ] );
        $action->setParameter('id', '+task.object.id+');
        $action->setParameter('key', '+task.object.id+');
        $b2 = new TActionLink('', $action, 'white', null, '', 'fas:eye');
        $b2->style = 'padding: 5px;opacity: 0.7;';
        
		$this->gantt = new JDhtmlxGantt('100%','500px');
		$this->gantt->setInput($tasks);
		$this->gantt->setName(__CLASS__);
		$this->gantt->setButtonSave($save);
		$this->gantt->addActionLink($b2,"object.grupo == 'Actividad'");
		$this->gantt->setThemeGantt('skyblue');
		$this->gantt->setColumnData('task_gantt_data');
		$this->gantt->setColumnOrder('field_order');
		$this->gantt->setColumnGroup('grupo',['Gantt','Actividad']);
		$this->gantt->setButtonsWidth(135);
		$this->gantt->zoomSetLevel('year');

		$this->gantt->setColumnId('id');
		$this->gantt->setColumnParent('parent');
		$this->gantt->setColumnText('titulo');
		$this->gantt->setColumnStartDate('fecha');
		$this->gantt->setColumnEndDate('fecha_limite');
		$this->gantt->setColumnDuration('duration');
		$this->gantt->setColumnType('tipo');
		$this->gantt->setColumnProgress('progress');
		$this->gantt->setColumnDragResize('drag_resize');
		$this->gantt->setColumnDragMove('drag_move');
		$this->gantt->setColumnDeleted('deleted',1,0);
		$this->gantt->setCustomColumn('monto','monto','', array('label'=>'Costo','align'=>'right','width'=> 75));

		$this->gantt->setLinkId('id');
		$this->gantt->setLinkSource('source');
		$this->gantt->setLinkTarget('target');
		$this->gantt->setLinkType('type');
		
		$this->gantt->addObjectField('grupo');
		$this->gantt->addObjectField('id');

		$vbox = new TVBox;
		$vbox->style = 'width: 100%';
		//$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
		$vbox->add($this->form);
		$vbox->add($this->gantt);

		parent::add($vbox);
	}

	public static function onProyectoChange($param = NULL)
	{
		try
		{
			TTransaction::open('database');

			if(!empty($param['id_proyecto']))
			{
				$data = new StdClass;
				$data->id_proyecto = $param['id_proyecto'];
				TSession::setValue(__CLASS__.'_filter_data',$data);

				TScript::create("Adianti.waitMessage = 'Cargando';");
				TApplication::postData('form_search_'.__CLASS__,__CLASS__,'onReload');
			}
			else
			{
				JDhtmlxGantt::clearGantt();
			}

			TTransaction::close();
		}
		catch(Exception $e)
		{
			new TMessage('error', $e->getMessage());
			TTransaction::rollback();
		}
	}

	public function onSave($param = NULL)
	{
		try
		{

			TTransaction::open($this->database);

			$this->form->validate();

			$data = $this->form->getData();

			$this->gantt->saveJsonTask($data,$data->id_proyecto,'Expediente','id_proyecto','ExpedienteLink','id_proyecto');

			TSession::setValue(__CLASS__.'_filter_data',$data);

			TTransaction::close();

			$this->onReload($param);

			new TMessage('info', _t('Record saved'));

		}
		catch(Exception $e)
		{
			new TMessage('error', $e->getMessage());
			TTransaction::rollback();
		}
	}

	public function onReload($param = NULL)
	{
		try
		{
			$data = TSession::getValue(__CLASS__.'_filter_data');

			if(isset($data->id_proyecto))
			{
				$id             = $data->id_proyecto;


				TTransaction::open($this->database);


				$column_deleted = $this->gantt->getColumnDeleted();

				$type           = $this->gantt->getColumnType()[0];

				$criteria_one   = new TCriteria;
				$criteria_one->add(new TFilter($type, '=', 'project'),TExpression::OR_OPERATOR);
				$criteria_one->add(new TFilter($type, '=', 'milestone'),TExpression::OR_OPERATOR);
				$criteria_one->add(new TFilter($type, '=', 'task'),TExpression::OR_OPERATOR);


				$criteria_two   = new TCriteria;
				$criteria_two->add(new TFilter('id_proyecto', '=', $id));
				$criteria_two->add(new TFilter('deleted', '=', '0'));

				$criteria       = new TCriteria;
				$criteria->add($criteria_one);
				$criteria->add($criteria_two);

				$criteria->setProperty('order','field_order');
				$criteria->setProperty('direction','asc');

				$repository     = new TRepository('Expediente');
				$tasks          = $repository->load($criteria);


				$criteria       = new TCriteria;
				$criteria->add(new TFilter('id_proyecto', '=', $id));
				$repository     = new TRepository('ExpedienteLink');
				$links          = $repository->load($criteria);


				$this->gantt->clear();
				$this->gantt->addTasks($tasks);
				$this->gantt->addLinks($links);


				TTransaction::close();

				$this->loaded = true;


			}

		}
		catch(Exception $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}

	public static function clear($param)
	{
		TScript::create('clearGantt();');
	}

	public static function reloadGantt($param)
	{
		TScript::create("Adianti.waitMessage = 'Cargando';");
		TApplication::postData('form_search_'.__CLASS__,__CLASS__,'onReload');
	}

	public function show()
	{
		// check if the gantt is already loaded
		if(!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
		{

			if(func_num_args() > 0)
			{

				$this->onReload( func_get_arg(0) );

			}
			else
			{

				$this->onReload();

			}
		}
		parent::show();
	}
}