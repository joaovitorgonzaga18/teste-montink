<div class="row">
    <div class="col-md-12">
        <div class="modal-header">
            <h5 class="modal-title h4" id="myLargeModalLabel"><?= isset($produto) ? 'Editar' : 'Cadastrar' ?> Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="/index.php/produtoscontroller/<?= (isset($produto['id'])) ? 'update/'.$produto['id'] : 'create' ?>">
                        <input type="hidden" id="idProduto" name="idProduto" value="<?= (isset($produto['id'])) ? $produto['id'] : 0 ?>">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex.: Bolo de chocolate" value="<?= (isset($produto['nome'])) ? $produto['nome'] : '' ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="preco">Preço (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?= (isset($produto['preco'])) ? $produto['preco'] : 0.0 ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="variacao">Variações</label>
                                <input type="number" step="0.01" class="form-control" id="variacao" name="variacao" value="<?= (isset($produto['variacao'])) ? $produto['variacao'] : 0 ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="estoque">Estoque</label>
                                <input type="number" class="form-control" id="estoque" name="estoque" value="<?= (isset($produto['estoque'])) ? $produto['estoque'] : 0 ?>" required>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><?= isset($produto) ? 'Editar' : 'Cadastrar' ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>