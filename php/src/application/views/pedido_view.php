<div class="row">
    <div class="col-md-12">
        <div class="modal-header">
            <h5 class="modal-title h4" id="myLargeModalLabel">Pedido #<?= $pedido['id'] ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Informações Adicionais</h5>
                    <ul>
                        <li>Data de criação: <?= $pedido['data_pedido'] ?></li>
                        <li>CEP: <?= $pedido['cep'] ?></li>
                        <li>Cupom aplicado: <?= $pedido['cupom'] ?></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr class="text-right">
                                <th scope="col">Produto</th>
                                <th scope="col">Preço</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $k => $produto) { ?>
                                <tr class="text-right">
                                    <td><?= $produto['nome'] ?></td>
                                    <td><?= $produto['preco'] ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="text-right">
                                <th>Frete</td>
                                <td><?= $pedido['frete'] ?></td>
                            </tr>
                            <?php if ($pedido['cupom'] != '---') { ?>
                                <tr class="text-right">
                                    <th>Desconto (<?= $pedido['cupom'] ?>)</th>
                                    <td><?= $pedido['desconto'] ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="text-right" style="border-top: solid 2px black">
                                <th>Valor total</th>
                                <td><?= $pedido['preco'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>