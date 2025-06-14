<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/infra/repositorio/CriadorDeGestores.php';

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
    $parametros = $request->getQueryParams();
    try {
        $locacoes = [];
        $gestorLocacao = criarGestorDeLocacao($pdo);
        if(count($parametros) > 0){
            $locacoes = $gestorLocacao->coletarCom($parametros);
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

$app->post('/locacoes', callable: function(Request $request, Response $response) use ($pdo){
    try{
        $dados = $request->getBody();
        $gestorLocacao = criarGestorDeLocacao($pdo);
        $gestorLocacao->salvarLocacao(json_decode($dados, true));

        $response = $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Locação cadastrada com sucesso!'
        ]));
    }catch(RepositorioException $e){
        $response = $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor:'.$e->getMessage()
        ]));
    }catch(DominioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor:'.$e->getMessage()
        ]));
    }catch(Exception $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor.'
        ]));
    }
    finally{
        return $response;
    }
});

$app->get('/devolucoes', callable:function(Request $request, Response $response) use($pdo){
    try{
        $dados = $request->getQueryParams();
        $gestorDevolucao = criarGestorDeDevolucao($pdo);
        if(isset($dados['dataInicial']) && isset($dados['dataFinal'])){
            $devolucoes = $gestorDevolucao->coletarDevolucoesParaGrafico($dados);
        }else{
            $devolucoes = $gestorDevolucao->coletarDevolucoes();
        }
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $devolucoes
        ]));
    }catch (DominioException $e) {
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
    $gestorDevolucao = criarGestorDeDevolucao($pdo);
    try{
        $gestorDevolucao->salvarDevolucao($dados);
      
        $response = $response->withStatus(201)->withHeader('Content-Type', 'application/json');
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

$app->get('/funcionarios', callable:function(Request $request, Response $response) use($pdo){
    try{
        $gestorFuncionario = criarGestorDeFuncionario($pdo);
        $funcionarios = $gestorFuncionario->coletarFuncionarios();

        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $funcionarios
        ]));
    }catch(DominioException $e){
        $response = $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor:'.$e->getMessage()
        ]));
    }catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }catch(Exception $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor.'
        ]));
    }
    finally{
        return $response;
    }
});

$app->get('/clientes', callable:function(Request $request, Response $response) use($pdo){
    try{
        $parametro = $request->getQueryParams();

        $gestorCliente = criarGestorDeCliente($pdo);
        $cliente = $gestorCliente->coletarComCodigoOuCpf($parametro['parametro']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data'    => $cliente
        ]));
    } catch(DominioException $e){
        $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]));
    }catch(Exception $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor.'
        ]));
    }
    finally{
        return $response;
    }
});

$app->get('/itens', callable:function(Request $request, Response $response) use($pdo){
    try{
        $gestorItem = criarGestorDeItem($pdo);
        $parametros = $request->getQueryParams();
        if(isset($parametros['dataInicial']) && isset($parametros['dataFinal'])){
            $resultado = $gestorItem->coletarItensParaRelatorio($parametros);
        } else {
            $resultado = $gestorItem->coletarComCodigo($parametros['codigo']);
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data'    => $resultado
        ]));
    }catch(DominioException $e){
        $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]));
    }catch(Exception $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor.'
        ]));
    }
    finally{
        return $response;
    }
});


$app->get('/avarias', callable:function(Request $request, Response $response) use($pdo){
    try{
        $gestorAvaria = criarGestorDeAvaria($pdo);
        $avarias = $gestorAvaria->coletarAvarias();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data'    => $avarias
        ]));
    } catch(DominioException $e){
        $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }catch(RepositorioException $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]));
    }catch(Exception $e){
        $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode([
            'success' => false, 
            'message' => 'Erro interno do servidor.'
        ]));
    }
    finally{
        return $response;
    }
});

$app->post('/avarias', callable: function(Request $request, Response $response) use ($pdo){
    $jsonBody = $request->getBody()->getContents();
    $dados = json_decode($jsonBody, true);
    $gestorAvaria = criarGestorDeAvaria($pdo);
    try{
        $gestorAvaria->salvarAvaria($dados);
      
        $response = $response->withStatus(201)->withHeader('Content-Type', 'application/json');
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

