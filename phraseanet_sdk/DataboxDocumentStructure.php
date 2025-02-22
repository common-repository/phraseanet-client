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
use PhraseanetSDK\EntityHydrator;

class DataboxDocumentStructure extends AbstractRepository
{
    /**
     * Find All structure document of the desired databox
     *
     * @param  integer          $databoxId The databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('v1/databoxes/%d/metadatas/', $databoxId));

        //return $response->getProperty('document_metadatas');

        if (true !== $response->hasProperty('document_metadatas')) {
            return '';
            //throw new RuntimeException('Missing "document_metadatas_structure" property in response content');
        }

        return $response->getProperty('document_metadatas');
    }
}
