<?php

class JDhtmlxGantt
{
	protected $columns;
	private $tasks;
	protected $format;
	protected $cols;
	protected $cell;

	protected $properties ;
	private $action ;
	private $input ;
	private $action_link ;
	private $field_order ;
	private $buttons_width ;
	public $cols_field;

	public $field_object;

	/**
	* Class Constructor
	*/
	public function __construct($width = '100%', $height = '100%')
	{
		$this->id = 'tdhtmlgantt_' . uniqid();

		$this->tasks = array();
		$this->links = array();
		$this->cols_field = array();

		//$this->cols_field['FIELD GANTT'] = array('FIELD RECORD','DEFAULT');

		$this->action = NULL;
		$this->input = NULL;
		$this->action_link = array();

		$this->name = $this->id;
		$this->width = $width;
		$this->height = $height;

		$this->zoomSetLevel = 'day';
		$this->plugin_quick_info = 'false';
		$this->plugin_tooltip = 'true';
		$this->plugin_marker = 'true';

		$this->theme_gantt = 'dhtmlxgantt';
		$this->field_data = 'task_gantt_data';
		$this->field_order = 'num_order';
		$this->buttons_width = 120;


		$this->add = 'add';

		$this->custom_cols = array();
		$this->custom_input = array();
		$this->section_labels = array();

		$this->object_fields = array();
	}

	/**
	* setCustomColumn
	* @param $field_gantt
	* @param $field_record
	* @param $default_value
	* @param $attributes An array of key and parameter value of DHTMLX
	*/
	public function setCustomColumn($field_gantt,$field_record, $default_value, $attributes = array())
	{
		$this->cols_field[$field_gantt] = array($field_record,$default_value);

		$attr = '{';
		$attr .= 'name:"'.$field_gantt.'"';
		foreach($attributes as $key => $attribute){
			$attr .= ','.$key.':"'.$attribute.'"';
		}
		$attr .= '}';
		$this->custom_cols[] = $attr;


		$attr = '{';
		$attr .= 'name:"'.$field_gantt.'"';
		$attr .= ',type:"textarea"';
		$attr .= ',map_to:"'.$field_gantt.'"';
		$attr .= '}';
		$this->custom_input[] = $attr;

		$label = isset($attributes['label'])?$attributes['label']:$field_gantt;
		$this->section_labels[] = 'gantt.locale.labels.section_'.$field_gantt.' = "'.$label.'";';
	}


	/**
	* setColumnId
	* @param $value
	*/
	public function setColumnId($value)
	{
		$this->cols_field['id'] = array($value,'0');
	}

	/**
	* setColText
	* @param $value
	*/
	public function setColumnText($value)
	{
		$this->cols_field['text'] = array($value,'-');
	}

	/**
	* setColumnParent
	* @param $value
	*/
	public function setColumnParent($value)
	{
		$this->cols_field['parent'] = array($value,0);
	}

	/**
	* setColumnStartDate
	* @param $value
	*/
	public function setColumnStartDate($value)
	{
		$this->cols_field['start_date'] = array($value,date('d-m-Y H:i:s'));
	}

	/**
	* setColumnEndDate
	* @param $value
	*/
	public function setColumnEndDate($value)
	{
		$this->cols_field['end_date'] = array($value,date('d-m-Y H:i:s'));
	}

	/**
	* setColumnDuration
	* @param $value
	*/
	public function setColumnDuration($value)
	{
		$this->cols_field['duration'] = array($value,'');
	}

	/**
	* setColumnDuration
	* @param $value
	*/
	public function setColumnType($value)
	{
		$this->cols_field['type'] = array($value,'task');
	}

	/**
	* getColumnType
	* @return array
	*/
	public function getColumnType()
	{
		return $this->cols_field['type'];
	}

	/**
	* setColumnProgress
	* @param $value
	*/
	public function setColumnProgress($value)
	{
		$this->cols_field['progress'] = array($value,0);
	}

	/**
	* setColumnDragResize
	* @param $value
	*/
	public function setColumnDragResize($value)
	{
		$this->cols_field['drag_resize'] = array($value,true);
	}

	/**
	* setColumnDragMove
	* @param $value
	*/
	public function setColumnDragMove($value)
	{
		$this->cols_field['drag_move'] = array($value,true);
	}



	/**
	* setColumnDeleted
	* @param $value
	* @param $flag_yes
	* @param $flag_not
	*/
	public function setColumnDeleted($value, $flag_yes, $flag_not)
	{
		$this->field_deleted = $value;
		$this->value_deleted_yes = $flag_yes;
		$this->value_deleted_not = $flag_not;
	}

	/**
	* getColumnDeleted
	* @param $value
	*/
	public function getColumnDeleted()
	{
		return $this->field_deleted;
	}

