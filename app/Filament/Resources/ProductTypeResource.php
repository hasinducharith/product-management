<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTypeResource\Pages;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class ProductTypeResource extends Resource
{
    protected static ?string $model = ProductType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('api_unique_number')
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('fetch')
                            ->action(function ($set) {
                                $response = Http::withHeaders([
                                    'Authorization' => 'Bearer ' . self::getBearerToken(),
                                    'Content-Type' => 'application/json',
                                    'Accept' => 'application/json',
                                ])->post('https://extranet.asmorphic.com/api/orders/findaddress', [
                                    'company_id' => 17,
                                    'street_name' => 'Sample Street',
                                    'suburb' => 'Melbourne',
                                    'postcode' => '3000',
                                    'state' => 'VIC',
                                ]);
                                $data = $response->json();
                                $set('api_unique_number', $data[0]['DirectoryIdentifier'] ?? self::generateRandomString(10));
                            }),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('api_unique_number'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProductTypes::route('/'),
            'create' => Pages\CreateProductType::route('/create'),
            'edit' => Pages\EditProductType::route('/{record}/edit'),
        ];
    }

    private static function getBearerToken(): string
    {
        $response = Http::post('https://extranet.asmorphic.com/api/login', [
            'email' => 'project-test@projecttest.com.au',
            'password' => 'oxhyV9NzkZ^02MEB',
        ]);
        return $response->json('token') ?? '';
    }

    public static function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
