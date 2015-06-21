<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 16:50
 */

class Series_Category {
	protected $file;
	protected $name;
	protected $title_unique;


	protected $store;
	protected $series_store;

	/**
	 * @return mixed
	 */
	public function get_store()
	{
		return $this->store;
	}

	/**
	 * @param mixed $store
	 */
	public function set_store($store)
	{
		$this->store = $store;
	}

	/**
	 * @return mixed
	 */
	public function get_file()
	{
		return $this->file;
	}

	/**
	 * @param mixed $file
	 */
	public function set_file($file)
	{
		if ( ! file_exists($file))
		{
			touch($file);
		}

		$series = @yaml_parse_file($file);

		if ($series === FALSE OR ! isset($series['series']['default']))
		{
			$series = array('series' => array('default' => array()));
		}


		$series = $series['series']['default'];
		$this->series_store = new Series_Store();
		foreach ($series as $serie)
		{
			$serie_object = new Series();
			$serie_object->set_name($serie);
			$serie_object->set_category($this);

			$this->series_store->add($serie_object, FALSE);
		}

		$added_file = substr($file,0,strlen($file)-4).'_added.csv';
		$removed_file = substr($file,0,strlen($file)-4).'_removed.csv';
		if ( ! file_exists($added_file))
		{
			touch($added_file);
		}
		if ( ! file_exists($removed_file))
		{
			touch($removed_file);
		}
		$fh = fopen($added_file, 'r');
		$added_array = array();
		while (($added_line = fgetcsv($fh)) !== FALSE)
		{
			$added_array[] = $added_line;
		}

		if ($added_array === FALSE)
		{
			$added_array = array();
		}
		$added_items = array();

		foreach ($added_array as $added_item)
		{
			if (is_array($added_item))
			{
				$added_item = reset($added_item);
			}
			if (empty($added_item))
			{
				continue;
			}
			$added_object = new Series();
			$added_object->set_name($added_item);
			$added_object->set_category($this);
			$added_items[] = $added_object;
		}
		$this->get_series_store()->set_added($added_items);
		fclose($fh);
		$fh = fopen($removed_file, 'r');
		$removed_array = array();
		while (($removed_line = fgetcsv($fh)) !== FALSE)
		{
			$removed_array[] = $removed_line;
		}

		if ($removed_array === FALSE)
		{
			$removed_array = array();
		}
		$removed_items = array();

		foreach ($removed_array as $removed_item)
		{
			if (is_array($removed_item))
			{
				$removed_item = reset($removed_item);
			}
			if (empty($removed_item))
			{
				continue;
			}
			$removed_object = new Series();
			$removed_object->set_name($removed_item);
			$removed_object->set_category($this);
			$removed_items[] = $removed_object;
		}

		$this->get_series_store()->set_removed($removed_items);
		fclose($fh);
		$this->file = $file;
	}

	public function save()
	{
		$series_store = $this->get_series_store();
		$series = $series_store->get_series();
		$series_array = array();

		foreach ($series as $serie)
		{
			$series_array[] = $serie->get_name();
		}

		$added = $removed = array();

		foreach ($series_store->get_added() as $added_item)
		{
			$added[] = array($added_item->get_name(), 'http://flexget.com');
		}

		foreach ($series_store->get_removed() as $removed_item)
		{
			$removed[] = array($removed_item->get_name(), 'http://flexget.com');
		}


		$fh = fopen(substr($this->get_file(),0,-4).'_added.csv','w');
		foreach ($added as $added_line) {
			fputcsv($fh, $added_line);
		}
		fclose($fh);

		$fh = fopen(substr($this->get_file(),0,-4).'_removed.csv','w');
		foreach ($removed as $removed_line) {
			fputcsv($fh, $removed_line);
		}
		fclose($fh);

		yaml_emit_file($this->get_file(), array('series' => array('default' => $series_array)));


	}

	public function get_series_store()
	{
		if ( ! isset($this->series_store))
		{
			$this->series_store = new Series_Store;
		}

		return $this->series_store;
	}
	/**
	 * @return mixed
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function set_name($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function get_title_unique()
	{
		return $this->title_unique;
	}

	/**
	 * @param mixed $title_unique
	 */
	public function set_title_unique($title_unique)
	{
		$this->title_unique = $title_unique;
	}

	public function get_is_changed()
	{
		return $this->get_series_store()->get_is_changed();
	}
}