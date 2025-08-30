# Filament Game Icons

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alizharb/filament-game-icons.svg?style=flat-square)](https://packagist.org/packages/alizharb/filament-game-icons)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alizharb/filament-game-icons/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alizharb/filament-game-icons/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alizharb/filament-game-icons/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alizharb/filament-game-icons/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alizharb/filament-game-icons.svg?style=flat-square)](https://packagist.org/packages/alizharb/filament-game-icons)

A comprehensive FilamentPHP 4 package that provides a type-safe enum for all 4000+ Game Icons from [game-icons.net](https://game-icons.net/). This package gives you IDE autocompletion, type safety, and easy integration with all FilamentPHP components.

## Features

- 🎮 **4000+ Game Icons**: Complete collection from [game-icons.net](https://game-icons.net/)
- 🔧 **Type Safe**: Full PHP enum with IDE autocompletion
- 🏷️ **Human Readable Labels**: Implements `HasLabel` for better UX
- 📦 **Zero Configuration**: Works out of the box with FilamentPHP 4
- 🔍 **Searchable**: Built-in search and categorization methods
- ⚡ **Performance Optimized**: Leverages blade-game-icons for caching
- 🎨 **Customizable**: Easy icon replacement and theming

## Installation

You can install the package via composer:

```bash
composer require alizharb/filament-game-icons
```

This package automatically installs `codeat3/blade-game-icons` as a dependency.

## Usage

### Basic Usage

Use Game Icons just like Heroicons in any FilamentPHP component:

```php
use Alizharb\FilamentGameIcons\Enums\GameIcons;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;

// Actions
Action::make('attack')
    ->icon(GameIcons::Sword)
    ->label('Attack with Sword');

Action::make('cast_spell')
    ->icon(GameIcons::MagicSwirl)
    ->color('primary');

// Form Components
Toggle::make('is_armed')
    ->onIcon(GameIcons::Sword)
    ->offIcon(GameIcons::Shield);

// Table Columns
IconColumn::make('weapon_type')
    ->icon(fn (string $state): string => match ($state) {
        'sword' => GameIcons::Sword->value,
        'bow' => GameIcons::BowArrow->value,
        'magic' => GameIcons::MagicSwirl->value,
        default => GameIcons::CrossedSwords->value,
    });
```

### Select Fields with Icon Options

Create beautiful select fields with Game Icons:

```php
use Filament\Forms\Components\Select;

Select::make('character_class')
    ->options(GameIcons::toSelectArray())
    ->searchable()
    ->native(false);

// Or use categorized options
Select::make('weapon')
    ->options(GameIcons::getWeaponsArray())
    ->searchable();
```

### Advanced Usage

#### Icon Categories

Get icons by category for organized interfaces:

```php
// Get all weapon icons
$weapons = GameIcons::getWeapons();

// Get all magic-related icons
$magic = GameIcons::getMagic();

// Get all character class icons
$characters = GameIcons::getCharacters();

// Get all creature icons
$creatures = GameIcons::getCreatures();

// Get dice icons
$dice = GameIcons::getDice();
```

#### Search Functionality

Search icons dynamically:

```php
// Search for icons containing "sword"
$swordIcons = GameIcons::search('sword');

// Search for magic-related icons
$magicIcons = GameIcons::search('magic');
```

#### Dynamic Icon Creation

For icons not in the enum or dynamic usage:

```php
// Create icon reference for any game-icon
$customIcon = GameIcons::make('custom-weapon-name');

Action::make('special_attack')
    ->icon($customIcon);
```

### Resource Example

Complete example in a FilamentPHP Resource:

```php
<?php

namespace App\Filament\Resources;

use Alizharb\FilamentGameIcons\Enums\GameIcons;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->components([
                Filament\Forms\Components\TextInput::make('name')
                    ->required(),

                Filament\Forms\Components\Select::make('class')
                    ->options([
                        'warrior' => 'Warrior',
                        'wizard' => 'Wizard',
                        'archer' => 'Archer',
                        'rogue' => 'Rogue',
                    ])
                    ->required(),

                Filament\Forms\Components\Select::make('primary_weapon')
                    ->options(GameIcons::getWeaponsArray())
                    ->searchable()
                    ->native(false),

                Filament\Forms\Components\Toggle::make('is_alive')
                    ->onIcon(GameIcons::Heart)
                    ->offIcon(GameIcons::Skull),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Filament\Tables\Columns\TextColumn::make('name'),

                Filament\Tables\Columns\IconColumn::make('class')
                    ->icon(fn (string $state): string => match ($state) {
                        'warrior' => GameIcons::Warrior->value,
                        'wizard' => GameIcons::Wizard->value,
                        'archer' => GameIcons::Archer->value,
                        'rogue' => GameIcons::Rogue->value,
                        default => GameIcons::Person->value,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'warrior' => 'danger',
                        'wizard' => 'info',
                        'archer' => 'success',
                        'rogue' => 'warning',
                        default => 'gray',
                    }),

                Filament\Tables\Columns\IconColumn::make('is_alive')
                    ->boolean()
                    ->trueIcon(GameIcons::Heart)
                    ->falseIcon(GameIcons::Skull)
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->recordActions([
                Filament\Actions\Action::make('revive')
                    ->icon(GameIcons::HealingPotion)
                    ->action(fn ($record) => $record->update(['is_alive' => true]))
                    ->visible(fn ($record) => !$record->is_alive),

                Filament\Actions\EditAction::make()
                    ->icon(GameIcons::Scroll),

                Filament\Actions\DeleteAction::make()
                    ->icon(GameIcons::Skull),
            ]);
    }
}
```

## Available Icon Categories

The package organizes icons into logical categories:

### Weapons & Combat

- Swords, Axes, Bows, Guns, Shields, and more
- Perfect for RPG and combat games

### Magic & Spells

- Magic effects, potions, staffs, runes, spells
- Ideal for fantasy and magical themes

### Characters & Classes

- Wizard, Warrior, Archer, Rogue, Paladin, etc.
- Complete set of RPG character archetypes

### Creatures & Monsters

- Dragons, wolves, mythical creatures
- Great for bestiary and creature management

### Items & Equipment

- Armor, weapons, accessories, treasures
- Perfect for inventory systems

### Game Mechanics

- Dice (D4, D6, D8, D10, D12, D20), cards, timers
- Essential for game rule interfaces

### Environment & World

- Castles, caves, forests, buildings
- World-building and location icons

## API Reference

### GameIcons Enum Methods

```php
// Get human-readable label
GameIcons::Sword->getLabel(); // Returns: "Sword"

// Get all icons as select options
GameIcons::toSelectArray(); // Returns: ['gameicon-sword' => 'Sword', ...]

// Search icons
GameIcons::search('magic'); // Returns: [GameIcons::MagicSwirl, ...]

// Get random icon
GameIcons::random(); // Returns: random GameIcons case

// Category helpers
GameIcons::getWeapons();    // Returns: array of weapon icons
GameIcons::getMagic();      // Returns: array of magic icons
GameIcons::getCharacters(); // Returns: array of character icons
GameIcons::getCreatures();  // Returns: array of creature icons
GameIcons::getDice();       // Returns: array of dice icons

// Get category as select array
GameIcons::getWeaponsArray();    // Returns: ['gameicon-sword' => 'Sword', ...]
GameIcons::getMagicArray();      // Returns: ['gameicon-magic-swirl' => 'Magic Swirl', ...]

// Dynamic icon creation
GameIcons::make('custom-icon-name'); // Returns: 'gameicon-custom-icon-name'
```

## Integration with FilamentPHP Components

### Actions

```php
use AliZharb\FilamentGameIcons\Enums\GameIcons;

Action::make('attack')
    ->icon(GameIcons::Sword)
    ->color('danger');

Action::make('heal')
    ->icon(GameIcons::HealingPotion)
    ->color('success');
```

### Form Components

```php
Toggle::make('is_magical')
    ->onIcon(GameIcons::MagicSwirl)
    ->offIcon(GameIcons::Sword);

Select::make('weapon_type')
    ->options(GameIcons::getWeaponsArray())
    ->searchable();

Radio::make('character_class')
    ->options(GameIcons::getCharactersArray())
    ->descriptions([
        GameIcons::Warrior->value => 'Strong melee fighter',
        GameIcons::Wizard->value => 'Powerful spell caster',
        GameIcons::Archer->value => 'Ranged combat specialist',
    ]);
```

### Table Columns

```php
IconColumn::make('status')
    ->icon(fn (string $state): string => match ($state) {
        'alive' => GameIcons::Heart->value,
        'dead' => GameIcons::Skull->value,
        'injured' => GameIcons::BandagedHeart->value,
        default => GameIcons::Question->value,
    })
    ->color(fn (string $state): string => match ($state) {
        'alive' => 'success',
        'dead' => 'danger',
        'injured' => 'warning',
        default => 'gray',
    });
```

### Info Lists

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('character_class')
    ->icon(fn (string $state): string => match ($state) {
        'warrior' => GameIcons::Warrior->value,
        'wizard' => GameIcons::Wizard->value,
        default => GameIcons::Person->value,
    });
```

### Blade Components

```php
<x-filament::badge :icon="GameIcons::Sword">
    Warrior
</x-filament::badge>

<x-filament::button :icon="GameIcons::MagicSwirl">
    Cast Spell
</x-filament::button>
```

## Replacing Default Filament Icons

Replace any default FilamentPHP icon with Game Icons:

```php
// In your AppServiceProvider boot() method
use Filament\Support\Facades\FilamentIcon;
use Filament\View\PanelsIconAlias;
use AliZharb\FilamentGameIcons\Enums\GameIcons;

public function boot(): void
{
    FilamentIcon::register([
        PanelsIconAlias::ACTIONS_DELETE_ACTION => GameIcons::Skull->value,
        PanelsIconAlias::ACTIONS_EDIT_ACTION => GameIcons::Scroll->value,
        PanelsIconAlias::ACTIONS_VIEW_ACTION => GameIcons::Eye->value,
        PanelsIconAlias::GLOBAL_SEARCH_FIELD => GameIcons::Search->value,
    ]);
}
```

### Console Command: Sync Icons

The package includes a handy Artisan command to **keep your `GameIcons` enum always up to date** with the latest icons from the `blade-game-icons` package.

````bash
php artisan sync:game-icons-enum
php artisan sync:game-icons-enum --dry-run
```

## Theming & Customization

Game Icons are SVG-based and fully customizable:

### CSS Styling
```css
/* Change icon color */
.game-icon {
    color: #3b82f6; /* Blue */
}

/* Icon sizing */
.game-icon-sm { width: 1rem; height: 1rem; }
.game-icon-md { width: 1.5rem; height: 1.5rem; }
.game-icon-lg { width: 2rem; height: 2rem; }
````

### Tailwind Classes

```php
Action::make('attack')
    ->icon(GameIcons::Sword)
    ->extraAttributes(['class' => 'text-red-500 w-6 h-6']);
```

## Icon Categories

The package includes over 4000 icons organized in categories:

| Category        | Description                       | Example Icons                         |
| --------------- | --------------------------------- | ------------------------------------- |
| **Weapons**     | Swords, axes, bows, guns, shields | `Sword`, `BowArrow`, `Shield`         |
| **Magic**       | Spells, potions, staffs, runes    | `MagicSwirl`, `HealingPotion`, `Rune` |
| **Characters**  | RPG classes and character types   | `Wizard`, `Warrior`, `Archer`         |
| **Creatures**   | Dragons, animals, monsters        | `Dragon`, `Wolf`, `Phoenix`           |
| **Items**       | Armor, jewelry, treasures         | `Armor`, `Crown`, `Gem`               |
| **Dice**        | Gaming dice D4 through D20        | `D4`, `D6`, `D20`                     |
| **Environment** | Buildings, nature, locations      | `Castle`, `Forest`, `Cave`            |
| **UI Elements** | Interface icons for games         | `Settings`, `Menu`, `Save`            |

## Advanced Examples

### Dynamic Icon Selection

```php
use Filament\Forms\Components\Select;

Select::make('icon')
    ->options(GameIcons::toSelectArray())
    ->searchable()
    ->allowHtml()
    ->getOptionLabelUsing(fn ($value): string =>
        '<div class="flex items-center gap-2">' .
        '<x-gameicon name="' . str_replace('gameicon-', '', $value) . '" class="w-4 h-4" />' .
        GameIcons::from($value)->getLabel() .
        '</div>'
    );
```

### Conditional Icons Based on Game State

```php
IconColumn::make('health_status')
    ->icon(fn ($record): string => match (true) {
        $record->health >= 80 => GameIcons::Heart->value,
        $record->health >= 40 => GameIcons::BandagedHeart->value,
        $record->health > 0 => GameIcons::BrokenHeart->value,
        default => GameIcons::Skull->value,
    })
    ->color(fn ($record): string => match (true) {
        $record->health >= 80 => 'success',
        $record->health >= 40 => 'warning',
        $record->health > 0 => 'danger',
        default => 'gray',
    });
```

### Custom Icon Categories

```php
// Create your own categorization
class MyGameIcons
{
    public static function getElementalMagic(): array
    {
        return [
            GameIcons::Fire,
            GameIcons::Water,
            GameIcons::Earth,
            GameIcons::Air,
            GameIcons::Lightning,
            GameIcons::Ice,
        ];
    }

    public static function getMeleeWeapons(): array
    {
        return [
            GameIcons::Sword,
            GameIcons::Axe,
            GameIcons::Hammer,
            GameIcons::Mace,
            GameIcons::Dagger,
        ];
    }
}
```

## Widget Integration

Perfect for gaming dashboards and widgets:

```php
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GameStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Players', '1,234')
                ->description('Currently online')
                ->descriptionIcon(GameIcons::Person->value)
                ->color('success'),

            Stat::make('Total Battles', '5,678')
                ->description('This month')
                ->descriptionIcon(GameIcons::CrossedSwords->value)
                ->color('danger'),

            Stat::make('Magic Items', '2,345')
                ->description('In circulation')
                ->descriptionIcon(GameIcons::MagicSwirl->value)
                ->color('primary'),
        ];
    }
}
```

## Requirements

- PHP 8.1 or higher
- Laravel 10 / 11 / 12
- FilamentPHP 4

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ali harb](https://github.com/alizharb)
- [Game Icons](https://game-icons.net/) - For the amazing icon collection
- [Blade Game Icons](https://github.com/codeat3/blade-game-icons) - For the Laravel integration
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Game Icons License

The Game Icons used in this package are licensed under [CC BY 3.0](https://creativecommons.org/licenses/by/3.0/). When using this package, you're required to give appropriate credit to the Game Icons project.

### Attribution

Add this to your application's credits or about page:

```
Icons made by various authors from https://game-icons.net/,
licensed under CC BY 3.0 (https://creativecommons.org/licenses/by/3.0/)
```

## Support

- 📖 [Documentation](https://github.com/alizharb/filament-game-icons/wiki)
- 🐛 [Issue Tracker](https://github.com/alizharb/filament-game-icons/issues)
- 💬 [Discussions](https://github.com/alizharb/filament-game-icons/discussions)
- 🌟 [FilamentPHP Discord](https://discord.gg/filamentphp)

---

<div align="center">

**Made with ❤️ for the FilamentPHP community**

[⭐ Star this repo](https://github.com/alizharb/filament-game-icons) • [🐛 Report Bug](https://github.com/alizharb/filament-game-icons/issues) • [✨ Request Feature](https://github.com/alizharb/filament-game-icons/issues)

</div>
