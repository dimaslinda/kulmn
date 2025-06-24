<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TransactionDetail;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionDetailResource\Pages;
use App\Filament\Resources\TransactionDetailResource\RelationManagers;

class TransactionDetailResource extends Resource
{
    protected static ?string $model = TransactionDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Manajemen Penjualan';
    protected static ?string $modelLabel = 'Detail Transaksi';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'invoice_number')
                    ->label('Transaksi (Invoice)')
                    ->disabled() // PERBAIKAN: Gunakan disabled() untuk Select
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Layanan')
                    ->disabled() // PERBAIKAN: Gunakan disabled() untuk Select
                    ->nullable(), // Bisa null jika ini produk
                Forms\Components\Select::make('product_id') // Tambahkan ini
                    ->relationship('product', 'name')
                    ->label('Produk')
                    ->disabled() // PERBAIKAN: Gunakan disabled() untuk Select
                    ->nullable(), // Bisa null jika ini layanan
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('type') // Tambahkan ini
                    ->label('Tipe')
                    ->disabled()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.branch.code')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable()
                    ->url(fn (TransactionDetail $record): string => TransactionResource::getUrl('view', ['record' => $record->transaction_id])),
                Tables\Columns\TextColumn::make('type') // Tampilkan tipe (layanan/produk)
                    ->label('Tipe')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name') // Tampilkan nama layanan
                    ->label('Layanan')
                    ->searchable()
                    ->sortable()
                    ->default('-'), // Default jika tidak ada layanan
                Tables\Columns\TextColumn::make('product.name') // Tampilkan nama produk
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->default('-'), // Default jika tidak ada produk
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Detail')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('transaction.branch_id')
                    ->label('Filter Berdasarkan Cabang')
                    ->relationship('transaction.branch', 'name')
                    ->searchable(),
                SelectFilter::make('type') // Filter berdasarkan tipe
                    ->label('Filter Tipe')
                    ->options([
                        'service' => 'Layanan',
                        'product' => 'Produk',
                    ]),
                SelectFilter::make('service_id')
                    ->label('Filter Berdasarkan Layanan')
                    ->relationship('service', 'name')
                    ->searchable(),
                SelectFilter::make('product_id')
                    ->label('Filter Berdasarkan Produk')
                    ->relationship('product', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([]);
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
            'index' => Pages\ListTransactionDetails::route('/'),
            'create' => Pages\CreateTransactionDetail::route('/create'),
            'view' => Pages\ViewTransactionDetail::route('/{record}'),
            'edit' => Pages\EditTransactionDetail::route('/{record}/edit'),
        ];
    }
}