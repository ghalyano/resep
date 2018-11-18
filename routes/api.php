<?php

use Illuminate\Http\Request;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});*/

Route::post('login', 'UsersController@login');
Route::post('register', 'UsersController@register');
Route::post('resep', 'ResepController@resep');
Route::post('cari_resep', 'ResepController@cari_resep');
Route::post('update_resep', 'ResepController@update_resep');
Route::post('delete_resep', 'ResepController@delete_resep');
Route::post('buat_resep', 'ResepController@buat_resep');
Route::post('koleksi','KoleksiController@koleksi');
Route::post('hapus_koleksi', 'KoleksiController@hapus_koleksi');
Route::post('hapus_dari_koleksi', 'KoleksiController@hapus_dari_koleksi');
Route::post('tambah_koleksi', 'KoleksiController@tambah_koleksi');
Route::post('tambah_ke_koleksi', 'KoleksiController@tambah_ke_koleksi');
Route::post('detail_resep', 'ResepController@detail_resep');
Route::post('profil', 'UsersController@profil');
Route::post('update_profil', 'UsersController@update_profil');
Route::post('tambah_kometar', 'KomentarController@tambah_komentar');
Route::post('ambil_kometar', 'KomentarController@ambil_komentar');
Route::post('delete_komentar', 'KomentarController@delete_komentar');
Route::post('update_komentar', 'KomentarController@update_komtar');
Route::post('tambah_like', 'LikeController@tambah_like');
Route::post('hapus_like', 'LikeController@hapus_like');
Route::post('kategori', 'KategoriController@kategori');
