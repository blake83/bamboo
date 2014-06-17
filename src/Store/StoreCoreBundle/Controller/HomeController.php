<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 */

namespace Store\StoreCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home controller
 *
 * This class should only contain home actions
 */
class HomeController extends Controller
{
    /**
     * Home page.
     *
     * @return array
     *
     * @Route(
     *      path = "/",
     *      name = "store_homepage"
     * )
     * @Template
     */
    public function homeAction()
    {
        $productCollectionProvider = $this
            ->container
            ->get('store.product.service.product_collection_provider');

        $products = $productCollectionProvider->getHomeProducts();

        return array(
            'products' => $products,
        );
    }
}
