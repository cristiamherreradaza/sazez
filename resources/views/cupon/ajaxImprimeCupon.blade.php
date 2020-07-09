

<div class="modal-content">
    <div class="modal-header bg-success">
        <h4 class="modal-title text-white" id="myModalLabel"><strong>DETALLE DE CUP&Oacute;N</strong></h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body text-center">
        <div id="printableArea">
            <div class="card text-center">
                <div class="card-header text-muted">
                    SAZEZ
                </div>
                <div class="card-body">
                    <h4 class="card-title">FELICITACIONES</h4>
                    <p class="card-text">Fuiste acreedor a un nuevo cupón de descuento de:</p>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                        <ul class="text-left">
                        <li><strong>Producto: </strong>  Mochila 90FUN Basic Urban Messenger Xiaomi.</li>
                        <li><strong>Precio normal: </strong> 272.00 Bs.</li>
                        <li><strong>Precio descuento: </strong> 163 Bs.</li>
                        <li><strong>Tienda: </strong> Almacen Central.</li>
                    </ul>
                        </div>
                    </div>
                    
                    <img class="d-block img-fluid text-center" src="{{ asset('qrs/0HMG-E3Q4-CAZ6.png') }}">
                    <p>Cupón valido hasta 2020-07-11 20:34. </p>
                    <p>Al momento de tu compra, muestra este código y se realizara el descuento <br>
                        Visitanos y conoce nuestros ofertas.</p>
                </div>
                <div class="card-footer text-muted">
                    © 2015 - 2020 Sazez.net
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input class="btn btn-success btn-block" type="button" onclick="printDiv('printableArea')" value="Imprimir Cupon" />
    </div>
</div>

<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

</script>