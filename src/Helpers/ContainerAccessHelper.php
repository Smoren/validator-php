<?php

declare(strict_types=1);

namespace Smoren\Validator\Helpers;

/**
 * @internal
 */
class ContainerAccessHelper
{
    /**
     * @param array<mixed>|\ArrayAccess<int|string, mixed> $container
     * @param string $attrName
     *
     * @return bool
     */
    public static function hasAccessibleAttribute($container, string $attrName): bool
    {
        $result = false;
        switch (true) {
            case \is_array($container):
                $result = \array_key_exists($attrName, $container);
                break;
            case $container instanceof \ArrayAccess:
                $result = $container->offsetExists($attrName);
                break;
            case \property_exists($container, $attrName):
                $result = (new \ReflectionClass($container))->getProperty($attrName)->isPublic();
                break;
        }
        return $result;
    }

    /**
     * @param array<mixed>|\ArrayAccess<int|string, mixed> $container
     * @param string $attrName
     *
     * @return mixed|null
     */
    public static function getAttributeValue($container, string $attrName)
    {
        $result = null;
        switch (true) {
            case \is_array($container):
                $result = $container[$attrName];
                break;
            case $container instanceof \ArrayAccess:
                $result = $container->offsetGet($attrName);
                break;
            case \property_exists($container, $attrName):
                $result = $container->{$container};
                break;
        }
        return $result;
    }
}
