<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 19:44
 */

class Category_Series {
	protected $name;
	protected $categories = array();

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

	public function category_exists(Series_Category $category)
	{
		foreach ($this->categories as $category_item)
		{
			if ($category->get_title_unique() == $category_item->get_title_unique())
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	public function add_category($category)
	{
		if (is_array($category))
		{
			foreach ($category as $category_item)
			{
				$this->add_category($category_item);
			}
		} else {
			if ( ! $this->category_exists($category))
			{
				$this->categories[] = $category;
			}
		}
	}

	public function remove_category($category)
	{
		if (is_array($category))
		{
			foreach ($category as $category_item)
			{
				$this->remove_category($category_item);
			}
		} else {
			if ($this->category_exists($category))
			{
				foreach ($this->categories as $category_key => $category_item) {
					if ($category->get_title_unique() == $category_item->get_title_unique()) {
						unset($this->categories[$category_key]);
					}
				}
			}
		}
	}

	public function get_categories()
	{
		return $this->categories;
	}
}