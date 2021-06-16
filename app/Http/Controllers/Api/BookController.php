<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $book;
    private $user;

    public function __construct(Book $book, User $user)
    {
        $this->book = $book;
        $this->user = $user;
    }

    // = /book (METODO GET) -> MANDAR TOKEN DO USUARIO, LISTA OS LIVROS QUE O USUARIO ESTA LENDO
    public function index()
    {
        try{
            $user = $this->user->findOrFail(auth('api')->user()->id);
            return response()->json([
                'data' => $user->book
            ], 200);

            return response()->json($user, 200);
        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


     // REGISTRA UM NOVO LIVRO PARA LEITURA DO USUARIO
     // = /book (METODO POST) ->
     //MANDAR TOKEN DO USUARIO,
     //goal = total de paginas do livro
     //id_google = ID DO LIVRO NA API DO GOOGLE

    public function store(Request $request)
    {
        $data = $request->all();

        try{

            $book = $this->book->create($data);

            $data['id_user'] = auth('api')->user()->id;

            $goal = $data['goal'];
            $pages_read = 0;
            $book->user()->sync([$data['id_user'] => ['goal' => $goal, 'pages_read' => $pages_read]]);

            return response()->json($book, 200);

        }catch(\Exception $e){
            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

}
