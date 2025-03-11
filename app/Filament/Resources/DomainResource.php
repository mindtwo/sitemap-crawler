<?php declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages\ListDomains;
use App\Models\Domain;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('locations'))
            ->columns([
                TextColumn::make('domain')
                    ->label(__('Domain')),
                TextColumn::make('locations_count')
                    ->label(__('Locations'))
                    ->url(fn (Domain $record) => LocationResource::getUrl('index', [
                        'tableFilters' => [
                            'domain_id' => [
                                'value' => $record->id,
                            ],
                        ],
                    ]))
                    ->icon('heroicon-m-link'),
            ])
            ->actions([DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDomains::route('/'),
        ];
    }
}
