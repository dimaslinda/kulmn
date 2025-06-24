<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactionDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Layanan')
                    ->disabled()
                    ->nullable(), // Bisa null jika ini produk
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Produk')
                    ->disabled()
                    ->nullable(), // Bisa null jika ini layanan
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->label('Tipe')
                    ->disabled()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.name')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Layanan')
                    ->default('-'),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->default('-'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}