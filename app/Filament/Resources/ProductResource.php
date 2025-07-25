<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Jobs\ProcessProduct;
use App\Livewire\StatusBar;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('product_category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('product_color_id')
                    ->relationship('color', 'name')
                    ->required(),
                Forms\Components\Select::make('type_ids')
                    ->multiple()
                    ->options(ProductType::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $categories = ProductCategory::whereHas('typeAssignments', function ($q) use ($state) {
                            $q->whereIn('product_type_id', $state);
                        })->pluck('name', 'id');
                        $set('product_category_id', null);
                        $set('available_categories', $categories);
                    }),
                Forms\Components\Livewire::make(StatusBar::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('color.name'),
                Tables\Columns\TextColumn::make('typeAssignments.productType.name')->listWithLineBreaks(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('process')
                    ->action(function (Product $record) {
                        ProcessProduct::dispatch($record);
                    })
                    ->icon('heroicon-o-cog')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
