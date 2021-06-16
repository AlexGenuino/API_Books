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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->namespace('Api')->group(function(){

    //ROTA PARA CONFIRMAR O LOGIN DO USUARIO, RETORNA UM TOKEN
    Route::post('/login', 'Auth\\LoginJwtController@login')->name('login');
    //ROTA PARA LOGOUT DO USUARIO TOKEN É INVALIDADO NA BLACKLISTED
    Route::get('/logout', 'Auth\\LoginJwtController@logout')->name('logout');
    //ROTA PARA RENOVAR O TOKEN DO USUARIO ENVIAR O ANTIGO TOKEN
    Route::get('/refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');


    //ROTA ABAIXO:
            //    /USER = NO METODO POST: REGISTRA UM USUARIO
                    //PARAMETROS:
                    //name
                    //password
                    //email
    Route::post('/userCreate', 'UserController@create');


    //ROTAS ABAIXO SÃO PERMITIDAS APENAS COM ENVIO DO TOKEN
    Route::group(['middleware' => ['jwt.auth']], function(){

        Route::name('user.')->group(function(){

            //ROTA FEITA PARA ATUALIZAR QUANTAS PAGINAS DO LIVRO DO USUARIO LIDAS
            //PARAMETROS:

            //TOKEN NO HEADER
            //id_book = ID DO LIVRO QUE O USUARIO VAI ATUALIZAR A LEITURA
            //pages_read = QUANTIDADE DE PAGINAS LIDAS
            Route::put('user/bookupdate', 'UserController@bookupdate');

            //    /USER = NO METODO GET: ENVIAR O TOKEN, RETORNA OS DADOS DO USUARIO
            Route::resource('user', 'UserController');

        });

        Route::name('book.')->group(function(){

            //ROTA ABAIXO:
            //    /BOOK = NO METODO POST: REGISTRA UM LIVRO QUE O USUARIO VAI LER
                    //PARAMETROS:
                    //TOKEN NO HEADER DO USUARIO LOGADO
                    //id_google - > ID do livro na api do google
                    //goal - > Quantidade de paginas que o livro tem

                    //BOOK = NO METODO GET: RETORNA OS LIVROS QUE O USUARIO ESTA LENDO
                        //PARAMETRO:
                        //TOKEN NO HEADER DO USUARIO LOGADO
            Route::resource('book', 'BookController');

        });
    });

});
