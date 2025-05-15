<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Teste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-size: 14px;
        }
    </style>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Lista de produtos</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col">Variações</th>
                                    <th scope="col">Estoque</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $k => $produto) { ?>
                                    <tr class="text-center">
                                        <th><?= $produto['id'] ?></th>
                                        <td><?= $produto['nome'] ?></td>
                                        <td><?= $produto['preco'] ?></td>
                                        <td><?= $produto['variacao'] ?></td>
                                        <td><?= $produto['estoque'] ?></td>
                                        <td><button type="button" class="btn btn-primary">Editar</button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Lista de pedidos</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th scope="col">ID</th>
                                    <th scope="col">Cupom Aplicado</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col">Desconto</th>
                                    <th scope="col">Frete</th>
                                    <th scope="col">CEP</th>
                                    <th scope="col">Data do Pedido</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $k => $pedido) { ?>
                                    <tr class="text-center">
                                        <th><?= $pedido['id'] ?></th>
                                        <td><?= $pedido['cupom'] ?></td>
                                        <td><?= $pedido['preco'] ?></td>
                                        <td><?= $pedido['desconto'] ?></td>
                                        <td><?= $pedido['frete'] ?></td>
                                        <td><?= $pedido['cep'] ?></td>
                                        <td><?= $pedido['data_pedido'] ?></td>
                                        <td><button type="button" class="btn btn-primary">Visualizar</button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>

</html>