	/**
	* setColumnData
	* @param $value
	*/
	public function setColumnData($value)
	{
		$this->field_data = $value;
	}

	/**
	* getColumnData
	* @param $value
	*/
	public function getColumnData()
	{
		return $this->field_data;
	}

	/**
	* setColumnOrder
	* @param $value
	*/
	public function setColumnOrder($value)
	{
		$this->field_order = $value;
	}

	/**
	* getColumnOrder
	* @param $value
	*/
	public function getColumnOrder()
	{
		return $this->field_order;
	}

	/**
	* setColumnGroup
	* @param string $field
	* @param array $value
	*/
	public function setColumnGroup($field, $value)
	{
		$this->field_group = $field;
		$this->value_group = $value;
	}
	

	/**
	* getColumnGroup
	* @param $value
	*/
	public function getColumnGroup()
	{
		return $this->field_group;
	}

	/**
	* getColumnGroup
	* @param $value
	*/
	public function setButtonsWidth(int $int)
	{
		$this->buttons_width = $int;
	}

	/**
	* setLinkId
	* @param $value
	*/
	public function setLinkId($value)
	{
		$this->link_id = $value;
	}

	/**
	* setLinkSource
	* @param $value
	*/
	public function setLinkSource($value)
	{
		$this->link_source = $value;
	}

	/**
	* setLinkTarget
	* @param $value
	*/
	public function setLinkTarget($value)
	{
		$this->link_target = $value;
	}

	/**
	* setLinkType
	* @param $value
	*/
	public function setLinkType($value)
	{
		$this->link_type = $value;
	}

	/**
	* zoomSetLevel
	* @param $value values (day,week,month,quarter,year)
	*/
	public function zoomSetLevel($value)
	{
		$this->zoomSetLevel = $value;
	}

	//Plugins
	/**
	* enablePluginToolTip
	* @param $bool value bool
	*/
	public function enablePluginToolTip($bool)
	{
		$this->plugin_tooltip = ($bool == TRUE)?'true':'false';
	}

	/**
	* enablePluginQuickInfo
	* @param $bool value bool
	*/
	public function enablePluginQuickInfo($bool)
	{
		$this->plugin_quick_info = ($bool == TRUE)?'true':'false';
	}

	/**
	* enablePluginMarker
	* @param $bool value bool
	*/
	public function enablePluginMarker($bool)
	{
		$this->plugin_marker = ($bool == TRUE)?'true':'false';
	}

	/**
	* setThemeGantt
	* @param $value values (broadway,contrast_black,contrast_white,material,meadow,skyblue,terrace)
	*/
	public function setThemeGantt($value)
	{
		if($value == 'dhtmlxgantt'){
			$this->theme_gantt = $value;
		}
		else
		{
			$this->theme_gantt = 'skins/dhtmlxgantt_'.$value;
		}
	}

	public function clear()
	{
		$this->tasks = array();
		$this->links = array();
	}

	public function addObjectField($field_name)
	{
		$this->object_fields[] = $field_name;
	}


	public function addLinks($links)
	{
		if($links)
		{
			foreach($links as $link)
			{
				$this->addLink($link);
			}
		}
	}

	public function addLink($link)
	{
		$row         = [];

		$link_id     = $this->link_id;
		$link_source = $this->link_source;
		$link_target = $this->link_target;
		$link_type   = $this->link_type;

		$row['id'] = isset($link->$link_id)?(float) $link->$link_id:0;
		$row['source'] = isset($link->$link_source)?(float) $link->$link_source:0;
		$row['target'] = isset($link->$link_target)?(float) $link->$link_target:0;
		$row['type'] = isset($link->$link_type)?(float) $link->$link_type:0;

		$this->links[] = $row;
	}


	public function getLinks()
	{
		return $this->links;
	}

	public function addTasks($tasks)
	{
		if($tasks)
		{
			foreach($tasks as $task)
			{
				$this->addTask($task);
			}
		}
	}

	public function addTask($task)
	{
		$row = [];



		$int = array();
		$int['id'] = 'id';
		$int['parent'] = 'parent';
		$int['duration'] = 'duration';

		foreach($this->cols_field as $key => $col_field)
		{
			$field_record = $col_field[0];
			$default_value= $col_field[1];

			$task->$field_record = array_key_exists($key,$int)? (float)$task->$field_record: $task->$field_record;

			$row[$key] = ($task->$field_record)?$task->$field_record:$default_value;

			if($key == 'start_date' || $key == 'end_date' )
			{
				$row[$key] = date('d-m-Y H:i:s',strtotime($row[$key]));
			}
		}

		if($this->object_fields)
		{
			foreach($this->object_fields as $field)
			{
				if(isset($task->$field))
				{
					$row['object'][$field] = $task->$field;
				}

			}
		}
		else
		{
			$object = NULL;

			if($task instanceof TRecord)
			{
				$object = $task->toArray();
			}
			else
			{
				$object = (array) $task;
			}

			$row['object'] = $object;
		}

		$this->tasks[] = $row;

	}

