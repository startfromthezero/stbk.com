<?php
class Currency extends modules_mem
{
	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Language
	 */
	protected $language;

	private $code;

	public $currencies = array();

	public function __construct($registry)
	{
		parent::__construct();
		$this->config     = $registry->get('config');
		$this->language   = $registry->get('language');
		$this->request    = $registry->get('request');
		$this->currencies = $this->hash_sql('SELECT * FROM ' . DB_PREFIX . 'currency WHERE status = 1', 'code');

		if (isset($this->request->get['currency']) && (isset($this->currencies[$this->request->get['currency']])))
		{
			$this->set($this->request->get['currency']);
		}
		elseif ((isset($this->request->cookie['currency'])) && (isset($this->currencies[$this->request->cookie['currency']])))
		{
			$this->set($this->request->cookie['currency']);
		}
		else
		{
			$this->set($this->config->get('config_currency'));
		}
	}

	public function set($currency)
	{
		$this->code = $currency;
		wcore_utils::set_cookie('currency', $currency, 365);
	}

	public function format($number, $currency = '', $value = '', $format = true)
	{
		if ($currency && $this->has($currency))
		{
			$symbol_left   = $this->currencies[$currency]['symbol_left'];
			$symbol_right  = $this->currencies[$currency]['symbol_right'];
			$decimal_place = $this->currencies[$currency]['decimal_place'];
		}
		else
		{
			$symbol_left   = $this->currencies[$this->code]['symbol_left'];
			$symbol_right  = $this->currencies[$this->code]['symbol_right'];
			$decimal_place = $this->currencies[$this->code]['decimal_place'];
			$currency      = $this->code;
		}

		if ($value)
		{
			$value = $value;
		}
		else
		{
			$value = $this->currencies[$currency]['value'];
		}

		if ($value)
		{
			$value = (float)$number * $value;
		}
		else
		{
			$value = $number;
		}

		$string = '';

		if (($symbol_left) && ($format))
		{
			$string .= $symbol_left;
		}

		if ($format)
		{
			$decimal_point = $this->language->get('decimal_point');
		}
		else
		{
			$decimal_point = '.';
		}

		if ($format)
		{
			$thousand_point = $this->language->get('thousand_point');
		}
		else
		{
			$thousand_point = '';
		}

		$string .= number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);

		if (($symbol_right) && ($format))
		{
			$string .= $symbol_right;
		}

		return $string;
	}

	public function convert($value, $from, $to)
	{
		if (isset($this->currencies[$from]))
		{
			$from = $this->currencies[$from]['value'];
		}
		else
		{
			$from = 0;
		}

		if (isset($this->currencies[$to]))
		{
			$to = $this->currencies[$to]['value'];
		}
		else
		{
			$to = 0;
		}

		return $value * ($to / $from);
	}

	public function getId($currency = '')
	{
		if (!$currency)
		{
			return $this->currencies[$this->code]['currency_id'];
		}
		elseif ($currency && isset($this->currencies[$currency]))
		{
			return $this->currencies[$currency]['currency_id'];
		}
		else
		{
			return 0;
		}
	}

	public function getSymbolLeft($currency = '')
	{
		if (!$currency)
		{
			return $this->currencies[$this->code]['symbol_left'];
		}
		elseif ($currency && isset($this->currencies[$currency]))
		{
			return $this->currencies[$currency]['symbol_left'];
		}
		else
		{
			return '';
		}
	}

	public function getSymbolRight($currency = '')
	{
		if (!$currency)
		{
			return $this->currencies[$this->code]['symbol_right'];
		}
		elseif ($currency && isset($this->currencies[$currency]))
		{
			return $this->currencies[$currency]['symbol_right'];
		}
		else
		{
			return '';
		}
	}

	public function getDecimalPlace($currency = '')
	{
		if (!$currency)
		{
			return $this->currencies[$this->code]['decimal_place'];
		}
		elseif ($currency && isset($this->currencies[$currency]))
		{
			return $this->currencies[$currency]['decimal_place'];
		}
		else
		{
			return 0;
		}
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getValue($currency = '')
	{
		if (!$currency)
		{
			return $this->currencies[$this->code]['value'];
		}
		elseif ($currency && isset($this->currencies[$currency]))
		{
			return $this->currencies[$currency]['value'];
		}
		else
		{
			return 0;
		}
	}

	public function has($currency)
	{
		return isset($this->currencies[$currency]);
	}
}
?>