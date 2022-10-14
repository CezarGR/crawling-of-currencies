<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyCrawlingRequest;
use App\Http\Resources\CurrencySearchResource;
use App\Services\Currency\CurrencyService;
use Illuminate\Support\Facades\DB;
use Exception;
use OpenApi\Annotations as OA;

class CurrencyController extends Controller
{
    /**
     * @OA\Post(
     *     path="v1/currencies/search",
     *     tags={"Currencies V1"},
     *     summary="Retorna informacões da uma ou varias moedas",
     *     description="Essa rota tem a funcao de realizar uma busca de informacoes sobre uma terminada moedas, para isso foi usado uma tecnica de Crawling se baseado no código ou numero ISO 4217 (padrão internacional que define códigos de três letras para as moedas)",
     *     @OA\RequestBody(
     *         description="É possível realiza a busca de várias maneiras utilizando as propriedade code, code_list, number ou number_list",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="code",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="code_list",
     *                     type="[string]"
     *                 ),
     *                 @OA\Property(
     *                     property="number",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="number_list",
     *                     type="[integer]"
     *                 ),
     *                 example={"code": "BRL"},
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="result", 
     *                 value={
     *                     "message": "Informações sobre a moeda retornada com sucesso",
     *                     "data": {
     *                         {
     *                             "name": "Real",
     *                             "code": "BRL",
     *                             "number": "986",
     *                             "symbol": "R$",
     *                             "decimal_places": 2,
     *                             "locations": {
     *                                 {
     *                                     "location": " Brasil",
     *                                     "icon": null
     *                                 }
     *                             }
     *                         }
     *                     }
     *                 },
     *                 summary="Exemplo de um retorno de sucesso")
     *         )
     *     )
     * )
     */
    public function search(CurrencyCrawlingRequest $request, CurrencyService $service)
    {
        try {
            DB::beginTransaction();

            $record = $service->search(
                $request->codes, 
                $request->numbers
            );
            
            DB::commit();
            
            return json_success_response(
                CurrencySearchResource::collection($record),
                count($request->codes) > 1 || count($request->numbers) > 1 ? 
                    'Informações sobre as moedas retornada com sucesso' : 
                    'Informações sobre a moeda retornada com sucesso'  
            );
            
        } catch (Exception $exception) {
            DB::rollBack();
            return json_error_response(
                empty($exception->getMessage()) ? 
                    "Erro ao rastrear informações sobre determinada moeda/moedas" : 
                    $exception->getMessage()
                );
        }
    }
}
