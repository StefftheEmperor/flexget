<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 16:47
 */

class Series {
	protected $name;
	protected $category;

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
	public function get_category()
	{
		return $this->category;
	}

	/**
	 * @param mixed $category
	 */
	public function set_category(Series_Category $category)
	{
		$this->category = $category;
	}


}