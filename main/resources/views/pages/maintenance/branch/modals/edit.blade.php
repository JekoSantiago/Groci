<form id="editBranchForm">
    <div class="form-group">
        <label for="role" class="text-semibold">BRANCH CODE</label>
        <input type="text" class="form-control" name="branchCode" id="branchCode" value="{{ $detail[0]->branch_code }}">
        <label class="error form-error-message" id="branchCodeError" >* Please enter banner code.</label>
     </div>
    <div class="form-group">
        <label for="role" class="text-semibold">BRANCH NAME</label>
        <input type="text" class="form-control" name="branchName" id="branchName" value="{{ $detail[0]->branch_name }}">
        <label class="error form-error-message" id="branchNameError">* Please enter branch name.</label>
    </div>

    <input type="hidden" id="branchID" value="{{ $detail[0]->branch_id }}">
</form>