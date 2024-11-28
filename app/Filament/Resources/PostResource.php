<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        '1' => 'Public',
                        '2' => 'Draft',
                        '3' => 'Pending',
                    ])
                    ->required(),
                Forms\Components\MarkdownEditor::make('content')
                    ->columnSpan('full')
                    ->required(), //sử dụng cú pháp markdown để viết nội dung
                Forms\Components\FileUpload::make('image')
                    ->required(), //upload file ảnh
                Forms\Components\Select::make('user_id')
                    ->options(User::all()->pluck('email', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([ //khai báo các cột trong bảng danh sách
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextInputColumn::make('title')->label('Tiêu đề')
                    ->rules(['required', 'min:3', 'max:20'])
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        '1' => 'Public',
                        '2' => 'Draft',
                        '3' => 'Pending',
                    ]),
                    // ->description(fn (Post $record): string => $record->content),
                Tables\Columns\ImageColumn::make('image')->label('Ảnh bìa'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('content')
                    //->lineClamp(2), //giới hạn theo số dòng
                    ->words(10), //giới hạn 10 từ
                    //->limit(30), //giới hạn 30 ký tự
            ])
            ->filters([ //khai báo những trường để thực hiện lọc
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Public',
                        '2' => 'Draft',
                        '3' => 'Pending',
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'email')
                    ->options(User::all()->pluck('email', 'id'))
                    ->multiple()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
