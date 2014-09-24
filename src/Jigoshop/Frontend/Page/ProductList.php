<?php

namespace Jigoshop\Frontend\Page;

use Jigoshop\Core\Options;
use Jigoshop\Core\Pages;
use Jigoshop\Core\Types;
use Jigoshop\Frontend\Page;
use Jigoshop\Helper\Render;
use Jigoshop\Service\ProductServiceInterface;
use WPAL\Wordpress;

class ProductList implements Page
{
	/** @var \WPAL\Wordpress */
	private $wp;
	/** @var \Jigoshop\Core\Options */
	private $options;
	/** @var ProductServiceInterface */
	private $productService;

	public function __construct(Wordpress $wp, Options $options, ProductServiceInterface $productService)
	{
		$this->wp = $wp;
		$this->options = $options;
		$this->productService = $productService;
	}


	public function action()
	{
		if (isset($_GET['page_id']) && $_GET['page_id'] == $this->options->getPageId(Pages::SHOP)) {
			$this->wp->wpSafeRedirect($this->wp->getPostTypeArchiveLink(Types::PRODUCT));
		}
	}

	public function render()
	{
		$content = $this->wp->getPostField('post_content', $this->options->getPageId(Pages::SHOP));
		$query = $this->wp->getWpQuery();
		$products = $this->productService->findByQuery($query);
		return Render::get('shop', array(
			'content' => $content,
			'products' => $products,
			'product_count' => $query->max_num_pages,
		));
	}
}
