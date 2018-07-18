<?php

namespace Mochilo;

class Helper
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Helper constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function img(
        string $src,
        int $height = null,
        int $width = null,
        string $alt = null,
        string $class = null,
        string $id = null
    ) {
        $img = '<img %s%s%s%s%s%s/>';

        $srcValue = sprintf('src="%s%s/%s" ', $this->config->get('base_url'), $this->config->get('img_url'), $src);
        $heightValue = $height ? sprintf('height="%d" ', $height): '';
        $widthValue = $width ? sprintf('width="%d" ', $width): '';
        $altValue = $alt ? sprintf('alt="%s" ', $alt): '';
        $classValue = $class ? sprintf('class="%s" ', $class): '';
        $idValue = $id ? sprintf('id="%s" ', $id): '';

        return sprintf($img, $srcValue, $heightValue, $widthValue, $altValue, $classValue, $idValue);
    }
}