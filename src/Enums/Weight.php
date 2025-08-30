<?php

declare(strict_types=1);

namespace Alizharb\FilamentGameIcons\Enums;

/**
 * Enum Weight
 *
 * Represents the available icon weights in FilamentGameIcons.
 *
 * @package Alizharb\FilamentGameIcons\Enums
 *
 * @method static self Thin()
 * @method static self Light()
 * @method static self Fill()
 * @method static self Duotone()
 * @method static self Bold()
 * @method static self Regular()
 *
 * @implements \BackedEnum<string>
 */
enum Weight: string
{
    /** Extra thin weight */
    case Thin = 'thin';

    /** Light weight */
    case Light = 'light';

    /** Filled style */
    case Fill = 'fill';

    /** Duotone style */
    case Duotone = 'duotone';

    /** Bold weight */
    case Bold = 'bold';

    /** Default regular weight */
    case Regular = 'regular';
}
