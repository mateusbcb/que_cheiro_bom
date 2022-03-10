<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Repositories\ReceitaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceitaController extends Controller
{
    public function __construct(Receita $receita)
    {
        $this->receita = $receita;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $receitaRepository = new ReceitaRepository($this->receita);

        if ( $request->has('filtro') ) {

            $receitaRepository->filtro($request->filtro);

        }

        if ( $request->has('atributos') ) {
            $receitaRepository->selectAtributos($request->atributos);
        }
        
        return response()->json($receitaRepository->getResultadoPaginado(4), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->receita->rules(), $this->receita->feedback());
        
        $imagem     = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $receita = $this->receita->create([
            'titulo'        => $request->titulo,
            'imagem'        => $imagem_urn,
            'ingredientes'  => json_encode($request->ingredientes),
            'modo_preparo'  => $request->modo_preparo,
            'tempo_preparo' => $request->tempo_preparo,
            'qtd_porcao'    => $request->qtd_porcao,
            'observacao'    => $request->observacao,
            'categorias'    => json_encode($request->categorias),
            'tipo'          => $request->tipo,
        ]);

        return response()->json($receita, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $receita = $this->receita->find($id);

        if ($receita === null) {
            return response()->json(['erro' => 'Receita não encontrada'], 404);
        }

        return response()->json($receita, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receita  $receita
     * @return \Illuminate\Http\Response
     */
    public function edit(Receita $receita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $receita = $this->receita->find($id);

        if ($receita === null) {
            return response()->json(['erro' => 'Receita não encontrada'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            // percorendo todas as regras definidas no model
            foreach ($receita->rules() as $input => $regra) {

                // coletar apenas as regras aplicáveis da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }

            }
            
            $request->validate($regrasDinamicas, $receita->feedback());
        }else {
            $request->validate($receita->rules(), $receita->feedback());
        }
        
        $receita->fill($request->all());
        // remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        // $receita->imagem - refere-se ao caminho do arquivo.
        if ( $request->file('imagem') ) {
            Storage::disk('public')->delete($receita->imagem);

            $imagem     = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $receita->imagem = $imagem_urn;
        }
        
        $receita->save();
        
        return response()->json($receita, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $receita = $this->receita->find($id);

        if ($receita === null) {
            return response()->json(['erro' => 'Receita não encontrada'], 404);
        }

        // remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        // $receita->imagem - refere-se ao caminho do arquivo.
        Storage::disk('public')->delete($receita->imagem);
        
        $receita->delete();

        return response()->json(['msg' => 'A receita foi removida com sucesso!'], 200);

    }
}
