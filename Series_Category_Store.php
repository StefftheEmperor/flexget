<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 17:04
 */

class Series_Category_Store {
	protected $categories = array();
	protected $category_store;
	protected $name;
	protected $base_dir;
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

	public function add(Series_Category $category)
	{
		$category->set_store($this);
		$category_store = $this->get_category_store();
		foreach ($category->get_series_store()->get_series() as $serie)
		{
			$category_serie = $category_store->get_series_by_name($serie->get_name());
			$category_serie->add_category($serie->get_category());
			$category_store->add($category_serie);
		}
		$this->categories[] = $category;
	}

	public function get_category_store()
	{
		if ( ! isset($this->series_store))
		{
			$this->series_store = new Category_Store();
		}

		return $this->series_store;
	}

	public function get_series()
	{
		$series = $this->get_category_store();

		return $series;
	}

	public function save()
	{
		$changed = FALSE;
		foreach ($this->categories as $category)
		{
			$category->save();
			if ($category->get_is_changed())
			{
				$changed = TRUE;
			}
		}

		if ($changed)
		{
			touch($this->get_base_dir().'/changed.status');
		}
	}
	public function process_post()
	{
		if (isset($_POST['category']) AND $_POST['category'] == $this->get_name())
		{
			$series = $this->get_series();
			foreach ($_POST['entry'] as $key => $value)
			{
				if (empty($value))
				{
					continue;
				}
				if ( ! $series->key_exists($key))
				{
					$category_series = new Category_Series();
					$category_series->set_name($value);
					foreach ($this->categories as $category)
					{
						if (isset($_POST[$category->get_title_unique()][$key]) AND $_POST[$category->get_title_unique()][$key])
						{
							$category_series->add_category($category);
							$series_element = new Series;
							$series_element->set_name($value);
							$series_element->set_category($category);
							$category->get_series_store()->add($series_element);
						}
					}
					$series->add($category_series);
				}
				else
				{
					foreach ($this->categories as $category)
					{
						if (( ! isset($_POST[$category->get_title_unique()][$key])) OR ( ! $_POST[$category->get_title_unique()][$key])) {
							$category->get_series_store()->remove($value);
							$series->get_series_by_name($value)->remove_category($category);
						} else
						{
							$series_element = new Series;
							$series_element->set_name($value);
							$series_element->set_category($category);
							$category->get_series_store()->add($series_element);
							$series->get_series_by_name($value)->add_category($category);
						}
					}
				}

			}
			$this->save();
		}
	}
	public function get_html()
	{
		$this->process_post();
		$html = '<form method="post"><input type="hidden" name="category" value="'.$this->get_name().'">';
		$html .= '<table><thead><tr><th>Name</th>';

		$category_count = count($this->categories);
		foreach ($this->categories as $category)
		{
			$html .= '<th>'.$category->get_name().'</th>';
		}

		$html .= '</tr></thead><tbody>';
		$series_store = $this->get_series();
		$series_key = 0;
		foreach ($series_store->get_series() as $series_key => $serie)
		{
			$html .= '<tr><td><input type="text" name="entry['.$series_key.']" value="'.$serie->get_name().'"></td>';
			foreach ($this->categories as $category)
			{
				$checked = FALSE;
				if ($serie->category_exists($category))
				{
					$checked = TRUE;
				}
				$html .= '<td><input type="checkbox" name="'.$category->get_title_unique().'['.$series_key.']"'.($checked ? ' checked="checked"' : '').' value="1"></td>';
			}
			$html .= '</tr>';
		}
		$html .= '<tr><td>';
		$html .= '<input type="text" name="entry['.($series_key + 1).']">';
		$html .= '</td>';
		foreach ($this->categories as $category)
		{
			$html .= '<td><input type="checkbox" name="'.$category->get_title_unique().'['.($series_key + 1).']" title="'.$category->get_name().'" /></td>';
		}
		$html .= '<td></td>';
		$html .= '</tr><tr><td colspan="'.($category_count+1).'">';

		$html .= '<input type="submit" value="Speichern">';
		$html .= '</td></tr></tbody></table>';
		$html .= '</form>';

		return $html;
	}

	/**
	 * @return mixed
	 */
	public function get_base_dir()
	{
		return $this->base_dir;
	}

	/**
	 * @param mixed $base_dir
	 */
	public function set_base_dir($base_dir)
	{
		$this->base_dir = $base_dir;
	}


}