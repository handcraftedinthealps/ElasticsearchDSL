<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Serializer;

use ONGR\ElasticsearchDSL\Serializer\Normalizer\OrderedNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Custom serializer which orders data before normalization.
 *
 * @internal
 */
class OrderedSerializer implements NormalizerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $serializer;

    public function __construct(array $normalizers = [], array $encoders = [])
    {
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $format = null, array $context = [])
    {
        return $this->serializer->normalize(
            is_array($data) ? $this->order($data) : $data,
            $format,
            $context
        );
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $this->serializer->supportsNormalization($data, $format);
    }

    /**
     * Orders objects if can be done.
     *
     * @param array $data Data to order.
     *
     * @return array
     */
    private function order(array $data)
    {
        $filteredData = $this->filterOrderable($data);

        if (!empty($filteredData)) {
            uasort(
                $filteredData,
                function (OrderedNormalizerInterface $a, OrderedNormalizerInterface $b) {
                    return $a->getOrder() > $b->getOrder() ? 1 : -1;
                }
            );

            return array_merge($filteredData, array_diff_key($data, $filteredData));
        }

        return $data;
    }

    /**
     * Filters out data which can be ordered.
     *
     * @param array $array Data to filter out.
     *
     * @return array
     */
    private function filterOrderable($array)
    {
        return array_filter(
            $array,
            function ($value) {
                return $value instanceof OrderedNormalizerInterface;
            }
        );
    }
}
