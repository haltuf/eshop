<?php declare(strict_types=1);

namespace Eshop\Model\ORM\Services;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Eshop\Model\ORM\Entity\Basket;
use Eshop\Model\ORM\Entity\Category;
use Eshop\Model\ORM\Entity\Product;
use Eshop\Model\ORM\Entity\ProductImage;
use Eshop\Model\ORM\Exception\ProductNotAvailable;
use Eshop\Model\ORM\Lists\VatType;
use Nette\Http\FileUpload;
use Nette\Http\Session;
use Nette\Utils\ArrayHash;

class BasketService extends AbstractService
{
	private Session $session;

	public function __construct(EntityManagerInterface $em, Session $session)
	{
		parent::__construct($em);
		$this->session = $session;
	}

	public function getBasket(): Basket
	{
		$visitorId = $this->session->getSection('eshop')->visitorId;
		$basket = $this->em->getRepository(Basket::class)->findOneBy(['visitorId' => $visitorId]);
		if ($basket === null) {
			$basket = new Basket($visitorId);
		}

		return $basket;
	}

	/**
	 * @throws ProductNotAvailable
	 */
	public function add(Product $product, int $quantity = 1): bool
	{
		$basket = $this->getBasket();
		$basket->addProduct($product, $quantity);
		$this->em->persist($basket);
		$this->em->flush();

		return true;
	}

	public function remove(Product $product, int $quantity = 0): bool
	{
		$basket = $this->getBasket();
		$basket->removeProduct($product, $quantity);
		$this->em->persist($basket);
		$this->em->flush();

		return true;
	}
}