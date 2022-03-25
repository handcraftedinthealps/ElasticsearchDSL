<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizer used with referenced normalized objects.
 *
 * @internal
 */
class CustomReferencedNormalizer implements NormalizerInterface
{
    /**
     * @var array
     */
    private $references = [];

    /**
     * @var CustomNormalizer
     */
    private $normalizer;

    public function __construct()
    {
        $this->normalizer = new CustomNormalizer();
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $object->setReferences($this->references);
        $data = $this->normalizer->normalize($object, $format, $context);
        $this->references = array_merge($this->references, $object->getReferences());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof AbstractNormalizable;
    }
}
