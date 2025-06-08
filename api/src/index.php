<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::create();

$pdo = null;
$gestorDevolucao = null;
$gestorLocacao = null;
try {
    $pdo = new PDO(
        'mysql:dbname=g4;host=localhost;charset=utf8',
        'root', '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $repositorioItemLocacao = new RepositorioItemLocacaoEmBDR($pdo);
    $repositorioLocacao = new RepositorioLocacaoEmBDR($pdo, $repositorioItemLocacao);
    $repositorioFuncionario = new RepositorioFuncionarioEmBDR($pdo);
    $transacao = new TransacaoComPDO($pdo);
    $gestorLocacao = new GestorLocacao($repositorioLocacao, new RepositorioClienteEmBDR($pdo), $repositorioFuncionario , $transacao);
    $gestorDevolucao = new GestorDevolucao(new RepositorioDevolucaoEmBDR($pdo, $repositorioLocacao, $repositorioFuncionario), $repositorioLocacao, $repositorioFuncionario, $transacao);
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

$app->get('/locacoes', function(Request $request, Response $response) use($gestorLocacao) {
    $parametros = $request->getQueryParams();
    try {
        $locacoes = [];
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

$app->post('/locacoes', callable: function(Request $request, Response $response) use ($gestorLocacao){
    try{
        $dados = $request->getBody();
        $gestorLocacao->salvarLocacao(json_decode($dados, true));

        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
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

$app->get('/devolucoes', callable:function(Request $request, Response $response) use($gestorDevolucao){
    try{
        $dados = $request->getQueryParams();
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

$app->post('/devolucoes', callable: function(Request $request, Response $response) use ($gestorDevolucao){
    $jsonBody = $request->getBody()->getContents();
    $dados = json_decode($jsonBody, true);
    try{
        $gestorDevolucao->salvarDevolucao($dados);
      
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

$app->get('/funcionarios', callable:function(Request $request, Response $response) use($pdo){
    try{
        $gestorFuncionario = new GestorFuncionario(new RepositorioFuncionarioEmBDR($pdo));
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

        $gestorCliente = new GestorCliente(new RepositorioClienteEmBDR($pdo));
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
        $gestorItem = new GestorItem(new RepositorioItemEmBDR($pdo));
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

$app->run();

