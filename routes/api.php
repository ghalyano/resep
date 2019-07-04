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
Route::get('resep', 'ResepController@resep');
Route::post('detail_resep', 'ResepController@detail_resep');
Route::post('cari_resep', 'ResepController@cari_resep');
Route::post('update_resep', 'ResepController@update_resep');
Route::post('delete_resep', 'ResepController@delete_resep');
Route::post('buat_resep', 'ResepController@buat_resep');
Route::post('koleksi', 'KoleksiController@koleksi');
Route::post('koleksi/detail', 'KoleksiController@koleksi_detail');
Route::post('hapus_koleksi', 'KoleksiController@hapus_koleksi');
Route::post('hapus_dari_koleksi', 'KoleksiController@hapus_dari_koleksi');
Route::post('tambah_koleksi', 'KoleksiController@tambah_koleksi');
Route::post('update_koleksi', 'KoleksiController@update_koleksi');
Route::post('tambah_ke_koleksi', 'KoleksiController@tambah_ke_koleksi');
Route::post('profil', 'UsersController@profil');
Route::post('ganti_password', 'UsersController@ganti_password');
Route::post('update_profil', 'UsersController@update_profil');
Route::post('tambah_kometar', 'KomentarController@tambah_komentar');
Route::post('komentar', 'KomentarController@ambil_komentar');
Route::post('simpan_komentar', 'KomentarController@simpan_komentar');
Route::post('delete_komentar', 'KomentarController@delete_komentar');
Route::post('like', 'LikeController@like');
Route::get('kategori', 'KategoriController@kategori');
Route::post('isi_kategori', 'KategoriController@isi_kategori');
Route::post('daftar', 'UsersController@daftar');
