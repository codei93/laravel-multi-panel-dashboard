<?php

namespace App\Filament\Default\Resources\Roles;

use App\Filament\Default\Resources\Roles\Pages\CreateRole;
use App\Filament\Default\Resources\Roles\Pages\EditRole;
use App\Filament\Default\Resources\Roles\Pages\ListRoles;
use App\Filament\Default\Resources\Roles\Pages\ViewRole;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;

class RoleResource extends BaseRoleResource
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->unique(
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn (Unique $rule): Unique => Utils::isTenancyEnabled()
                                            ? $rule->where(Utils::getTenantModelForeignKey(), Filament::getTenant()?->id)
                                            : $rule
                                    )
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),
                                Select::make(config('permission.column_names.team_foreign_key'))
                                    ->label(__('filament-shield::filament-shield.field.team'))
                                    ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                                    ->default(Filament::getTenant()?->id)
                                    ->options(fn (): array => in_array(Utils::getTenantModel(), [null, '', '0'], true) ? [] : Utils::getTenantModel()::pluck('name', 'id')->toArray())
                                    ->visible(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled())
                                    ->dehydrated(fn (): bool => static::shield()->isCentralApp() && Utils::isTenancyEnabled()),
                                static::getSelectAllFormComponent(),
                            ])
                            ->columns(['sm' => 2, 'lg' => 3])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                static::getShieldFormComponents(),
            ]);
    }

    public static function getShieldFormComponents(): Component
    {
        $tabs = static::getResourcesGroupedByPanel()
            ->map(fn (Collection $entities, string $panelId) => static::buildPanelTab($panelId, $entities->values()->toArray()))
            ->values()
            ->toArray();

        return Tabs::make('Permissions')
            ->contained()
            ->tabs($tabs)
            ->columnSpan('full');
    }

    protected static function buildPanelTab(string $panelId, array $entities): Tab
    {
        $panelLabel = Str::title(str_replace('_', ' ', $panelId)) . ' Panel';
        $toggleName = "panel_{$panelId}_select_all";
        $accessPermission = "access_{$panelId}_panel";
        $resourceFqcns = collect($entities)->pluck('resourceFqcn')->toArray();

        $resourceSections = collect($entities)
            ->map(fn (array $entity) => static::buildResourceSection($entity, $panelId, $resourceFqcns))
            ->toArray();

        $tabLabel = new HtmlString(
            '<span class="flex items-center gap-2">'
            . '<input type="checkbox"'
            . ' :checked="Array.isArray($wire.data.' . $accessPermission . ') && $wire.data.' . $accessPermission . '.length > 0"'
            . ' @change.stop="$wire.set(\'data.' . $accessPermission . '\', $event.target.checked ? [\'' . $accessPermission . '\'] : [])"'
            . ' @click.stop'
            . ' class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-primary-500"'
            . ' />'
            . '<span>' . e($panelLabel) . '</span>'
            . '</span>'
        );

        return Tab::make($panelId)
            ->label($tabLabel)
            ->schema([
                CheckboxList::make($accessPermission)
                    ->hiddenLabel()
                    ->options([$accessPermission => $panelLabel])
                    ->live()
                    ->afterStateHydrated(function (Component $component, string $operation, ?Model $record) use ($accessPermission, $panelLabel): void {
                        static::setPermissionStateForRecordPermissions(
                            component: $component,
                            operation: $operation,
                            permissions: [$accessPermission => $panelLabel],
                            record: $record
                        );
                    })
                    ->afterStateUpdated(function (Livewire $livewire, Set $set): void {
                        static::toggleSelectAllViaEntities($livewire, $set);
                    })
                    ->dehydrated(fn ($state): bool => ! blank($state))
                    ->extraAttributes(['style' => 'display:none']),
                Toggle::make($toggleName)
                    ->label(__('filament-shield::filament-shield.field.select_all.name') . ' — ' . $panelLabel)
                    ->onIcon('heroicon-s-shield-check')
                    ->offIcon('heroicon-s-shield-exclamation')
                    ->live()
                    ->afterStateUpdated(function (Livewire $livewire, Set $set, bool $state) use ($entities): void {
                        static::togglePanelPermissions($livewire, $set, $state, $entities);
                        static::toggleSelectAllViaEntities($livewire, $set);
                    })
                    ->dehydrated(false),
                Grid::make()
                    ->schema($resourceSections)
                    ->columns(static::shield()->getGridColumns()),
            ]);
    }

    protected static function buildResourceSection(array $entity, string $panelId, array $resourceFqcns): Section
    {
        $sectionLabel = static::shield()->hasLocalizedPermissionLabels()
            ? FilamentShield::getLocalizedResourceLabel($entity['resourceFqcn'])
            : $entity['model'];

        return Section::make($sectionLabel)
            ->description(fn (): HtmlString => new HtmlString(
                '<span style="word-break: break-word;">' . Utils::showModelPath($entity['resourceFqcn']) . '</span>'
            ))
            ->compact()
            ->schema([
                static::buildPanelCheckboxList($entity, $panelId, $resourceFqcns),
            ])
            ->columnSpan(static::shield()->getSectionColumnSpan())
            ->collapsible();
    }

    protected static function buildPanelCheckboxList(array $entity, string $panelId, array $resourceFqcns): Component
    {
        $permissionsArray = static::getResourcePermissionOptions($entity);
        $toggleName = "panel_{$panelId}_select_all";

        return CheckboxList::make($entity['resourceFqcn'])
            ->hiddenLabel()
            ->options(fn (): array => $permissionsArray)
            ->searchable(false)
            ->live()
            ->afterStateHydrated(function (Component $component, string $operation, ?Model $record, Set $set) use ($permissionsArray, $toggleName, $resourceFqcns): void {
                static::setPermissionStateForRecordPermissions(
                    component: $component,
                    operation: $operation,
                    permissions: $permissionsArray,
                    record: $record
                );
                static::toggleSelectAllViaEntities($component->getLivewire(), $set);
                static::syncPanelToggle($component->getLivewire(), $set, $toggleName, $resourceFqcns);
            })
            ->afterStateUpdated(function (Livewire $livewire, Set $set) use ($toggleName, $resourceFqcns): void {
                static::toggleSelectAllViaEntities($livewire, $set);
                static::syncPanelToggle($livewire, $set, $toggleName, $resourceFqcns);
            })
            ->selectAllAction(fn (
                Action $action,
                Component $component,
                Livewire $livewire,
                Set $set
            ) => static::bulkToggleableAction(
                action: $action,
                component: $component,
                livewire: $livewire,
                set: $set
            ))
            ->deselectAllAction(fn (
                Action $action,
                Component $component,
                Livewire $livewire,
                Set $set
            ) => static::bulkToggleableAction(
                action: $action,
                component: $component,
                livewire: $livewire,
                set: $set,
                resetState: true
            ))
            ->dehydrated(fn ($state): bool => ! blank($state))
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns(static::shield()->getResourceCheckboxListColumns())
            ->columnSpan(static::shield()->getResourceCheckboxListColumnSpan());
    }

    protected static function getResourcesGroupedByPanel(): Collection
    {
        return collect(FilamentShield::getResources())
            ->unique(fn (array $entity): string => $entity['modelFqcn'])
            ->groupBy(fn (array $entity): string => static::getPanelIdForResource($entity['resourceFqcn']))
            ->sortKeys();
    }

    protected static function getPanelIdForResource(string $resourceFqcn): string
    {
        if (Str::contains($resourceFqcn, 'App\\Filament\\')) {
            return Str::of($resourceFqcn)
                ->after('App\\Filament\\')
                ->before('\\')
                ->lower()
                ->toString();
        }

        return 'default';
    }

    protected static function togglePanelPermissions(Livewire $livewire, Set $set, bool $state, array $entities): void
    {
        $resourceFqcns = collect($entities)->pluck('resourceFqcn')->toArray();

        collect($livewire->form->getFlatComponents())
            ->filter(fn (Component $component): bool =>
                $component instanceof CheckboxList &&
                in_array($component->getName(), $resourceFqcns)
            )
            ->each(function (CheckboxList $component) use ($set, $state): void {
                $set($component->getName(), $state ? array_keys($component->getOptions()) : []);
            });
    }

    protected static function syncPanelToggle(Livewire $livewire, Set $set, string $toggleName, array $resourceFqcns): void
    {
        $panelCheckboxes = collect($livewire->form->getFlatComponents())
            ->filter(fn (Component $c): bool =>
                $c instanceof CheckboxList &&
                in_array($c->getName(), $resourceFqcns) &&
                count($c->getOptions()) > 0
            );

        if ($panelCheckboxes->isEmpty()) {
            return;
        }

        $allChecked = $panelCheckboxes->every(fn (CheckboxList $c): bool =>
            count(array_keys($c->getOptions())) === count(collect($c->getState())->values()->unique()->toArray())
        );

        $set($toggleName, $allChecked);
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->recordActions([
                EditAction::make()
                    ->modal(false)
                    ->url(fn (Model $record): string => static::getUrl('edit', ['record' => $record])),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
