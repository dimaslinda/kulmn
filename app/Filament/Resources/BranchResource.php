<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Branch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BranchResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Widgets\AverageTransactionOverview;
use App\Filament\Resources\BranchResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Manajemen Cabang';
    protected static ?string $modelLabel = 'Cabang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Cabang')
                            ->placeholder('Contoh: Barbershop Bandung')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Cabang')
                            ->placeholder('Contoh: BDG, JKT, SBY')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->helperText('Contoh: BDG, JKT, SBY. Digunakan untuk prefix invoice.'),
                    ])->columns(2),

                Forms\Components\Section::make('Lokasi')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->placeholder('Contoh: Jl. Raya Bandung')
                            ->maxLength(65535)
                            ->columnSpanFull(),


                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->placeholder('Contoh: -6.2088')
                                    ->numeric()
                                    ->nullable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('longitude')) {
                                            $set('map_url', '<iframe src="https://www.google.com/maps?q=' . $state . ',' . $get('longitude') . '&output=embed" class="border-0 w-full md:w-3/4 h-full" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
                                        } else {
                                            $set('map_url', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->placeholder('Contoh: 106.8456')
                                    ->numeric()
                                    ->nullable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $get('latitude')) {
                                            $set('map_url', '<iframe src="https://www.google.com/maps?q=' . $get('latitude') . ',' . $state . '&output=embed" class="border-0 w-full md:w-3/4 h-full" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
                                        } else {
                                            $set('map_url', null);
                                        }
                                    }),
                            ]),
                        Forms\Components\ViewField::make('map_url')
                            ->label('Google Map Embed')
                            ->view('filament.forms.components.google-map')
                            ->hidden(fn(callable $get): bool => ! $get('map_url'))

                    ]),

                Forms\Components\Section::make('Kontak & Media')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->maxLength(255),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('branch_images')
                            ->image()
                            ->hint('Gambar cabang')
                            ->label('Gambar Cabang')
                            ->hintIcon('heroicon-o-information-circle')
                            ->hintColor('primary')
                            ->imageEditor()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Cabang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(50),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }

    // Tambahkan atau modifikasi bagian ini
    public static function getWidgets(): array
    {
        return [
            AverageTransactionOverview::class,
        ];
    }

    // Opsi: Jika Anda ingin widget muncul di halaman daftar resource
    public static function getHeaderWidgets(): array
    {
        return [
            AverageTransactionOverview::class,
        ];
    }
}
