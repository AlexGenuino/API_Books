<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $user;
    private $book;

    public function __construct(User $user, Book $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    // = /user (metodo post) => REGISTRA UM USUARIO

    public function create(Request $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password'))
        {
            $message = new ApiMessages('it is necessary to enter a password for the user');
            return response()->json($message->getMessage(), 401);
        }

        try{

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);

            return response()->json([
                'data' => [
                    'Id' => $user['id'],
                    'Nome' => $user['name'],
                    'email' => $user['email']
                ]
            ], 200);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    // = /user (metodo GET) => RETORNA OS DADOS DO USUARIO LOGADO
    //ENVIAR O TOKEN NO HEADER
    public function show()
    {
        try{
            $user = $this->user->findOrFail(auth('api')->user()->id);

            return response()->json([
                'data' => [
                    'Id' => $user['id'],
                    'Nome' => $user['name'],
                    'email' => $user['email']
                ]
            ], 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    // = /user (metodo GET) => UPDATE NO LIVRO QUE O USUARIO ESTA LENDO
    //ENVIAR O TOKEN NO HEADER
    //$pages_read = QUANTIDADE DE PAGINAS LIDAS
    //$id_book = ID DO LIVRO

    public function bookupdate(Request $request)
    {
        $data = $request->all();

        try{

            $user = $this->user->findOrFail(auth('api')->user()->id);

            $pages_read = $data['pages_read'];
            $id_book = $data['id_book'];
            $user->book()->updateExistingPivot($id_book,
            [
                'pages_read' => $pages_read,
            ]);

            $leitura = $user->book()->findOrFail($id_book);
            return response()->json([
                'data' => [
                    'msg' => 'Leitura Atualizada!',
                    'Usuario' => $user['name'],
                    'leitura' => $leitura
                ]
            ], 200);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

}
