<!-- Tollways -->
<div class="modal fade" id="modal_tollways" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tollways</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <a target="blank" href="partial/test_chart?cat=Tollways&type=Internal" style="float:right">
                            <button class="btn btn-primary">
                                Internal
                            </button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a target="blank" href="partial/test_chart?cat=Tollways&type=External" style="float:left">
                            <button class="btn btn-primary">
                                External
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Logistics -->
<div class="modal fade" id="modal_logistics" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Logistics</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <a target="blank" href="partial/test_chart?cat=Logistics&type=Internal" style="float:right">
                            <button class="btn btn-primary">
                                Internal
                            </button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a target="blank" href="partial/test_chart?cat=Logistics&type=External" style="float:left">
                            <button class="btn btn-primary">
                                External
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View certificate individual -->
<div id="indModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Stock certificate</h4>
            </div>
            <div class="modal-body" id="ind_detail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View certificate Corporation -->
<div id="corpModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Stock certificate</h4>
            </div>
            <div class="modal-body" id="corp_detail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>