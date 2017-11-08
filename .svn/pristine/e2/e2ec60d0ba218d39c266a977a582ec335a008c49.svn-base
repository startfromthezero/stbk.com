<?php
class Cart extends modules_mem
{
	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @var wcore_session
	 */
	protected $session;

	/**
	 * @var Tax
	 */
	protected $tax;

	/**
	 * @var Weight
	 */
	protected $weight;

	/**
	 * @var array 购物车数据
	 */
	private $data = array();

	/**
	 * @var int 语言编号
	 */
	public $language_id = 1;

	public function __construct($registry)
	{
		parent::__construct();
		$this->config      = $registry->get('config');
		$this->customer    = $registry->get('customer');
		$this->session     = $registry->get('session');
		$this->tax         = $registry->get('tax');
		$this->weight      = $registry->get('weight');
		$this->language_id = intval($this->config->get('config_language_id'));
	}

	/**
	 * 获取购物车中的产品信息
	 *
	 * @return array
	 */
	public function getProducts()
	{
		if (empty($this->data) && isset($this->session->data['cart']))
		{
			foreach ($this->session->data['cart'] as $key => $quantity)
			{
				$product    = explode(':', $key);
				$product_id = $product[0];
				$stock      = true;

				// Options
				if (isset($product[1]))
				{
					$options = unserialize(base64_decode($product[1]));
				}
				else
				{
					$options = array();
				}

				$prd_res = $this->mem_sql('SELECT * FROM ' . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '{$product_id}' AND pd.language_id = '{$this->language_id}' AND p.date_available <= NOW() AND p.status = '1'");
				if (!empty($prd_res))
				{
					$option_price  = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();
					foreach ($options as $product_option_id => $option_value)
					{
						$opt_res = $this->mem_sql("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '{$product_option_id}' AND po.product_id = '{$product_id}' AND od.language_id = '{$this->language_id}'");
						if (!empty($opt_res))
						{
							if ($opt_res['type'] == 'select' || $opt_res['type'] == 'radio' || $opt_res['type'] == 'image')
							{
								$opt_val_res = $this->mem_sql("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '{$option_value}' AND pov.product_option_id = '{$product_option_id}' AND ovd.language_id = '{$this->language_id}'");
								if (!empty($opt_val_res))
								{
									if ($opt_val_res['price_prefix'] == '+')
									{
										$option_price += $opt_val_res['price'];
									}
									elseif ($opt_val_res['price_prefix'] == '-')
									{
										$option_price -= $opt_val_res['price'];
									}

									if ($opt_val_res['points_prefix'] == '+')
									{
										$option_points += $opt_val_res['points'];
									}
									elseif ($opt_val_res['points_prefix'] == '-')
									{
										$option_points -= $opt_val_res['points'];
									}

									if ($opt_val_res['weight_prefix'] == '+')
									{
										$option_weight += $opt_val_res['weight'];
									}
									elseif ($opt_val_res['weight_prefix'] == '-')
									{
										$option_weight -= $opt_val_res['weight'];
									}

									if ($opt_val_res['subtract'] && (!$opt_val_res['quantity'] || ($opt_val_res['quantity'] < $quantity)))
									{
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $option_value,
										'option_id'               => $opt_res['option_id'],
										'option_value_id'         => $opt_val_res['option_value_id'],
										'name'                    => $opt_res['name'],
										'option_value'            => $opt_val_res['name'],
										'type'                    => $opt_res['type'],
										'quantity'                => $opt_val_res['quantity'],
										'subtract'                => $opt_val_res['subtract'],
										'price'                   => $opt_val_res['price'],
										'price_prefix'            => $opt_val_res['price_prefix'],
										'points'                  => $opt_val_res['points'],
										'points_prefix'           => $opt_val_res['points_prefix'],
										'weight'                  => $opt_val_res['weight'],
										'weight_prefix'           => $opt_val_res['weight_prefix']
									);
								}
							}
							elseif ($opt_res['type'] == 'checkbox' && is_array($option_value))
							{
								foreach ($option_value as $product_option_value_id)
								{
									$opt_val_res = $this->mem_sql("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '{$product_option_value_id}' AND pov.product_option_id = '{$product_option_id}' AND ovd.language_id = '{$this->language_id}'");
									if (!empty($opt_val_res))
									{
										if ($opt_val_res['price_prefix'] == '+')
										{
											$option_price += $opt_val_res['price'];
										}
										elseif ($opt_val_res['price_prefix'] == '-')
										{
											$option_price -= $opt_val_res['price'];
										}

										if ($opt_val_res['points_prefix'] == '+')
										{
											$option_points += $opt_val_res['points'];
										}
										elseif ($opt_val_res['points_prefix'] == '-')
										{
											$option_points -= $opt_val_res['points'];
										}

										if ($opt_val_res['weight_prefix'] == '+')
										{
											$option_weight += $opt_val_res['weight'];
										}
										elseif ($opt_val_res['weight_prefix'] == '-')
										{
											$option_weight -= $opt_val_res['weight'];
										}

										if ($opt_val_res['subtract'] && (!$opt_val_res['quantity'] || ($opt_val_res['quantity'] < $quantity)))
										{
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $opt_res['option_id'],
											'option_value_id'         => $opt_val_res['option_value_id'],
											'name'                    => $opt_res['name'],
											'option_value'            => $opt_val_res['name'],
											'type'                    => $opt_res['type'],
											'quantity'                => $opt_val_res['quantity'],
											'subtract'                => $opt_val_res['subtract'],
											'price'                   => $opt_val_res['price'],
											'price_prefix'            => $opt_val_res['price_prefix'],
											'points'                  => $opt_val_res['points'],
											'points_prefix'           => $opt_val_res['points_prefix'],
											'weight'                  => $opt_val_res['weight'],
											'weight_prefix'           => $opt_val_res['weight_prefix']
										);
									}
								}
							}
							elseif ($opt_res['type'] == 'text' || $opt_res['type'] == 'textarea' || $opt_res['type'] == 'file' || $opt_res['type'] == 'date' || $opt_res['type'] == 'datetime' || $opt_res['type'] == 'time')
							{
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $opt_res['option_id'],
									'option_value_id'         => '',
									'name'                    => $opt_res['name'],
									'option_value'            => $option_value,
									'type'                    => $opt_res['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',
									'weight'                  => '',
									'weight_prefix'           => ''
								);
							}
						}
					}

					if ($this->customer->isLogged())
					{
						$customer_group_id = $this->customer->getGroupId();
					}
					else
					{
						$customer_group_id = $this->config->get('config_customer_group_id');
					}

					// Product Specials
					$price       = $prd_res['price'];
					$prd_spc_res = $this->mem_sql("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '{$product_id}' AND customer_group_id = '{$customer_group_id}' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
					if (!empty($prd_spc_res))
					{
						$price = $prd_spc_res['price'];
					}

					// Product Discounts
					$discount_quantity = 0;
					foreach ($this->session->data['cart'] as $key_2 => $quantity_2)
					{
						$product_2 = explode(':', $key_2);
						if ($product_2[0] == $product_id)
						{
							$discount_quantity += $quantity_2;
						}
					}

					$prd_disc_res = $this->mem_sql("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '{$product_id}' AND customer_group_id = '{$customer_group_id}' AND quantity <= '{$discount_quantity}' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
					if (!empty($prd_disc_res))
					{
						$price = $prd_disc_res['price'];
					}

					// Reward Points
					$prd_rwd_res = $this->mem_sql("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '{$product_id}' AND customer_group_id = '{$customer_group_id}'");
					$reward      = !empty($prd_rwd_res) ? $prd_rwd_res['points'] : 0;

					// Downloads
					$download_data = array();
					$download_res  = $this->mem_sql('SELECT * FROM ' . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '{$product_id}' AND dd.language_id = '{$this->language_id}'", DB_GET_ALL);
					foreach ($download_res as $download)
					{
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'remaining'   => $download['remaining']
						);
					}

					// Stock
					if (!$prd_res['quantity'] || ($prd_res['quantity'] < $quantity))
					{
						$stock = false;
					}

					$this->data[$key] = array(
						'key'             => $key,
						'product_id'      => $prd_res['product_id'],
						'name'            => $prd_res['name'],
						'model'           => $prd_res['model'],
						'shipping'        => $prd_res['shipping'],
						'image'           => $prd_res['image'],
						'option'          => $option_data,
						'download'        => $download_data,
						'quantity'        => $quantity,
						'minimum'         => $prd_res['minimum'],
						'subtract'        => $prd_res['subtract'],
						'costing'         => $prd_res['costing'] * $quantity,
						'stock'           => $stock,
						'price'           => ($price + $option_price),
						'total'           => ($price + $option_price) * $quantity,
						'reward'          => $reward * $quantity,
						'points'          => ($prd_res['points'] ? ($prd_res['points'] + $option_points) * $quantity : 0),
						'tax_class_id'    => $prd_res['tax_class_id'],
						'weight'          => ($prd_res['weight'] + $option_weight) * $quantity,
						'weight_class_id' => $prd_res['weight_class_id'],
						'length'          => $prd_res['length'],
						'width'           => $prd_res['width'],
						'height'          => $prd_res['height'],
						'length_class_id' => $prd_res['length_class_id']
					);
				}
				else
				{
					$this->remove($key);
				}
			}
		}

		return $this->data;
	}

	/**
	 * 添加产品到购物车
	 *
	 * @param       $product_id 产品编号
	 * @param int   $qty        购买数量
	 * @param array $option     产品选项
	 */
	public function add($product_id, $qty = 1, $option = array())
	{
		if (!$option)
		{
			$key = (int)$product_id;
		}
		else
		{
			$key = (int)$product_id . ':' . base64_encode(serialize($option));
		}

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart']))
		{
			$this->session->data['cart'] = array();
		}

		if ((int)$qty && ((int)$qty > 0))
		{
			if (!isset($this->session->data['cart'][$key]))
			{
				$this->session->data['cart'][$key] = (int)$qty;
			}
			else
			{
				$this->session->data['cart'][$key] += (int)$qty;
			}
		}

		$this->data = array();
	}

	/**
	 * 更新产品购买数量
	 *
	 * @param $key 产品唯一标识符
	 * @param $qty 产品数量
	 */
	public function update($key, $qty)
	{
		if ((int)$qty && ((int)$qty > 0))
		{
			$this->session->data['cart'][$key] = (int)$qty;
		}
		else
		{
			$this->remove($key);
		}

		$this->data = array();
	}

	/**
	 * 产品购物车中的产品
	 *
	 * @param $key
	 */
	public function remove($key)
	{
		if (isset($this->session->data['cart'][$key]))
		{
			unset($this->session->data['cart'][$key]);
		}

		$this->data = array();
	}

	/**
	 * 清空购物车
	 */
	public function clear()
	{
		$this->session->data['cart'] = array();
		$this->data                  = array();
	}

	/**
	 * 计算产品重量
	 *
	 * @return float
	 */
	public function getWeight()
	{
		$weight = 0;
		foreach ($this->getProducts() as $product)
		{
			if ($product['shipping'])
			{
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	/**
	 * 计算商品小计金额
	 *
	 * @return float
	 */
	public function getSubTotal()
	{
		$total = 0;
		foreach ($this->getProducts() as $product)
		{
			$total += $product['total'];
		}

		return $total;
	}

	/**
	 * 计算税费
	 *
	 * @return array
	 */
	public function getTaxes()
	{
		$tax_data = array();
		foreach ($this->getProducts() as $product)
		{
			if ($product['tax_class_id'])
			{
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);
				foreach ($tax_rates as $tax_rate)
				{
					if (!isset($tax_data[$tax_rate['tax_rate_id']]))
					{
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					}
					else
					{
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	/**
	 * 计算总金额
	 *
	 * @return float
	 */
	public function getTotal()
	{
		$total = 0;
		foreach ($this->getProducts() as $product)
		{
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	/**
	 * 统计购物车中购买的产品数量
	 *
	 * @return int
	 */
	public function countProducts()
	{
		$product_total = 0;
		$products      = $this->getProducts();
		foreach ($products as $product)
		{
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	/**
	 * 购物车中是否有产品
	 *
	 * @return int
	 */
	public function hasProducts()
	{
		return isset($this->session->data['cart']) ? count($this->session->data['cart']) : 0;
	}

	/**
	 * 是否有库存
	 *
	 * @return bool
	 */
	public function hasStock()
	{
		$stock = true;
		foreach ($this->getProducts() as $product)
		{
			if (!$product['stock'])
			{
				$stock = false;
			}
		}

		return $stock;
	}

	/**
	 * 购买的产品中是否需要配送（虚拟产品不需要配送）
	 *
	 * @return boolean
	 */
	public function hasShipping()
	{
		$shipping = false;
		foreach ($this->getProducts() as $product)
		{
			if ($product['shipping'])
			{
				$shipping = true;
				break;
			}
		}

		return $shipping;
	}

	/**
	 * 是否需要下载文件
	 *
	 * @return bool
	 */
	public function hasDownload()
	{
		$download = false;
		foreach ($this->getProducts() as $product)
		{
			if ($product['download'])
			{
				$download = true;
				break;
			}
		}

		return $download;
	}
}
?>