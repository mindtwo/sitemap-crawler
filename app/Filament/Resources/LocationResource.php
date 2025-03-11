<?php declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages\ListLocations;
use App\Models\Domain;
use App\Models\Location;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;
    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['domain']))
            ->defaultSort('last_modified_at', 'desc')
            ->columns([
                TextColumn::make('location')
                    ->label(__('Location'))
                    ->icon('heroicon-o-link')
                    ->url(fn (Location $record) => $record->location)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('domain.domain')
                    ->label(__('Domain')),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->date('d.m.Y'),
                TextColumn::make('last_modified_at')
                    ->label(__('Modified At'))
                    ->date('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('domain_id')
                    ->label(__('Domain'))
                    ->options(fn () => Domain::query()->pluck('domain', 'id')),
            ])
            ->actions([ViewAction::make()]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('location')
                    ->label(__('Location'))
                    ->icon('heroicon-o-link')
                    ->url(fn (Location $record) => $record->location),
                TextEntry::make('change_frequency')
                    ->label(__('Change Frequency'))
                    ->badge(),
                TextEntry::make('priority')
                    ->label(__('Priority')),
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->date('d.m.Y'),
                TextEntry::make('last_modified_at')
                    ->label(__('Modified At'))
                    ->date('d.m.Y H:i'),
                TextEntry::make('status')
                    ->label(__('Status'))
                    ->badge(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
        ];
    }
}
