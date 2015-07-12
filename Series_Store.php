<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 16:47
 */

class Series_Store {
	protected $series = array();
	protected $series_category = array();
	protected $added = array();
	protected $removed = array();
	protected $is_changed = FALSE;
	public function add(Series $series, $track = TRUE)
	{
		if ($track) {

			$found = FALSE;
			foreach ($this->added as $added)
			{
				if ($added->get_name() == $series->get_name())
				{
					$found = TRUE;
				}
			}
			if ( ! $found) {
				$this->added[] = $series;
				$this->is_changed = TRUE;
			}
			foreach ($this->removed as $key => $removed)
			{
				if ($removed->get_name() == $series->get_name())
				{
					unset($this->removed[$key]);
				}
			}
		}

		if ( ! $this->has_series($series))
		{
			$this->series[] = $series;
		}
	}

	public function has_series(Series $series)
	{
		foreach ($this->series as $series_item)
		{
			if ($series_item->get_name() == $series->get_name() AND $series_item->get_category()->get_name() == $series->get_category()->get_name())
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	public function get_added()
	{
		return $this->added;
	}

	public function get_removed()
	{
		return $this->removed;
	}

	public function set_added($added)
	{
		$this->added = $added;

	}

	public function set_removed($removed)
	{
		$this->removed = $removed;
	}
	public function remove($serie, $track = TRUE)
	{
		foreach ($this->series as $series_key => $serie_item)
		{
			if ($serie_item->get_name() == $serie)
			{
				if ($track) {
					$found = FALSE;
					foreach ($this->removed as $removed)
					{
						if ($removed->get_name() == $serie_item->get_name())
						{
							$found = TRUE;
						}
					}
					if ( ! $found) {
						$this->removed[] = $serie_item;
						$this->is_changed = TRUE;
					}


					foreach ($this->added as $key => $added)
					{
						if ($added->get_name() == $serie)
						{
							unset($this->added[$key]);
						}
					}
				}

				unset($this->series[$series_key]);
			}
		}
	}
	public function get_series()
	{
		return $this->series;
	}
	/**
	 * @return array
	 */
	public function get_series_category()
	{
		return $this->series_category;
	}

	/**
	 * @param array $series_category
	 */
	public function set_series_category($series_category)
	{
		$this->series_category = $series_category;
	}

	public function get_is_changed()
	{
		return $this->is_changed;
	}

}