<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'statusCode' => 200,
    ], 200);
});

Route::post('/auth', 'App\Http\Controllers\AuthController@login');
Route::post('/register', 'App\Http\Controllers\AuthController@register');

Route::middleware('api.user')->group(function () {
    Route::get('/discovery', 'App\Http\Controllers\UserController@get_discovery');
    Route::get('/search', 'App\Http\Controllers\UserController@search');
    Route::get('/play/{id}', 'App\Http\Controllers\UserController@play');

    Route::prefix('/user')->group(function () {
        Route::prefix('/playlist')->group(function () {
            Route::get('/me', 'App\Http\Controllers\UserController@get_my_playlist');
            Route::get('/{id}', 'App\Http\Controllers\UserController@get_playlist_by_id');
            Route::post('/', 'App\Http\Controllers\UserController@create_playlist');
            Route::post('/{id}', 'App\Http\Controllers\UserController@update_playlist');
            Route::delete('/{id}', 'App\Http\Controllers\UserController@delete_playlist');

            // add or remove song from playlist
            Route::post('/{id}/song', 'App\Http\Controllers\UserController@add_song_to_playlist');
            Route::delete('/{id}/song/{song_id}', 'App\Http\Controllers\UserController@remove_song_from_playlist');
        });

        Route::get('/following', 'App\Http\Controllers\UserController@get_following_artist');
        Route::post('/follow/{id}', 'App\Http\Controllers\UserController@follow');
        Route::post('/unfollow/{id}', 'App\Http\Controllers\UserController@unfollow');
    });

    Route::get('/albums', 'App\Http\Controllers\UserController@get_all_albums');
    Route::get('/albums/{id}', 'App\Http\Controllers\UserController@get_album_by_id');

    Route::get('/liked-songs', 'App\Http\Controllers\UserController@get_liked_songs');
    Route::post('/like/{id}', 'App\Http\Controllers\UserController@like');
    Route::post('/unlike/{id}', 'App\Http\Controllers\UserController@unlike');
});

Route::middleware('api.artist')->prefix('/artist')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the artist API',
            'statusCode' => 200,
        ], 200);
    });

    Route::post('/register', 'App\Http\Controllers\ArtistController@register');
    Route::get('/followers', 'App\Http\Controllers\ArtistController@get_followers');
    Route::get('/followers/{id}', 'App\Http\Controllers\ArtistController@get_follower_by_id');

    // create, read, update, delete album
    Route::get('/albums', 'App\Http\Controllers\ArtistController@get_all_albums');
    Route::get('/albums/{id}', 'App\Http\Controllers\ArtistController@get_album_by_id');
    Route::post('/albums', 'App\Http\Controllers\ArtistController@create_album');
    Route::post('/albums/{id}', 'App\Http\Controllers\ArtistController@update_album');
    Route::delete('/albums/{id}', 'App\Http\Controllers\ArtistController@delete_album');

    // create, read, update, delete song
    Route::get('/songs', 'App\Http\Controllers\ArtistController@get_all_songs');
    Route::get('/songs/{id}', 'App\Http\Controllers\ArtistController@get_song_by_id');
    Route::post('/songs', 'App\Http\Controllers\ArtistController@create_song');
    Route::put('/songs/{id}', 'App\Http\Controllers\ArtistController@update_song');
    Route::delete('/songs/{id}', 'App\Http\Controllers\ArtistController@delete_song');
});

Route::middleware('api.admin')->prefix('/admin')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the admin API',
            'statusCode' => 200,
        ], 200);
    });

    Route::prefix('/users')->group(function () {
        Route::get('/', 'App\Http\Controllers\UserManagementController@get_all_users');
        Route::get('/{id}', 'App\Http\Controllers\UserManagementController@get_user_by_id');
        Route::post('/', 'App\Http\Controllers\UserManagementController@create_user');
        Route::put('/{id}', 'App\Http\Controllers\UserManagementController@update_user');
        Route::delete('/{id}', 'App\Http\Controllers\UserManagementController@delete_user');
    });

    Route::prefix('/artists')->group(function () {
        Route::get('/', 'App\Http\Controllers\ArtistManagementController@get_all_artists');
        Route::get('/{id}', 'App\Http\Controllers\ArtistManagementController@get_artist_by_id');
        Route::post('/', 'App\Http\Controllers\ArtistManagementController@create_artist');
        Route::post('/{id}', 'App\Http\Controllers\ArtistManagementController@update_artist');
        Route::delete('/{id}', 'App\Http\Controllers\ArtistManagementController@delete_artist');
    });

    Route::prefix('/albums')->group(function () {
        Route::get('/', 'App\Http\Controllers\AlbumManagementController@get_all_albums');
        Route::get('/{id}', 'App\Http\Controllers\AlbumManagementController@get_album_by_id');
        Route::post('/', 'App\Http\Controllers\AlbumManagementController@create_album');
        Route::post('/{id}', 'App\Http\Controllers\AlbumManagementController@update_album');
        Route::delete('/{id}', 'App\Http\Controllers\AlbumManagementController@delete_album');
    });

    Route::prefix('/songs')->group(function () {
        Route::get('/', 'App\Http\Controllers\SongManagementController@get_all_songs');
        Route::get('/{id}', 'App\Http\Controllers\SongManagementController@get_song_by_id');
        Route::post('/', 'App\Http\Controllers\SongManagementController@create_song');
        Route::put('/{id}', 'App\Http\Controllers\SongManagementController@update_song');
        Route::delete('/{id}', 'App\Http\Controllers\SongManagementController@delete_song');

        Route::put('/approve/{id}', 'App\Http\Controllers\SongManagementController@approve_song');
        Route::put('/reject/{id}', 'App\Http\Controllers\SongManagementController@reject_song');
    });

    Route::prefix('/playlists')->group(function () {
        Route::get('/', 'App\Http\Controllers\PlaylistManagementController@get_all_playlists');
        Route::get('/{id}', 'App\Http\Controllers\PlaylistManagementController@get_playlist_by_id');
        Route::post('/', 'App\Http\Controllers\PlaylistManagementController@create_playlist');
        Route::put('/{id}', 'App\Http\Controllers\PlaylistManagementController@update_playlist');
        Route::delete('/{id}', 'App\Http\Controllers\PlaylistManagementController@delete_playlist');
    });

    Route::prefix('/genres')->group(function () {
        Route::get('/', 'App\Http\Controllers\GenreManagementController@get_all_genres');
        Route::get('/{id}', 'App\Http\Controllers\GenreManagementController@get_genre_by_id');
        Route::post('/', 'App\Http\Controllers\GenreManagementController@create_genre');
        Route::put('/{id}', 'App\Http\Controllers\GenreManagementController@update_genre');
        Route::delete('/{id}', 'App\Http\Controllers\GenreManagementController@delete_genre');
    });
});
