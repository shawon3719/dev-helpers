@include('layouts.admin')

<<div class="modal" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="commonModalLabel" aria-hidden="true">>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title text-white" id="commonModalHeading"></h4>
                <button class="btn btn-sm text-white closeModal" data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body commonModalBody" id="commonModalBody">
            </div>
        </div>
    </div>
</div>