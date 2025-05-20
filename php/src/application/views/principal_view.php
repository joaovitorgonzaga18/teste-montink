<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Teste</title>
    <script src="/js/jquery-3.7.1.js"></script>
    <script src="/js/index.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <script>
        $(document).ready(function() {
            alert("jQuery is working!");
        });
    </script> -->

    <style>
        body {
            font-size: 14px;
        }

        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: .5;
        }

        #tabela-lista {
            height: 400px;
            max-height: 400px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #tabela-lista thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .hidden {
            display: none;
        }
    </style>

</head>

<body>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modal-content">
                ...
            </div>
        </div>
    </div>


    <div class="container-fluid" style="padding: 0px 30px;">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Lista de produtos</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" id="tabela-lista">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col">Variações</th>
                                    <th scope="col">Estoque</th>
                                    <th scope="col">Ações</th>
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
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="add_pedido(<?= $produto['id'] ?>)"><i class="fa-solid fa-cart-plus"></i></button>
                                            <button type="button" class="btn btn-primary" onclick="abrir_div('index.php/produtoscontroller/get/<?= $produto['id'] ?>', 'modal-content')" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa-solid fa-pen-to-square"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 20px; text-align: center;">
                        <button type="button" class="btn btn-success" onclick="abrir_div('index.php/produtoscontroller/get/0', 'modal-content')" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa-solid fa-plus"></i> Novo Produto</button>
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
                    <div class="col-md-12" id="tabela-lista">
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
                                    <th scope="col">Ações</th>
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
                                        <td><button type="button" class="btn btn-primary" onclick="abrir_div('index.php/pedidoscontroller/get/<?= $pedido['id'] ?>', 'modal-content')" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa-solid fa-eye"></i></button></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="border-top: 2px solid #bbb; margin-top: 20px;">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Lista de compras</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="tabela-lista">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col">Quantidade</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="lista-pedido">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Informações do pedido</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="alert alert-danger hidden" role="alert" id="alert"></div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="cupom">Cupom</label>
                        <input type="text" class="form-control" id="cupom" name="cupom" placeholder="Insira aqui o código do cupom" value="" required>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-primary" style="margin-top: 20px; width:100%" onclick="check_cupom()">Aplicar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" placeholder="Ex.: 3234-120" value="" required>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-primary" style="margin-top: 20px; width:100%" onclick="check_cep()">Verificar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Frete:</th>
                                    <td id="valor-frete">R$ 20,00</td>
                                </tr>
                                <tr>
                                    <th>Desconto:</th>
                                    <td id="desconto">R$ 0,00</td>
                                </tr>
                                <tr style="font-size: 20px;">
                                    <th>Valor total:</th>
                                    <th id="valor-total">R$ 20,00</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-primary" style="margin-top: 20px; width:100%" id="confirma-pedido">Confirmar pedido</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script>load_pedido();</script>
</body>

</html>