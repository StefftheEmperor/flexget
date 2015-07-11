<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 17:08
 */

class Category_Store {
	protected $series = array();

	public function add(Category_Series $series)
	{
		if ($this->series_exists($series))
		{
			$serie = $this->get_series_by_name($series->get_name());
			$serie->add_category($series->get_categories());
		} else {
			$this->series[] = $series;
		}
	}

	public function get_series()
	{
		return $this->series;
	}
	public function series_exists(Category_Series $series)
	{
		foreach ($this->series as $serie)
		{
			if ($serie->get_name() == $series->get_name())
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	public function key_exists($key)
	{
		return array_key_exists($key, $this->series);
	}

	public function get_series_by_name($name)
	{
		foreach ($this->series as $serie)
		{
			if ($serie->get_name() == $name)
			{
				return $serie;
			}

		}
		$category_serie = new Category_Series();
		$category_serie->set_name($name);
		$this->add($category_serie);

		return $category_serie;
	}
}