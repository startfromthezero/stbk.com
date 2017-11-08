<?php
class Length extends modules_mem
{
	private $lengths = array();

	public $language_id = 1;

	public function __construct($registry)
	{
		$this->config       = $registry->get('config');
		$this->language_id  = intval($this->config->get('config_language_id'));
		$length_class_rows = $this->sdb()->fetch_all('SELECT * FROM ' . DB_PREFIX . "length_class mc LEFT JOIN " . DB_PREFIX . "length_class_description mcd ON (mc.length_class_id = mcd.length_class_id) WHERE mcd.language_id = '{$this->language_id}'");
		foreach ($length_class_rows as $result)
		{
			$this->lengths[$result['length_class_id']] = array(
				'length_class_id' => $result['length_class_id'],
				'title'           => $result['title'],
				'unit'            => $result['unit'],
				'value'           => $result['value']
			);
		}
	}

	public function convert($value, $from, $to)
	{
		if ($from == $to)
		{
			return $value;
		}

		if (isset($this->lengths[$from]))
		{
			$from = $this->lengths[$from]['value'];
		}
		else
		{
			$from = 0;
		}

		if (isset($this->lengths[$to]))
		{
			$to = $this->lengths[$to]['value'];
		}
		else
		{
			$to = 0;
		}

		return $value * ($to / $from);
	}

	public function format($value, $length_class_id, $decimal_point = '.', $thousand_point = ',')
	{
		if (isset($this->lengths[$length_class_id]))
		{
			return number_format($value, 2, $decimal_point, $thousand_point) . $this->lengths[$length_class_id]['unit'];
		}
		else
		{
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	public function getUnit($length_class_id)
	{
		if (isset($this->lengths[$length_class_id]))
		{
			return $this->lengths[$length_class_id]['unit'];
		}
		else
		{
			return '';
		}
	}
}
?>