	public function getTasks()
	{
		return $this->tasks;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setButtonSave(TButton $button)
	{
		$button->addFunction("onSave();");
		$this->action = $button->getAction();
	}

	/**
	* addActionLink
	* @param $action TActionLink object
	* @param $display_condicion TActionLink object
	*/
	public function addActionLink(TActionLink $action, $display_condicion = NULL)
	{
		$this->action_link[] = [$action, $display_condicion];
		return $action;
	}

	public function setInput(TField $input)
	{
		$this->input = $input->getName();
	}

	public static function onReload($data = array(), $links = array())
	{
		$ge         = new self();
		$ge->addTasks($data);
		$ge->addLinks($links);

		$datasource = $ge->getDataSource();
		$datasource = self::formatJsonTask($datasource);

		TScript::create("onReload('$datasource');");
	}

	public static function formatJsonTask($tasks)
	{
		$tasks = json_encode($tasks);

		$bool  = array();
		$bool['drag_resize'] = 'drag_resize';
		$bool['drag_move'] = 'drag_move';



		foreach($bool as $key => $item)
		{
			$tasks = str_replace('"'.$key.'":"false"','"'.$key.'":false',$tasks);
			$tasks = str_replace('"'.$key.'":"true"','"'.$key.'":true',$tasks);
		}

		return $tasks;
	}


	public static function clearGantt()
	{
		TScript::create('onClear();');
	}

	public function getDataSource()
	{
		$datasource = array();
		$datasource['data'] = $this->getTasks();
		$datasource['links'] = $this->getLinks();
		return $datasource;
	}

	/**
	* saveJsonTask
	* @param $data Data data extracted from the form
	* @param $id_project Project ID number
	* @param $target_class Destination Class name
	* @param $foreign_key_project Name of the Foranera Key of the Project in the Destination Class
	* @param $target_link_class class name that keeps the links between Gantt tasks
	* @param $foreign_key_project Name of the Foraneal Key of the Project in the Link Class
	*/
	public function saveJsonTask($data, $id_project, $target_class, $foreign_key_project, $target_link_class, $foreign_key_project_link)
	{
		$input = $this->input;

		if(isset($data->$input)){
			$tasks = json_decode($data->$input,TRUE);

			$data = (isset($tasks['data']))?$tasks['data']:[];
			$links = (isset($tasks['links']))?$tasks['links']:[];

			$item_ids_data = array();
			foreach($data as $task){
				if(isset($task['id'])){
					$item_ids_data[] = $task['id'];
				}
			}

			$field_deleted = $this->field_deleted;
			$field_group   = $this->field_group;

			//eliminamos los ids que no estan en el post
			$class         = new $target_class;
			$pk            = $class->getPrimaryKey();

			$criteria      = new TCriteria;
			$criteria->add(new TFilter($foreign_key_project, '=', $id_project));
			$criteria->add(new TFilter($field_group, 'IN', $this->value_group));
			$repository    = new TRepository($target_class);
			$rows          = $repository->load($criteria);
			

			if($rows)
			{
				foreach($rows as $row)
				{
					if(!in_array($row->$pk,$item_ids_data))
					{
						$row->$field_deleted = $this->value_deleted_yes;
						$row->store();
					}
				}
			}

			$parents_id = array();

			$num_order = 0;
			foreach($data as $task)
			{
				$target = new $target_class;

				if(isset($task['id']))
				{
					$id = $this->cols_field['id'][0];

					$row= $target_class::where($id,'=',$task['id'])
					->load();

					if(count($row) > 0){
						$target = $row[0];
					}
				}

				foreach($this->cols_field as $key => $col_field){
					if($key == 'id'){
						continue;
					}

					$field_record = $col_field[0];
					$default_value= $col_field[1];

					$target->$field_record = isset($task[$key])?$task[$key]:$default_value;

					if($key == 'parent'){
						$target->$field_record = isset($parents_id['tmp_'.$target->$field_record])?$parents_id['tmp_'.$target->$field_record]:0;
					}
				}

				$field_order         = $this->field_order;
				$field_group         = $this->field_group;
				
				
				if(empty($target->$id))
				{
					$value_default = $this->value_group[0];
					$target->$field_group         = $value_default;
				
				}
				$target->$foreign_key_project = $id_project;
				$target->$field_order         = $num_order;
				$target->store();

				$id                  = $this->cols_field['id'][0];

				$parents_id['tmp_'.$task['id']] = $target->$id;

				$num_order++;
			}
			unset($pk);


			$item_ids_link = array();
			foreach($links as $link){
				if(isset($link['id'])){
					$item_ids_link[] = $link ['id'];
				}
			}
			unset($pk);

			$class      = new $target_link_class;
			$pk         = $class->getPrimaryKey();

			$criteria   = new TCriteria;
			$criteria->add(new TFilter($foreign_key_project_link, '=', $id_project));
			$repository = new TRepository($target_link_class);
			$rows       = $repository->load($criteria);

			if($rows){
				foreach($rows as $row){
					if(!in_array($row->$pk,$item_ids_link)){
						$target_link_class::where($pk,'=',$row->$pk)->delete();
					}
				}
			}


			foreach($links as $link){
				$target = new $target_link_class;

				if(isset($link['id'])){
					$row = $target_link_class::where($this->link_id,'=',$link['id'])->load();

					if(count($row) > 0){
						$target = $row[0];
					}
				}


				$link_source = $this->link_source;
				$link_target = $this->link_target;
				$link_type   = $this->link_type;

				$target->$link_source = isset($parents_id['tmp_'.$link['source']])?$parents_id['tmp_'.$link['source']]:0;//isset($link['source'])?$link['source']:0;
				$target->$link_target = isset($parents_id['tmp_'.$link['target']])?$parents_id['tmp_'.$link['target']]:0;//isset($link['target'])?$link['target']:0;
				$target->$link_type = isset($link['type'])?$link['type']:0;
				$target->$foreign_key_project_link = $id_project;
				$target->store();
			}
		}
	}



	protected function prepareActionsLinks(TActionLink $action)
	{
		$link = "".$action;

		preg_match_all('#\D%2B(.*?)%2B#', $link, $match);

		foreach($match[0] as $key => $item)
		{
			$link = str_replace($item,"='+".$match[1][$key]."+'",$link);
			$link = str_replace("\n",'',$link);
		}

		return $link;
	}


	/**
	* Shows the widget at the screen
	*/
	public function show()
	{

		$html       = new THtmlRenderer('app/lib/include/dhtmlxgantt/dhtmlxgantt.html');
		$html->disableHtmlConversion();

		$datasource = $this->getDataSource();
		$datasource = self::formatJsonTask($datasource);
		$datasource = json_encode(json_decode($datasource), JSON_PRETTY_PRINT);

		$coma       = count($this->custom_cols) > 0?',':'';
		$custom_cols= !empty($this->custom_cols)?implode(',',$this->custom_cols):'';
		$custom_cols .= $coma;

		$custom_input = !empty($this->custom_input)?implode(',',$this->custom_input):'';
		$custom_input .= $coma;

		$section_labels = !empty($this->custom_input)?implode("\n",$this->section_labels):'';

		$action_links = array();

		$string = "";
		if($this->action_link)
		{
			foreach($this->action_link as $item)
			{
				$action            = $item[0];
				$display_condition = $item[1];

				$link              = $this->prepareActionsLinks($action);

				if(is_null($display_condition))
				{

					$string .= "
					buttons += '".$link."';
					";
				}
				else
				{
					$string .= "

					if(typeof task.object !== 'undefined')
					{
					if(task.".$display_condition.")
					{
					buttons += '".$link."';
					}
					else
					{
					buttons += '<a class=\'fas\' style=\'padding-right: 22px;\'></a>';
					}
					}
					else
					{
					buttons += '<a class=\'fas\' style=\'padding-right: 22px;\'></a>';
					}

					";
				}
			}
		}

		$width = (strstr((string) $this->width, '%') !== FALSE) ? $this->width : "{$this->width}px";
		$height = (strstr($this->height, '%') !== FALSE) ? $this->height : "{$this->height}px";

		$html->enableSection('main', array(
				'datasource'       => $datasource,
				'action'           => $this->action->serialize(),
				'buttons'          => $string,
				'buttons_width'    => $this->buttons_width,

				'uniqid'           => $this->id,
				'zoomSetLevel'     => $this->zoomSetLevel,
				'plugin_tooltip'   => $this->plugin_tooltip,
				'plugin_quick_info'=> $this->plugin_quick_info,
				'plugin_marker'    => $this->plugin_marker,
				'width'            => $width,
				'height'           => $height,
				'theme_gantt'      => $this->theme_gantt,
				'input'            => $this->input,

				'custom_cols'      => $custom_cols,
				'custom_input'     => $custom_input,
				'section_labels'   => $section_labels,

				'name'             => $this->name)
		);

		echo $html->getContents();

	}
}