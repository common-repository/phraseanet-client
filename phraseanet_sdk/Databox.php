<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;

class Databox extends AbstractRepository
{
    /**
     * Find All databoxes
     *
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findAll()
    {
        $response = $this->query('GET', 'v1/databoxes/list/');

        if (true !== $response->hasProperty('databoxes')) {
            return false;
        }

        return $response->getProperty('databoxes');
    }


    public function getV3Subdefs($databoxId='')
    {
        $endpoint = !empty($databoxId) ? "v3/databoxes/$databoxId/subdefs/" : "v3/databoxes/subdefs/";

        $response = $this->query('GET', $endpoint);

        if (!$response->hasProperty('databoxes')) {
            return false;
        }

        return $response->getProperty('databoxes');
    }



    public function findMyAllCollections()
    {
        $response = $this->query('GET', 'v1/me/collections/');

        if (true !== $response->hasProperty('collections')) {
            return false;
        }

        return $response->getProperty('collections');
    }
}
