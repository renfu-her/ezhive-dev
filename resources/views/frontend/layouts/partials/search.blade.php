<form class="form-inline my-2 my-lg-0" action="{{ route('search') }}" method="get">
    <div class="input-group">
        <input type="text" class="form-control input-search input-border-radius-50 bg-search" placeholder="搜尋"
            aria-label="Search" aria-describedby="basic-addon2" name="search" value="{{ request('search') }}"
            required minlength="1">
        <div class="input-group-append">
            <button class="btn btn-widget text-white input-border-radius-50 bg-search" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>

<style>
    .form-inline .custom-select,
    .form-inline .input-group {
        width: 180px !important;
    }
</style>
