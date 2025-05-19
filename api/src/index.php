<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();

$pdo = null;
try {
    $pdo = new PDO(
        'mysql:dbname=g4;host=localhost;charset=utf8',
        'root', '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
    );
} catch ( PDOException $e ) {
    http_response_code( 500 );
    die( 'Erro ao criar o banco de dados.' );
}

$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->get('/locacoes', function(Request $request, Response $response) use($pdo) {
    $repositorioLocacao = new RepositorioLocacaoEmBDR($pdo); 
    $repositorioCliente = new RepositorioClienteEmBDR($pdo);
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);

    $gestorLocacao = new GestorLocacao($repositorioLocacao, $repositorioCliente, $repositorioFuncionario);
    $parametros = $request->getQueryParams();
    try {
        $locacoes = [];
        if(isset($parametros['cpf'])){
            $locacoes = $gestorLocacao->coletarCom(["cpf" => $parametros['cpf']]);
        }else if(isset($parametros['id'])){
            $locacoes = $gestorLocacao->coletarCom(["id" => $parametros['id']]);
        }else{
            $locacoes = $gestorLocacao->coletarTodos();
        }
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $locacoes
        ]));

    } catch (DominioException $e) {
        $response = $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));

    } catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));

    } catch(Exception $e) {
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => 'Erro interno do servidor: ' . $e->getMessage()
        ]));

    } finally{
        return $response;
    }
});

$app->post('/devolucoes', callable: function(Request $request, Response $response) use ($pdo){
    $jsonBody = $request->getBody()->getContents();
    $dados = json_decode($jsonBody, true);
    $gestor = new GestorDevolucao(new RepositorioDevolucaoEmBDR($pdo), new RepositorioLocacaoEmBDR($pdo));
    try{
        $gestor->salvarDevolucao($dados);
        
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => true
        ]));

    }catch(DominioException $e){
        $response = $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));

    } catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));

    } catch(Exception $e) {
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => 'Erro interno do servidor: ' . $e->getMessage()
        ])); 
    }
    finally{
        return $response;
    }

});

$app->run();

